<x-admin-v2-layout title="Admin Control Center - ENREMCO">
    @php
        /**
         * Optional: if you don't pass these from the controller,
         * we compute safe fallbacks so the page won't break.
         */
        $pendingMemberships = $pendingMemberships ?? null;
        $recentMembershipRequests = $recentMembershipRequests ?? null;
        $pendingLoanApprovals = $pendingLoanApprovals ?? null;
        $recentLoanApplications = $recentLoanApplications ?? null;

        if ($pendingMemberships === null) {
            $pendingMemberships = \App\Models\User::where('status', 'Awaiting Approval')->count();
        }

        if ($recentMembershipRequests === null) {
            $recentMembershipRequests = \App\Models\User::where('status', 'Awaiting Approval')
                ->latest()
                ->take(3)
                ->get();
        }

        // Your system doesn't really have “loan applications for approval” yet,
        // so this is best-effort: loans with no date_approved (if any).
        if ($pendingLoanApprovals === null) {
            $pendingLoanApprovals = \App\Models\LoanDetail::whereNull('date_approved')->count();
        }

        if ($recentLoanApplications === null) {
            $recentLoanApplications = \App\Models\LoanDetail::with('user')
                ->orderByDesc('date_applied')
                ->take(3)
                ->get();
        }

        $adminName = auth()->user()->name ?? 'Administrator';
        $adminRole = 'System Administrator';
        $photo = auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : null;

        $initials = collect(explode(' ', (string) $adminName))
            ->filter()
            ->map(fn($p) => mb_substr($p, 0, 1))
            ->take(2)
            ->implode('');
    @endphp

    {{-- HEADER --}}
    <header
        class="flex items-center justify-between border-b border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] px-6 py-3 sticky top-0 z-50">
        <div class="flex items-center gap-4 text-[#111814] dark:text-white">
            <h2 class="text-[#111814] dark:text-white text-xl font-bold tracking-tight uppercase">
                ENREMCO Admin
            </h2>
        </div>

        <div class="flex flex-1 justify-end gap-6 items-center">
            <div
                class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-[#f6f8f7] dark:bg-[#112119]/50 rounded-full border border-[#dce5e0] dark:border-[#2a3a32]">
                <span class="material-symbols-outlined text-[#19e680] text-lg">search</span>
                <input class="bg-transparent border-none text-xs focus:ring-0 w-48 placeholder:text-[#638875]"
                    placeholder="Search members, loans..." type="text" />
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-[#111814] dark:text-white">{{ $adminName }}</p>
                    <p class="text-[10px] text-[#638875] font-medium uppercase tracking-wider">{{ $adminRole }}</p>
                </div>

                @if($photo)
                    <div class="bg-center bg-no-repeat bg-cover rounded-full size-10 border-2 border-[#19e680]"
                        style='background-image: url("{{ $photo }}");'></div>
                @else
                    <div
                        class="rounded-full size-10 border-2 border-[#19e680] bg-[#dce5e0] flex items-center justify-center text-xs font-black">
                        {{ $initials ?: 'AD' }}
                    </div>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="material-symbols-outlined text-[#638875] hover:text-[#111814] dark:hover:text-white transition-colors"
                    aria-label="Settings">settings</a>
            </div>
        </div>
    </header>

    <div class="flex max-w-[1600px] mx-auto">
        {{-- SIDEBAR --}}
        <aside
            class="w-64 sticky top-[65px] h-[calc(100vh-65px)] border-r border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] p-4 flex flex-col gap-6">
            <nav class="flex flex-col gap-1">
                <p class="px-4 text-[10px] font-bold text-[#638875] uppercase tracking-[0.15em] mb-2">Main Menu</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors
                    {{ request()->routeIs('admin.dashboard') ? 'bg-[#19e680]/10 border-l-4 border-[#19e680] text-[#111814] font-semibold' : 'text-[#638875] hover:bg-[#f6f8f7] dark:hover:bg-[#112119]/50' }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.members') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors
                    {{ request()->routeIs('admin.members') || request()->routeIs('admin.new-members') ? 'bg-[#19e680]/10 border-l-4 border-[#19e680] text-[#111814] font-semibold' : 'text-[#638875] hover:bg-[#f6f8f7] dark:hover:bg-[#112119]/50' }}">
                    <span class="material-symbols-outlined">group</span>
                    <span>Membership</span>
                </a>

                <a href="{{ route('admin.loans') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors
                    {{ request()->routeIs('admin.loans') ? 'bg-[#19e680]/10 border-l-4 border-[#19e680] text-[#111814] font-semibold' : 'text-[#638875] hover:bg-[#f6f8f7] dark:hover:bg-[#112119]/50' }}">
                    <span class="material-symbols-outlined">payments</span>
                    <span>Loans Management</span>
                </a>

                <a href="{{ route('admin.shares') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors
                    {{ request()->routeIs('admin.shares') || request()->routeIs('admin.savings') ? 'bg-[#19e680]/10 border-l-4 border-[#19e680] text-[#111814] font-semibold' : 'text-[#638875] hover:bg-[#f6f8f7] dark:hover:bg-[#112119]/50' }}">
                    <span class="material-symbols-outlined">account_balance</span>
                    <span>Capital &amp; Savings</span>
                </a>

                <a href="{{ route('admin.withdraw') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition-colors
                    {{ request()->routeIs('admin.withdraw') ? 'bg-[#19e680]/10 border-l-4 border-[#19e680] text-[#111814] font-semibold' : 'text-[#638875] hover:bg-[#f6f8f7] dark:hover:bg-[#112119]/50' }}">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span>Withdrawals</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#638875] hover:bg-[#f6f8f7] dark:hover:bg-[#112119]/50 text-sm transition-colors">
                    <span class="material-symbols-outlined">analytics</span>
                    <span>Reports</span>
                </a>
            </nav>

            <nav class="flex flex-col gap-1 mt-auto">
                <p class="px-4 text-[10px] font-bold text-[#638875] uppercase tracking-[0.15em] mb-2">System</p>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-[#638875] hover:bg-[#f6f8f7] dark:hover:bg-[#112119]/50 text-sm transition-colors">
                    <span class="material-symbols-outlined">verified_user</span>
                    <span>Audit Logs</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 text-sm transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Sign Out</span>
                    </button>
                </form>
            </nav>
        </aside>

        {{-- MAIN --}}
        <main class="flex-1 p-8 overflow-x-hidden">
            <div class="mb-8">
                <h1 class="text-[#111814] dark:text-white text-3xl font-black tracking-tight">Admin Control Center</h1>
                <p class="text-[#638875] text-base">Overview of ENREMCO's operational performance and pending approvals.
                </p>
            </div>

            {{-- METRICS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-[#1a2e24] p-6 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="material-symbols-outlined text-blue-600">person_add</span>
                        </div>
                    </div>
                    <p class="text-[#638875] text-xs font-bold uppercase tracking-wider mb-1">Pending Memberships</p>
                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">
                        {{ number_format($pendingMemberships) }}
                    </h3>
                </div>

                <div
                    class="bg-white dark:bg-[#1a2e24] p-6 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-[#19e680]/10 rounded-lg">
                            <span class="material-symbols-outlined text-[#19e680]">account_balance_wallet</span>
                        </div>
                    </div>
                    <p class="text-[#638875] text-xs font-bold uppercase tracking-wider mb-1">Total Share Capital</p>
                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">
                        ₱{{ number_format($totalShares ?? 0, 2) }}</h3>
                </div>

                <div
                    class="bg-white dark:bg-[#1a2e24] p-6 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <span class="material-symbols-outlined text-orange-600">savings</span>
                        </div>
                    </div>
                    <p class="text-[#638875] text-xs font-bold uppercase tracking-wider mb-1">Total Member Savings</p>
                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">
                        ₱{{ number_format($totalSavings ?? 0, 2) }}</h3>
                </div>

                <div
                    class="bg-white dark:bg-[#1a2e24] p-6 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <span class="material-symbols-outlined text-purple-600">pending_actions</span>
                        </div>
                    </div>
                    <p class="text-[#638875] text-xs font-bold uppercase tracking-wider mb-1">Loan Items Pending (if
                        any)</p>
                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">
                        {{ number_format($pendingLoanApprovals) }}
                    </h3>
                </div>
            </div>

            {{-- TABLES --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <div
                    class="bg-white dark:bg-[#1a2e24] rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex justify-between items-center">
                        <h2 class="text-lg font-bold text-[#111814] dark:text-white">Recent Membership Requests</h2>
                        <a href="{{ route('admin.new-members') }}"
                            class="text-[#19e680] text-xs font-bold hover:underline">View All</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-[#f6f8f7] dark:bg-[#112119]/30">
                                <tr>
                                    <th class="px-6 py-3 text-[10px] font-bold text-[#638875] uppercase tracking-wider">
                                        Applicant</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-[#638875] uppercase tracking-wider">
                                        Date Applied</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-[#638875] uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">
                                @forelse($recentMembershipRequests as $member)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="size-8 rounded-full bg-[#dce5e0] flex items-center justify-center font-bold text-xs">
                                                    {{ strtoupper(mb_substr($member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-[#111814] dark:text-white">
                                                        {{ $member->name }}
                                                    </p>
                                                    <p class="text-[10px] text-[#638875]">{{ $member->office ?? '—' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-[#111814] dark:text-white">
                                            {{ optional($member->created_at)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <form method="POST"
                                                    action="{{ route('admin.approve-member', $member->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-[#19e680] text-[#112119] text-[10px] font-bold rounded-lg hover:bg-[#19e680]/90">
                                                        APPROVE
                                                    </button>
                                                </form>

                                                <form method="POST"
                                                    action="{{ route('admin.disapprove-member', $member->id) }}"
                                                    onsubmit="return confirm('Disapprove and remove this registration?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-red-100 dark:bg-red-900/20 text-red-600 text-[10px] font-bold rounded-lg hover:bg-red-200">
                                                        REJECT
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-6 text-sm text-[#638875]">No pending membership
                                            requests.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-[#1a2e24] rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex justify-between items-center">
                        <h2 class="text-lg font-bold text-[#111814] dark:text-white">Recent Loan Entries</h2>
                        <a href="{{ route('admin.loans') }}"
                            class="text-[#19e680] text-xs font-bold hover:underline">View All</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-[#f6f8f7] dark:bg-[#112119]/30">
                                <tr>
                                    <th class="px-6 py-3 text-[10px] font-bold text-[#638875] uppercase tracking-wider">
                                        Member</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-[#638875] uppercase tracking-wider">
                                        Amount</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-[#638875] uppercase tracking-wider">
                                        Type</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-[#638875] uppercase tracking-wider">
                                        Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">
                                @forelse($recentLoanApplications as $loan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-[#111814] dark:text-white">
                                                {{ $loan->user?->name ?? '—' }}
                                            </p>
                                            <p class="text-[10px] text-[#638875]">ID: {{ $loan->employee_ID ?? '—' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814] dark:text-white">
                                            ₱{{ number_format((float) ($loan->loan_amount ?? 0), 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-[10px] font-bold bg-gray-100 dark:bg-white/10 px-2 py-0.5 rounded uppercase">
                                                {{ $loan->loan_type ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-[#111814] dark:text-white">
                                            {{ $loan->date_applied ? \Carbon\Carbon::parse($loan->date_applied)->format('M d, Y') : optional($loan->created_at)->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-6 text-sm text-[#638875]">No loan entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="mt-12 pt-8 border-t border-[#dce5e0] dark:border-[#2a3a32]">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[#638875] text-[10px] uppercase tracking-widest font-bold">
                        ENREMCO Administrative Interface
                    </p>
                    <p class="text-[#638875] text-[10px]">
                        © {{ date('Y') }} Energy Regulatory Commission Employees Multi-Purpose Cooperative.
                    </p>
                </div>
            </footer>
        </main>
    </div>
</x-admin-v2-layout>