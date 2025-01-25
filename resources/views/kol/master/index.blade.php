@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Data Raw KOL Master</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">KOL MASTER</div>
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
                                    <th scope="col">USER ID</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">NAMA</th>
                                    <th scope="col">FOLLOWER</th>
                                    <th scope="col">FOLLOWING</th>
                                    <th scope="col">LIKE</th>
                                    <th scope="col">TOTAL VIDEO</th>
                                    <th scope="col">TIER</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">

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

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function loadKolMaster(page = 1) {
            const search = $('#searchInput').val();
            const url = `/kol/master/load-list?page=${page}&search=${search}`;
    
            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolMaster) {
                    rows += `
                    <tr>
                        <td><a href="${kolMaster.link_account}" target="_blank">${kolMaster.author_id}</a></td>
                        <td>${kolMaster.unique_id}</td>
                        <td>${kolMaster.nickname}</td>
                        <td>${kolMaster.follower_count}</td>
                        <td>${kolMaster.following_count}</td>
                        <td>${kolMaster.heart_count}</td>
                        <td>${kolMaster.video_count}</td>
                        <td>
                            <span class="badge ${getBadgeClass(kolMaster.tier)}">${kolMaster.tier}</span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="window.location.href='/kol/master/edit/${kolMaster.id}'">Edit</button>
                        </td>
                    </tr>`;
                });
    
                $('#tableBody').html(rows);
    
                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadKolMaster(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }
    
        function getBadgeClass(tier) {
            switch (tier.toLowerCase()) {
                case 'nano':
                    return 'bg-secondary-transparent';
                case 'micro':
                    return 'bg-primary-transparent';
                case 'macro':
                    return 'bg-warning-transparent';
                case 'mega':
                    return 'bg-danger-transparent';
                default:
                    return 'light';
            }
        }
    
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                loadKolMaster();
            });
    
            loadKolMaster();
        });
    </script>
    
@endsection
