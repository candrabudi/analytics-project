@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        RIWAYAT PENCARIAN
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="search-input" type="text"
                                placeholder="Cari data..." aria-label=".form-control-sm example">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap" id="history-table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Keyword</th>
                                    <th scope="col">Total Data</th>
                                    <th scope="col">Mulai</th>
                                    <th scope="col">Berakhir</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        <div> Showing <span id="entry-count">0</span> Entries <i
                                class="bi bi-arrow-right ms-2 fw-semibold"></i> </div>
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-4">
                                <ul class="pagination mb-0" id="pagination">

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            let currentPage = 1;
            let perPage = 10;
            let debounceTimeout;

            function loadHistory(page = 1, search = '') {
                $.ajax({
                    url: '/scrape-username/history/load',
                    method: 'GET',
                    data: {
                        page: page,
                        perPage: perPage,
                        search: search,
                    },
                    success: function(response) {
                        let tbody = $('#history-table tbody');
                        tbody.empty();
                        response.data.forEach((item, index) => {
                            const startedAt = new Date(item.started_at).toLocaleString('en-GB', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: false
                            }).replace(',', '');

                            const updatedAt = new Date(item.updated_at).toLocaleString('en-GB', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: false
                            }).replace(',', '');

                            tbody.append(`
                                <tr>
                                    <td>${(page - 1) * perPage + index + 1}</td>
                                    <td>${item.keyword}</td>
                                    <td>${item.total_search}</td>
                                    <td>${startedAt}</td>
                                    <td>${updatedAt}</td>
                                    <td>${item.status}</td>
                                    <td><button class="btn btn-info btn-sm" onclick="viewDetails(${item.id})">Details</button></td>
                                </tr>
                            `);
                        });


                        let pagination = $('#pagination');
                        pagination.empty();
                        for (let i = 1; i <= response.last_page; i++) {
                            pagination.append(`
                    <li class="page-item ${i === page ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadHistory(${i}, '${search}')">${i}</a>
                    </li>
                `);
                        }

                        $('#entry-count').text(response.total);
                    }
                });
            }

            $('#search-input').on('keyup', function() {
                clearTimeout(debounceTimeout);
                let search = $(this).val();
                debounceTimeout = setTimeout(function() {
                    loadHistory(1, search);
                }, 500);
            });

            function viewDetails(id) {
                window.location.href = '/scrape-username/history/detail/' + id;
            }
            $(document).ready(function() {
                loadHistory();
            });
        </script>
    @endpush
@endsection
