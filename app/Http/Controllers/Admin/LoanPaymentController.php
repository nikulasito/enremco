<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanPayment;
use App\Models\LoanDetail;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoanPaymentsTemplateExport;
use App\Imports\LoanPaymentsImport;

class LoanPaymentController extends Controller
{

    public function downloadTemplate()
    {
        return Excel::download(new LoanPaymentsTemplateExport, 'loan_payments_template.xlsx');
    }

    public function uploadTemplate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new LoanPaymentsImport, $request->file('file'));
            return back()->with('success', '✅ Loan payments uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LoanDetail::with('loanPayments', 'user');

            if ($request->has('employee_id') && !empty($request->employee_id)) {
                $query->where('employee_ID', $request->employee_id);
            }

            $loans = $query->get()->map(function ($loan) {
                return [
                    'loan_id' => $loan->loan_id,
                    'employee_ID' => $loan->employee_ID,
                    'employee_name' => $loan->user->name,
                    'loan_amount' => $loan->loan_amount,
                    'paid_amount' => $loan->loanPayments->total_payments ?? 0,
                    'outstanding_balance' => $loan->loanPayments->outstanding_balance ?? $loan->loan_amount,
                    'latest_payment_date' => $loan->loanPayments->latest_payment ?? null,
                ];
            });

            return response()->json($loans);
        }

        // Load the Blade template when not using AJAX
        return view('admin.loan_payments');
    }

    public function storeLoanPayment(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|string|exists:loan_details,loan_id',
            'total_payments' => 'required|numeric|min:1',
            'remittance_no' => 'required|string',
            'date_of_remittance' => 'required|date',
            'date_covered_month' => 'required|integer|min:1|max:12',
            'date_covered_year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        // ✅ Get the latest remittance from existing records
        $latestRemittance = LoanPayment::where('loan_id', $validated['loan_id'])
            ->latest('date_of_remittance')
            ->value('latest_remittance');

        // ✅ If no previous remittance exists, set it to the new `date_of_remittance`
        if (!$latestRemittance) {
            $latestRemittance = $validated['date_of_remittance'];
        }

        // ✅ Check if a payment for the same month and year already exists
        $existingPayment = LoanPayment::where('loan_id', $validated['loan_id'])
            ->where('date_covered_month', $validated['date_covered_month'])
            ->where('date_covered_year', $validated['date_covered_year'])
            ->exists();

        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'A payment for this month and year already exists.'
            ], 409); // HTTP 409 Conflict
        }

    

        // ✅ Fetch Loan Details
        $loan = LoanDetail::where('loan_id', $validated['loan_id'])->firstOrFail();

        // ✅ Fetch Previous Payments and Calculate New Outstanding Balance
        $previousTotalPayments = LoanPayment::where('loan_id', $validated['loan_id'])->sum('total_payments');
        $newTotalPayments = $previousTotalPayments + $validated['total_payments'];
        $latestOutstandingBalance = max(0, $loan->loan_amount - $newTotalPayments); // ✅ Prevents negative balances

        // Log values for debugging
        \Log::info("Loan ID: {$validated['loan_id']} | Previous Total Payments: {$previousTotalPayments} | New Total Payments: {$newTotalPayments} | New Outstanding Balance: {$latestOutstandingBalance}");

        // ✅ Log for debugging
        \Log::info("Loan ID: {$validated['loan_id']} | Latest Remittance: {$latestRemittance}");

        // ✅ Prevent saving 0-payment records
        if ($validated['total_payments'] > 0) {
            // ✅ Save New Payment Entry
            $loanPayment = new LoanPayment([
                'loan_id' => $validated['loan_id'],
                'total_payments' => $validated['total_payments'],
                'total_payments_count' => LoanPayment::where('loan_id', $validated['loan_id'])->count() + 1,
                'outstanding_balance' => $latestOutstandingBalance,
                'latest_remittance' => $latestRemittance,
                'remittance_no' => $validated['remittance_no'],
                'date_of_remittance' => $validated['date_of_remittance'],
                'date_covered_month' => $validated['date_covered_month'],
                'date_covered_year' => $validated['date_covered_year'],
            ]);
            $loanPayment->save();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment amount. Payment must be greater than 0.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'loan_payment' => [
                'total_payments' => number_format($newTotalPayments, 2),
                'latest_outstanding_balance' => number_format($latestOutstandingBalance, 2),
                'latest_remittance' => $loanPayment->latest_remittance, // ✅ Send correct value
                'date_of_remittance' => $loanPayment->date_of_remittance,
                'date_covered_month' => $loanPayment->date_covered_month,
                'date_covered_year' => $loanPayment->date_covered_year,
            ]
        ]);
    }


    public function storeBulkLoanPayments(Request $request)
    {
        $validated = $request->validate([
            'loans' => 'required|array',
            'loans.*.loan_id' => 'required|string|exists:loan_details,loan_id',
            'loans.*.total_payments' => 'required|numeric|min:1',
            'remittance_no' => 'required|string',
            'date_of_remittance' => 'required|date',
            'date_covered_month' => 'required|integer|min:1|max:12',
            'date_covered_year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $loanPayments = [];
        
        foreach ($validated['loans'] as $loanData) {
            $loan = LoanDetail::where('loan_id', $loanData['loan_id'])->firstOrFail();
            
            // Get previous total payments
            $previousTotalPayments = LoanPayment::where('loan_id', $loanData['loan_id'])->sum('total_payments');
            $latestOutstandingBalance = $loan->loan_amount - $previousTotalPayments;

            // 🚨 Prevent overpayment
            if ($loanData['total_payments'] > $latestOutstandingBalance) {
                return response()->json([
                    'success' => false,
                    'message' => "Payment for Loan ID {$loanData['loan_id']} cannot exceed the outstanding balance of " . number_format($latestOutstandingBalance, 2) . "."
                ], 400);
            }

            $newTotalPayments = $previousTotalPayments + $loanData['total_payments'];
            $latestOutstandingBalance = max(0, $loan->loan_amount - $newTotalPayments);

            // Get the current count of payments for this loan
            $totalPaymentsCount = LoanPayment::where('loan_id', $loanData['loan_id'])->count() + 1;

            // Save the new loan payment with total_payments_count
            $loanPayment = LoanPayment::create([
                'loan_id' => $loanData['loan_id'],
                'total_payments' => $loanData['total_payments'],
                'total_payments_count' => $totalPaymentsCount, // ✅ Now saving the total count of payments
                'outstanding_balance' => $latestOutstandingBalance,
                'latest_remittance' => $validated['date_of_remittance'], // ✅ Save Latest Remittance
                'remittance_no' => $validated['remittance_no'], // ✅ Save Remittance No.
                'date_of_remittance' => $validated['date_of_remittance'],
                'date_covered_month' => $validated['date_covered_month'],
                'date_covered_year' => $validated['date_covered_year'],
            ]);

            // Store the updated values for frontend update
            $loanPayments[$loanData['loan_id']] = [
                'total_payments' => number_format($newTotalPayments, 2),
                'latest_outstanding_balance' => number_format($latestOutstandingBalance, 2),
                'total_payments_count' => $totalPaymentsCount, 
                'latest_remittance' => $loanPayment->latest_remittance,
                'remittance_no' => $loanPayment->remittance_no,
                'date_of_remittance' => $loanPayment->date_of_remittance,
                'date_covered_month' => $loanPayment->date_covered_month,
                'date_covered_year' => $loanPayment->date_covered_year,
            ];
        }

        return response()->json([
            'success' => true,
            'loan_payments' => $loanPayments
        ]);
    }


    public function getByRemittance($remittanceNo, $loanId)
    {
        $payment = LoanPayment::where('remittance_no', $remittanceNo)
            ->where('loan_id', $loanId)
            ->first();

        if (!$payment) {
            return response()->json(['success' => false]);
        }

        $loan = LoanDetail::where('loan_id', $loanId)->first();
        $user = $loan->user; // assuming the relationship is defined

        return response()->json([
            'success' => true,
            'payment' => $payment,
            'employee_id' => $user->employee_ID ?? null,
            'employee_name' => $user->name ?? null,
            'loan_type' => $loan->loan_type ?? null,
        ]);
    }
    public function updateLoanPayment(Request $request)
    {
        $request->validate([
            'remittance_no' => 'required|string',
            'total_payments' => 'required|numeric|min:0',
            'outstanding_balance' => 'required|numeric|min:0',
            'latest_remittance' => 'required|date',
        ]);

        $loanPayment = LoanPayment::where('remittance_no', $request->remittance_no)->first();

        if (!$loanPayment) {
            return response()->json(['success' => false, 'message' => 'Remittance not found.']);
        }

        $loanPayment->update([
            'total_payments' => $request->total_payments,
            'outstanding_balance' => $request->outstanding_balance,
            'latest_remittance' => $request->latest_remittance,
        ]);

        return response()->json(['success' => true, 'message' => 'Loan payment updated successfully.', 'data' => $loanPayment]);
    }


    public function loanPayments()
    {
        $offices = User::pluck('office')->unique()->toArray();

        // ✅ Fetch all LoanDetails with latest payment preloaded
        $loans = LoanDetail::with(['user', 'latestPayment'])->get(); 

        return view('admin.loan_payments', compact('offices', 'loans'));
    }




}
