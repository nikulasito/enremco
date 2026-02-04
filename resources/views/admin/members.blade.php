<x-admin-layout>
    <div class="content-wrapper">
        <div class="page-title"><h4>View Members</h4></div>
            <!-- Download Members Button -->
    <div class="members-download">
        <a href="{{ route('admin.download-members') }}" class="btn btn-success">
            <i class="fas fa-download"></i> Download Members List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Search Field and Filters -->
    <form method="GET" action="{{ route('admin.members') }}" id="filtersForm"
      class="mb-4 d-flex align-items-center justify-content-between search-filter">

        <input
            type="text"
            id="searchInput"
            name="search"
            class="form-control w-50"
            placeholder="Search members..."
            value="{{ request('search') }}"
        />

        <div class="mt-3">
            <select id="statusFilter" name="status" class="form-select">
                <option value="">Filter by Status</option>
                <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mt-3">
            <select id="officeFilter" name="office" class="form-select">
                @include('admin.partials.office_options', ['offices' => $offices])
            </select>
        </div>

        <div class="mt-3">
            <button type="button" id="clearFilters" class="btn btn-secondary">Clear Filters</button>
        </div>

        {{-- keep per_page in the same form so AJAX reads it --}}
        <input type="hidden" name="per_page" id="per_page_hidden" value="{{ $perPage }}">
    </form>





    <!-- Members Table -->
    <div id="membersTable">
        <form method="GET" action="{{ route('admin.members') }}" class="d-flex align-items-center mb-3">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="office" value="{{ request('office') }}">

            <label for="per_page" class="me-2">Show:</label>
            <select id="per_page" class="form-select w-auto me-2">
                @foreach ([10, 20, 50, 100] as $option)
                    <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            <span>members per page</span>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Office</th>
                    <th>Status</th>
                    <th>Date Approved</th>
                    <th>Date Inactive</th>
                    <th>Date Reactive</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $index => $member)
                    <tr data-member-id="{{ $member->id }}">
                        <td>{{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}</td>
                        <td>{{ $member->employee_ID }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->office }}</td>
                        <td class="status">
                            <span style="color: {{ $member->status == 'Active' ? 'green' : 'red' }}">
                                {{ $member->status }}
                            </span>
                        </td>
                        <td>{{ $member->date_approved ? \Carbon\Carbon::parse($member->date_approved)->format('Y-m-d') : 'N/A' }}</td>
                        <td class="date-inactive">
                            {{ $member->date_inactive ? \Carbon\Carbon::parse($member->date_inactive)->format('Y-m-d') : 'N/A' }}
                        </td>
                        <td class="date-reactive">
                            {{ $member->date_reactive ? \Carbon\Carbon::parse($member->date_reactive)->format('Y-m-d') : 'N/A' }}
                        </td>
                        <td>
                            <button 
                                class="btn btn-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#updateMemberModal" 
                                data-url="{{ route('admin.edit-member', $member->id) }}">
                                Update
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


<!--MODAL-->
<div class="modal fade" id="updateMemberModal" tabindex="-1" aria-labelledby="updateMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="modal-title m-0 font-weight-bold text-primary" id="updateMemberModalLabel">Update Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="updateMemberModalBody">
                <p>Loading...</p>
            </div>
        </div>
    </div>
</div>
    <div class="mt-4" id="membersPagination">
        {{ $members->appends(request()->except('page'))->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ===== KEEP YOUR MODAL FETCH CODE (unchanged) =====
    const updateModal = document.getElementById('updateMemberModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-url');
        const modalBody = document.getElementById('updateMemberModalBody');

        modalBody.innerHTML = '<p>Loading...</p>';

        fetch(url)
            .then(r => {
                if (!r.ok) throw new Error('Network response was not ok');
                return r.text();
            })
            .then(html => modalBody.innerHTML = html)
            .catch(err => modalBody.innerHTML = `<p class="text-danger">Error loading content: ${err.message}</p>`);
    });

    // ===== NEW: AJAX SEARCH/FILTER/PAGINATION (NO PAGE REFRESH) =====
    const filtersForm = document.getElementById('filtersForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const officeFilter = document.getElementById('officeFilter');
    const clearBtn = document.getElementById('clearFilters');

    const membersTableEl = document.getElementById('membersTable');
    const paginationEl = document.getElementById('membersPagination');

    const perPageHidden = document.getElementById('per_page_hidden');

    let debounceTimer = null;
    let abortController = null;

    function buildUrl() {
        const params = new URLSearchParams(new FormData(filtersForm));
        return `${filtersForm.action}?${params.toString()}`;
    }

    function bindPerPageListener() {
        const perPageSelect = document.getElementById('per_page');
        if (!perPageSelect) return;

        perPageSelect.addEventListener('change', () => {
            perPageHidden.value = perPageSelect.value; // sync hidden field used by filtersForm
            requestUpdate();
        });
    }

    async function loadAndReplace(url) {
        if (abortController) abortController.abort();
        abortController = new AbortController();

        // light loading state to avoid “jitter”
        membersTableEl.style.opacity = '0.6';
        paginationEl.style.opacity = '0.6';

        const resp = await fetch(url, { signal: abortController.signal }); // IMPORTANT: no form submit
        if (!resp.ok) throw new Error('Failed to load');

        const html = await resp.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');

        const newMembersTable = doc.getElementById('membersTable');
        const newPagination = doc.getElementById('membersPagination');
        const newOfficeFilter = doc.getElementById('officeFilter');

        if (newMembersTable) membersTableEl.innerHTML = newMembersTable.innerHTML;
        if (newPagination) paginationEl.innerHTML = newPagination.innerHTML;

        // Optional: keep office options in sync if your controller updates them based on status
        if (newOfficeFilter) {
            const selectedOffice = officeFilter.value;
            officeFilter.innerHTML = newOfficeFilter.innerHTML;
            if ([...officeFilter.options].some(o => o.value === selectedOffice)) {
                officeFilter.value = selectedOffice;
            }
        }

        membersTableEl.style.opacity = '1';
        paginationEl.style.opacity = '1';

        // Rebind because #membersTable was replaced (events on #per_page get lost)
        bindPerPageListener();

        // Update URL without reload
        window.history.pushState({}, '', url);
    }

    function requestUpdate(urlOverride = null) {
        const url = urlOverride || buildUrl();
        loadAndReplace(url).catch(err => {
            if (err.name === 'AbortError') return;
            console.error(err);
            membersTableEl.style.opacity = '1';
            paginationEl.style.opacity = '1';
        });
    }

    // Prevent any normal submit
    filtersForm.addEventListener('submit', (e) => {
        e.preventDefault();
        requestUpdate();
    });

    // Debounced search
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => requestUpdate(), 300);
    });

    statusFilter.addEventListener('change', () => requestUpdate());
    officeFilter.addEventListener('change', () => requestUpdate());

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        statusFilter.value = '';
        officeFilter.value = '';
        requestUpdate();
    });

    // AJAX pagination clicks
    paginationEl.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;
        e.preventDefault();
        requestUpdate(link.href);
    });

    // Initial bind
    bindPerPageListener();

    // Back/forward support
    window.addEventListener('popstate', () => {
        requestUpdate(window.location.href);
    });
});
</script>

</x-admin-layout>
