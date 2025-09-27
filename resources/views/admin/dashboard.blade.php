<x-admin-layout>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        </div>

        <div class="row mb-6">
            <!-- Active Members -->
            <div class="col-lg-4 col-md-6">
                <div class="ibox footer color-white widget-stat">
                    <div class="ibox-body">
                        <h4 class="m-b-5 font-strong">{{ $totalUsers }}</h4>
                        <p class="m-b-5">Active Members</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Savings -->
            <div class="col-lg-4 col-md-6">
                <div class="ibox color-white widget-stat" style="background: #7c7c7c;">
                    <div class="ibox-body">
                        <h4 class="m-b-5 font-strong">₱{{ number_format($totalSavings, 2) }}</h4>
                        <p class="m-b-5">Total Savings</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Shares -->
            <div class="col-lg-4 col-md-6">
                <div class="ibox btn-info color-white widget-stat">
                    <div class="ibox-body">
                        <h4 class="m-b-5 font-strong">₱{{ number_format($totalShares, 2) }}</h4>
                        <p class="m-b-5">Total Shares</p>
                    </div>
                </div>
            </div>
        </div>


    </div>
</x-admin-layout>
