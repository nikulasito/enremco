<x-admin-layout>
<div class="content-wrapper">
        <div class="page-title"><h4>Loan Payments</h4></div>

    <div class="templates-container">
        <div class="row mb-4">
            <div class="col-md-6 text-left">
                <a href="{{ url('/download/loan-payments-template') }}" class="btn btn-success btn-block">
                    Download Loan Payments Template
                </a>
            </div>
            <div class="col-md-6 text-right">
            <form action="{{ route('loan-payments.upload') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success btn-block">Upload Template</button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="white-space: pre-line;">{{ session('error') }}</div>
@endif
    </div>

                <!-- Filter by Office -->
            <div class="d-flex align-items-center mb-3 gap-3">
                <div class="col-lg-2">
                    <label>Filter by Office</label>
                    <select id="filter_office" class="form-control">
                        <option value="">All Offices</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office }}">{{ $office }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Loan Type -->
                <div class="col-lg-2">
                    <label>Filter by Loan Type</label>
                    <select id="filter_loan_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="Regular Loan">Regular Loan</option>
                        <option value="Educational Loan">Educational Loan</option>
                        <option value="Appliance Loan">Appliance Loan</option>
                        <option value="Grocery Loan">Grocery Loan</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Remittance No. -->
                <div class="col-lg-2">
                    <label for="remittance_no">Remittance No.:</label>
                    <input type="text" id="remittance_no" class="form-control" placeholder="Enter Remittance No.">
                </div>

                <div class="col-lg-2">
                    <label for="date_of_remittance">Date of Remittance:</label>
                    <input type="date" id="date_of_remittance" class="form-control">
                </div>

                <!-- Date Covered -->
                <div class="col-lg-2">
                    <label for="date_covered_month">Date Covered (Month):</label>
                    <select id="date_covered_month" class="form-control">
                        <option value="">Select Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="date_covered_year">Date Covered (Year):</label>
                    <select id="date_covered_year" class="form-control">
                        <option value="">Select Year</option>
                        @for ($y = date('Y'); $y >= date('Y') - 20; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-3">
                <label for="searchLoanPayments" class="form-label">Search Loan Payments</label>
                <input type="text" id="searchLoanPayments" class="form-control" placeholder="Search by Employee Name, Loan ID, or Office...">
            </div>

            <!-- Loan Payment Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>No.</th>
                        <th>Loan ID</th>
                        <th>Employee ID</th>
                        <th>Employee</th>
                        <th>Office</th>
                        <th>Loan Type</th>
                        <th>Loan Amount</th>
                        <th>Terms</th>
                        <th>Total Deduction</th>
                        <th>Net Amount</th>
                        <th>Monthly Payment</th>
                        <th>Total Payments</th>
                        <th>Balance</th>
                        <th>Latest Remittance</th>
                        <th>Remittance No.</th>
                        <th>Date of Remittance</th>
                        <th>Date Covered</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="loanPaymentTable">
                    @foreach ($loans as $index => $loan)
                        @if ($loan->latest_outstanding_balance > 0) <!-- ✅ Hide loans with zero balance -->
                            @php
                                $userName = strtolower(optional($loan->user)->name ?? '');
                                $userOffice = strtolower(optional($loan->user)->office ?? '');
                                $empId = strtolower($loan->employee_ID ?? '');
                            @endphp

                            <tr class="loanPaymentRow"
                                data-name="{{ $userName }}"
                                data-id="{{ $empId }}"
                                data-office="{{ $userOffice }}"
                                data-loan-id="{{ $loan->loan_id }}">
                                <td><input type="checkbox"></td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $loan->loan_id }}</td>
                                <td>{{ $loan->employee_ID }}</td>
                                <td>{{ optional($loan->user)->name ?? 'NA' }}</td>
                                <td>{{ optional($loan->user)->office ?? 'NA' }}</td>
                                <td>{{ $loan->loan_type }}</td>
                                <td>{{ number_format($loan->loan_amount, 2) }}</td>
                                <td>{{ $loan->terms }}</td>
                                <td>{{ $loan->total_deduction }}</td>
                                <td>{{ $loan->total_net }}</td>
                                <td>{{ number_format($loan->monthly_payment, 2) }}</td>
                                <td class="total-payments">{{ $loan['total_payments'] }}</td>
                                <td class="outstanding-balance">{{ $loan['latest_outstanding_balance'] }}</td>
                                <td class="latest-payment">
                                    {{ optional($loan->latestPayment)->latest_remittance ?? 'No Remittance Yet' }}
                                </td>
                                <td class="remittance-no">{{ optional($loan->latestPayment)->remittance_no ?? 'N/A' }}</td>
                                <td class="date-of-remittance">{{ optional($loan->latestPayment)->date_of_remittance ?? 'N/A' }}</td>
                                <td class="date-of-remittance">
                                    {{ optional($loan->latestPayment)->date_covered_month ? date('F', mktime(0, 0, 0, optional($loan->latestPayment)->date_covered_month, 1)) : 'N/A' }},
                                    {{ optional($loan->latestPayment)->date_covered_year ?? 'N/A' }}
                                </td>
                                <td>
                                    <input type="number" class="form-control payment-amount"
                                        data-loan-id="{{ $loan['loan_id'] }}"
                                        placeholder="Enter Amount"
                                        value="{{ $loan->monthly_payment }}" 
                                    >
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm update-payment-btn"
                                        data-loan-id="{{ $loan->loan_id }}"
                                        data-employee-id="{{ $loan->employee_ID }}"
                                        data-employee-name="{{ optional($loan->user)->name ?? 'NA' }}"
                                        data-loan-type="{{ $loan->loan_type }}"
                                        data-monthly-payment="{{ $loan->monthly_payment }}"
                                        data-remittance-no="{{ optional($loan->latestPayment)->remittance_no }}"
                                        data-date-of-remittance="{{ optional($loan->latestPayment)->date_of_remittance }}"
                                        data-date-covered-month="{{ optional($loan->latestPayment)->date_covered_month }}"
                                        data-date-covered-year="{{ optional($loan->latestPayment)->date_covered_year }}"
                                        data-total-payments="{{ optional($loan->latestPayment)->total_payments }}"
                                    >
                                        Update
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>

            <!-- Modal -->
