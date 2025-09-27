<x-admin-layout>
    <div class="content-wrapper">
        <div class="page-title"><h4>Savings Management</h4></div>
        <div class="templates-container">
            <div class="row mb-4">
                <!-- Download Button on the Left -->
                <div class="col-md-6 text-left">
                    <a href="{{ url('/download/savings-template') }}" class="btn btn-success btn-block">Download Savings Template</a>
                </div>
                
                <!-- Upload Form on the Right -->
                <div class="col-md-6 text-right">
                    <form action="{{ route('savings.upload') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success btn-block">Upload Savings Template</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger" style="white-space: pre-line;">{{ session('error') }}</div>
            @endif
        </div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">Upload Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <pre class="text-danger">{{ session('error') }}</pre>
      </div>
    </div>
  </div>
</div>

    <!-- Filters and Actions -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Office Filter -->
        <div>
            <label for="officeFilter" class="form-label">Filter by Office</label>
            <select id="officeFilter" class="form-select">
                <option value="">All Offices</option>
                @foreach($offices as $office)
                    <option value="{{ $office }}">{{ $office }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Right-Aligned Inputs -->
        <div class="col-lg-2">
            <label for="officeFilter" class="form-label">Date of Remittance</label>
            <input type="date" id="latestRemittanceDate" class="form-control me-2">
        </div>

        <!-- Remittance No. -->
        <div class="col-lg-2">
            <label for="remittanceNo" class="form-label">Remittance/Receipt No.</label>
            <input type="text" id="remittanceNo" class="form-control me-2" placeholder="Enter Remittance No.">
        </div>

        <div class="covered-period-filter col-lg-3">
            <label for="officeFilter" class="form-label">Covered Period</label>
            <div class="d-flex gap-2">
                <!-- Month Dropdown -->
                <select id="covered_month" name="covered_month" class="form-control me-2" required>
                    <option value="">Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                </select>

                <!-- Year Dropdown -->
                <select id="covered_year" name="covered_year" class="form-control" required>
                    <option value="">Year</option>
                        @for ($y = date('Y'); $y >= date('Y') - 50; $y--)
                             <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                </select>
            </div>
        </div>

        <div class="col-lg-3">
            <label for="savingsAmount" class="form-label">Monthly Savings Amount</label>
            <div class="d-flex gap-2">
                <input type="number" id="savingsAmount" class="form-control me-2" placeholder="Enter Amount" step="any" min="0">
                <button id="addSavingsBtn" class="btn btn-primary">Add Monthly Savings</button>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-3">
        <label for="searchSavings" class="form-label">Search Savings</label>
        <input type="text" id="searchSavings" class="form-control" placeholder="Search by Employee Name, ID, or Office...">
    </div>

    <!-- Members Table -->
    <table class="table table-striped savings-table">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>No.</th>
                <th>Member ID</th>
                <th>Name</th>
                <th>Office</th>
                <th>Monthly Saving Contribution</th>
                <th>Initial Remittance</th>
                <th>Latest Remittance</th>
                <th>Total Savings</th>
                <th>Amount Withdrawn</th>
                <th>Remaining Balance</th>
                <th>No. of Months Contributed</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="membersTableBody">
            @php $count = 1; @endphp
            @foreach($members as $index => $member)
            @if($member->is_admin != 1) <!-- Skip if user is an admin -->
                @php
                    // Totals (use numeric users.id everywhere)
                    $totalSavings   = (float) ($savingTotals[$member->id] ?? 0);
                    $totalWithdrawn = (float) ($withdrawTotals[$member->id] ?? 0);
                    $remainingBalance = max($totalSavings - $totalWithdrawn, 0); // <-- fix

                    $monthsContributed = \App\Models\Saving::where('employees_id', $member->id)
                        ->whereNotNull('covered_month')->count();

                    $firstRemittance = \App\Models\Saving::where('employees_id', $member->id)
                        ->orderBy('date_remittance', 'asc')->value('date_remittance');

                    $latestRemittance = \App\Models\Saving::where('employees_id', $member->id)
                        ->orderBy('date_remittance', 'desc')->value('date_remittance');

                    $latestUpdated = \App\Models\Saving::where('employees_id', $member->id)
                        ->orderBy('date_updated', 'desc')->value('date_updated');
                @endphp

                <tr class="memberRow" data-name="{{ strtolower($member->name) }}" data-id="{{ strtolower($member->employee_ID) }}" data-office="{{ strtolower($member->office) }}" data-savings="{{ $member->savings }}">
                    <td><input type="checkbox" class="memberCheckbox" value="{{ $member->id }}"></td>
                    <td>{{ $count++ }}</td>
                    <td>{{ $member->employee_ID }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->office }}</td>
                    <td class="current-savings">{{ $member->savings }}</td>
                    <td>{{ $firstRemittance ?? 'N/A' }}</td>
                    <td>{{ $latestRemittance ?? 'N/A' }}</td>
                    <td>{{ number_format($totalSavings,2) }}</td>
                    <td>{{ number_format($totalWithdrawn,2) }}</td>
                    <td>{{ number_format($remainingBalance,2) }}</td>
                    <td>{{ $monthsContributed }}</td>
                    @php
                        $latestUpdated = \App\Models\Saving::where('employees_id', $member->id)
                            ->orderBy('date_updated', 'desc')->value('date_updated');
                    @endphp
                    <td>{{ $latestUpdated ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-info update-details-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#updateDetailsModal"
                            data-id="{{ $member->id }}" {{-- Auto-generated ID --}}
                            data-employee_id="{{ $member->employee_ID }}" {{-- Actual Employee Number (for display only) --}}
                            data-name="{{ $member->name }}"
                            data-office="{{ $member->office }}"
                            data-contribution="{{ $member->savings }}"
                            data-first-remittance="{{ $firstRemittance ?? 'N/A' }}"
                            data-latest-remittance="{{ $latestRemittance ?? 'N/A' }}"
                            data-total-savings="{{ $totalSavings }}"
                            data-months-contributed="{{ $monthsContributed }}">
                            Update
                        </button>
                        <button class="btn btn-info view-details-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDetailsModal"
                            data-id="{{ $member->id }}" {{-- Auto-generated ID --}}
                            data-employee_id="{{ $member->employee_ID }}" {{-- Actual Employee Number (for display only) --}}
                            data-name="{{ $member->name }}"
                            data-office="{{ $member->office }}"
                            data-contribution="{{ $member->savings }}"
                            data-first-remittance="{{ $firstRemittance ?? 'N/A' }}"
                            data-latest-remittance="{{ $latestRemittance ?? 'N/A' }}"
                            data-total-savings="{{ $totalSavings }}"
                            data-months-contributed="{{ $monthsContributed }}">
                            View Contributions
                        </button>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>


<!-- Modal for Updating Member Details -->
<div class="modal fade" id="updateDetailsModal" tabindex="-1" aria-labelledby="updateDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header btn-primary text-white">
                <h5 class="modal-title" id="viewDetailsModalLabel">Update Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><th>Member ID</th><td id="modalUpdateEmployeeID"></td></tr>
                        <tr><th>Name</th><td id="modalUpdateName"></td></tr>
                        <tr><th>Office</th><td id="modalUpdateOffice"></td></tr>
                        <tr><th>Monthly Saving Contribution</th><td id="modalUpdateContribution"></td></tr>
                        <tr><th>Initial Remittance</th><td id="modalUpdateFirstRemittance"></td></tr>
                        <tr><th>Latest Remittance</th><td id="modalUpdateLatestRemittance"></td></tr>
                        <tr><th>Total Savings</th><td id="modalUpdateTotalSavings"></td></tr>
                        <tr><th>No. of Months Contributed</th><td id="modalUpdateMonthsContributed"></td></tr>
                    </tbody>
                </table>

                <!-- Remittance Input Field -->
                <div class="mt-3">
                    <label for="remittanceFilter" class="form-label">Enter Remittance No.</label>
                    <input type="text" id="searchRemittanceModal" class="form-control" placeholder="Enter Remittance No.">
                    <button class="btn btn-primary mt-2" id="searchRemittanceBtn">Search Remittance No.</button>
                </div>

                <!-- Contributions Table -->
                <div id="remittanceResult" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Viewing Member Details -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header btn-primary text-white">
                <h5 class="modal-title" id="viewDetailsModalLabel">Member Contribution Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><th>Member ID</th><td id="modalEmployeeID"></td></tr>
                        <tr><th>Name</th><td id="modalName"></td></tr>
                        <tr><th>Office</th><td id="modalOffice"></td></tr>
                        <tr><th>Monthly Saving Contribution</th><td id="modalContribution"></td></tr>
                        <tr><th>Initial Remittance</th><td id="modalFirstRemittance"></td></tr>
                        <tr><th>Latest Remittance</th><td id="modalLatestRemittance"></td></tr>
                        <tr><th>Total Savings</th><td id="modalTotalSavings"></td></tr>
                        <tr><th>No. of Months Contributed</th><td id="modalMonthsContributed"></td></tr>
                    </tbody>
                </table>

                <!-- Year Input Field -->
                <div class="mt-3">
                    <label for="yearFilter" class="form-label">Enter Year</label>
                    <input type="number" id="yearFilter" class="form-control" min="2000" max="{{ date('Y') }}" placeholder="Enter year">
                    <button class="btn btn-primary mt-2" id="viewYearContributions">View Contributions</button>
                </div>

                <!-- Contributions Table -->
                <div id="contributionsResult" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal for No Checkbox Selected -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please select at least one member before adding savings.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    const memberCheckboxes = document.querySelectorAll('.memberCheckbox');
    const addSavingsBtn = document.getElementById('addSavingsBtn');
    const savingsAmount = document.getElementById('savingsAmount');
    const officeFilter = document.getElementById('officeFilter');
    const latestRemittanceDate = document.getElementById('latestRemittanceDate');

    function toggleAddSavingsButton() {
        const selected = document.querySelectorAll('.memberCheckbox:checked').length > 0;
        addSavingsBtn.disabled = !selected;

        // Disable filtering when checkboxes are selected
        savingsAmount.disabled = selected;
        officeFilter.disabled = selected;
    }

    // Select All Checkbox Logic
    selectAllCheckbox.addEventListener('change', function () {
        memberCheckboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        toggleAddSavingsButton();
    });

    // Individual Checkbox Logic
    memberCheckboxes.forEach(cb => {
        cb.addEventListener('change', toggleAddSavingsButton);
    });

    // Office Filtering
    officeFilter.addEventListener('change', function () {
        const selectedOffice = officeFilter.value.toLowerCase().trim();

        document.querySelectorAll('.memberRow').forEach(row => {
            const office = row.getAttribute('data-office').toLowerCase().trim();
            row.style.display = selectedOffice === '' || office === selectedOffice ? '' : 'none';
        });

        // Reset savings amount filter
        savingsAmount.value = "";
    });

    // Savings Filtering - Apply only to selected office
    savingsAmount.addEventListener('input', function () {
        const filterValue = parseInt(savingsAmount.value, 10);
        const selectedOffice = officeFilter.value;

        document.querySelectorAll('.memberRow').forEach(row => {
            const savings = parseInt(row.getAttribute('data-savings'), 10);
            const office = row.getAttribute('data-office');

            // Apply filtering only to members within the selected office
            if (selectedOffice === '' || office === selectedOffice) {
                row.style.display = isNaN(filterValue) || savings === filterValue ? '' : 'none';
            }
        });
    });

    // Handle Adding Savings
addSavingsBtn.addEventListener('click', function () {
    let selectedIds = Array.from(document.querySelectorAll('.memberCheckbox:checked'))
        .map(cb => cb.value);

    let amount = savingsAmount.value;
    let remittanceDate = latestRemittanceDate.value;
    let coveredMonth = document.getElementById('covered_month').value;
    let remittanceNo = document.getElementById('remittanceNo').value;
    let coveredYear = document.getElementById('covered_year').value;

    if (selectedIds.length === 0) {
        let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
        return;
    }

    if (!amount || isNaN(amount) || amount <= 0) {
        alert('Enter a valid savings amount.');
        return;
    }

    if (!remittanceDate) {
        alert('Please select a remittance date.');
        return;
    }

    if (!coveredMonth || !coveredYear) {
        alert('Please select a covered period (Month and Year).');
        return;
    }

    fetch("{{ route('admin.bulk-add-savings') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            member_ids: selectedIds, 
            amount: amount, 
            date_remittance: remittanceDate,
            remittance_no: remittanceNo,
            covered_month: coveredMonth,
            covered_year: coveredYear
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.duplicates && data.duplicates.length > 0) {
            // Show only the duplicates — do not reload
            let duplicatesMessage = "The following remittances already exist:\n";
            data.duplicates.forEach(duplicate => {
                duplicatesMessage += `Remittance No: ${duplicate.remittance_no}, Covered Period: ${duplicate.covered_month}/${duplicate.covered_year}\n`;
            });
            alert(duplicatesMessage);
        } else if (data.success) {
            // No duplicates, proceed normally
            alert('Shares added successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        console.error('Error:', error);
    });
});

document.getElementById("searchSavings").addEventListener("keyup", function () {
    let query = this.value.toLowerCase();
    document.querySelectorAll("#membersTableBody .memberRow").forEach(row => {
        let name = row.getAttribute("data-name");
        let id = row.getAttribute("data-id");
        let office = row.getAttribute("data-office");

        row.style.display = name.includes(query) || id.includes(query) || office.includes(query) ? "" : "none";
    });
});


document.querySelectorAll(".update-details-btn").forEach(button => {
    button.addEventListener("click", function () {
        // ✅ DISPLAY: Proper employee_ID in the modal
        document.getElementById("modalUpdateEmployeeID").textContent = this.dataset.employee_id;
        // ✅ STORE the users.id in a hidden field for later fetch
        document.getElementById("searchRemittanceBtn").setAttribute("data-user-update-id", this.dataset.id);
        document.getElementById("modalUpdateName").textContent = this.dataset.name;
        document.getElementById("modalUpdateOffice").textContent = this.dataset.office;
        document.getElementById("modalUpdateContribution").textContent = this.dataset.contribution;
        document.getElementById("modalUpdateFirstRemittance").textContent = this.dataset.firstRemittance;
        document.getElementById("modalUpdateLatestRemittance").textContent = this.dataset.latestRemittance;
        document.getElementById("modalUpdateTotalSavings").textContent = this.dataset.totalSavings;
        document.getElementById("modalUpdateMonthsContributed").textContent = this.dataset.monthsContributed;

        document.getElementById("searchRemittanceModal").value = "";
        document.getElementById("remittanceResult").innerHTML = "";
    });
});


document.querySelectorAll(".view-details-btn").forEach(button => {
    button.addEventListener("click", function () {
        // ✅ DISPLAY: Proper employee_ID in the modal
        document.getElementById("modalEmployeeID").textContent = this.dataset.employee_id;
        // ✅ STORE the users.id in a hidden field for later fetch
        document.getElementById("viewYearContributions").setAttribute("data-user-id", this.dataset.id);
        document.getElementById("modalName").textContent = this.dataset.name;
        document.getElementById("modalOffice").textContent = this.dataset.office;
        document.getElementById("modalContribution").textContent = this.dataset.contribution;
        document.getElementById("modalFirstRemittance").textContent = this.dataset.firstRemittance;
        document.getElementById("modalLatestRemittance").textContent = this.dataset.latestRemittance;
        document.getElementById("modalTotalSavings").textContent = this.dataset.totalSavings;
        document.getElementById("modalMonthsContributed").textContent = this.dataset.monthsContributed;

        document.getElementById("yearFilter").value = "";
        document.getElementById("contributionsResult").innerHTML = "";
    });
});

document.getElementById("viewYearContributions").addEventListener("click", function () {
    let year = document.getElementById("yearFilter").value.trim();
    let employeeID = document.getElementById("viewYearContributions").getAttribute("data-user-id");

    console.log("Fetching contributions for Member ID:", employeeID, "Year:", year);

    if (!year) {
        alert("Please enter a year.");
        return;
    }

    let url = `/admin/get-savings/${encodeURIComponent(employeeID)}/${year}`;
    console.log("Request URL:", url);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log("Fetched Contributions Data:", data);

            let tableContent = `<table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date Remittance</th>
                                            <th>Remittance No.</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

            if (data.contributions && data.contributions.length > 0) {
                data.contributions.forEach(contribution => {
                    tableContent += `<tr>
                        <td>${contribution.date_remittance || 'N/A'}</td>
                        <td>${contribution.remittance_no || 'N/A'}</td>
                        <td>${contribution.month_name || 'N/A'}</td>
                        <td>${contribution.covered_year || 'N/A'}</td>
                        <td>${contribution.amount || 'N/A'}</td>
                    </tr>`;
                });
            } else {
                console.warn("No contributions found in response.");
                tableContent += `<tr><td colspan="5" class="text-center">No contributions found</td></tr>`;
            }

            tableContent += `</tbody></table>
                             <button id="printContributions" class="btn btn-primary mt-3">Print Contribution</button>`;
            document.getElementById("contributionsResult").innerHTML = tableContent;
            // Add print functionality
            document.getElementById("printContributions").addEventListener("click", function () {
                // Remove the print button before printing
                let printContent = document.getElementById("contributionsResult").innerHTML;
                printContent = printContent.replace('<button id="printContributions" class="btn btn-primary mt-3">Print Contribution</button>', '');

                // Open a new window for printing
                const newWindow = window.open('', '', 'width=800, height=600');
                newWindow.document.write('<html><head><title>Print Contributions</title>');
                
                // Add custom styles for printing
                newWindow.document.write('<style>@media print { body { font-family: Arial, sans-serif; font-size: 12px; } table { width: 100%; border-collapse: collapse; } th, td { padding: 8px; text-align: left; border: 1px solid #ddd; } }</style>');
                
                // Write the content inside the new window
                newWindow.document.write('</head><body>');
                newWindow.document.write(`<h2>Contribution for the year ${year}</h2>`);
                newWindow.document.write(printContent);
                newWindow.document.write('<p>This is a system generated data.</p>');
                newWindow.document.write('</body></html>');
                newWindow.document.close();
                
                // Trigger the print dialog
                newWindow.print();
            });
        })
        .catch(error => {
            console.error("Error fetching contributions:", error);
            document.getElementById("contributionsResult").innerHTML = `<p class="text-danger">Failed to load contributions.</p>`;
        });
    });

document.getElementById("searchRemittanceBtn").addEventListener("click", function () {
    let searchQuery = document.getElementById("searchRemittanceModal").value.trim();
    let userId = this.getAttribute("data-user-update-id");
    
    // Fix: Get the correct Employee ID from the modal field
    let updateemployeeID = document.getElementById("modalUpdateEmployeeID").textContent.trim(); 

    console.log("🔍 Searching Contributions...");
    console.log("User ID:", userId);
    console.log("Search Query:", searchQuery);

    if (!searchQuery) {
        alert("⚠️ Please enter a search query (Year or Remittance No.)");
        return;
    }

    let requestUrl = `/admin/get-savings/${encodeURIComponent(userId)}/${encodeURIComponent(searchQuery)}`;
    console.log("📡 Request URL:", requestUrl);

    fetch(requestUrl)
        .then(response => response.json())
        .then(data => {
            console.log("✅ Fetched Contributions Data:", data);

            let tableContent = `<table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date Remittance</th>
                                            <th>Remittance No.</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

            if (data.success && data.contributions.length > 0) {
                data.contributions.forEach((contribution, index) => {
                    tableContent += `<tr data-saving-id="${contribution.savings_id}">
                        <td><input type="date" class="form-control" name="date_remittance_${index}" value="${contribution.date_remittance || ''}"></td>
                        <td><input type="text" class="form-control" name="remittance_no_${index}" value="${contribution.remittance_no || ''}"></td>
                        <td><input type="text" class="form-control" name="month_name_${index}" value="${contribution.month_name || ''}"></td>
                        <td><input type="text" class="form-control" name="covered_year_${index}" value="${contribution.covered_year || ''}"></td>
                        <td><input type="number" class="form-control" name="amount_${index}" value="${contribution.amount || ''}" step="any" min="0"></td>
                    </tr>`;
                });
            } else {
                console.warn("⚠️ No contributions found.");
                tableContent += `<tr><td colspan="5" class="text-center">${data.message || 'No contributions found'}</td></tr>`;
            }

            tableContent += `</tbody></table>
                            <div class="text-end mt-3">
                                <button class="btn btn-success" id="saveRemittanceChangesBtn">Save Changes</button>
                            </div>`;
            document.getElementById("remittanceResult").innerHTML = tableContent;
        })

        .catch(error => {
            console.error("❌ Error fetching contributions:", error);
            document.getElementById("remittanceResult").innerHTML = `<p class="text-danger">⚠️ Failed to load contributions.</p>`;
        });
});

