<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile</title>
</head>
<body>
    <h1>Member Profile</h1>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Address:</strong> {{ $user->address }}</p>
    <p><strong>Contact No.:</strong> {{ $user->contact_no }}</p>
    <p><strong>Office:</strong> {{ $user->office }}</p>
    <p><strong>Section:</strong> {{ $user->section }}</p>
    <p><strong>Position:</strong> {{ $user->position }}</p>
    <p><strong>SG Level:</strong> {{ $user->sg_level }}</p>
    <p><strong>Status:</strong> 
        <span class="font-semibold {{ auth()->user()->status === 'Active' ? 'text-green-500' : 'text-red-500' }}">
            {{ auth()->user()->status }}
        </span></p>
</body>
</html>
