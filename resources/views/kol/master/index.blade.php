@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Manajemen Landingpage</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATA landingpage AKUN</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example">
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                Tambah Landingpage
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">KODE</th>
                                    <th scope="col">LANDINGPAGE URL</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">CTA STATUS</th>
                                    <th scope="col">Aksi</th>
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
        
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">RANK LANDINGPAGE</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">KODE</th>
                                    <th scope="col">CPC</th>
                                    <th scope="col">SPEND</th>
                                    <th scope="col">USED SPEND</th>
                                </tr>
                            </thead>
                            <tbody id="table-rank-landingpage">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Landing Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('landingpages.list.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="url" class="form-control" id="link" name="link" required>
                        </div>
                        <div class="mb-3">
                            <label for="landingpage_status" class="form-label">Landing Page Status</label>
                            <select class="form-select" id="landingpage_status" name="landingpage_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="cta_status" class="form-label">CTA Status</label>
                            <select class="form-select" id="cta_status" name="cta_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Landing Page</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Landing Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLandingPageForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editCode" class="form-label">Code</label>
                            <input type="text" class="form-control" id="editCode" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLink" class="form-label">Link</label>
                            <input type="url" class="form-control" id="editLink" name="link" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLandingpageStatus" class="form-label">Landing Page Status</label>
                            <select class="form-select" id="editLandingpageStatus" name="landingpage_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editCtaStatus" class="form-label">CTA Status</label>
                            <select class="form-select" id="editCtaStatus" name="cta_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Landing Page</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        fetchRankLandingpage();

        function fetchRankLandingpage() {
            $.ajax({
                url: "{{ route('landingpages.landingpageRank') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var tbody = $('#table-rank-landingpage');
                    tbody.empty(); 
                    $.each(response, function(index, lp) {
                        var row = `
                            <tr>
                                <td>${lp.code}</td>
                                <td>${lp.total_performance}</td>
                                <td>${lp.amount_spent}</td>
                                <td>${lp.used_spend}%</td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });
        }
    });
</script>

    <script>
        function loadLandingpages(page = 1) {
            const search = $('#searchInput').val();
            const url =
                `/landingpage/list/load?page=${page}&search=${search}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(landingpage) {
                    rows += `
                <tr>
                    <td>${landingpage.code}</td>
                    <td>${landingpage.link}</td>
                    <td>${landingpage.landingpage_status}</td>
                    <td>${landingpage.cta_status}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editLandingpage(${landingpage.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteLandingpage(${landingpage.id})">Delete</button>
                    </td>
                </tr>`;
                });

                $('#tableBody').html(rows);

                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadLandingpages(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        function editLandingpage(id) {
            $.get(`/landingpage/list/edit/${id}`, function(landingpage) {
                $('#editCode').val(landingpage.code);
                $('#editLink').val(landingpage.link);
                $('#editLandingpageStatus').val(landingpage.landingpage_status);
                $('#editCtaStatus').val(landingpage.cta_status);
                $('#editLandingPageForm').attr('action', `/landingpage/list/update/${id}`);
                $('#editModal').modal('show');
            });
        }

        function deleteLandingpage(id) {
            if (confirm('Are you sure you want to delete this landing page?')) {
                $.ajax({
                    url: `/landingpage/list/destroy/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.success);
                        loadLandingpages();
                    }
                });
            }
        }

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                loadLandingpages();
            });

            loadLandingpages();
        });
    </script>
@endsection
