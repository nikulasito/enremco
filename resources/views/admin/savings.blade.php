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
<div class="modal fade" id="uploadErrorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
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

    <form method="GET" action="{{ url()->current() }}" id="savingsFiltersForm" class="mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="officeFilter" class="form-label">Filter by Office</label>
                <select id="officeFilter" name="office" class="form-select">
                    <option value="">All Offices</option>
                    @foreach($offices as $office)
                        <option value="{{ $office }}" {{ request('office') === $office ? 'selected' : '' }}>
                            {{ $office }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5">
                <label for="searchSavings" class="form-label">Search Savings</label>
                <input type="text" id="searchSavings" class="form-control"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by Employee Name, ID, or Office...">
            </div>

            <div class="col-md-2">
                <label for="per_page" class="form-label">Show</label>
                <select name="per_page" id="per_page" class="form-select">
                    @foreach ([10, 20, 50, 100] as $option)
                        <option value="{{ $option }}" {{ (request('per_page', $perPage ?? 10)) == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="button" id="clearSavingsFilters" class="btn btn-secondary w-100">Clear</button>
            </div>
        </div>
    </form>

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
            @include('admin.savings._rows')
        </tbody>
    </table>

    <div id="savingsPagination" class="mt-4">
        @include('admin.savings._pagination')
    </div>


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
    <div class="modal fade" id="noSelectionModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
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

    const filtersForm   = document.getElementById('savingsFiltersForm');
    const searchInput   = document.getElementById('searchSavings');
    const officeSelect  = document.getElementById('officeFilter');
    const perPageSelect = document.getElementById('per_page');
    const clearBtn      = document.getElementById('clearSavingsFilters');

    // Prevent Enter from submitting and refreshing
    filtersForm.addEventListener('submit', (e) => e.preventDefault());

    let debounceTimer = null;
    let abortCtrl = null;

    function buildParams(page = 1) {
        return new URLSearchParams({
            search: searchInput.value || '',
            office: officeSelect.value || '',
            per_page: perPageSelect.value || 10,
            page: page
        });
    }

    function syncUrl(params) {
        // keeps URL updated without refresh
        const newUrl = `${filtersForm.action}?${params.toString()}`;
        history.replaceState({}, '', newUrl);
    }

    function fetchSavings(page = 1) {
        const params = buildParams(page);
        syncUrl(params);

        if (abortCtrl) abortCtrl.abort();
        abortCtrl = new AbortController();

        fetch(`{{ route('admin.savings.partial') }}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: abortCtrl.signal
        })
        .then(r => r.json())
        .then(res => {
            document.getElementById('membersTableBody').innerHTML = res.tbody;
            document.getElementById('savingsPagination').innerHTML = res.pagination;

            // Reset select all checkbox after refresh
            const selectAll = document.getElementById('selectAll');
            if (selectAll) selectAll.checked = false;

            // Re-evaluate button state after refresh
            toggleAddSavingsButton();
        })
        .catch(err => {
            if (err.name !== 'AbortError') console.error(err);
        });
    }

    // Auto search while typing (no refresh)
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchSavings(1), 350);
    });

    // Filter changes (no refresh)
    officeSelect.addEventListener('change', () => fetchSavings(1));
    perPageSelect.addEventListener('change', () => fetchSavings(1));

    // Clear (no refresh)
    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        officeSelect.value = '';
        fetchSavings(1);
    });

    // AJAX pagination (no refresh)
    document.addEventListener('click', (e) => {
        const link = e.target.closest('#savingsPagination a');
        if (!link) return;

        e.preventDefault();
        const url = new URL(link.href);
        const page = url.searchParams.get('page') || 1;
        fetchSavings(page);
    });


    const selectAllCheckbox = document.getElementById('selectAll');
    const memberCheckboxes = document.querySelectorAll('.memberCheckbox');
    const addSavingsBtn = document.getElementById('addSavingsBtn');
    const savingsAmount = document.getElementById('savingsAmount');
    const officeFilter = document.getElementById('officeFilter');
    const latestRemittanceDate = document.getElementById('latestRemittanceDate');

    function toggleAddSavingsButton() {
        const selected = document.querySelectorAll('.memberCheckbox:checked').length > 0;
        addSavingsBtn.disabled = !selected;

        // Disable filters while selecting (optional)
        searchInput.disabled = selected;
        officeSelect.disabled = selected;
        perPageSelect.disabled = selected;

        // DO NOT disable the amount input
        // savingsAmount.disabled = selected;
    }

    // Select All Checkbox Logic
    selectAllCheckbox.addEventListener('change', function () {
        document.querySelectorAll('.memberCheckbox').forEach(cb => cb.checked = selectAllCheckbox.checked);
        toggleAddSavingsButton();
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
        let m = new bootstrap.Modal(document.getElementById('noSelectionModal'));
        m.show();
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
            alert('Savings added successfully!');
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

document.addEventListener('click', function (e) {
    const updateBtn = e.target.closest('.update-details-btn');
    if (updateBtn) {
        document.getElementById("modalUpdateEmployeeID").textContent = updateBtn.dataset.employee_id;
        document.getElementById("searchRemittanceBtn").setAttribute("data-user-update-id", updateBtn.dataset.id);
        document.getElementById("modalUpdateName").textContent = updateBtn.dataset.name;
        document.getElementById("modalUpdateOffice").textContent = updateBtn.dataset.office;
        document.getElementById("modalUpdateContribution").textContent = updateBtn.dataset.contribution;
        document.getElementById("modalUpdateFirstRemittance").textContent = updateBtn.dataset.firstRemittance;
        document.getElementById("modalUpdateLatestRemittance").textContent = updateBtn.dataset.latestRemittance;
        document.getElementById("modalUpdateTotalSavings").textContent = updateBtn.dataset.totalSavings;
        document.getElementById("modalUpdateMonthsContributed").textContent = updateBtn.dataset.monthsContributed;

        document.getElementById("searchRemittanceModal").value = "";
        document.getElementById("remittanceResult").innerHTML = "";
        return;
    }

    const viewBtn = e.target.closest('.view-details-btn');
    if (viewBtn) {
        document.getElementById("modalEmployeeID").textContent = viewBtn.dataset.employee_id;
        document.getElementById("viewYearContributions").setAttribute("data-user-id", viewBtn.dataset.id);
        document.getElementById("modalName").textContent = viewBtn.dataset.name;
        document.getElementById("modalOffice").textContent = viewBtn.dataset.office;
        document.getElementById("modalContribution").textContent = viewBtn.dataset.contribution;
        document.getElementById("modalFirstRemittance").textContent = viewBtn.dataset.firstRemittance;
        document.getElementById("modalLatestRemittance").textContent = viewBtn.dataset.latestRemittance;
        document.getElementById("modalTotalSavings").textContent = viewBtn.dataset.totalSavings;
        document.getElementById("modalMonthsContributed").textContent = viewBtn.dataset.monthsContributed;

        document.getElementById("yearFilter").value = "";
        document.getElementById("contributionsResult").innerHTML = "";
    }
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
        var errorModal = new bootstrap.Modal(document.getElementById('uploadErrorModal'));
        errorModal.show();
    });
@endif



});
</script>
</x-admin-layout>
