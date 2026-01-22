<x-admin-layout>
<div class="content-wrapper">
        <div class="page-title"><h4>Loan Details</h4></div>

        <div class="loan-container">
                <form method="POST" action="{{ route('admin.store-loan') }}" class="form-loan-details">
                    @csrf

                    <!-- Loan Form -->
                    <div class="row">
                        <!-- Account No. (Employee ID) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="employee_id">Employee ID</label>
                            <input type="text" name="employee_id" id="employee_id" class="form-control" placeholder="Enter Employee ID (Format:ENREMCO-XXX-XXX)" required>
                        </div>
                        <!-- Employee Name (Auto-filled) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="employee_name">Employee Name</label>
                            <input type="text" name="employee_name" id="employee_name" class="form-control" readonly>
                        </div>

                        <!-- Office (Auto-filled) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="office">Office</label>
                            <input type="text" name="office" id="office" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Co Maker 1 -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="co_maker_name">Co-maker 1</label>
                            <input type="text" name="co_maker_name" id="co_maker_name" class="form-control" required>
                            <div id="co_maker_suggestions" class="autocomplete-suggestions col-md-3"></div>
                            <small id="co_maker_error" class="error-message">Co-Maker not found!</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="co_maker_position">Position</label>
                            <input type="text" name="co_maker_position" id="co_maker_position" class="form-control" required>
                        </div>

                        <!-- Co Maker 1 -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="co_maker2_name">Co-maker 2</label>
                            <input type="text" name="co_maker2_name" id="co_maker2_name" class="form-control" required>
                            <div id="co_maker2_suggestions" class="autocomplete-suggestions col-md-3"></div>
                            <small id="co_maker2_error" class="error-message">Co-Maker not found!</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="co_maker2_position">Position</label>
                            <input type="text" name="co_maker2_position" id="co_maker2_position" class="form-control"required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Loan Type</label>
                            <select name="loan_type" class="form-control">
                                <option value="Regular Loan">Regular Loan</option>
                                <option value="Educational Loan">Educational Loan</option>
                                <option value="Appliance Loan">Appliance Loan</option>
                                <option value="Grocery Loan">Grocery Loan</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Loan Amount</label>
                            <input type="text" step="0.01" id="loan_amount" name="loan_amount" class="form-control" required>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label" for="date_applied">Date Applied</label>
                            <input type="date" id="date_applied" name="date_applied" class="form-control" required>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Interest Rate %</label>
                            <input type="number" id="interest_rate" name="interest_rate" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label" >Term</label>
                            <input type="number" id="terms" name="terms" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label" >Monthly Payment</label>
                            <input type="text" id="monthly_payment" name="monthly_payment" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label" for="total_deduction">Total Deduction</label>
                            <input type="text" name="total_deduction" id="total_deduction" class="form-control disabled" required readonly>
                            <br>
                            <label class="form-label" for="old_balance">Balance</label>
                            <input type="text" step="0.01" name="old_balance" id="old_balance" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label" for="total_net">Total Net</label>
                            <input type="text" name="total_net" id="total_net" class="form-control disabled" required readonly>
                            <br>
                            <label class="form-label" for="interest">Interest</label>
                            <input type="text" step="0.01" name="interest" id="interest" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label" for="date_approved">Date Approved</label>
                            <input type="date" id="date_approved" name="date_approved" class="form-control" required>
                            <br>
                            <label class="form-label" for="petty_cash_loan">Petty Cash Loan</label>
                            <input type="text" step="0.01" name="petty_cash_loan" id="petty_cash_loan" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label" for="lpp">LPP</label>
                            <input type="text" step="0.01" name="lpp" id="lpp" class="form-control" required>
                            <br>
                            <label class="form-label" for="handling_fee">Handling Fee</label>
                            <input type="text" step="0.01" name="handling_fee" id="handling_fee" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="remarks">Remarks</label>
                            <select name="remarks" id="remarks" class="form-control" required>
                                <option value="New Loan">New Loan</option>
                                <option value="Re-Loan">Re-Loan</option>
                            </select>
                            <button type="submit" class="btn btn-primary loan-details-btn">Add Loan</button>
                        </div>
                    </div>
                </form>

        <!-- Search Bar -->
            <div class="mb-3">
                <label for="searchLoans" class="form-label">Search Loan</label>
                <input type="text" id="search_loans" class="form-control" placeholder="Search by Employee Name, Loan ID, or Office...">
            </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Loan ID</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Office</th>
                            <!-- <th>Employment Status</th> -->
                            <th>Loan Type</th>
                            <th>Loan Amount</th>
                            <th>Date Approved</th>
                            <!-- <th>Total Deduction</th>
                            <th>Total Net</th>
                            <th>Terms</th>
                            <th>Monthly Payment</th>
                            <th>No. of Total Payments</th>
                            <th>Total Payments</th>
                            <th>Outstanding Balance</th>
                            <th>Latest Payment</th>
                            <th>Remarks</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="loanTableBody">
                    @foreach ($loans as $loan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $loan->loan_id }}</td>
                        <td>{{ $loan->employee_ID }}</td>  <!-- ✅ Shows Account No. (employee_ID) -->
                        <td>{{ optional($loan->user)->name ?? 'NA' }}</td>
                        <td>{{ optional($loan->user)->office ?? 'NA' }}</td>
                        <!-- <td>{{ optional($loan->user)->status ?? 'NA'}}</td> -->
                        <td>{{ $loan->loan_type }}</td>
                        <td>{{ $loan->loan_amount }}</td>
                        <td>{{ $loan->date_approved }}</td>
                        <!-- <td>{{ $loan->total_deduction }}</td>
                        <td>{{ $loan->total_net }}</td>
                        <td>{{ $loan->terms }}</td>
                        <td>{{ $loan->monthly_payment }}</td>
                        <td>{{ optional($loan->latestPayment)->total_payments_count ?? 0 }}</td>
                        <td>{{ optional($loan->latestPayment)->total_payments ?? '0.00' }}</td>
                        <td>{{ optional($loan->latestPayment)->outstanding_balance ?? '0.00' }}</td>
                        <td>{{ optional($loan->latestPayment)->latest_remittance ?? 'No Remittance Yet' }}</td>
                        <td>{{ $loan->remarks ?? 'N/A' }}</td> -->
                        <td>
                            <!-- View Details Button -->
                            <button class="btn btn-info view-loan-btn"
                                data-loan-id="{{ $loan->loan_id }}"
                                data-employee-id="{{ $loan->employee_ID }}"
                                data-employee-name="{{ optional($loan->user)->name ?? 'NA' }}"
                                data-office="{{ optional($loan->user)->office ?? 'NA' }}"
                                data-loan-type="{{ $loan->loan_type }}"
                                data-loan-amount="{{ $loan->loan_amount }}"
                                data-terms="{{ $loan->terms }}"
                                data-interest-rate="{{ $loan->interest_rate }}"
                                data-monthly-payment="{{ $loan->monthly_payment }}"
                                data-total-deduction="{{ $loan->total_deduction }}"
                                data-total-net="{{ $loan->total_net }}"
                                data-date-approved="{{ $loan->date_approved }}"
                                data-remarks="{{ $loan->remarks }}"
                                data-no-of-payments="{{ optional($loan->latestPayment)->total_payments_count ?? 0 }}"
                                data-total-payments="{{ optional($loan->latestPayment)->total_payments ?? '0.00' }}"
                                data-latest-payment="{{ optional($loan->latestPayment)->latest_remittance ?? '' }}">
                                View Details
                            </button>
                            <br>
                            <!-- Update Button -->
                            <button class="btn btn-warning update-loan-btn"
                                data-loan-id="{{ $loan->loan_id }}"
                                data-employee-id="{{ $loan->employee_ID }}"
                                data-employee-name="{{ optional($loan->user)->name ?? 'NA' }}"
                                data-office="{{ optional($loan->user)->office ?? 'NA' }}"
                                data-loan-type="{{ $loan->loan_type }}"
                                data-loan-amount="{{ $loan->loan_amount }}"
                                data-terms="{{ $loan->terms }}"
                                data-interest-rate="{{ $loan->interest_rate }}"
                                data-monthly-payment="{{ $loan->monthly_payment }}"
                                data-total-deduction="{{ $loan->total_deduction }}"
                                data-total-net="{{ $loan->total_net }}"
                                data-date-approved="{{ $loan->date_approved }}"
                                data-remarks="{{ $loan->remarks }}"
                                data-no-of-payments="{{ optional($loan->latestPayment)->total_payments_count ?? 0 }}"
                                data-total-payments="{{ optional($loan->latestPayment)->total_payments ?? '0.00' }}"
                                data-latest-payment="{{ optional($loan->latestPayment)->latest_remittance ?? '' }}">
                                Update
                            </button>
                        </td>

                    </tr>
                    @endforeach
                </tbody>

                </table>
    </div>


