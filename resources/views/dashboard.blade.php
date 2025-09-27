<x-app-layout>
    <!-- 🔹 Main Content -->
    <div class="home-details details-container">
        
        <div class="row contributions-cont">
            <div class="col-12 mb-4 order-0 d-none d-xl-flex">
                Introducing our new Member Portal—a space where simplicity meets functionality. Effortlessly manage your account details, access exclusive benefits, and explore new features that have been thoughtfully designed to enhance your experience. Enjoy a smoother, more intuitive way to stay connected and in control.
            </div>
            <div class="col-lg-4 col-md-0 mb-2 mb-xl-4 order-1">
                <div class="card h-100 px-0 px-lg-4">
                    <div class="card-body pb-4">
                        <div class="d-flex justify-content-start align-items-center">
                            <h5 class="ms-2 mb-0">Contributions</h5>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <img src="/build/assets/contribution.jpg">
                        </div>
                        <div class="contributions-btn">
                            <a href="{{ route('member.contributions') }}" class="btn btn-sm btn-label-primary">View Contributions</a>
                        </div>
                        <!-- <div class="total-contributions">
                            @php
                                $totalContributions = (isset($totalShares) && $totalShares !== null ? $totalShares : 0) 
                                                    + (isset($totalSavingsDisplayed) && $totalSavingsDisplayed !== null ? $totalSavingsDisplayed : 0);
                            @endphp
                            <h4 class="mb-0">
                            &#8369; {{ $totalContributions > 0 ? number_format($totalContributions, 2) : 'N/A' }}
                            </h4>
                            <small>Total amount of contributions</small>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-0 mb-2 mb-xl-4 order-1">
                <div class="card h-100 px-0 px-lg-4">
                    <div class="card-body pb-4">
                        <div class="d-flex justify-content-start align-items-center">
                            <h5 class="mb-0">Summary of Contributions</h5>
                        </div>
                            <!-- 🔹 Shares Summary -->
                            <div class="mt-3">
                                <h5>Shares</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Total Months Contributed</td>
                                        <td>{{ isset($totalEntries) && $totalEntries !== null ? $totalEntries : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount of Shares</td>
                                        <td>{{ isset($totalShares) && $totalShares !== null ? number_format($totalShares, 2) : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- 🔹 Savings Summary -->
                            <div class="mt-3">
                                <h5>Savings</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Total Months Contributed</td>
                                        <td>{{ isset($totalSavingsEntries) && $totalSavingsEntries !== null ? $totalSavingsEntries : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount of Savings</td>
                                        <td>{{ isset($totalSavingsDisplayed) && $totalSavingsDisplayed !== null ? number_format($totalSavingsDisplayed, 2) : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card position-relative">
            <div class="card-body services-body disabled-section">
                <h4>Services</h4>
                <div class="row">
                    <div class="col-12 mb-4 order-0 d-none d-xl-flex">
                        <p>ENREMCO provides a range of affordable and flexible loan services tailored to meet the diverse needs of its members. With low interest rates, convenient payment terms, and a member-first approach, ENREMCO is committed to empowering members through reliable financial support.</p>
                    </div>
                    <div class="col-lg-3 col-md-0 mb-2 mb-xl-4 order-1">
                        <div class="card h-100 px-0">
                            <div class="card-body">
                                <div class="justify-content-start loans">
                                    <h5 class="ms-2 mb-0">Regular Loan</h5>
                                    <small>For general personal and financial needs.</small>
                                </div>
                                <ul class="p-0 m-0">
                                    <li class="d-flex pb-1">
                                        <div class="card-body px-0">
                                            <div class="bg-label-primary rounded-3 text-center mb-3">
                                                <img src="/build/assets/regular.jpg">
                                            </div>
                                            <div class="loan-button">
                                                <div>
                                                    <a href="#" data-mod="93" class="btn btn-sm btn-label-primary">Apply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-0 mb-2 mb-xl-4 order-1">
                        <div class="card h-100 px-0">
                            <div class="card-body">
                                <div class="justify-content-start loans">
                                    <h5 class="ms-2 mb-0">Educational Loan</h5>
                                    <small>To support tuition fees and school-related expenses.</small>
                                </div>
                                <ul class="p-0 m-0">
                                    <li class="d-flex pb-1">
                                        <div class="card-body px-0">
                                            <div class="bg-label-primary rounded-3 text-center mb-3">
                                                <img src="/build/assets/educational.jpg">
                                            </div>
                                            <div class="loan-button">
                                                <div>
                                                    <a href="#" data-mod="93" class="btn btn-sm btn-label-primary">Apply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-0 mb-2 mb-xl-4 order-1">
                        <div class="card h-100 px-0">
                            <div class="card-body">
                                <div class="justify-content-start loans">
                                    <h5 class="ms-2 mb-0">Appliance Loan</h5>
                                    <small>For purchasing essential home appliances.</small>
                                </div>
                                <ul class="p-0 m-0">
                                    <li class="d-flex pb-1">
                                        <div class="card-body px-0">
                                            <div class="bg-label-primary rounded-3 text-center mb-3">
                                                <img src="/build/assets/appliance.jpg">
                                            </div>
                                            <div class="loan-button">
                                                <div>
                                                    <a href="#" data-mod="93" class="btn btn-sm btn-label-primary">Apply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-0 mb-2 mb-xl-4 order-1">
                        <div class="card h-100 px-0">
                            <div class="card-body">
                                <div class="justify-content-start loans">
                                    <h5 class="ms-2 mb-0">Grocery Loan</h5>
                                    <small>To help with daily food and household essentials.</small>
                                </div>
                                <ul class="p-0 m-0">
                                    <li class="d-flex pb-1">
                                        <div class="card-body px-0">
                                            <div class="bg-label-primary rounded-3 text-center mb-3">
                                                <img src="/build/assets/grocery.jpg">
                                            </div>
                                            <div class="loan-button">
                                                <div>
                                                    <a href="#" data-mod="93" class="btn btn-sm btn-label-primary">Apply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                                <!-- 🔹 Coming Soon Overlay -->
                <div class="coming-soon-overlay">
                    <h3 class="text-white">Coming Soon</h3>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
