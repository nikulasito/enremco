<?php

// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request; 
use Carbon\Carbon;
use App\Models\Loan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;   // for Brevo API call
use Illuminate\Support\Facades\Log;    // for Log::error / w
use App\Mail\MemberApprovedMail;
use App\Mail\MemberDisapprovedMail;
use App\Models\User;


class AdminController extends Controller
{
        // Dashboard Method
        public function dashboard()
        {
            $totalActiveMembers = User::where('status', 'active')
                ->where('is_admin', '!=', 1)
                ->count();

            return view('admin.dashboard', [
                'totalUsers' => $totalActiveMembers,
                'totalSavings' => \App\Models\Saving::sum('amount'),
                'totalShares' => \App\Models\Share::sum('amount'),
                'recentActivities' => [
                    '5 new members registered',
                    '3 savings entries updated',
                    '1 loan application approved',
                ],
            ]);
        }
        //end dashboard method



        public function index()
        {
            return view('admin.dashboard'); // Ensure the 'admin/dashboard.blade.php' file exists
        }

        // View Members Method
        public function viewMembers(Request $request)
        {
            $perPage = $request->get('per_page', 10);

            $members = User::where('is_admin', 0) // Only non-admin users
            ->whereIn('status', ['Inactive', 'Active']) // Include both Approved and Inactive members
            ->paginate($perPage);

            $allMembers = User::select('id', 'name', 'office', 'status')->get();

            $links = [
                ['url' => route('admin.dashboard'), 'label' => 'Dashboard'],
                ['url' => route('admin.members'), 'label' => 'Members'],
                ['url' => '', 'label' => 'View Members']
            ];
        
            // return view('admin.members', compact('members', 'links'));
            // return view('admin.members', compact('members', 'perPage'));
            return view('admin.members', compact('members', 'allMembers', 'perPage'));


        }


        public function approveMember(Request $request, $id)
        {
            $member = User::findOrFail($id);

            // Optional validation (unobtrusive): ensure approved_at is a valid date if provided
            $request->validate([
                'approved_at' => 'nullable|date',
                'approve_notes' => 'nullable|string|max:1000',
            ]);

            // Use provided approved_at or default to now()
            $approvedAt = $request->input('approved_at')
                ? Carbon::parse($request->input('approved_at'))->startOfDay()
                : now();

            // 1) Update status & date_approved & token (keep your naming: date_approved)
            $member->status = 'Active';
            $member->date_approved = $approvedAt;
            $member->email_verification_token = Str::random(64);

            // Optional notes
            if ($request->filled('approve_notes')) {
                // Ensure you have a column to store notes (approve_notes). If not, remove this.
                $member->approve_notes = $request->input('approve_notes');
            }

            $member->save();

            // 2) Render your Blade email (MATCH the filename you created)
            try {
                $html = view('emails.member-approved', compact('member'))->render();
            } catch (\Throwable $e) {
                Log::error('Failed to render member-approved email view', ['err' => $e->getMessage()]);
                return back()->with('error', 'Failed to prepare approval email.');
            }

            // 3) Build Brevo payload
            $payload = [
                'sender' => [
                    'name'  => env('MAIL_FROM_NAME', 'ENREMCO'),
                    'email' => env('MAIL_FROM_ADDRESS', 'denr10enremco@gmail.com'),
                ],
                'to' => [[ 'email' => $member->email, 'name' => $member->name ]],
                'subject' => 'Member approved and verified',
                'htmlContent' => $html,
            ];

            // 4) OPTIONAL: attach PDF (try in-memory dompdf; fallback: skip cleanly)
            try {
                if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                    // If your pdf view relies on the approval date make sure it reads $member->date_approved
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.member', compact('member'));
                    $pdfContent = base64_encode($pdf->output());
                    $payload['attachment'] = [[
                        'name'    => "member-{$member->id}.pdf",
                        'content' => $pdfContent,
                    ]];
                } else {
                    Log::warning('PDF attachment skipped: barryvdh/laravel-dompdf not installed.');
                }
            } catch (\Throwable $e) {
                Log::error('PDF generation failed; sending email without attachment.', ['err' => $e->getMessage()]);
                // Continue without attachment
            }

            // 5) Send via Brevo HTTP API
            try {
                $resp = Http::withHeaders([
                    'api-key'      => env('BREVO_API_KEY'),
                    'accept'       => 'application/json',
                    'content-type' => 'application/json',
                ])->post('https://api.brevo.com/v3/smtp/email', $payload);

                if (!$resp->successful()) {
                    Log::error('Brevo API send failed', ['status' => $resp->status(), 'body' => $resp->body()]);
                    return back()->with('error', 'Email send failed.');
                }
            } catch (\Throwable $e) {
                Log::error('Brevo API request failed', ['err' => $e->getMessage()]);
                return back()->with('error', 'Email send failed.');
            }

