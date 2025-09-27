<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use App\Models\Saving;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\SavingsTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\SavingsImport;
use Illuminate\Support\Facades\Session;


class SavingsController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // default is 10
        $members = User::where('is_admin', '!=', 1)->paginate($perPage); // adjust your query as needed

        $offices = User::distinct()->pluck('office');
        return view('savings', compact('members', 'offices'));
    }

    public function controllerSavings()
        {
            $members = User::where('status', 'Active')->get();
            $offices = User::where('status', 'Active')->pluck('office')->unique();

            // SAVINGS totals keyed by users.id
            $savingById = Saving::join('users', 'savings.employees_id', '=', 'users.id')
                ->select('users.id as uid', DB::raw('SUM(savings.amount) as total'))
                ->groupBy('users.id')
                ->pluck('total', 'uid')
                ->toArray();

            $savingViaEmpNum = Saving::join('users', 'savings.employees_id', '=', 'users.employee_ID')
                ->select('users.id as uid', DB::raw('SUM(savings.amount) as total'))
                ->groupBy('users.id')
                ->pluck('total', 'uid')
                ->toArray();

            $savingTotals = Saving::select('employees_id', DB::raw('SUM(amount) as total'))
            ->groupBy('employees_id')
            ->pluck('total', 'employees_id');

            // WITHDRAW totals keyed by users.id
            $withdrawById = Withdraw::join('users', 'withdrawals.employees_id', '=', 'users.id')
                ->select('users.id as uid', DB::raw('SUM(withdrawals.amount_withdrawn) as total'))
                ->groupBy('users.id')
                ->pluck('total', 'uid')
                ->toArray();

            $withdrawViaEmpNum = Withdraw::join('users', 'withdrawals.employees_id', '=', 'users.employee_ID')
                ->select('users.id as uid', DB::raw('SUM(withdrawals.amount_withdrawn) as total'))
                ->groupBy('users.id')
                ->pluck('total', 'uid')
                ->toArray();

            $withdrawTotals = Withdraw::select('employees_id', DB::raw('SUM(amount_withdrawn) as total'))
            ->groupBy('employees_id')
            ->pluck('total', 'employees_id');

            return view('admin.savings', compact('members', 'offices', 'savingTotals', 'withdrawTotals'));
        }



    public function bulkAddSavings(Request $request)
    {
    try {
        $request->validate([
            'member_ids' => 'required|array',
            'amount' => 'required|numeric|min:0.01',
            'date_remittance' => 'required|date',
            'remittance_no' => 'required|string|max:255',
            'covered_month' => 'required|integer|min:1|max:12',
             'covered_year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $duplicates = [];

        // Loop through each selected member and insert into `savings` table
        foreach ($request->member_ids as $memberId) {
            $member = User::find($memberId);

            // Log the current member being processed
                \Log::info('Processing member:', ['memberId' => $memberId, 'memberName' => $member->name]);

                // Check if the remittance already exists for this member
                $existingSaving = Saving::where('remittance_no', $request->remittance_no)
                    ->where('covered_month', $request->covered_month)
                    ->where('covered_year', $request->covered_year)
                    ->where('employees_id', $member->id)
                    ->first();

                if ($existingSaving) {
                    $duplicates[] = [
                        'remittance_no' => $request->remittance_no,
                        'covered_month' => $request->covered_month,
                        'covered_year' => $request->covered_year
                    ];
                    continue; // Skip adding the saving for this member
                }

            if ($member) {
                // Insert into `savings` table including `date_created`
                Saving::create([
                    'employees_id' => $member->id,
                    'name' => $member->name,
                    'date_remittance' => now()->format('Y-m-d'),
                    'amount' => $request->amount,
                    'office' => $member->office ?? 'Unknown',
                    'date_created' => now()->format('Y-m-d'), // ✅ Added
                    'date_remittance' => $request->date_remittance,
                    'remittance_no' => $request->remittance_no,
                    'covered_month' => $request->covered_month,
                    'covered_year' => $request->covered_year
                ]);
            }
        }

        // Log the final response before sending it
            \Log::info('Response for Bulk Add Savings', ['duplicates' => $duplicates]);

            return response()->json([
                'success' => true,
                'duplicates' => $duplicates // Return duplicates array if it exists
            ]);

    } catch (\Exception $e) {
        \Log::error('Bulk Savings Error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }

    }

    public function updateSavingsRemittances(Request $request)
    {
        \Log::info('Update Remittances Request:', $request->all());

        $updates = $request->input('updates');
        $changesMade = false;

        foreach ($updates as $data) {
            \Log::info('Processing update for ID: ' . $data['savings_id']);

            $saving = Saving::where('savings_id', $data['savings_id'])->first();

            if ($saving) {
                \Log::info('Original:', $saving->toArray());

                $originalData = [
                    'date_remittance' => $saving->date_remittance,
                    'remittance_no' => $saving->remittance_no,
                    'covered_month' => $saving->covered_month,
                    'covered_year' => $saving->covered_year,
                    'amount' => $saving->amount,
                ];

                $newMonth = (int)date('m', strtotime($data['month_name']));
                $newAmount = (float)$data['amount'];
                $newYear = (int)$data['covered_year'];

                if (
                    $saving->date_remittance != $data['date_remittance'] ||
                    $saving->remittance_no != $data['remittance_no'] ||
                    (int)$saving->covered_month != $newMonth ||
                    (int)$saving->covered_year != $newYear ||
                    (float)$saving->amount != $newAmount
                ) {
                    $saving->date_remittance = $data['date_remittance'];
                    $saving->remittance_no = $data['remittance_no'];
                    $saving->covered_month = $newMonth;
                    $saving->covered_year = $newYear;
                    $saving->amount = $newAmount;
                    $saving->date_updated = now();
                    $saving->save();

                    \Log::info('Updated successfully', $saving->toArray());

                    $changesMade = true;
                }
            } else {
                \Log::warning('Saving not found for ID: ' . $data['savings_id']);
            }
        }

        if ($changesMade) {
            return response()->json(['success' => true, 'message' => 'Savings updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'No changes made']);
        }
    }

    public function updateSavings(Request $request)
    {
        \Log::info('Update Savings Request:', $request->all());

        $updates = $request->input('updates');
        $changesMade = false;

        foreach ($updates as $data) {
            \Log::info('Processing update for Savings ID: ' . $data['savings_id']);

            $saving = \App\Models\Saving::where('savings_id', $data['savings_id'])->first();

            if ($saving) {
                \Log::info('Original:', $saving->toArray());

                $newMonth = (int)date('m', strtotime($data['month_name']));
                $newAmount = (float)$data['amount'];
                $newYear = (int)$data['covered_year'];

                if (
                    $saving->date_remittance != $data['date_remittance'] ||
                    $saving->remittance_no != $data['remittance_no'] ||
                    (int)$saving->covered_month != $newMonth ||
                    (int)$saving->covered_year != $newYear ||
                    (float)$saving->amount != $newAmount
                ) {
                    $saving->date_remittance = $data['date_remittance'];
                    $saving->remittance_no = $data['remittance_no'];
                    $saving->covered_month = $newMonth;
                    $saving->covered_year = $newYear;
                    $saving->amount = $newAmount;
                    $saving->save();

                    \Log::info('Updated successfully', $saving->toArray());

                    $changesMade = true;
                }
            } else {
                \Log::warning('Saving not found for ID: ' . $data['savings_id']);
            }
        }

        if ($changesMade) {
            return response()->json([
                'contributions' => $savings  // alias it for frontend compatibility
            ]);
            return response()->json(['success' => true, 'message' => 'Savings updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'No changes made']);
        }
    }


    public function downloadTemplate()
    {
        return Excel::download(new SavingsTemplateExport, 'savings_template.xlsx');
    }

    public function uploadSavingsTemplate(Request $request)
    {
        try {
            Excel::import(new SavingsImport, $request->file('file'));
            return back()->with('success', '✅ Savings data uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function importSavings(Request $request)
    {
        try {
            Excel::import(new SavingsImport, $request->file('import_file'));
            return back()->with('success', '✅ Savings imported successfully!');
        } catch (\Exception $e) {
            return back()->with('import_error', $e->getMessage());
        }
    }

}