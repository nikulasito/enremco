<x-admin-layout>
     <div class="content-wrapper">
        <div class="page-title"><h4>New Members</h4></div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    <div class="new-members-table">
            <form method="GET" action="{{ route('admin.new-members') }}" class="d-flex align-items-center mb-3">
                <label for="per_page" class="me-2">Show:</label>
                <select name="per_page" id="per_page" class="form-select w-auto me-2" onchange="this.form.submit()">
                    @foreach ([10, 20, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                <span>members per page</span>
            </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Member ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Office</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($newMembers as $index => $member)
                    <tr>
                        <td>{{ ($newMembers->currentPage() - 1) * $newMembers->perPage() + $index + 1 }}</td>
                        <td>{{ $member->employee_ID }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->office }}</td>
                        <td>{{ $member->email }}</td>
                        <td><span style="color: red;">{{ $member->status }}</span></td>
                        <td>
                            <!-- <form method="POST" action="{{ route('admin.approve-member', $member->id) }}" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form> -->
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $member->id }}">
                              Approve
                            </button>

                            <!-- <form method="POST" action="{{ route('admin.disapprove-member', $member->id) }}" style="display:inline-block; margin-left: 5px;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">Disapprove</button>
                            </form> -->

                            <!-- Disapprove Button (opens modal) -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#disapproveModal{{ $member->id }}">
                                Disapprove
                            </button>

                            <!-- View Button (opens view modal) -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewMemberModal{{ $member->id }}">
                                View
                            </button>

                                <!-- Disapprove Modal -->
                            <div class="modal fade" id="disapproveModal{{ $member->id }}" tabindex="-1" aria-labelledby="disapproveModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('admin.disapprove-member', $member->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Disapprove Member</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to disapprove <strong>{{ $member->name }}</strong>?</p>
                                                <div class="form-group">
                                                    <label for="reason">Reason for disapproval:</label>
                                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Disapprove</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- View Modal - PDF-like layout -->
