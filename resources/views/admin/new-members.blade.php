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
                            <form method="POST" action="{{ route('admin.approve-member', $member->id) }}" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>

                            <!-- <form method="POST" action="{{ route('admin.disapprove-member', $member->id) }}" style="display:inline-block; margin-left: 5px;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">Disapprove</button>
                            </form> -->

                            <!-- Disapprove Button (opens modal) -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#disapproveModal{{ $member->id }}">
                                Disapprove
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
</x-admin-layout>