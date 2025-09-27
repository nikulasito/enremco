<p>Dear {{ $member->name }},</p>

<p>Thank you for registering. Please verify your email address by clicking the link below:</p>

<p>
    <a href="{{ URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['id' => $member->id, 'hash' => sha1($member->email)]) }}"
        style="display: inline-block; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
        Verify Email
    </a>
</p>

<p>Attached to this email are your registration details in PDF format for your records.</p>

<p>If you did not register, please ignore this email.</p>
