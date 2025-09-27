<form method="POST" id="updateMemberForm" action="{{ route('admin.update-member', $member->id) }}">
    @csrf
    @method('PATCH')

    <div class="mb-3 member-details">
        <div class="profile-container">
            <div class="row">
                <div class="profile-left col-md-auto">
                    <div class="profile-photo">
                        <img src="{{ asset('storage/' . $member->photo) }}" alt="User Photo">
                    </div>
                </div>
                <div class="profile-right col-5">
                    <div class="personal-data">
                        <h4>Personal Data</h4>
                        <ul>
                            <li><strong>Name:</strong> {{ $member->name }}</li>
                            <li><strong>Email:</strong> {{ $member->email }}</li>
                            <li><strong>Position:</strong> {{ $member->position }}</li>
                            <li><strong>Address:</strong> {{ $member->address }}</li>
                            <li><strong>Sex:</strong> {{ $member->sex }}</li>
                            <li><strong>Marital Status:</strong> {{ $member->marital_status }}</li>
                            <li><strong>Office:</strong> {{ $member->office }}</li>
                            <li><strong>Contact No.:</strong> {{ $member->contact_no }}</li>
                            <li><strong>Annual Income:</strong> {{ $member->annual_income }}</li>
                            <li><strong>Beneficiary/ies:</strong> {{ $member->beneficiaries }}</li>
                            <li><strong>Birthdate:</strong> {{ $member->birthdate }}</li>
                            <li><strong>Educational Attainment:</strong> {{ $member->education }}</li>
                            <li><strong>Employee ID:</strong> {{ $member->employee_ID }}</li>
                            <li><strong>Membership Date:</strong> {{ $member->membership_date }}</li>
                            <li><strong>Username:</strong> {{ $member->username }}</li>
                            <li><strong>Status:</strong>
                                <span class="font-semibold {{ $member->status === 'Active' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $member->status }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="contribution-details col">
                    <h4>Contribution Details</h4>
                    <ul>
                        <li><strong>Shares:</strong> {{ $member->shares }}</li>
                        <li><strong>Savings:</strong> {{ $member->savings }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="shares" class="form-label">Shares</label>
        <input type="number" name="shares" class="form-control" id="shares" value="{{ $member->shares }}" step="any" min="0">
    </div>

    <div class="mb-3">
        <label for="savings" class="form-label">Savings</label>
        <input type="number" name="savings" class="form-control" id="savings" value="{{ $member->savings }}" step="any" min="0">
    </div>

    <div class="mb-3">
        <label for="membership_date" class="form-label">Date of Membership</label>
        <input type="date" name="membership_date" class="form-control" id="membership_date" value="{{ $member->membership_date ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="Active" {{ $member->status === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ $member->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <!-- Submit button to save changes -->
    <button type="submit" class="btn btn-primary">Update</button>

    <!-- Cancel button to go back to the members page -->
    <a href="{{ route('admin.members') }}" class="btn btn-secondary">Cancel</a>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("updateMemberForm").addEventListener("submit", function(event) {
        event.preventDefault();  // ✅ Prevent default form submission

        const form = event.target;
        const formData = new FormData(form);
        const memberId = form.getAttribute("action").split("/").pop();

        fetch(form.action, {
            method: "POST",
            headers: { 
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
        .then(response => response.json()) // ✅ Parse JSON response
        .then(data => {
            //console.log("✅ AJAX Response:", data); // Debugging output

            if (data.success) {
                alert("✅ Member updated successfully!");

                // ✅ Update the table row dynamically
                const row = document.querySelector(`tr[data-member-id="${memberId}"]`);
                if (row) {
                    row.querySelector(".status").textContent = data.status;
                    row.querySelector(".date-inactive").textContent = data.date_inactive ? data.date_inactive : 'N/A';
                    row.querySelector(".date-reactive").textContent = data.date_reactive ? data.date_reactive : 'N/A';
                }

                // ✅ Close the modal after update
                bootstrap.Modal.getInstance(document.getElementById("updateMemberModal")).hide();
            } else {
                alert("❌ Error updating member: " + data.error);
            }
        })
        .catch(error => {
            alert("❌ An error occurred while updating the member.");
            console.error("Error:", error);
        });
    });
});
</script>


