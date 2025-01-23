@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Meta Dashboard</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"> <a href="javascript:void(0);"> Meta </a> </li>
                <li class="breadcrumb-item active" aria-current="page">Meta Dashboard</li>
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
        <div class="col-xl-12">
            <div class="card custom-card overflow-hidden sales-statistics-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        CHART DATA
                    </div>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown btn btn-primary btn-sm text-white">
                            <a href="javascript:void(0);" class="p-2 fs-12 text-white" data-bs-toggle="dropdown"
                                aria-expanded="false"> Sort By <i
                                    class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i> </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="javascript:void(0);" id="this-week">This Week</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" id="last-week">Last Week</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" id="this-month">This Month</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);" id="last-month">Last Month</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body position-relative p-0">
                    <div id="chart" style="margin-top: -30px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <ul class="nav nav-tabs" id="scaleTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="scaleup-tab" data-bs-toggle="tab" href="#scaleup" role="tab"
                        aria-controls="scaleup" aria-selected="true">SCALE UP ADS</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="scaledown-tab" data-bs-toggle="tab" href="#scaledown" role="tab"
                        aria-controls="scaledown" aria-selected="false">SCALE DOWN ADS</a>
                </li>
            </ul>

            <div class="row">
                <div class="col-sm-6">
                    <div class="tab-content mt-3" id="scaleTabsContent">
                        <div class="tab-pane fade show active" id="scaleup" role="tabpanel"
                            aria-labelledby="scaleup-tab">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        SCALE UP ADS
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="data-table-scaleup" class="table text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama Akun</th>
                                                    <th scope="col">Nama Campaign</th>
                                                    <th scope="col">CPR</th>
                                                    <th scope="col">Result</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-table-body-scaleup">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer border-top-0">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-auto">
                                            <nav aria-label="Page navigation" class="pagination-style-4">
                                                <ul class="pagination mb-0" id="pagination-scaleup">

                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="scaledown" role="tabpanel" aria-labelledby="scaledown-tab">
                            <div class="card custom-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        SCALE DOWN ADS
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="data-table-scaledown" class="table text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama Akun</th>
                                                    <th scope="col">Nama Campaign</th>
                                                    <th scope="col">CPR</th>
                                                    <th scope="col">Result</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-table-body-scaledown">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer border-top-0">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-auto">
                                            <nav aria-label="Page navigation" class="pagination-style-4">
                                                <ul class="pagination mb-0" id="pagination-scaledown">

                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card custom-card mt-3">
                        <div class="card-header">
                            <div class="card-title">
                                WINNING ADS
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="data-table-scaleup" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nama Akun</th>
                                            <th scope="col">Nama Campaign</th>
                                            <th scope="col">CPR</th>
                                            <th scope="col">Result</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-table-body-winingads">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center">
                                <div class="ms-auto">
                                    <nav aria-label="Page navigation" class="pagination-style-4">
                                        <ul class="pagination mb-0" id="pagination-scaleup">

                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        CAMPAIGN ANALYTICS
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example">
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" class="form-control flatpickr-input" id="daterangeCampaign"
                                    placeholder="Date range picker" readonly="readonly">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Nama Akun</th>
                                    <th scope="col">Nama Campaign</th>
                                    <th scope="col">Spending</th>
                                    <th scope="col">Result</th>
                                    <th scope="col">Cost Per Result</th>
                                    <th scope="col">CTA WA</th>
                                    <th scope="col">CTA Shopee</th>
                                </tr>
                            </thead>
                            <tbody id="table-campaign">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        <div id="paginationInfo"> Showing Entries </div>
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-4">
                                <ul class="pagination mb-0" id="paginationTableCampaign">

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.3.0/apexcharts.min.js"
        integrity="sha512-QgLS4OmTNBq9TujITTSh0jrZxZ55CFjs4wjK8NXsBoZb04UYl8wWQJNaS8jRiLq6/c60bEfOj3cPsxadHICNfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @include('advertiser.scripts.scaleup')
    @include('advertiser.scripts.scaledown')

    <script>
        const today = new Date();
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const endOfMonth = today;
        const formattedStartOfMonth = startOfMonth.toISOString().split('T')[0];
        const formattedEndOfMonth = endOfMonth.toISOString().split('T')[0];

        flatpickr("#daterangeCampaign", {
            mode: "range",
            defaultDate: [formattedStartOfMonth, formattedEndOfMonth],
            dateFormat: "Y-m-d"
        });

        function loadDataCampaign(page = 1) {
            const search = $('#searchInput').val();
            const dateRange = $('#daterangeCampaign').val();
            const [startDate, endDate] = dateRange ? dateRange.split(' to ') : [formattedStartOfMonth, formattedEndOfMonth];
            const url =
                `/advertiser/dashboard/data-campaign?page=${page}&search=${search}&startDate=${startDate}&endDate=${endDate}`;

            $.get(url, function(data) {
                let rows = '';
                if (data.data && data.data.length > 0) {
                    data.data.forEach(function(data_raw) {
                        rows += `
                        <tr>
                            <td>${data_raw.account_name || '-'}</td>
                            <td>${data_raw.campaign_name || '-'}</td>
                            <td>${data_raw.amount_spent_idr || '-'}</td>
                            <td>${data_raw.adds_of_payment_info || '-'}</td>
                            <td>${data_raw.cost_per_add_of_payment_info || '-'}</td>
                            <td>${data_raw.leads || '-'}</td>
                            <td>${data_raw.donations || '-'}</td>
                        </tr>`;
                    });
                } else {
                    rows = `<tr><td colspan="7" class="text-center">No data found</td></tr>`;
                }


                $('#table-campaign').html(rows);
                const paginationTableCampaign = (data.links || []).map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadDataCampaign(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationTableCampaign').html(paginationTableCampaign);
                $('#paginationInfo').html(
                    `Showing ${data.from || 0} to ${data.to || 0} of ${data.total || 0} entries`);
            }).fail(function() {
                $('#table-campaign').html('<tr><td colspan="7" class="text-center">Error loading data</td></tr>');
                $('#paginationTableCampaign').html('');
            });
        }

        loadDataCampaign();

        $('#searchInput').on('input', function() {
            loadDataCampaign(1);
        });

        $('#daterangeCampaign').on('change', function() {
            loadDataCampaign(1);
        });
    </script>



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

    @include('advertiser.scripts.chart_one')
@endsection
