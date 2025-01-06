@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATABASE RAW LIST</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example">
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" class="form-control flatpickr-input" id="daterangeDataRaw"
                                    placeholder="Date range picker" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px; overflow-x: auto;">
                        <div class="handsontable-container" id="example1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        thead th {
            padding: 20px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/handsontable@11.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@11.1.0/dist/handsontable.full.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
        integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const container = document.getElementById('example1');

        const hotDataRaw = new Handsontable(container, {
            colHeaders: ['Account Name', 'Campaign Name', 'Campaign Budget', 'Amount Spent (IDR)',
                'Adds of Payment Info', 'Cost per Add of Payment Info', 'Leads', 'Cost per Lead', 'Donations',
                'Reach', 'Impressions', 'CPM', 'Link Clicks', 'CPC', 'Purchases', 'Cost per Purchase',
                'Adds to Cart', 'Cost per Add to Cart', 'Reporting Starts', 'Reporting Ends'
            ],
            rowHeaders: true,
            stretchH: 'all',
            height: 700,
            width: '100%',
            manualColumnResize: true,
            licenseKey: 'non-commercial-and-evaluation',
            columnSorting: true, 
            columns: [{
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                },
                {
                    readOnly: true
                }
            ]

        });

        const todayRaw = new Date();
        const startOfMonthRaw = todayRaw.toISOString().split('T')[0];
        const endOfMonthRaw = todayRaw.toISOString().split('T')[0];
        flatpickr("#daterangeDataRaw", {
            mode: "range",
            defaultDate: [startOfMonthRaw, endOfMonthRaw],
            dateFormat: "Y-m-d"
        });

        function loadDataRaw() {
            const search = $('#searchInputDataRaw').val();
            const dateRange = $('#daterangeDataRaw').val();
            const [startdate, enddate] = dateRange ? dateRange.split(' to ') : ['', ''];

            const params = new URLSearchParams({
                search,
                startdate,
                enddate
            }).toString();

            const url = `/database-raw/list/load?${params}`;

            $.get(url, function(data) {
                if (data.length === 0) {
                    const hotData = [
                        []
                    ];
                    hotDataRaw.loadData(null);
                } else {
                    const hotData = data.map((video, index) => [
                        video.account_name || 'No Data',
                        video.campaign_name || 'No Data',
                        video.campaign_budget || 'No Data',
                        video.amount_spent_idr || 'No Data',
                        video.adds_of_payment_info || 'No Data',
                        video.cost_per_add_of_payment_info || 'No Data',
                        video.leads || 'No Data',
                        video.cost_per_lead || 'No Data',
                        video.donations || 'No Data',
                        video.reach || 'No Data',
                        video.impressions || 'No Data',
                        video.cpm || 'No Data',
                        video.link_clicks || 'No Data',
                        video.cpc || 'No Data',
                        video.purchases || 'No Data',
                        video.cost_per_purchase || 'No Data',
                        video.adds_to_cart || 'No Data',
                        video.cost_per_add_to_cart || 'No Data',
                        video.reporting_starts || 'No Data',
                        video.reporting_ends || 'No Data'
                    ]);
                    hotDataRaw.loadData(hotData);
                }
            });
        }

        $('#searchInputDataRaw, #daterangeDataRaw').on('change', function() {
            loadDataRaw();
        });

        $('#fullScreenModalDataRaw').on('shown.bs.modal', function() {
            loadDataRaw();
        });

        loadDataRaw();
    </script>
@endsection