            return back()->with('success', 'Member approved and verification email sent.');
        }


        public function disapproveMember(Request $request, $id)
        {
            $request->validate([
                'reason' => 'required|string|max:1000',
            ]);

            $member = User::findOrFail($id);

            // Build HTML from Blade
            $html = view('emails.member-disapproved', [
                'member' => $member,
                'reason' => $request->reason,
            ])->render();

            // Send via Brevo HTTP API (no SMTP)
            try {
                $apiKey = env('BREVO_API_KEY');
                if (empty($apiKey)) {
                    Log::error('BREVO_API_KEY missing; cannot send disapproval email.');
                    return back()->with('error', 'Disapproved, but email failed to send (missing API key).');
                }

                $payload = [
                    'sender' => [
                        'name'  => env('MAIL_FROM_NAME', 'ENREMCO'),
                        'email' => env('MAIL_FROM_ADDRESS', 'support@enremco.com'),
                    ],
                    'to'          => [[ 'email' => $member->email, 'name' => $member->name ]],
                    'subject'     => 'Registration Disapproved',
                    'htmlContent' => $html,
                ];

                $resp = Http::timeout(20)->withHeaders([
                    'api-key'      => $apiKey,
                    'accept'       => 'application/json',
                    'content-type' => 'application/json',
                ])->post('https://api.brevo.com/v3/smtp/email', $payload);

                if (!$resp->successful()) {
                    Log::error('Brevo disapprove send failed', ['status' => $resp->status(), 'body' => $resp->body()]);
                    return back()->with('error', 'Disapproved, but email failed to send.');
                }
            } catch (\Throwable $e) {
                Log::error('Disapprove email exception', ['err' => $e->getMessage()]);
                return back()->with('error', 'Disapproved, but email failed to send.');
            }

            // Delete the member only after attempting to notify
            $member->delete();

            return redirect()
                ->route('admin.new-members')
                ->with('success', 'Member disapproved and email sent.');
        }

    
        public function newMembers(Request $request)
        {
            // Fetch members with status "Awaiting Approval"
            // $newMembers = User::where('status', 'Awaiting Approval')->get();
            $perPage = $request->input('per_page', 10); // default is 10
            $newMembers = User::where('status', 'Awaiting Approval')->paginate($perPage);
            // return view('admin.new-members', compact('newMembers'));
            return view('admin.new-members', [
                'newMembers' => $newMembers,
                'perPage' => $perPage,
            ]);

        }

        

        public function editMember($id)
        {
            $member = User::findOrFail($id);
            return view('admin.partials.edit-member', compact('member')); // Return the update form partial view
        }
        
public function updateMember(Request $request, $id)
{
    try {
        $request->validate([
            'shares' => 'sometimes|nullable|numeric|min:0',
            'savings' => 'sometimes|nullable|numeric|min:0',
            'membership_date' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|in:Active,Inactive',
        ]);

        $member = User::findOrFail($id);
        $previousStatus = $member->status; // Store previous status

        // ✅ Update only if fields are present in the request
        if ($request->has('shares')) {
            $member->shares = $request->input('shares');
        }

        if ($request->has('savings')) {
            $member->savings = $request->input('savings');
        }

        if ($request->has('membership_date')) {
            $member->membership_date = $request->input('membership_date');
        }

        if ($request->has('status')) {
            $newStatus = $request->input('status');

            if ($newStatus === 'Inactive') {
                $member->date_inactive = now();
            } elseif ($newStatus === 'Active' && $previousStatus !== 'Active') {
                $member->date_reactive = now();
            }

            $member->status = $newStatus;
        }

        $member->save();

        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');


    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}





        public function deleteMember($id)
        {
            // Find the member by ID
            $member = User::findOrFail($id);
    
            // Optionally, delete the profile photo
            if ($member->photo && \Storage::exists('public/' . $member->photo)) {
                \Storage::delete('public/' . $member->photo);
            }
    
            // Delete the member
            $member->delete();
    
            // Redirect back with success message
            return redirect()->route('admin.members')->with('success', 'Member deleted successfully.');
        }

        public function inactivateMember($id)
        {
            // Find the member by ID
            $member = User::findOrFail($id);
        
            // Update the status to "Inactive"
            $member->status = 'Inactive';
            $member->date_inactive = now();
            $member->save();
        
            // Redirect back with a success message
            return redirect()->route('admin.members')->with('success', $member->name . ' has been marked as Inactive.');
        }
    
        public function markAsActive($id)
        {
            $member = User::findOrFail($id);
            $member->status = 'Active';
            $member->date_reactive = now(); // Assuming you want to set the date of reactivation
            $member->save();
        
            return redirect()->route('admin.members')->with('success', 'Member marked as active.');
        }

        public function loans()
        {
            $loans = LoanDetail::with('user')->get(); // Fetch loans with user data
            return view('admin.loans', compact('loans'));
        }

        

}