document.addEventListener("click", function (e) {
    if (e.target && e.target.id === "saveRemittanceChangesBtn") {
        let rows = document.querySelectorAll("#remittanceResult table tbody tr");
        let updates = [];

        rows.forEach((row, index) => {
            let savings_id = row.getAttribute("data-saving-id");
            let date_remittance = row.querySelector(`input[name="date_remittance_${index}"]`).value;
            let remittance_no = row.querySelector(`input[name="remittance_no_${index}"]`).value;
            let month_name = row.querySelector(`input[name="month_name_${index}"]`).value;
            let covered_year = row.querySelector(`input[name="covered_year_${index}"]`).value;
            let amount = row.querySelector(`input[name="amount_${index}"]`).value;

            if (!date_remittance || !remittance_no || !month_name || !covered_year || isNaN(amount)) {
                alert(`⚠️ Please fill all fields correctly for row ${index + 1}`);
                return;
            }

            updates.push({
                savings_id: savings_id,
                date_remittance: date_remittance,
                remittance_no: remittance_no,
                month_name: month_name,
                covered_year: covered_year,
                amount: amount,
            });
        });

        if (updates.length === 0) {
            alert("⚠️ Nothing to save.");
            return;
        }

        fetch("/admin/update-saving-remittances", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ updates: updates })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("✅ Remittances updated successfully!");
                const updateModal = bootstrap.Modal.getInstance(document.getElementById('updateDetailsModal'));
                updateModal.hide(); 
                location.reload();
            } else {
                alert("⚠️ " + (data.message || "No changes made."));
            }
        })
        .catch(error => {
            console.error("Error saving remittances:", error);
            alert("❌ Something went wrong while saving.");
        });
    }
});


@if(session('error'))

        document.addEventListener('DOMContentLoaded', function () {
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        });

@endif


});
</script>
</x-admin-layout>
