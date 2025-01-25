@extends('layouts.app')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <ul class="nav nav-tabs nav-tabs-header mb-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="javascript:void(0);" onclick="loadKol('all')">ALL</a>
                                </li>
                                @foreach ($categories as $ctg)
                                    <li class="nav-item">
                                        <a class="nav-link" id="{{ $ctg->id }}-tab" data-bs-toggle="tab" href="javascript:void(0);" onclick="loadKol('{{ $ctg->id }}')">{{ strtoupper($ctg->name) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="tab-content">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title" id="kol-title">KOL ALL</div>
                                <div class="d-flex align-items-center w-80">
                                    <input class="form-control form-control-sm me-2 w-200" id="searchInput" type="text" placeholder="Search Here">
                                    <button class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#addDataModal">
                                        Tambah Data
                                    </button>
                                </div>
                            </div>
                                                        
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>USERNAME</th>
                                                <th>KATEGORI</th>
                                                <th>LABEL</th>
                                                <th>NOMOR</th>
                                                <th>CHAT</th>
                                                <th>CATATAN</th>
                                                <th>FILE</th>
                                                <th class="text-center">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-kol"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex align-items-center">
                                    <div id="paginationInfo"> Showing Entries </div>
                                    <div class="ms-auto">
                                        <nav aria-label="Page navigation" class="pagination-style-4">
                                            <ul class="pagination mb-0" id="paginationLinks"></ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalLabel">Tambah Database Raw</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm">
                        <div class="mb-3">
                            <label for="kolName" class="form-label">Userame Akun Tiktok</label>
                            <input type="text" class="form-control" id="kolName" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveDataBtn">Save Data</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let currentTier = 'all';

        function loadKol(tier, page = 1) {
            currentTier = tier;
            const search = $('#searchInput').val();
            const url = `/kol/master/load-list?page=${page}&search=${search}&tier=${tier}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolMaster) {
                    let assignCategories = kolMaster.assign_category?.length ? kolMaster.assign_category.map(category => category.name).join(', ') : 'No Category';
                    let statusClass = '';
                    let statusLabel = kolMaster.status_call;

                    switch (kolMaster.status_call) {
                        case 'pending': statusClass = 'badge bg-warning'; break;
                        case 'response': statusClass = 'badge bg-success'; break;
                        case 'no_response': statusClass = 'badge bg-danger'; break;
                    }

                    rows += `
                        <tr>
                            <td>${kolMaster.unique_id} <a href="https://tiktok.com/@${kolMaster.unique_id}" target="_blank"><i class="fa fa-link"></i></a></td>
                            <td>${assignCategories}</td>
                            <td><span class="${statusClass}">${statusLabel}</span></td>
                            <td>${kolMaster.whatsapp_number}</td>
                            <td><a href="https://wa.me/${kolMaster.whatsapp_number}" target="_blank">Chat Sekarang</a></td>
                            <td>${kolMaster.notes}</td>
                            <td>${kolMaster.file_url ? `<a href="${kolMaster.file_url}" class="btn btn-secondary btn-sm" target="_blank">Lihat File</a>` : 'Belum Ada File'}</td>
                            <td class="text-center"><button class="btn btn-primary btn-sm" onclick="window.location.href='/kol/type-influencer/edit/${kolMaster.id}'">Edit</button></td>
                        </tr>`;
                });

                $('#table-kol').html(rows);
                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}"><a class="page-link" href="javascript:void(0);" onclick="loadKol('${tier}', ${pageNumber})">${link.label}</a></li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(`Showing ${data.from} to ${data.to} of ${data.total} entries`);
                $('#kol-title').html(`KOL ${tier === 'all' ? 'ALL' : tier.toUpperCase()}`);
            });
        }

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() { loadKol(currentTier); });
            loadKol('all');
        });
    </script>
@endsection
