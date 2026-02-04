@php $count = ($members->currentPage() - 1) * $members->perPage(); @endphp

@foreach($members as $member)
@php
    $totalSavings     = (float) ($savingTotals[$member->id] ?? 0);
    $totalWithdrawn   = (float) ($withdrawTotals[$member->id] ?? 0);
    $remainingBalance = max($totalSavings - $totalWithdrawn, 0);

    $monthsContributed = (int) ($monthsContributedByUser[$member->id] ?? 0);
    $firstRemittance   = $firstRemittanceByUser[$member->id] ?? null;
    $latestRemittance  = $latestRemittanceByUser[$member->id] ?? null;
    $latestUpdated     = $latestUpdatedByUser[$member->id] ?? null;
@endphp

<tr class="memberRow"
    data-name="{{ strtolower($member->name) }}"
    data-id="{{ strtolower($member->employee_ID) }}"
    data-office="{{ strtolower($member->office) }}"
    data-savings="{{ $member->savings }}"
>
    <td><input type="checkbox" class="memberCheckbox" value="{{ $member->id }}"></td>
    <td>{{ ++$count }}</td>
    <td>{{ $member->employee_ID }}</td>
    <td>{{ $member->name }}</td>
    <td>{{ $member->office }}</td>
    <td class="current-savings">{{ $member->savings }}</td>
    <td>{{ $firstRemittance ?? 'N/A' }}</td>
    <td>{{ $latestRemittance ?? 'N/A' }}</td>
    <td>{{ number_format($totalSavings, 2) }}</td>
    <td>{{ number_format($totalWithdrawn, 2) }}</td>
    <td>{{ number_format($remainingBalance, 2) }}</td>
    <td>{{ $monthsContributed }}</td>
    <td>{{ $latestUpdated ?? 'N/A' }}</td>
    <td>
        <button class="btn btn-info update-details-btn"
            data-bs-toggle="modal"
            data-bs-target="#updateDetailsModal"
            data-id="{{ $member->id }}"
            data-employee_id="{{ $member->employee_ID }}"
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
            data-id="{{ $member->id }}"
            data-employee_id="{{ $member->employee_ID }}"
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
@endforeach
