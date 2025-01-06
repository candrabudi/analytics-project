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
                                <input type="text" class="form-control flatpickr-input" id="daterange"
                                    placeholder="Date range picker" readonly="readonly">
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#fullScreenModal">
                                View Fullscreen
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px; overflow-x: auto;">
                        <table class="table table-bordered table-hover table-striped" id="excelTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Akun Iklan</th>
                                    <th scope="col">Nama Campaign</th>
                                    <th scope="col">Campaign Budget</th>
                                    <th scope="col">Amount Spent Idr</th>
                                    <th scope="col">Adds of Payment Info</th>
                                    <th scope="col">Cost Per Add of Payment Info</th>
                                    <th scope="col">Leads</th>
                                    <th scope="col">Cost Per Lead</th>
                                    <th scope="col">Donations</th>
                                    <th scope="col">Reach</th>
                                    <th scope="col">Impressions</th>
                                    <th scope="col">CPM</th>
                                    <th scope="col">Link Clicks</th>
                                    <th scope="col">CPC</th>
                                    <th scope="col">Purchases</th>
                                    <th scope="col">Cost Per Purchase</th>
                                    <th scope="col">Adds to Cart</th>
                                    <th scope="col">Cost Per Add to Cart</th>
                                    <th scope="col">Reporting Starts</th>
                                    <th scope="col">Reporting Ends</th>
                                </tr>

                            </thead>
                            <tbody id="tableBody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Modal -->
    <div class="modal fade" id="fullScreenModal" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fullScreenModalLabel">DATABASE RAW LIST</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Table Container with Horizontal Scroll -->
                    <div class="table-responsive" style="max-height: 100%; overflow-x: auto;">
                        <table class="table table-bordered table-hover table-striped" id="excelTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Akun Iklan</th>
                                    <th scope="col">Nama Campaign</th>
                                    <th scope="col">Campaign Budget</th>
                                    <th scope="col">Amount Spent Idr</th>
                                    <th scope="col">Adds of Payment Info</th>
                                    <th scope="col">Cost Per Add of Payment Info</th>
                                    <th scope="col">Leads</th>
                                    <th scope="col">Cost Per Lead</th>
                                    <th scope="col">Donations</th>
                                    <th scope="col">Reach</th>
                                    <th scope="col">Impressions</th>
                                    <th scope="col">CPM</th>
                                    <th scope="col">Link Clicks</th>
                                    <th scope="col">CPC</th>
                                    <th scope="col">Purchases</th>
                                    <th scope="col">Cost Per Purchase</th>
                                    <th scope="col">Adds to Cart</th>
                                    <th scope="col">Cost Per Add to Cart</th>
                                    <th scope="col">Reporting Starts</th>
                                    <th scope="col">Reporting Ends</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody2">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const today = new Date();
            const startOfMonth = today.toISOString().split('T')[0];
            const endOfMonth = today.toISOString().split('T')[0];
            flatpickr("#daterange", {
                mode: "range",
                defaultDate: [startOfMonth, endOfMonth],
                dateFormat: "Y-m-d"
            });

            function loadData() {
                const search = $('#searchInput').val();
                const dateRange = $('#daterange').val();
                const [startdate, enddate] = dateRange ? dateRange.split(' to ') : ['', ''];

                const params = new URLSearchParams({
                    search,
                    startdate,
                    enddate
                }).toString();

                const url = `/database-raw/list/load?${params}`;

                $.get(url, function(data) {
                    let rows = '';
                    let rowNum = 1;
                    data.forEach(function(video) {
                        rows += `
                <tr>
                    <td>${rowNum++}</td>
                    <td>${video.account_name || 'No Data'}</td>
                    <td>${video.campaign_name || 'No Data'}</td>
                    <td>${video.campaign_budget || 'No Data'}</td>
                    <td>${video.amount_spent_idr || 'No Data'}</td>
                    <td>${video.adds_of_payment_info || 'No Data'}</td>
                    <td>${video.cost_per_add_of_payment_info || 'No Data'}</td>
                    <td>${video.leads || 'No Data'}</td>
                    <td>${video.cost_per_lead || 'No Data'}</td>
                    <td>${video.donations || 'No Data'}</td>
                    <td>${video.reach || 'No Data'}</td>
                    <td>${video.impressions || 'No Data'}</td>
                    <td>${video.cpm || 'No Data'}</td>
                    <td>${video.link_clicks || 'No Data'}</td>
                    <td>${video.cpc || 'No Data'}</td>
                    <td>${video.purchases || 'No Data'}</td>
                    <td>${video.cost_per_purchase || 'No Data'}</td>
                    <td>${video.adds_to_cart || 'No Data'}</td>
                    <td>${video.cost_per_add_to_cart || 'No Data'}</td>
                    <td>${video.reporting_starts || 'No Data'}</td>
                    <td>${video.reporting_ends || 'No Data'}</td>
                </tr>
                `;
                    });

                    $('#tableBody').html(rows);
                    $('#tableBody2').html(rows);
                });
            }

            $('#searchInput, #daterange').on('change', function() {
                loadData();
            });

            $('#fullScreenModal').on('shown.bs.modal', function() {
                loadData();
            });

            loadData();
        });
    </script>

    <style>
        .modal-fullscreen {
            max-width: 100%;
            height: 100%;
            margin: 0;
        }

        .modal-body {
            height: 100%;
            overflow-y: auto;
        }

        #excelTable {
            white-space: nowrap;
        }

        #excelTable th,
        #excelTable td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endsection
