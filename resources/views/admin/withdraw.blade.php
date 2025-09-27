<x-admin-layout>
<div class="content-wrapper">
    <div class="page-title"><h4>Withdrawals Management</h4></div>

    <div class="templates-container">
  <div class="row mb-4">
    <div class="col-md-6 text-left">
      <a href="{{ url('/download/withdraw-template') }}" class="btn btn-success btn-block">
        Download Withdrawals Template
      </a>
    </div>
    <div class="col-md-6 text-right">
      <form action="{{ route('withdraw.upload') }}" method="POST" enctype="multipart/form-data" class="mb-3">
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
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger" style="white-space: pre-line;">{{ session('error') }}</div>
  @endif
</div>


    <!-- Filters and Inputs -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <label class="form-label">Filter by Office</label>
            <select id="officeFilter" class="form-select">
                <option value="">All Offices</option>
                @foreach($offices as $office)
                    <option value="{{ $office }}">{{ $office }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="col-lg-2">
            <label class="form-label">Date of Withdrawal</label>
            <input type="date" id="dateWithdrawal" class="form-control">
        </div>

        <div class="col-lg-2">
            <label class="form-label">Reference/Receipt No.</label>
            <input type="text" id="referenceNo" class="form-control" placeholder="Optional">
        </div>

        <div class="covered-period-filter col-lg-2">
            <label class="form-label">Covered Period (Optional)</label>
            <div class="d-flex gap-2">
                <select id="covered_month" class="form-control">
                    <option value="">Month</option>
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
                <select id="covered_year" class="form-control">
                    <option value="">Year</option>
                    @for($y = date('Y'); $y >= date('Y') - 50; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="col-lg-2">
            <label class="form-label">Amount Withdrawn</label>
            <div class="d-flex gap-2">
                <input type="number" id="withdrawAmount" class="form-control" placeholder="Enter amount">
                <button id="addWithdrawBtn" class="btn btn-danger">Add Withdrawal</button>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-3">
        <label class="form-label">Search Members</label>
        <input type="text" id="searchWithdraw" class="form-control" placeholder="Search by Name, ID, or Office...">
    </div>

    <!-- Members table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>No.</th>
                <th>Member ID</th>
                <th>Name</th>
                <th>Office</th>
                <th>Latest Withdraw</th>
                <th>Total Withdraw</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="membersTableBody">
            @php $i = 1; @endphp
            @foreach($members as $m)
                @php
                    $totalWithdraw = \App\Models\Withdraw::where('employees_id', $m->id)->sum('amount_withdrawn');
                    $latestWithdraw = \App\Models\Withdraw::where('employees_id', $m->id)->orderBy('date_of_withdrawal','desc')->value('date_of_withdrawal');
                @endphp
                <tr class="memberRow" data-name="{{ strtolower($m->name) }}" data-id="{{ strtolower($m->employee_ID) }}" data-office="{{ strtolower($m->office) }}">
                    <td><input type="checkbox" class="memberCheckbox" value="{{ $m->id }}"></td>
                    <td>{{ $i++ }}</td>
                    <td>{{ $m->employee_ID }}</td>
                    <td>{{ $m->name }}</td>
                    <td>{{ $m->office }}</td>
                    <td>{{ $latestWithdraw ?? 'N/A' }}</td>
                    <td>{{ number_format($totalWithdraw,2) }}</td>
                    <td>
                        <button class="btn btn-info view-withdrawals-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#viewWithdrawalsModal"
                            data-id="{{ $m->id }}"
                            data-employee_id="{{ $m->employee_ID }}"
                            data-name="{{ $m->name }}"
                            data-office="{{ $m->office }}">
                            View / Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="viewWithdrawalsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header btn-danger text-white">
                    <h5 class="modal-title">Member Withdrawals</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered mb-3">
                        <tbody>
                            <tr><th>Member ID</th><td id="wModalEmployeeID"></td></tr>
                            <tr><th>Name</th><td id="wModalName"></td></tr>
                            <tr><th>Office</th><td id="wModalOffice"></td></tr>
                        </tbody>
                    </table>

                    <div class="d-flex gap-2">
                        <input type="text" id="wSearch" class="form-control" placeholder="Enter YYYY">
                        <button class="btn btn-primary" id="wSearchBtn">Search</button>
                    </div>

                    <div id="withdrawalsResult" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const addBtn = document.getElementById('addWithdrawBtn');

    function selectedIds(){
        return Array.from(document.querySelectorAll('.memberCheckbox:checked')).map(cb => cb.value);
    }

    selectAll.addEventListener('change', () => {
        document.querySelectorAll('.memberCheckbox').forEach(cb => cb.checked = selectAll.checked);
    });

    document.getElementById('searchWithdraw').addEventListener('keyup', function(){
        const q = this.value.toLowerCase();
        document.querySelectorAll('#membersTableBody .memberRow').forEach(row => {
            const name = row.getAttribute('data-name');
            const id = row.getAttribute('data-id');
            const office = row.getAttribute('data-office');
            row.style.display = (name.includes(q) || id.includes(q) || office.includes(q)) ? '' : 'none';
        });
    });

    document.getElementById('officeFilter').addEventListener('change', function(){
        const office = this.value.toLowerCase().trim();
        document.querySelectorAll('#membersTableBody .memberRow').forEach(row => {
            row.style.display = (office === '' || row.getAttribute('data-office') === office) ? '' : 'none';
        });
    });

    addBtn.addEventListener('click', function(){
        const members = selectedIds();
        const amount = document.getElementById('withdrawAmount').value;
        const date   = document.getElementById('dateWithdrawal').value;
        const ref    = document.getElementById('referenceNo').value;
        const month  = document.getElementById('covered_month').value;
        const year   = document.getElementById('covered_year').value;

        if (members.length === 0) { alert('Select at least one member.'); return; }
        if (!amount || isNaN(amount) || amount <= 0) { alert('Enter a valid amount.'); return; }
        if (!date) { alert('Select a date of withdrawal.'); return; }

        fetch("{{ route('admin.bulk-add-withdraw') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                member_ids: members,
                amount_withdrawn: amount,
                date_of_withdrawal: date,
                reference_no: ref,
                covered_month: month || null,
                covered_year: year || null,
            })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) { alert('Withdrawals saved.'); location.reload(); }
            else { alert('Error: ' + (d.error || 'Unknown error')); }
        })
        .catch(err => alert('Error: ' + err.message));
    });

    // View/Edit modal
    document.querySelectorAll('.view-withdrawals-btn').forEach(btn => {
        btn.addEventListener('click', function(){
            document.getElementById('wModalEmployeeID').textContent = this.dataset.employee_id;
            document.getElementById('wModalName').textContent       = this.dataset.name;
            document.getElementById('wModalOffice').textContent     = this.dataset.office;
            document.getElementById('wSearch').value = '';
            document.getElementById('withdrawalsResult').innerHTML = '';
            document.getElementById('wSearchBtn').setAttribute('data-user-id', this.dataset.id);
        });
    });

    document.getElementById('wSearchBtn').addEventListener('click', function(){
        const userId = this.getAttribute('data-user-id');
        const q = document.getElementById('wSearch').value.trim();
        if (!q) { alert('Enter a Year or a Reference No.'); return; }

        fetch(`/admin/get-withdrawals/${encodeURIComponent(userId)}/${encodeURIComponent(q)}`)
          .then(r => r.json())
          .then(data => {
              let html = `<table class="table table-bordered"><thead><tr>
                  <th>Date of Withdrawal</th><th>Reference No.</th><th>Month</th><th>Year</th><th>Amount Withdrawn</th>
              </tr></thead><tbody>`;

              if (data.success && data.withdrawals.length > 0) {
                  data.withdrawals.forEach((w, i) => {
                      html += `<tr data-withdrawals-id="${w.withdrawals_id}">
                          <td><input type="date" class="form-control" name="date_of_withdrawal_${i}" value="${w.date_of_withdrawal || ''}"></td>
                          <td><input type="text" class="form-control" name="reference_no_${i}" value="${w.reference_no || ''}"></td>
                          <td><input type="text" class="form-control" name="month_name_${i}" value="${w.month_name || ''}"></td>
                          <td><input type="text" class="form-control" name="covered_year_${i}" value="${w.covered_year || ''}"></td>
                          <td><input type="number" class="form-control" name="amount_withdrawn_${i}" value="${w.amount_withdrawn || ''}"></td>
                      </tr>`;
                  });
              } else {
                  html += `<tr><td colspan="6" class="text-center">No withdrawals found</td></tr>`;
              }

              html += `</tbody></table>
                       <div class="text-end"><button class="btn btn-success" id="saveWithdrawChangesBtn">Save Changes</button></div>`;

              document.getElementById('withdrawalsResult').innerHTML = html;
          })
          .catch(err => {
              document.getElementById('withdrawalsResult').innerHTML = `<p class="text-danger">Failed to load.</p>`;
          });
    });

    // Save inline edits
    document.addEventListener('click', function(e){
        if (e.target && e.target.id === 'saveWithdrawChangesBtn') {
            const rows = document.querySelectorAll('#withdrawalsResult table tbody tr');
            const updates = [];
            rows.forEach((row, i) => {
                const id  = row.getAttribute('data-withdrawals-id');
                const date= row.querySelector(`[name="date_of_withdrawal_${i}"]`).value;
                const ref = row.querySelector(`[name="reference_no_${i}"]`).value;
                const mon = row.querySelector(`[name="month_name_${i}"]`).value;
                const yr  = row.querySelector(`[name="covered_year_${i}"]`).value;
                const amt = row.querySelector(`[name="amount_withdrawn_${i}"]`).value;

                if (!date || isNaN(amt)) { return; }

                updates.push({
                    withdrawals_id: id,
                    date_of_withdrawal: date,
                    reference_no: ref,
                    month_name: mon,
                    covered_year: yr,
                    amount_withdrawn: amt
                });
            });

            fetch("{{ url('/admin/update-withdrawals') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ updates })
            })
            .then(r => r.json())
            .then(d => {
                alert(d.message || (d.success ? 'Saved' : 'No changes'));
                if (d.success) location.reload();
            })
            .catch(err => alert('Error saving changes'));
        }
    });
});
</script>
</x-admin-layout>
