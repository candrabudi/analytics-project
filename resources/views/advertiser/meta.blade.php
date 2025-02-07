@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Facebook Data View</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
            <style>
                #selectAdAccounts {
                    width: 400px !important;
                }

                .select2-container {
                    width: 400px !important;
                }
                .select2-selection {
                    width: 400px !important;
                }
            </style>

            <div class="card-header justify-content-between">
                <div class="card-title">Facebook Insights Data</div>
                <div class="d-flex flex-wrap gap-2">
                    <form method="GET" action="{{ url('/meta-api/get-facebook-data/view') }}">
                        <div class="d-flex gap-2">
                            <input class="form-control form-control-sm" type="date" name="start_date" 
                                value="{{ $startDate }}" aria-label="Start Date">
                            <input class="form-control form-control-sm" type="date" name="end_date" 
                                value="{{ $endDate }}" aria-label="End Date">

                            <select class="form-select form-select-sm select2" name="act_[]" multiple="multiple" id="selectAdAccounts">
                                @foreach($actIds as $actId)
                                    <option value="{{ $actId['account_id'] }}">{{ $actId['name'] }} ({{ $actId['account_id'] }})</option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </form>
                </div>
            </div>


                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">TANGGAL</th>
                                    <th scope="col">MEDIA</th>
                                    <th scope="col">AKUN IKLAN</th>
                                    <th scope="col">SPENT</th>
                                    <th scope="col">RESULT</th>
                                    <th scope="col">COST PER result</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse ($allAccountData as $data)
                                    <tr>
                                        <td>{{ $data['date'] }}</td>
                                        <td>{{ $data['platform'] }}</td>
                                        <td>{{ $data['account_name'] }}</td>
                                        <td>{{ $data['spend'] }}</td>
                                        <td>{{ $data['add_to_cart'] }}</td>
                                        <td>{{ $data['cost_add_to_cart'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data yang tersedia untuk rentang yang dipilih</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        <div id="paginationInfo"> Showing {{ count($allAccountData) }} Entries </div>
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-4">
                                <ul class="pagination mb-0" id="paginationLinks">
                                    {{-- Pagination links would go here if implemented --}}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        $('#selectAdAccounts').select2({
            placeholder: "Select Ad Accounts",
            allowClear: true
        });
    });
    </script>
@endsection