<div class="modal fade" id="viewMemberModal{{ $member->id }}" tabindex="-1" aria-labelledby="viewMemberModalLabel{{ $member->id }}" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registration Details - {{ $member->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Printable area matching RegistrationDetails.pdf -->
      <div class="modal-body">
        <div id="printable-member-{{ $member->id }}" class="p-4" >
          <div class="header">
            <img src="https://enremco.com/assets/images/enremco_logo.png" alt="ENREMCO Logo">
            <h1>ENVIRONMENT AND NATURAL RESOURCES<br>EMPLOYEES MULTI-PURPOSE COOPERATIVE<br>(ENREMCO)</h1>
            <div class="subtitle">DENR-10, Compound Cagayan de Oro City</div>
          </div>

            <div class="section">
                <p><span class="bold">The Chairperson</span><br>ENREMCO</p>
                <p>Madam/Sir:</p>
                <p>
                  I have the honor to apply as member of ENREMCO Region 10. If accepted, I shall pledge my loyalty to the Cooperative.
                </p>
            </div>

            <div class="section">
                <table class="table" style="width:100%;text-transform: uppercase;">
                    <tr>
                        <td style="width: 50%;"><strong>Name:</strong> {{ $member->name }}</td>
                        <td style="width: 50%;"><strong>Email:</strong> {{ $member->email }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"><strong>Office:</strong> {{ $member->office }}</td>
                        <td style="width: 50%;"><strong>Home Address:</strong> {{ $member->address }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"><strong>Religion:</strong> {{ $member->religion }}</td>
                        <td style="width: 50%;"><strong>Sex:</strong> {{ $member->sex }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"><strong>Marital Status:</strong> {{ $member->marital_status }}</td>
                        <td style="width: 50%;"><strong>Annual Income:</strong> {{ $member->annual_income }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"><strong>Beneficiaries:</strong> {{ $member->beneficiaries }}</td>
                        <td style="width: 50%;"><strong>Birthdate:</strong> {{ $member->birthdate }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"><strong>Education:</strong> {{ $member->education }}</td>
                        <td style="width: 50%;"><strong>Member ID:</strong> {{ $member->employee_ID }}</td>
                    </tr>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p>
                  I hereby authorize the Disbursing Office/Cashier to deduct from my salary the amount of Fifty Pesos (50.00 PESOS) as membership fee.
                </p>
                <p>
                  Further authorize to deduct the amount of ₱ {{ $member->shares }} per month which is equivalent to 2% of my salary until the amount of _______ is fully satisfied, representing my subscribed Capital Stock of _______ shares.
                </p>
                <p>
                  This application, where my signature is affixed, is my voluntary act and deed.
                </p>
                @php
                  // choose the date to display: approved_at -> created_at -> now
                  $dateToShow = $member->approved_at ?? $member->created_at ?? now();
                  // ensure it's a Carbon instance
                  $dateToShow = \Illuminate\Support\Arr::wrap($dateToShow)[0] instanceof \Carbon\Carbon
                      ? $dateToShow
                      : \Carbon\Carbon::parse($dateToShow);
                @endphp

                <p>
                  This {{ $dateToShow->format('jS') }} day of {{ $dateToShow->format('F') }}, {{ $dateToShow->format('Y') }}.
                </p>
            </div>

            <div class="text-center-pdf">
                <p><strong style="text-transform: uppercase;">{{ $member->name }}</strong><br>Applicant</p>
            </div>

          <div class="signature-section">
            <div class="signature-block">
              Attested by:<br><br>
              <div class="signature-line"></div>
              Member
            </div>
            <div class="signature-block">
              Approved:<br><br>
              <div class="signature-line"></div>
              MARY GRACE O. ALEMANIO<br>
              Chairperson
            </div>
          </div>
        </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="printMember({{ $member->id }})">Print</button>
      </div>

    </div>
  </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal{{ $member->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $member->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.approve-member', $member->id) }}">
        @csrf
        @method('PATCH')

        <div class="modal-header">
          <h5 class="modal-title" id="approveModalLabel{{ $member->id }}">Approve Member</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <p>Are you sure you want to approve <strong>{{ $member->name }}</strong>?</p>

          <!-- Optional: let admin set the approval date (default to today) -->
          <div class="mb-3">
            <label for="approved_at_{{ $member->id }}" class="form-label">Approval date</label>
            <input type="date" name="approved_at" id="approved_at_{{ $member->id }}" class="form-control"
                   value="{{ old('approved_at') ?? now()->format('Y-m-d') }}">
            <small class="text-muted">Change this if approving a different date.</small>
          </div>

          <!-- Optional: remarks/notes -->
          <div class="mb-3">
            <label for="approve_notes_{{ $member->id }}" class="form-label">Notes (optional)</label>
            <textarea name="approve_notes" id="approve_notes_{{ $member->id }}" class="form-control" rows="2"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Confirm Approve</button>
        </div>
      </form>
    </div>
  </div>
</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No new members awaiting approval.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4 pagination-links">
            {{ $newMembers->links() }}
        </div>
    </div>

<script>
  function printMember(memberId) {
    const element = document.getElementById('printable-member-' + memberId);
    if (!element) {
      alert('Printable content not found.');
      return;
    }

    const content = element.innerHTML;
    const win = window.open('', '_blank', 'toolbar=0,location=0,menubar=0,width=900,height=800');

    if (!win) {
      alert('Popup blocked. Allow popups for this site to print.');
      return;
    }

    win.document.write(`
      <html>
        <head>
          <title>Registration Details</title>
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
          <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.5;
      margin: 10px;
    }
    .header {
      text-align: center;
    }
    .header img {
      max-width: 100px;
      margin: 0px auto 10px;
    }
    h1 {
      text-transform: uppercase;
      font-size: 25px;
      font-weight: bold;
      margin-bottom: 0;
    }
    .subtitle {
      font-size: 14px;
      margin-bottom: 30px;
    }
    .section {
      margin-bottom: 20px;
    }
    .section p {
      margin: 10px 0;
    }
    .bold {
      font-weight: bold;
    }
    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 40px;
    }
    .signature-block {
      text-align: center;
      width: 45%;
    }
    .signature-line {
      margin-top: 40px;
      border-top: 1px solid #000;
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }
    .text-center-pdf{
        text-align: center;
        margin-top: 2rem;
    }
  </style>
        </head>
        <body>
          ${content}
        </body>
      </html>
    `);

    win.document.close();
    win.focus();

    setTimeout(() => {
      win.print();
      // win.close();
    }, 300);
  }
</script>
</x-admin-layout>