<!-- Update Loan Modal -->
<div class="modal fade" id="updateLoanModal" tabindex="-1" aria-labelledby="updateLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="updateLoanModalLabel">Update Loan Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateLoanForm" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="loan_id" id="update_loan_id">
                    
                    <div class="mb-3">
                        <label>Account No.</label>
                        <input type="text" id="update_employee_id" class="form-control disabled" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Employee Name</label>
                        <input type="text" id="update_employee_name" class="form-control disabled" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Loan Type</label>
                        <select id="update_loan_type" name="loan_type" class="form-control">
                            <option value="Regular Loan">Regular Loan</option>
                            <option value="Educational Loan">Educational Loan</option>
                            <option value="Appliance Loan">Appliance Loan</option>
                            <option value="Grocery Loan">Grocery Loan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Loan Amount</label>
                        <input type="text" id="update_loan_amount" name="loan_amount" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Monthly Payment</label>
                        <input type="text" name="update_monthly_payment" id="update_monthly_payment" class="form-control">
                    </div>

                    <!-- Editable Fields -->
                    <div class="mb-3">
                        <label>No. of Total Payments</label>
                        <input type="number" name="no_of_payments" id="update_no_of_payments" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Total Payments</label>
                        <input type="text" name="total_payments" id="update_total_payments" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Latest Payment</label>
                        <input type="date" name="latest_payment" id="update_latest_payment" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-warning w-100">Update Loan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- View Details Modal -->
