@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-start">
                        <iconify-icon icon="solar:dollar-bold-duotone" class="fs-6 me-2"></iconify-icon>
                        <h5 class="card-title mb-0">Sold Properties Overview</h5>
                    </div>

                    <div id="traffic-overview"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-start">
                        <iconify-icon icon="solar:database-bold-duotone" class="fs-6 me-2"></iconify-icon>
                        <h5 class="card-title mb-0">Data Overview</h5>
                    </div>

                    <div class="row mt-4">
                        <div class="col-6 d-flex flex-column align-items-center">
                            <iconify-icon icon="solar:laptop-minimalistic-line-duotone"
                                class="fs-7 d-flex text-primary"></iconify-icon>
                            <span class="fs-11 mt-2 d-block text-nowrap text-center">Property Views</span>
                            <h4 class="mb-0 mt-1 text-center count" data-count="{{ $totalViews }}">
                                {{ $percentageViews }}%
                            </h4>
                        </div>
                        <div class="col-6 d-flex flex-column align-items-center">
                            <iconify-icon icon="solar:smartphone-line-duotone"
                                class="fs-7 d-flex text-danger"></iconify-icon>
                            <span class="fs-11 mt-2 d-block text-nowrap text-center">Interactions</span>
                            <h4 class="mb-0 mt-1 text-center count" data-count="{{ $totalInteractions }}">
                                {{ $percentageInteractions }}%
                            </h4>
                        </div>
                    </div>

                    <div class="vstack gap-4 pt-2 mt-4">
                        <div>
                            <div class="hstack justify-content-between">
                                <span class="fs-3 fw-medium">Pending Requests</span>
                                <h6 class="fs-3 fw-medium text-dark lh-base mb-0 count"
                                    data-count="{{ $listWithUsRequests }}">
                                    {{ number_format($listWithUsRequestsPercentage, 2) }}%
                                </h6>
                            </div>
                            <div class="progress mt-6" role="progressbar" aria-label="Pending Requests"
                                aria-valuenow="{{ number_format($listWithUsRequestsPercentage, 2) }}" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar bg-warning"
                                    style="width: {{ number_format($listWithUsRequestsPercentage, 2) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="hstack justify-content-between">
                                <span class="fs-3 fw-medium">Available Properties</span>
                                <h6 class="fs-3 fw-medium text-dark lh-base mb-0 count"
                                    data-count="{{ $availableProperties }}">
                                    {{ number_format($availablePropertiesPercentage, 2) }}%
                                </h6>
                            </div>
                            <div class="progress mt-6" role="progressbar" aria-label="Available Properties"
                                aria-valuenow="{{ number_format($availablePropertiesPercentage, 2) }}" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar bg-secondary"
                                    style="width: {{ number_format($availablePropertiesPercentage, 2) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="hstack justify-content-between">
                                <span class="fs-3 fw-medium">Sold Properties</span>
                                <h6 class="fs-3 fw-medium text-dark lh-base mb-0 count"
                                    data-count="{{ $soldProperties }}">
                                    {{ number_format($soldPropertiesPercentage, 2) }}%
                                </h6>
                            </div>
                            <div class="progress mt-6" role="progressbar" aria-label="Sold Properties"
                                aria-valuenow="{{ number_format($soldPropertiesPercentage, 2) }}" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar bg-success"
                                    style="width: {{ number_format($soldPropertiesPercentage, 2) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="hstack justify-content-between">
                                <span class="fs-3 fw-medium">Contact Us Messages</span>
                                <h6 class="fs-3 fw-medium text-dark lh-base mb-0 count"
                                    data-count="{{ $contactUsMessages }}">
                                    {{ number_format($contactUsMessages, 2) }}%
                                </h6>
                            </div>
                            <div class="progress mt-6" role="progressbar" aria-label="Contact Us Messages"
                                aria-valuenow="{{ number_format($contactUsMessages, 2) }}" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar bg-info"
                                    style="width: {{ number_format($contactUsMessages, 2) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-start">
                        <iconify-icon icon="solar:file-text-bold-duotone" class="fs-6 me-2"></iconify-icon>
                        <h5 class="card-title mb-0">Recent Logs</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0">No.</th>
                                    <th scope="col" class="ps-0">Type</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Message</th>
                                    <th scope="col" class="text-center">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @if($logs->count() > 0)
                                    @foreach($logs as $log)
                                        <tr>
                                            <th class="align-middle text-center">{{ $loop->iteration }}</th>
                                            <td>{{ $log->type }}</td>
                                            <td>{{ $log->user }}</td>
                                            <td>{{ $log->subject }}</td>
                                            <td>{{ $log->message }}</td>
                                            <td class="text-center">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No logs found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .count {
        cursor: default;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('.count').hover(
            function () {
                const originalValue = $(this).text();
                const actualCount = $(this).data('count');

                $(this).text(actualCount);
                $(this).data('original-value', originalValue);
            },
            function () {
                const originalValue = $(this).data('original-value');
                $(this).text(originalValue);
            }
        );
    });

    $(function () {
        var chart = {
            series: [
                {
                    name: "Last Year",
                    data: {!! json_encode($lastYearSoldData) !!},  // Pass PHP array to JS
                },
                {
                    name: "This Year",
                    data: {!! json_encode($thisYearSoldData) !!},  // Pass PHP array to JS
                },
            ],
            chart: {
                toolbar: {
                    show: false,
                },
                type: "line",
                fontFamily: "inherit",
                foreColor: "#adb0bb",
                height: 320,
                stacked: false,
            },
            colors: ["var(--bs-gray-300)", "var(--bs-primary)"],
            plotOptions: {},
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
            },
            stroke: {
                width: 2,
                curve: "smooth",
                dashArray: [8, 0],
            },
            grid: {
                borderColor: "rgba(0,0,0,0.1)",
                strokeDashArray: 3,
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
            },
            yaxis: {
                tickAmount: 4,
            },
            xaxis: {
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            },
            markers: {
                strokeColor: ["var(--bs-gray-300)", "var(--bs-primary)"],
                strokeWidth: 2,
            },
            tooltip: {
                theme: "dark",
            },
        };

        var chart = new ApexCharts(
            document.querySelector("#traffic-overview"),
            chart
        );
        chart.render();
    });
</script>
@endsection