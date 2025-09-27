<x-app-layout>
    <div class="details-container cont-tab">
        <div id="meminfonbar-wrapper" class="row">
            <div class="col-md-12">
                <!-- Desktop Navbar -->
                <ul class="meminfonav nav nav-pills d-none d-md-flex flex-column flex-md-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">
                            <i class="ico-memdets me-1"></i> Member Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="view-contributions" href="{{ route('member.contributions') }}">
                            <i class="ico-contri me-1"></i>My Contributions
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Tabs for Shares & Savings -->
                <ul class="nav nav-tabs" id="contributionsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="shares-tab" data-bs-toggle="tab" data-bs-target="#shares"
                            type="button" role="tab" aria-controls="shares" aria-selected="true">
                            Shares
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="savings-tab" data-bs-toggle="tab" data-bs-target="#savings"
                            type="button" role="tab" aria-controls="savings" aria-selected="false">
                            Savings
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="contributionsTabContent">
                    <!-- Shares Tab -->
                    <div class="tab-pane fade show active" id="shares" role="tabpanel" aria-labelledby="shares-tab">
                        @if($shares->isEmpty())
                            <p class="text-center">No share contributions available.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>YEAR</th>
                                        <th>JAN</th>
                                        <th>FEB</th>
                                        <th>MAR</th>
                                        <th>APR</th>
                                        <th>MAY</th>
                                        <th>JUN</th>
                                        <th>JUL</th>
                                        <th>AUG</th>
                                        <th>SEP</th>
                                        <th>OCT</th>
                                        <th>NOV</th>
                                        <th>DEC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shares as $share)
                                        <tr>
                                            <td>{{ $share->year }}</td>
                                            <td>{{ number_format($share->jan, 2) }}</td>
                                            <td>{{ number_format($share->feb, 2) }}</td>
                                            <td>{{ number_format($share->mar, 2) }}</td>
                                            <td>{{ number_format($share->apr, 2) }}</td>
                                            <td>{{ number_format($share->may, 2) }}</td>
                                            <td>{{ number_format($share->jun, 2) }}</td>
                                            <td>{{ number_format($share->jul, 2) }}</td>
                                            <td>{{ number_format($share->aug, 2) }}</td>
                                            <td>{{ number_format($share->sep, 2) }}</td>
                                            <td>{{ number_format($share->oct, 2) }}</td>
                                            <td>{{ number_format($share->nov, 2) }}</td>
                                            <td>{{ number_format($share->dec, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Summary Section -->
                            <div class="mt-3">
                                <h5>Shares Summary</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <td><strong>Total Number of Shares</strong></td>
                                        <td>{{ $totalEntries }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Amount of Shares</strong></td>
                                        <td>{{ number_format($totalDisplayed, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Savings Tab -->
                    <div class="tab-pane fade" id="savings" role="tabpanel" aria-labelledby="savings-tab">
                        @if($savings->isEmpty())
                            <p class="text-center">No savings contributions available.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>YEAR</th>
                                        <th>JAN</th>
                                        <th>FEB</th>
                                        <th>MAR</th>
                                        <th>APR</th>
                                        <th>MAY</th>
                                        <th>JUN</th>
                                        <th>JUL</th>
                                        <th>AUG</th>
                                        <th>SEP</th>
                                        <th>OCT</th>
                                        <th>NOV</th>
                                        <th>DEC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($savings as $saving)
                                        <tr>
                                            <td>{{ $saving->year }}</td>
                                            <td>{{ number_format($saving->jan, 2) }}</td>
                                            <td>{{ number_format($saving->feb, 2) }}</td>
                                            <td>{{ number_format($saving->mar, 2) }}</td>
                                            <td>{{ number_format($saving->apr, 2) }}</td>
                                            <td>{{ number_format($saving->may, 2) }}</td>
                                            <td>{{ number_format($saving->jun, 2) }}</td>
                                            <td>{{ number_format($saving->jul, 2) }}</td>
                                            <td>{{ number_format($saving->aug, 2) }}</td>
                                            <td>{{ number_format($saving->sep, 2) }}</td>
                                            <td>{{ number_format($saving->oct, 2) }}</td>
                                            <td>{{ number_format($saving->nov, 2) }}</td>
                                            <td>{{ number_format($saving->dec, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Summary Section -->
                            <div class="mt-3">
                                <h5>Savings Summary</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <td><strong>Total Number of Savings</strong></td>
                                        <td>{{ $totalEntries }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Amount of Savings</strong></td>
                                        <td>{{ number_format($totalSavingsDisplayed, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
