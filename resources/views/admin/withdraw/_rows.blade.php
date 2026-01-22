@php $i = $members->firstItem() ?? 1; @endphp

@if($members->count() === 0)
<tr><td colspan="8" class="text-center text-muted">No records found</td></tr>
@else
@foreach($members as $m)
    @php
        $uid = $m->id;
        $totalWithdraw = (float)($withdrawTotals[$uid] ?? 0);
        $latestWithdraw = $latestWithdrawByUser[$uid] ?? null;
    @endphp

    <tr class="memberRow"
        data-name="{{ strtolower($m->name) }}"
        data-id="{{ strtolower($m->employee_ID) }}"
        data-office="{{ strtolower($m->office) }}">
        <td><input type="checkbox" class="memberCheckbox" value="{{ $m->id }}"></td>
        <td>{{ $i++ }}</td>
        <td>{{ $m->employee_ID }}</td>
        <td>{{ $m->name }}</td>
        <td>{{ $m->office }}</td>
        <td>{{ $latestWithdraw ?? 'N/A' }}</td>
        <td>{{ number_format($totalWithdraw, 2) }}</td>
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
@endif