<div class="modal fade" id="viewLoanModal" tabindex="-1" aria-labelledby="viewLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewLoanModalLabel">Loan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><th>Loan ID</th><td id="view_loan_id"></td></tr>
                        <tr><th>Employee ID</th><td id="view_employee_id"></td></tr>
                        <tr><th>Employee Name</th><td id="view_employee_name"></td></tr>
                        <tr><th>Office</th><td id="view_office"></td></tr>
                        <tr><th>Loan Type</th><td id="view_loan_type"></td></tr>
                        <tr><th>Loan Amount</th><td id="view_loan_amount"></td></tr>
                        <tr><th>Terms</th><td id="view_terms"></td></tr>
                        <tr><th>Interest Rate</th><td id="view_interest_rate"></td></tr>
                        <tr><th>Monthly Payment</th><td id="view_monthly_payment"></td></tr>
                        <tr><th>Total Deduction</th><td id="view_total_deduction"></td></tr>
                        <tr><th>Total Net</th><td id="view_total_net"></td></tr>
                        <tr><th>Date Approved</th><td id="view_date_approved"></td></tr>
                        <tr><th>Remarks</th><td id="view_remarks"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>

    <!-- JavaScript for Filtering & Modal -->
<script>
document.addEventListener("DOMContentLoaded", function () {


    let employeeInput = document.getElementById("employee_id");

    if (employeeInput) {
        employeeInput.addEventListener("keyup", function() {
            let employeeId = this.value.trim();

            if (employeeId.length > 3) {
                fetch(`/admin/get-user-details/${encodeURIComponent(employeeId)}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(async response => {
                    if (!response.ok) {
                        const text = await response.text();
                        console.error("get-user-details non-ok:", response.status, text);
                        document.getElementById("employee_name").value = "Not found";
                        document.getElementById("office").value = "Not found";
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    if (data.success) {
                        document.getElementById("employee_name").value = data.name || "";
                        document.getElementById("office").value = data.office || "";
                    } else {
                        document.getElementById("employee_name").value = "Not found";
                        document.getElementById("office").value = "Not found";
                    }
                })
                .catch(error => {
                    console.error("Error fetching user data:", error);
                    document.getElementById("employee_name").value = "Not found";
                    document.getElementById("office").value = "Not found";
                });
            }
        });
    } else {
        console.error("Element with ID 'employee_id' not found.");
    }




function setupCoMakerSearch(inputId, positionId, suggestionBoxId, errorId) {
                let inputField = document.getElementById(inputId);
                let positionField = document.getElementById(positionId);
                let suggestionBox = document.getElementById(suggestionBoxId);
                let errorMessage = document.getElementById(errorId);

                inputField.addEventListener("keyup", function () {
                    let coMakerName = this.value.trim();

                    if (coMakerName.length > 2) {
                        fetch(`/admin/get-co-maker/${encodeURIComponent(coMakerName)}`, {
                            headers: { 'Accept': 'application/json' }
                        })
                            .then(async response => {
                                if (!response.ok) {
                                    const txt = await response.text();
                                    console.error('get-co-maker non-ok:', response.status, txt);
                                    return null;
                                }
                                return response.json();
                            })
                            .then(data => {
                                suggestionBox.innerHTML = "";
                                if (!data) return;
                                if (data.success && data.users.length > 0) {
                                    suggestionBox.style.display = "block";
                                    errorMessage.style.display = "none"; // Hide error

                                    data.users.forEach(user => {
                                        let suggestionItem = document.createElement("div");
                                        suggestionItem.textContent = user.name;
                                        suggestionItem.classList.add("suggestion-item");

                                        suggestionItem.addEventListener("click", function () {
                                            inputField.value = user.name;
                                            positionField.value = user.position;
                                            suggestionBox.style.display = "none";
                                        });

                                        suggestionBox.appendChild(suggestionItem);
                                    });
                                } else {
                                    suggestionBox.style.display = "none";
                                    errorMessage.style.display = "block"; // Show error
                                }
                            })
                            .catch(error => {
                                console.error("Error fetching co-maker data:", error);
                                suggestionBox.style.display = "none";
                                errorMessage.style.display = "block";
                            });
                    } else {
                        suggestionBox.style.display = "none";
                        errorMessage.style.display = "none"; // Hide error
                        positionField.value = "";
                    }
                });

                document.addEventListener("click", function (e) {
                    if (!suggestionBox.contains(e.target) && e.target !== inputField) {
                        suggestionBox.style.display = "none";
                    }
                });
            }

            setupCoMakerSearch("co_maker_name", "co_maker_position", "co_maker_suggestions", "co_maker_error");
            setupCoMakerSearch("co_maker2_name", "co_maker2_position", "co_maker2_suggestions", "co_maker2_error");
//for calculation
function formatNumber(value) {
    return value.toFixed(2); // Removes commas
}

function calculateTotalDeduction() {
    let oldBalance = parseFloat(document.querySelector("#old_balance")?.value.replace(/,/g, "")) || 0;
    let lpp = parseFloat(document.querySelector("#lpp")?.value.replace(/,/g, "")) || 0;
    let interest = parseFloat(document.querySelector("#interest")?.value.replace(/,/g, "")) || 0;
    let pettyCashLoan = parseFloat(document.querySelector("#petty_cash_loan")?.value.replace(/,/g, "")) || 0;
    let handlingFee = parseFloat(document.querySelector("#handling_fee")?.value.replace(/,/g, "")) || 0;
    let loanAmount = parseFloat(document.querySelector("#loan_amount")?.value.replace(/,/g, "")) || 0;
    let terms = parseInt(document.querySelector("#terms")?.value) || 1; // Default to 1 to avoid division by zero

    let totalDeduction = oldBalance + lpp + interest + pettyCashLoan + handlingFee;
    let totalNet = loanAmount - totalDeduction;

    let monthlyPayment = loanAmount / terms; // Compute monthly payment

    let totalDeductionField = document.querySelector("#total_deduction");
    let totalNetField = document.querySelector("#total_net");
    let monthlyPaymentField = document.querySelector("#monthly_payment");

    if (totalDeductionField) totalDeductionField.value = formatNumber(totalDeduction);
    if (totalNetField) totalNetField.value = formatNumber(Math.max(totalNet, 0)); // Prevents negative values
    if (monthlyPaymentField) monthlyPaymentField.value = formatNumber(monthlyPayment); // Auto-fill monthly payment
}

let inputs = ["old_balance", "lpp", "interest", "handling_fee", "petty_cash_loan", "loan_amount", "terms"];

inputs.forEach(id => {
    let inputField = document.querySelector(`#${id}`);
    if (inputField) {
        inputField.addEventListener("input", calculateTotalDeduction);
    }
});

document.querySelectorAll(".update-loan-btn").forEach(button => {
        button.addEventListener("click", function () {
            let updateLoanModal = new bootstrap.Modal(document.getElementById("updateLoanModal"));

            if (!updateLoanModal) {
                console.error("⚠️ Modal not found!");
                return;
            }

            // ✅ Retrieve data attributes safely
            let loanId = this.dataset.loanId;
            let employeeId = this.dataset.employeeId;
            let employeeName = this.dataset.employeeName;
            let loanType = this.dataset.loanType;
            let loanAmount = this.dataset.loanAmount || "0.00"; 
            let noOfPayments = this.dataset.noOfPayments || 0;
            let totalPayments = this.dataset.totalPayments || "0.00";
            let monthlyPayment = this.dataset.monthlyPayment || "0.00"; 
            let latestPayment = this.dataset.latestPayment || "";

            // ✅ Helper function to safely set values
            function setValue(id, value) {
                let element = document.getElementById(id);
                if (element) {
                    element.value = value;
                } else {
                    console.warn(`⚠️ Element with ID '${id}' not found.`);
                }
            }

            // ✅ Set values in modal
            setValue("update_loan_id", loanId);
            setValue("update_employee_id", employeeId);
            setValue("update_employee_name", employeeName);
            setValue("update_loan_amount", loanAmount);
            setValue("update_no_of_payments", noOfPayments);
            setValue("update_total_payments", totalPayments);
            setValue("update_latest_payment", latestPayment);
            setValue("update_monthly_payment", monthlyPayment);

            // ✅ Set loan type dropdown
            let loanTypeDropdown = document.getElementById("update_loan_type");
            if (loanTypeDropdown) {
                loanTypeDropdown.value = loanType;
            }

            // ✅ Show the modal
            updateLoanModal.show();
        });
    });


document.getElementById("updateLoanForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    let formData = new FormData(this);
    
    // ✅ Debugging: Check if loan_amount exists
    console.log("🛠 FormData before submission:", Object.fromEntries(formData.entries()));

    if (!formData.get("loan_amount")) {
        console.error("🚨 Loan amount is missing from the request.");
        return;
    }

    let loanId = document.getElementById("update_loan_id").value;

    fetch(`/admin/loans/update/${encodeURIComponent(loanId)}`, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "Accept": "application/json"
        },
    })
    .then(async response => {
        if (!response.ok) {
            const text = await response.text();
            throw new Error(text || 'Server error');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            let updateLoanModal = document.getElementById("updateLoanModal");
            let modal = bootstrap.Modal.getInstance(updateLoanModal);
            modal.hide();

            let row = document.querySelector(`button[data-loan-id="${loanId}"]`).closest("tr");
            if (row) {
                // adjust column indices if your table layout changes
                row.querySelector("td:nth-child(8)").textContent = data.loan.loan_amount; 
                row.querySelector("td:nth-child(12)").textContent = data.loan.monthly_payment; 
            }
        } else {
            console.error("⚠️ Error updating loan:", data.message);
        }
    })
    .catch(error => {
        console.error("🚨 Error updating loan:", error);
    });
});


//Search
let searchInput = document.getElementById("search_loans");

    searchInput.addEventListener("keyup", function () {
        let query = this.value.trim();

        fetch(`/admin/loans/search?q=${encodeURIComponent(query)}`, {
            headers: { "Accept": "application/json" }
        })
        .then(async response => {
            if (!response.ok) {
                const txt = await response.text();
                console.error('loans/search non-ok:', response.status, txt);
                return [];
            }
            return response.json();
        })
        .then(data => {
            let tableBody = document.getElementById("loanTableBody");
            tableBody.innerHTML = ""; // Clear the table

            if (!data || data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="18" class="text-center">No results found</td></tr>`;
                return;
            }

            data.forEach((loan, index) => {
                let row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${loan.loan_id}</td>
                    <td>${loan.employee_ID}</td>
                    <td>${loan.employee_name}</td>
                    <td>${loan.office}</td>
                    <td>${loan.employment_status}</td>
                    <td>${loan.loan_type}</td>
                    <td>${loan.loan_amount}</td>
                    <td>${loan.date_approved}</td>
                    <td>${loan.total_deduction}</td>
                    <td>${loan.total_net}</td>
                    <td>${loan.terms}</td>
                    <td>${loan.monthly_payment}</td>
                    <td>${loan.total_payments_count ?? 0}</td>
                    <td>${loan.total_payments ?? '0.00'}</td>
                    <td>${loan.outstanding_balance ?? '0.00'}</td>
                    <td>${loan.latest_remittance ?? 'No Remittance Yet'}</td>
                    <td>${loan.remarks}</td>
                    <td>
                        <button class="btn btn-warning update-loan-btn" data-loan-id="${loan.loan_id}">Update</button>
                    </td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error("Error fetching search results:", error));
    });

// Handle View Details button click
document.querySelectorAll(".view-loan-btn").forEach(button => {
    button.addEventListener("click", function () {
        let viewLoanModal = new bootstrap.Modal(document.getElementById("viewLoanModal"));

        // Set the modal values
        document.getElementById("view_loan_id").textContent = this.dataset.loanId;
        document.getElementById("view_employee_id").textContent = this.dataset.employeeId;
        document.getElementById("view_employee_name").textContent = this.dataset.employeeName;
        document.getElementById("view_office").textContent = this.dataset.office;
        document.getElementById("view_loan_type").textContent = this.dataset.loanType;
        document.getElementById("view_loan_amount").textContent = this.dataset.loanAmount;
        document.getElementById("view_terms").textContent = this.dataset.terms;
        document.getElementById("view_interest_rate").textContent = this.dataset.interestRate;
        document.getElementById("view_monthly_payment").textContent = this.dataset.monthlyPayment;
        document.getElementById("view_total_deduction").textContent = this.dataset.totalDeduction;
        document.getElementById("view_total_net").textContent = this.dataset.totalNet;
        document.getElementById("view_date_approved").textContent = this.dataset.dateApproved;
        document.getElementById("view_remarks").textContent = this.dataset.remarks;

        // Show the modal
        viewLoanModal.show();
    });
});

});
</script>

</x-admin-layout>