<div class="modal fade" id="updatePaymentModal" tabindex="-1" aria-labelledby="updatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="updatePaymentForm">
                @csrf
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="updatePaymentModalLabel">Update Loan Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Remittance No.</label>
                            <input type="text" id="search_remittance_no" class="form-control" placeholder="Search Remittance No.">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary w-100" id="searchRemittanceBtn">Search</button>
                        </div>
                    </div>

                    <div id="paymentDetailsSection" style="display: none;">
                        <input type="hidden" name="loan_id" id="modal_loan_id">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Employee ID</label>
                                <input type="text" id="modal_employee_id" class="form-control disabled" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Employee Name</label>
                                <input type="text" id="modal_employee_name" class="form-control disabled" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Loan Type</label>
                                <input type="text" id="modal_loan_type" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Remittance No.</label>
                                <input type="text" id="modal_remittance_no" name="remittance_no" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Date of Remittance</label>
                                <input type="date" id="modal_date_of_remittance" name="date_of_remittance" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Total Payment</label>
                                <input type="text" id="modal_total_payment" name="total_payments" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Covered Month</label>
                                <select id="modal_date_covered_month" name="date_covered_month" class="form-control">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Covered Year</label>
                                <select id="modal_date_covered_year" name="date_covered_year" class="form-control">
                                    @for ($y = date('Y'); $y >= date('Y') - 20; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="savePaymentBtn" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



            <!-- Add Payment Button -->
            <div class="text-center mt-3">
                <button class="btn btn-primary bulkAddPayment" disabled>Add Payment</button>
            </div>

</div>
<!-- JavaScript for Filtering and AJAX -->
<script>
   document.addEventListener("DOMContentLoaded", function() {

    const STORE_BULK_URL = "{{ route('admin.loan-payments.store-bulk') }}";
    const UPDATE_URL     = "{{ route('admin.loan-payments.update') }}";
    const REMITTANCE_URL = "{{ url('admin/loan-payments/remittance') }}";

    const selectAllCheckbox = document.getElementById("selectAll");
    const bulkAddPaymentBtn = document.querySelector(".bulkAddPayment");
    const checkboxes = document.querySelectorAll("#loanPaymentTable input[type='checkbox']");
    
    function updateButtonState() {
        let anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        bulkAddPaymentBtn.disabled = !anyChecked;
    }

    // ✅ Select/Deselect All
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("click", function () {
            let checked = this.checked;
            checkboxes.forEach(checkbox => checkbox.checked = checked);
            updateButtonState();
        });
    }

    // ✅ Enable/Disable Button on Checkbox Change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateButtonState);
    });

