@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <ul class="nav nav-tabs nav-tabs-header mb-0" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab" role="tab"
                                        href="javascript:void(0);" aria-selected="true" tabindex="0"
                                        onclick="loadKol('all')">
                                        ALL
                                    </a>
                                </li>
                                @foreach ($categories as $ctg)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="{{ $ctg->id }}-tab" data-bs-toggle="tab" role="tab"
                                            href="javascript:void(0);" aria-selected="false" tabindex="0"
                                            onclick="loadKol('{{ $ctg->id }}')">
                                            {{ strtoupper($ctg->name) }}
                                        </a>
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
                                <div class="d-flex flex-wrap gap-2">
                                    <div>
                                        <input class="form-control form-control-sm" id="searchInput" type="text"
                                            placeholder="Search Here" aria-label=".form-control-sm example">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                            <tr>
                                                <th scope="col">USERNAME</th>
                                                <th scope="col">LINK</th>
                                                <th scope="col">KATEGORI</th>
                                                <th scope="col">LABEL</th>
                                                <th scope="col">NOMOR</th>
                                                <th scope="col">CHAT</th>
                                                <th scope="col">CATATAN</th>
                                                <th scope="col">FILE</th>
                                                <th width="40" class="text-end">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-kol">
                                            <!-- Data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex align-items-center">
                                    <div id="paginationInfo"> Showing Entries </div>
                                    <div class="ms-auto">
                                        <nav aria-label="Page navigation" class="pagination-style-4">
                                            <ul class="pagination mb-0" id="paginationLinks">
                                                <!-- Pagination links will be loaded here -->
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
                    rows += `
                        <tr>
                            <td>${kolMaster.unique_id}</td>
                            <td>${kolMaster.nickname}</td>
                            <td>${kolMaster.follower}</td>
                            <td>${kolMaster.following}</td>
                            <td>${kolMaster.like}</td>
                            <td>${kolMaster.total_video}</td>
                            <td>${kolMaster.notes}</td>
                            <td>
                                ${kolMaster.file_url ? 
                                    `<a href="${kolMaster.file_url}" class="btn btn-secondary btn-sm" target="_blank">Lihat File</a>` : 
                                    'Belum Ada File'}
                            </td>
                            <td>
                                <span class="badge">${kolMaster.tier}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm" onclick="window.location.href='/kol/type-influencer/edit/${kolMaster.id}'">Edit</button>
                            </td>
                        </tr>`;
                });


                $('#table-kol').html(rows);

                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadKol('${tier}', ${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
                $('#kol-title').html(`KOL ${tier === 'all' ? 'ALL' : tier.toUpperCase()}`);
            });
        }

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                loadKol(currentTier);
            });

            loadKol('all');
        });
    </script>
@endsection
