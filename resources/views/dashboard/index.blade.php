@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">All Data</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"> <a href="javascript:void(0);"> Dashboards </a> </li>
                <li class="breadcrumb-item active" aria-current="page">All Data</li>
            </ol>
        </div>
        <div> 
            <button class="btn btn-secondary-light btn-wave me-0 waves-effect waves-light"> 
                <i class="ri-upload-cloud-line align-middle"></i> 
                Export Report 
            </button> 
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12 col-xl-12">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-lg-4">
                    <div class="card custom-card project-card overflow-hidden">
                        <div class="card-header bg-primary p-4 align-items-start overflow-hidden">
                            <div>
                                <div class="card-title fs-5 mb-2 text-fixed-white">
                                    <svg class="upcoming-icon me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path
                                            d="M8.04492,22a.99922.99922,0,0,1-.96533-1.25879L8.88574,14H5.04541a1.00007,1.00007,0,0,1-.96582-1.25879l2.67969-10A.99954.99954,0,0,1,7.7251,2h7a1.00008,1.00008,0,0,1,.96582,1.25879L14.42041,8h6.53418a1,1,0,0,1,.73975,1.67285l-10.90918,12A.99947.99947,0,0,1,8.04492,22Z">
                                        </path>
                                    </svg>ADVERTISER ANALYTICS
                                </div>
                                <span class="subtitle text-fixed-white">
                                    Track the performance of your ads. Get detailed insights into clicks, impressions,
                                    conversion rates.
                                </span>
                            </div>
                        </div>

                        <div class="card-body project-cardbody">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card custom-card card-bg-light shadow-none border-0 mb-3">
                                        <div class="card-body p-0">
                                            <div class="row g-0">
                                                <div
                                                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 project-analysis-border">
                                                    <div class="p-4">
                                                        <div class="d-flex align-items-start">
                                                            <span class="svg-primary">
                                                                <i class="ri-wallet-3-line"
                                                                    style="
                                                                font-size: 22.5px; 
                                                                background: linear-gradient(90deg, #FF4E50 0%, #F9D423 100%);
                                                                -webkit-background-clip: text;
                                                                -webkit-text-fill-color: transparent;
                                                                display: inline-block;
                                                            "></i>

                                                            </span>
                                                        </div>
                                                        <span class="d-block fw-medium mt-3">Spending</span>
                                                        <div class="d-flex align-items-center mt-3 gap-3 lh-1 flex-wrap">
                                                            <h6 class="fw-medium mb-0 lh-1">34,876</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="p-4">
                                                        <div class="d-flex align-items-start">
                                                            <span class="svg-secondary">
                                                                <i class="ri-links-line"
                                                                    style="
                                                                    font-size: 22.5px; 
                                                                    background: linear-gradient(90deg, #FF4E50 0%, #F9D423 100%);
                                                                    -webkit-background-clip: text;
                                                                    -webkit-text-fill-color: transparent;
                                                                    display: inline-block;
                                                                "></i>
                                                            </span>
                                                        </div>
                                                        <span class="d-block fw-medium mt-3">CPC</span>
                                                        <div class="d-flex align-items-center mt-3 gap-3 lh-1 flex-wrap">
                                                            <h6 class="fw-medium mb-0 lh-1">26,231</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="card custom-card card-bg-light shadow-none border-0 mb-0">
                                        <div class="card-body p-0">
                                            <div class="row g-0">
                                                <div
                                                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 project-analysis-border">
                                                    <div class="p-4">
                                                        <div class="d-flex align-items-start">
                                                            <span class="svg-success">
                                                                <i class="ri-group-3-line"
                                                                    style="
                                                                    font-size: 22.5px; 
                                                                    background: linear-gradient(90deg, #FF4E50 0%, #F9D423 100%);
                                                                    -webkit-background-clip: text;
                                                                    -webkit-text-fill-color: transparent;
                                                                    display: inline-block;
                                                                "></i>
                                                            </span>
                                                        </div>
                                                        <span class="d-block fw-medium mt-3">Pending</span>
                                                        <div class="d-flex align-items-center mt-3 gap-3 lh-1 flex-wrap">
                                                            <h6 class="fw-medium mb-0 lh-1">8,645</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="p-4">
                                                        <div class="d-flex align-items-start">
                                                            <span class="svg-orange">
                                                                <i class="ri-pulse-line"
                                                                    style="
                                                                    font-size: 22.5px;
                                                                    background: linear-gradient(90deg, #FF5722 0%, #FF9800 100%);
                                                                    -webkit-background-clip: text;
                                                                    -webkit-text-fill-color: transparent;
                                                                    display: inline-block;
                                                                ">
                                                                </i>

                                                            </span>
                                                        </div>
                                                        <span class="d-block fw-medium mt-3">New Projects</span>
                                                        <div class="d-flex align-items-center mt-3 gap-3 lh-1 flex-wrap">
                                                            <h6 class="fw-medium mb-0 lh-1">3,579</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-8 col-lg-8">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Graphic Overview</div>
                        </div>
                        <div class="card-body">
                            <div id="project-statistics"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.3.0/apexcharts.min.js"
        integrity="sha512-QgLS4OmTNBq9TujITTSh0jrZxZ55CFjs4wjK8NXsBoZb04UYl8wWQJNaS8jRiLq6/c60bEfOj3cPsxadHICNfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        fetch("{{ route('chart.chartAdvertiser') }}")
            .then(response => response.json())
            .then(data => {
                var options = {
                    series: [{
                        name: 'Spending',
                        type: 'line',
                        data: data.spending
                    }, {
                        name: 'Cost Per Result',
                        type: 'line',
                        data: data.costPerResult
                    }, {
                        name: 'Impressions',
                        type: 'line',
                        data: data.impressions
                    }, {
                        name: 'Bounce Rate',
                        type: 'line',
                        data: data.bounceRate
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        stacked: false,
                        toolbar: {
                            show: false
                        },
                        dropShadow: {
                            enabled: true,
                            top: 7,
                            blur: 3,
                            color: ["var(--primary-color)", "rgb(215, 124, 247)", "rgb(12, 215, 177)"],
                            opacity: 0.1
                        },
                    },
                    colors: ["var(--primary-color)", "rgb(215, 124, 247)", "rgb(12, 215, 177)",
                        "rgb(255, 99, 132)"
                    ],
                    grid: {
                        borderColor: '#f1f1f1',
                        strokeDashArray: 3
                    },
                    stroke: {
                        width: [2, 2, 2, 2],
                        curve: 'smooth',
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '30%',
                            borderRadius: 5,
                        }
                    },
                    labels: data.dates, // Use the dates from the backend as labels
                    markers: {
                        size: 0,
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        fontFamily: "Montserrat",
                        markers: {
                            width: 10,
                            height: 10,
                        }
                    },
                    xaxis: {
                        type: 'datetime',
                        axisBorder: {
                            show: true,
                            color: 'rgba(119, 119, 142, 0.05)',
                        },
                        axisTicks: {
                            show: true,
                            color: 'rgba(119, 119, 142, 0.05)',
                            width: 6,
                        },
                        labels: {
                            rotate: -90
                        }
                    },
                    yaxis: {
                        title: {
                            style: {
                                color: '#adb5be',
                                fontSize: '14px',
                                fontFamily: 'Mulish, sans-serif',
                                fontWeight: 600,
                                cssClass: 'apexcharts-yaxis-label',
                            },
                        },
                    },
                    tooltip: {
                        shared: true,
                        theme: "dark",
                    }
                };

                // Render the chart
                var chart = new ApexCharts(document.querySelector("#project-statistics"), options);
                chart.render();
            })
            .catch(error => console.error('Error fetching chart data:', error));
    </script>
@endsection