// ✅ Bulk Add Payment Function
bulkAddPaymentBtn.addEventListener("click", function () {
        let selectedLoans = [];

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            let row = checkbox.closest("tr");
            let loanId = row.dataset.loanId;
            let amountInput = row.querySelector(".payment-amount");
            let balanceCell = row.querySelector(".outstanding-balance");

            let amount = parseFloat(amountInput.value) || 0;
            let outstandingBalance = parseFloat(balanceCell.textContent.replace(/,/g, "")) || 0;

            if (amount <= 0) {
                alert(`⚠ Invalid payment for Loan ID ${loanId}. Amount must be greater than 0.`);
                return;
            }

            if (amount > outstandingBalance) {
                alert(`⚠ Error: Payment for Loan ID ${loanId} cannot exceed the outstanding balance of ${outstandingBalance.toFixed(2)}.`);
                return;
            }

            selectedLoans.push({
                loan_id: loanId,
                total_payments: amount
            });
        }
    });

        if (selectedLoans.length === 0) {
            alert("⚠ Please select at least one loan with a valid amount.");
            return;
        }

        let remittanceNo = document.getElementById("remittance_no").value;
        let dateOfRemittance = document.getElementById("date_of_remittance").value;
        let dateCoveredMonth = document.getElementById("date_covered_month").value;
        let dateCoveredYear = document.getElementById("date_covered_year").value;

        if (!remittanceNo || !dateOfRemittance || !dateCoveredMonth || !dateCoveredYear) {
            alert("⚠ Please fill in all remittance and date details.");
            return;
        }

        fetch(STORE_BULK_URL, {
            method: "POST",
            body: JSON.stringify({
                loans: selectedLoans,
                remittance_no: remittanceNo,
                date_of_remittance: dateOfRemittance,
                date_covered_month: parseInt(dateCoveredMonth),
                date_covered_year: parseInt(dateCoveredYear)
            }),
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("✅ Payments added successfully!");

                selectedLoans.forEach(loan => {
                    let row = document.querySelector(`tr[data-loan-id="${loan.loan_id}"]`);
                    if (row) {
                        let balanceCell = row.querySelector(".outstanding-balance");
                        let totalPaymentsCell = row.querySelector(".total-payments");
                        let latestPaymentCell = row.querySelector(".latest-payment");
                        let remittanceNoCell = row.querySelector(".remittance-no");
                        let dateOfRemittanceCell = row.querySelector(".date-of-remittance");
                        let dateCoveredCell = row.querySelector(".date-covered");

                        if (balanceCell) balanceCell.textContent = data.loan_payments[loan.loan_id].latest_outstanding_balance;
                        if (totalPaymentsCell) totalPaymentsCell.textContent = data.loan_payments[loan.loan_id].total_payments;
                        if (latestPaymentCell) latestPaymentCell.textContent = data.loan_payments[loan.loan_id].latest_remittance ?? 'N/A';
                        if (remittanceNoCell) remittanceNoCell.textContent = data.loan_payments[loan.loan_id].remittance_no ?? 'N/A';
                        if (dateOfRemittanceCell) dateOfRemittanceCell.textContent = data.loan_payments[loan.loan_id].date_of_remittance ?? 'N/A';
                        if (dateCoveredCell) dateCoveredCell.textContent = `${data.loan_payments[loan.loan_id].date_covered_month}-${data.loan_payments[loan.loan_id].date_covered_year}`;

                        if (parseFloat(data.loan_payments[loan.loan_id].latest_outstanding_balance) === 0) {
                            row.remove();
                        }
                    }
                });

                updateButtonState();
            } else {
                alert("⚠ Error: " + data.message);
            }
        })
        .catch(error => {
            console.error("❌ API Error: ", error);
            alert(`❌ Error: ${error.message}`);
        });
    });

document.getElementById("searchLoanPayments").addEventListener("keyup", function () {
    let query = this.value.toLowerCase();
    document.querySelectorAll("#loanPaymentTable .loanPaymentRow").forEach(row => {
        let name = row.getAttribute("data-name");
        let id = row.getAttribute("data-id");
        let office = row.getAttribute("data-office");

        row.style.display = name.includes(query) || id.includes(query) || office.includes(query) ? "" : "none";
    });
});

let modal = new bootstrap.Modal(document.getElementById("updatePaymentModal"));
    // Open modal on click
    document.querySelectorAll(".update-payment-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.getElementById("modal_loan_id").value = this.dataset.loanId;
            document.getElementById("modal_employee_id").value = this.dataset.employeeId;
            document.getElementById("modal_employee_name").value = this.dataset.employeeName;
            document.getElementById("modal_loan_type").value = this.dataset.loanType;

            document.getElementById("paymentDetailsSection").style.display = "none";
            document.getElementById("search_remittance_no").value = '';
            modal.show();
        });
    });

    // Search remittance
    document.getElementById("searchRemittanceBtn").addEventListener("click", function () {
        const remittanceNo = document.getElementById("search_remittance_no").value;
        const loanId = document.getElementById("modal_loan_id").value;

        fetch(`/admin/loan-payments/remittance/${remittanceNo}/${loanId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const p = data.payment;
                    document.getElementById("modal_employee_id").value = data.employee_id || '';
                    document.getElementById("modal_employee_name").value = data.employee_name || '';
                    document.getElementById("modal_loan_type").value = data.loan_type || '';
                    document.getElementById("modal_remittance_no").value = p.remittance_no;
                    document.getElementById("modal_date_of_remittance").value = p.date_of_remittance;
                    document.getElementById("modal_total_payment").value = p.total_payments;
                    document.getElementById("modal_date_covered_month").value = p.date_covered_month;
                    document.getElementById("modal_date_covered_year").value = p.date_covered_year;
                    document.getElementById("paymentDetailsSection").style.display = "block";
                } else {
                    alert("No remittance record found.");
                }
            })
            .catch(() => alert("Failed to fetch remittance data."));
    });

    // Save update
    document.getElementById("updatePaymentForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const loanId = document.getElementById("modal_loan_id").value;
        const formData = new FormData(this);

        fetch(UPDATE_URL, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Payment updated successfully!");
                modal.hide();
                location.reload(); // Optional: Refresh to reflect changes
            } else {
                alert("Update failed.");
            }
        })
        .catch(() => alert("Something went wrong."));
    });


});
</script>

</x-admin-layout>
