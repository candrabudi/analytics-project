@extends('layouts.app')

@section('content')
    <div class="row">
        <!-- Spending Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="card custom-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div id="card_spending">
                            <div>
                                <span class="d-block mb-2">Spending</span>
                                <h5 class="mb-4 fs-4" id="todaySpending">0</h5>
                            </div>
                            <span class="text-success me-2 fw-medium d-inline-block">
                                <i class="ti ti-trending-up fs-5 align-middle me-1 d-inline-block"></i><span
                                    id="percentageChangeSpending">0%</span>
                            </span>
                            <span class="text-muted" id="lastMonthLabel">Since yesterday</span>
                        </div>

                        <div>
                            <div class="main-card-icon primary">
                                <div
                                    class="avatar avatar-lg bg-primary-transparent border border-primary border-opacity-10">
                                    <i class="ri-wallet-3-line"
                                        style="
                                        font-size: 22.5px; 
                                        background: linear-gradient(90deg, #FF4E50 0%, #F9D423 100%);
                                        -webkit-background-clip: text;
                                        -webkit-text-fill-color: transparent;
                                        display: inline-block;">
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="card custom-card main-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div id="card_cpr">
                            <div>
                                <span class="d-block mb-2">Cost Per Result</span>
                                <div style="display: flex; align-items: center;">
                                    <h5 class="mb-4 fs-4" id="todayCpr" style="margin-left: 10px;">0</h5>
                                    <span style="font-size: 12px; margin-left: 10px;" id="todayTotalResult">(0)</span>
                                </div>
                            </div>

                            <span class="text-success me-2 fw-medium d-inline-block">
                                <i class="ti ti-trending-up fs-5 align-middle me-1 d-inline-block"></i><span
                                    id="percentageChangeCpr">0%</span>
                            </span>
                            <span class="text-muted" id="lastMonthLabel">Since yesterday</span>
                        </div>

                        <div>
                            <div class="main-card-icon secondary">
                                <div
                                    class="avatar avatar-lg bg-secondary-transparent border border-secondary border-opacity-10">
                                    <i class="ri-links-line"
                                        style="
                                            font-size: 22.5px;
                                            background: linear-gradient(90deg, #D5006D 0%, #FF4081 100%);
                                            -webkit-background-clip: text;
                                            -webkit-text-fill-color: transparent;
                                            display: inline-block;
                                        "></i>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impression Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="card custom-card main-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div id="card_impression">
                            <div>
                                <span class="d-block mb-2">Impression</span>
                                <h5 class="mb-4 fs-4" id="todayImpression">0</h5>
                            </div>
                            <span class="text-success me-2 fw-medium d-inline-block">
                                <i class="ti ti-trending-up fs-5 align-middle me-1 d-inline-block"></i><span
                                    id="percentageChangeImpression">0%</span>
                            </span>
                            <span class="text-muted" id="lastMonthLabel">Since yesterday</span>
                        </div>
                        <div>
                            <div class="main-card-icon success">
                                <div
                                    class="avatar avatar-lg bg-success-transparent border border-success border-opacity-10">
                                    <i class="ri-group-3-line"
                                        style="
                                            font-size: 22.5px;
                                            background: linear-gradient(90deg, #00E676 0%, #69F0AE 100%);
                                            -webkit-background-clip: text;
                                            -webkit-text-fill-color: transparent;
                                            display: inline-block;">
                                    </i>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bounce Rate Card -->
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="card custom-card main-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div id="card_bounce_rate">
                            <div>
                                <span class="d-block mb-2">Bounce Rate</span>
                                <h5 class="mb-4 fs-4" id="todayBounceRate">0</h5>
                            </div>
                            <span class="text-success me-2 fw-medium d-inline-block">
                                <i class="ti ti-trending-up fs-5 align-middle me-1 d-inline-block"></i><span
                                    id="percentageChangeBounceRate">0%</span>
                            </span>
                            <span class="text-muted" id="lastMonthLabel">Since yesterday</span>
                        </div>
                        <div>
                            <div class="main-card-icon orange">
                                <div class="avatar avatar-lg bg-orange-transparent border border-orange border-opacity-10">
                                    <i class="ri-pulse-line"
                                        style="
                                                                    font-size: 22.5px;
                                                                    background: linear-gradient(90deg, #FF5722 0%, #FF9800 100%);
                                                                    -webkit-background-clip: text;
                                                                    -webkit-text-fill-color: transparent;
                                                                    display: inline-block;
                                                                ">
                                    </i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9">
            <div class="card custom-card overflow-hidden sales-statistics-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        SALES STATISTICS
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="p-2 fs-12 text-muted" data-bs-toggle="dropdown"
                            aria-expanded="false"> Sort By <i
                                class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i> </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">This Week</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Last Week</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">This Month</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body position-relative p-0">
                    <div id="sales-statistics"></div>
                    <div id="sales-statistics1"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        SCALE UP / SCALE DOWN ADS
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-0">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('{{ route('advertiser.dashboard.data_card') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const cardSpending = data.card_spending;

                document.getElementById('todaySpending').innerText = cardSpending.todayTotalSpending;

                const percentageChangeSpending = cardSpending.percentageChangeSpending.toFixed(2);
                document.getElementById('percentageChangeSpending').innerText = `${percentageChangeSpending}%`;

                const percentageElementSpending = document.getElementById('percentageChangeSpending');
                if (percentageChangeSpending > 0) {
                    percentageElementSpending.classList.add('text-success');
                    percentageElementSpending.classList.remove('text-danger');
                } else {
                    percentageElementSpending.classList.add('text-danger');
                    percentageElementSpending.classList.remove('text-success');
                }


                const cardCpr = data.card_cpr;

                document.getElementById('todayCpr').innerText = cardCpr.todayTotalCpr;
                document.getElementById('todayTotalResult').innerText = "(" + cardCpr.todayTotalResult + ")";

                const percentageChangeCpr = cardCpr.percentageChangeCpr.toFixed(2);
                document.getElementById('percentageChangeCpr').innerText = `${percentageChangeCpr}%`;

                const percentageElementCpr = document.getElementById('percentageChangeCpr');
                if (percentageChangeCpr > 0) {
                    percentageElementCpr.classList.add('text-success');
                    percentageElementCpr.classList.remove('text-danger');
                } else {
                    percentageElementCpr.classList.add('text-danger');
                    percentageElementCpr.classList.remove('text-success');
                }


                const cardBounceRate = data.card_bounce_rate;

                document.getElementById('todayBounceRate').innerText = cardBounceRate.todayTotalBounceRate;

                const percentageChangeBounceRate = cardBounceRate.percentageChangeBounceRate.toFixed(2);
                document.getElementById('percentageChangeBounceRate').innerText = `${percentageChangeBounceRate}%`;

                const percentageElementBounceRate = document.getElementById('percentageChangeBounceRate');
                if (percentageChangeBounceRate > 0) {
                    percentageElementBounceRate.classList.add('text-success');
                    percentageElementBounceRate.classList.remove('text-danger');
                } else {
                    percentageElementBounceRate.classList.add('text-danger');
                    percentageElementBounceRate.classList.remove('text-success');
                }

                const cardImpression = data.card_impression;

                document.getElementById('todayImpression').innerText = cardImpression.todayTotalImpression;

                const percentageChangeImpression = cardImpression.percentageChangeImpression.toFixed(2);
                document.getElementById('percentageChangeImpression').innerText = `${percentageChangeImpression}%`;

                const percentageElementImpression = document.getElementById('percentageChangeImpression');
                if (percentageChangeImpression > 0) {
                    percentageElementImpression.classList.add('text-success');
                    percentageElementImpression.classList.remove('text-danger');
                } else {
                    percentageElementImpression.classList.add('text-danger');
                    percentageElementImpression.classList.remove('text-success');
                }

            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.3.0/apexcharts.min.js"
        integrity="sha512-QgLS4OmTNBq9TujITTSh0jrZxZ55CFjs4wjK8NXsBoZb04UYl8wWQJNaS8jRiLq6/c60bEfOj3cPsxadHICNfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Ambil data dari API
        fetch('/advertiser/dashboard/chart-one') // Endpoint API Anda
            .then(response => response.json())
            .then(data => {
                // Proses data API untuk mengisi grafik
                const dates = data.map(item => new Date(item.upload_date).getTime()); // Convert to timestamp for x-axis
                const salesData = data.map(item => item.total_spent); // Data total_spent (dalam ribuan)
                const refundsData = data.map(item => item.total_cost); // Data total_cost (dalam ribuan)

                // Definisikan opsi untuk grafik ApexCharts
                var options = {
                    series: [{
                        name: "Sales",
                        type: "area",
                        data: dates.map((date, index) => [date, salesData[
                        index]]), // Map dates and sales data
                    }, {
                        name: "Refunds",
                        type: "area",
                        data: dates.map((date, index) => [date, refundsData[
                        index]]), // Map dates and refunds data
                    }],
                    chart: {
                        height: 220,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false,
                        },
                        sparkline: {
                            enabled: true
                        }
                    },
                    colors: [
                        "rgba(12, 215, 177, 0.8)", "var(--primary07)"
                    ],
                    fill: {
                        type: 'solid'
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        show: false,
                        position: "top",
                        offsetX: 0,
                        offsetY: 8,
                        markers: {
                            width: 10,
                            height: 4,
                            strokeWidth: 0,
                            strokeColor: '#fff',
                            fillColors: undefined,
                            radius: 5,
                            customHTML: undefined,
                            onClick: undefined,
                            offsetX: 0,
                            offsetY: 0
                        },
                    },
                    stroke: {
                        curve: 'smooth',
                        width: [1, 1],
                        lineCap: 'round',
                    },
                    grid: {
                        borderColor: "#edeef1",
                        strokeDashArray: 2,
                    },
                    yaxis: {
                        axisBorder: {
                            show: false,
                            color: "rgba(119, 119, 142, 0.05)",
                            offsetX: 0,
                            offsetY: 0,
                        },
                        axisTicks: {
                            show: false,
                            borderType: "solid",
                            color: "rgba(119, 119, 142, 0.05)",
                            width: 6,
                            offsetX: 0,
                            offsetY: 0,
                        },
                        labels: {
                            show: false,
                            formatter: function(y) {
                                return y.toFixed(0) + "";
                            },
                        },
                    },
                    xaxis: {
                        type: "datetime", // Use datetime for x-axis
                        categories: dates, // Set x-axis categories to dates
                        axisBorder: {
                            show: false,
                            color: "rgba(119, 119, 142, 0.05)",
                            offsetX: 0,
                            offsetY: 0,
                        },
                        axisTicks: {
                            show: false,
                            borderType: "solid",
                            color: "rgba(119, 119, 142, 0.05)",
                            width: 6,
                            offsetX: 0,
                            offsetY: 0,
                        },
                        labels: {
                            show: true,
                            rotate: -90,
                        },
                    },
                    tooltip: {
                        enabled: false,
                    }
                };

                var chart4 = new ApexCharts(document.querySelector("#sales-statistics"), options);
                chart4.render();
            })
            .catch(error => console.error("Error fetching data: ", error));
    </script>


<script>
    // Ambil data dari API
    fetch('/advertiser/dashboard/chart-one') // Endpoint API Anda
      .then(response => response.json())
      .then(data => {
        // Proses data API untuk mengisi grafik
        const dates = data.map(item => new Date(item.upload_date).toLocaleDateString('en-GB')); // Format tanggal
        const spentData = data.map(item => item.total_spent); // Data total_spent (dalam ribuan)
        const costData = data.map(item => item.total_cost); // Data total_cost (dalam ribuan)
        const impressionsData = data.map(item => item.total_impressions); // Data total_impressions (dalam ribuan)
        const donationsData = data.map(item => item.total_donations !== null ? item.total_donations : 0); // Data total_donations (0 jika null)
  
        // Definisikan opsi untuk grafik ApexCharts
        var options2 = {
          series: [{
            name: 'Total Spent',
            data: spentData,
          }, {
            name: 'Total Cost',
            data: costData,
          }, {
            name: 'Impressions',
            data: impressionsData,
          }, {
            name: 'Donations',
            data: donationsData,
          }],
          chart: {
            height: 280,
            type: 'line',
            toolbar: {
              show: false,
            },
            background: 'none',
            fill: "#fff",
            dropShadow: {
              enabled: true,
              top: 7,
              left: 0,
              blur: 1,
              color: ["var(--primary-color)", "rgb(12, 215, 177)", "rgb(215, 124, 247)", "rgb(255, 99, 132)"],
              opacity: 0.05,
            },
          },
          grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 3
          },
          colors: ["var(--primary-color)", "rgb(12, 215, 177)", "rgb(215, 124, 247)", "rgb(255, 99, 132)"],
          background: 'transparent',
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'smooth',
            width: 2,
          },
          legend: {
            show: true,
            position: 'top',
            offsetY: 30,
          },
          xaxis: {
            categories: dates,
            show: false,
          },
          yaxis: {
            axisBorder: {
              show: false,
            },
            axisTicks: {
              show: false,
            },
            labels: {
              formatter: function(value) {
                if (value >= 1000) {
                  return (value / 1000).toFixed(2) + 'RB';
                } else if (value >= 1000000) {
                  return (value / 1000000).toFixed(2) + 'JT';
                }
                return value.toFixed(2);
              }
            },
          },
          tooltip: {
            y: [{
              formatter: function(e) {
                if (e >= 1000000) {
                  return "Rp " + (e / 1000000).toFixed(0) + " JT";
                } else if (e >= 1000) {
                  return "Rp " + (e / 1000).toFixed(0) + " RB";
                }
                return "Rp " + e.toFixed(0);
              }
            }, {
              formatter: function(e) {
                if (e >= 1000000) {
                  return "Rp " + (e / 1000000).toFixed(0) + " JT";
                } else if (e >= 1000) {
                  return "Rp " + (e / 1000).toFixed(0) + " RB";
                }
                return "Rp " + e.toFixed(0);
              }
            }, {
              formatter: function(e) {
                if (e >= 1000000) {
                  return "Rp " + (e / 1000000).toFixed(0) + " JT";
                } else if (e >= 1000) {
                  return "Rp " + (e / 1000).toFixed(0) + " RB";
                }
                return "Rp " + e.toFixed(0);
              }
            }, {
              formatter: function(e) {
                return e !== null ? e : '0';
              }
            }],
            theme: "dark",
          }
        };
  
        var chart4 = new ApexCharts(document.querySelector("#sales-statistics1"), options2);
        chart4.render();
      })
      .catch(error => console.error("Error fetching data: ", error));
  </script>
@endsection
