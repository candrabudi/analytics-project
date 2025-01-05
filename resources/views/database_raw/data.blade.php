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
                    <!-- Table Container with Horizontal Scroll -->
                    <div class="table-responsive" style="max-height: 800px; overflow-x: auto;">
                        <table class="table table-bordered table-hover table-striped" id="excelTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Akun Iklan</th> <!-- account_name -->
                                    <th scope="col">Nama Campaign</th>
                                    <th scope="col">Campaign Budget</th>
                                    <th scope="col">Amount Spent Idr</th> <!-- amount_spent_idr -->
                                    <th scope="col">Adds of Payment Info</th> <!-- adds_of_payment_info -->
                                    <th scope="col">Cost Per Add of Payment Info</th>
                                    <!-- cost_per_add_of_payment_info -->
                                    <th scope="col">Leads</th> <!-- leads -->
                                    <th scope="col">Cost Per Lead</th> <!-- cost_per_lead -->
                                    <th scope="col">Donations</th> <!-- donations -->
                                    <th scope="col">Reach</th> <!-- reach -->
                                    <th scope="col">Impressions</th> <!-- impressions -->
                                    <th scope="col">CPM</th> <!-- cpm -->
                                    <th scope="col">Link Clicks</th> <!-- link_clicks -->
                                    <th scope="col">CPC</th> <!-- cpc -->
                                    <th scope="col">Purchases</th> <!-- purchases -->
                                    <th scope="col">Cost Per Purchase</th> <!-- cost_per_purchase -->
                                    <th scope="col">Adds to Cart</th> <!-- adds_to_cart -->
                                    <th scope="col">Cost Per Add to Cart</th> <!-- cost_per_add_to_cart -->
                                    <th scope="col">Reporting Starts</th> <!-- reporting_starts -->
                                    <th scope="col">Reporting Ends</th> <!-- reporting_ends -->
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
                                    <th scope="col">Akun Iklan</th> <!-- account_name -->
                                    <th scope="col">Nama Campaign</th>
                                    <th scope="col">Campaign Budget</th>
                                    <th scope="col">Amount Spent Idr</th> <!-- amount_spent_idr -->
                                    <th scope="col">Adds of Payment Info</th> <!-- adds_of_payment_info -->
                                    <th scope="col">Cost Per Add of Payment Info</th>
                                    <!-- cost_per_add_of_payment_info -->
                                    <th scope="col">Leads</th> <!-- leads -->
                                    <th scope="col">Cost Per Lead</th> <!-- cost_per_lead -->
                                    <th scope="col">Donations</th> <!-- donations -->
                                    <th scope="col">Reach</th> <!-- reach -->
                                    <th scope="col">Impressions</th> <!-- impressions -->
                                    <th scope="col">CPM</th> <!-- cpm -->
                                    <th scope="col">Link Clicks</th> <!-- link_clicks -->
                                    <th scope="col">CPC</th> <!-- cpc -->
                                    <th scope="col">Purchases</th> <!-- purchases -->
                                    <th scope="col">Cost Per Purchase</th> <!-- cost_per_purchase -->
                                    <th scope="col">Adds to Cart</th> <!-- adds_to_cart -->
                                    <th scope="col">Cost Per Add to Cart</th> <!-- cost_per_add_to_cart -->
                                    <th scope="col">Reporting Starts</th> <!-- reporting_starts -->
                                    <th scope="col">Reporting Ends</th> <!-- reporting_ends -->
                                </tr>
                            </thead>
                            <tbody id="tableBody2">
                                <!-- Dynamic data rows will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery & AJAX for dynamic data loading -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const today = new Date();
            const startOfMonth = today.toISOString().split('T')[0];
            const endOfMonth = today.toISOString().split('T')[0];

            // Initialize date range picker
            flatpickr("#daterange", {
                mode: "range",
                defaultDate: [startOfMonth, endOfMonth],
                dateFormat: "Y-m-d"
            });

            // Load all data without pagination (spreadsheet-like)
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

            // Trigger load data on search or date range change
            $('#searchInput, #daterange').on('change', function() {
                loadData();
            });

            // Load data when the modal is shown
            $('#fullScreenModal').on('shown.bs.modal', function() {
                loadData(); // Load the data when the modal is shown
            });

            // Initial load
            loadData();
        });
    </script>

    <!-- Custom CSS to style like Excel -->
    <style>
        /* Custom CSS for fullscreen modal */
        .modal-fullscreen {
            max-width: 100%;
            height: 100%;
            margin: 0;
        }

        .modal-body {
            height: 100%;
            overflow-y: auto;
        }

        /* Table like spreadsheet */
        #excelTable {
            white-space: nowrap;
        }

        #excelTable th,
        #excelTable td {
            border: 1px solid #dee2e6;
            /* Border similar to spreadsheet cells */
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        /* Responsive table with horizontal scroll */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endsection
