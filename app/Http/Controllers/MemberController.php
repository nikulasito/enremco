<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Mail\RegistrationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Exports\ApprovedMembersExport;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    public function create()
    {
        return view('member.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_no' => 'required|string|max:15',
            'email' => 'required|email|unique:members,email',
            'office' => 'required|string|max:255',
            'religion' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female,Other',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'annual_income' => 'required|numeric|min:0|max:1000000000',
            'beneficiaries' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'education' => 'nullable|string|max:255',
            'employee_ID' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048', // Validate photo
        ]);
        
    // Handle photo upload
    $photoPath = $request->file('photo')->store('photos', 'public');

            // Save the member to the database
        $member = new Member();
        $member->name = $request->name;
        $member->photo = $photoPath; // Save the file path
        $member->save();

        Member::create($request->all());
        return redirect()->back()->with('success', 'Registration submitted successfully!');

        // Send email verification
        $member->sendEmailVerificationNotification();

        // Configure Dompdf Options
        $options = new Options();
        $options->set('defaultFont', 'Courier'); // Optional: Change the default font if needed

        // Initialize Dompdf
        $dompdf = new Dompdf($options);

        // Load the HTML content from the Blade view
        $html = view('emails.registration-details', ['member' => $notifiable])->render();
        $dompdf->loadHtml($html);

        // Set Paper Size and Orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Save the PDF or get the binary output
        $pdfOutput = $dompdf->output();

        // Optional: Save the PDF to a file
        file_put_contents(storage_path('app/public/RegistrationDetails.pdf'), $pdfOutput);

        // // Attach the PDF to an email
        // \Mail::to($member->email)->send(new \App\Mail\YourMailClass($pdfOutput));

        // Send email with PDF attachment
        Mail::to($member->email)->send(new RegistrationEmail($member, $pdf));

        return redirect()->route('verification.notice')->with('success', 'Please verify your email.');
    }

    public function show()
    {
        $user = auth()->user(); // Get the authenticated user
        return view('member.profile', compact('user'));
    }


    public function downloadMembers()
    {

        $members = User::where('is_admin', '!=', 1)
        ->where('status', 'active')
        ->select('id', 'name', 'office', 'status', 'date_approved', 'date_inactive', 'date_reactive')
        ->get();

        return Excel::download(new ApprovedMembersExport($members), 'approved_members.xlsx');
    }
}
