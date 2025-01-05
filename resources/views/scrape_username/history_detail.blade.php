@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        DETAIL RIWAYAT PENCARIAN <b>{{ $tiktokSearch->keyword }}</b>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="search-input" type="text"
                                placeholder="Cari data..." aria-label=".form-control-sm example">
                        </div>
                        <div>
                            <button class="btn btn-success btn-sm" id="export-button">Export All Data</button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap" id="history-table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">USER ID</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">FOLLOWER</th>
                                    <th scope="col">TOTAL VIDEO</th>
                                    <th scope="col">AVERAGE VIEW</th>
                                    <th scope="col">TANGGAL SCRAPE</th>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            let currentPage = 1;
            let perPage = 10;
            let debounceTimeout;
            let a = {{ $a }};
            let allData = [];

            function loadHistory(page = 1, search = '') {
                $.ajax({
                    url: `/scrape-username/history/detail/load/${a}`,
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
                            let accountRow = `
                            <tr>
                                <td>${(page - 1) * perPage + index + 1}</td>
                                <td>${item.author_id}</td>
                                <td>${item.nickname}</td>
                                <td>${item.follower}</td>
                                <td>${item.total_video}</td>
                               <td>${Number(item.average).toLocaleString()}</td>

                                <td>${new Date(item.created_at).toLocaleString()}</td>
                                <td>
                                    <a href="/scrape-username/account/${item.author_id}" class="btn btn-info btn-sm">Details</a>
                                </td>
                            </tr>
                        `;
                            tbody.append(accountRow);
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

            function loadAllHistory(search = '') {
                let allPages = [];

                function fetchPage(page) {
                    return $.ajax({
                        url: `/scrape-username/history/detail/load/${a}`,
                        method: 'GET',
                        data: {
                            page: page,
                            perPage: perPage,
                            search: search,
                        }
                    });
                }

                return fetchPage(1).then((response) => {
                    const totalPages = response.last_page;
                    const promises = [];
                    console.log(response.data);
                    allData = response.data;

                    for (let page = 2; page <= totalPages; page++) {
                        promises.push(fetchPage(page));
                    }

                    return Promise.all(promises).then((results) => {
                        results.forEach(result => {
                            allData = allData.concat(result.data);
                        });
                    });
                });
            }

            function convertToXLSX(data) {
                const worksheetData = data.map((item, index) => ({
                    "KEYWORD": "{{ $tiktokSearch->keyword }}",
                    "USER ID": item.author_id,
                    "USERNAME": item.nickname,
                    "FOLLOWERS": item.follower,
                    "TOTAL VIDEO": item.total_video ? Number(item.total_video).toLocaleString() : '0',
                    "AVERAGE VIEW": item.average ? Number(item.average).toLocaleString() : 'N/A',
                    "TANGGAL SCRAPE": item.created_at ? new Date(item.created_at).toLocaleString() : 'N/A'
                }));

                const worksheet = XLSX.utils.json_to_sheet(worksheetData);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "History Data");
                return workbook;
            }

            function downloadXLSX(workbook, filename) {
                XLSX.writeFile(workbook, filename);
            }

            $('#export-button').on('click', function() {
                loadAllHistory().then(() => {
                    const workbook = convertToXLSX(allData);
                    const fileName = `KOL_{{ $tiktokSearch->keyword }}_{{ $tiktokSearch->results }}.xlsx`;
                    downloadXLSX(workbook, fileName);
                });
            });

            $(document).ready(function() {
                loadHistory(1, '');
            });
        </script>
    @endpush
@endsection
