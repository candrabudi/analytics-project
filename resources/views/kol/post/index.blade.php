@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">TikTok Progress Post</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-header justify-content-between">
                    <div class="card-title">DATA Progress Post</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example">
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                Tambah Progress Post
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">TANGGAL INPUT</th>
                                    <th scope="col">KOL TRX</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">LINK POST</th>
                                    <th scope="col">VIEWS</th>
                                    <th scope="col">LIKES</th>
                                    <th scope="col">COMMENTS</th>
                                    <th scope="col">SHARES</th>
                                    <th scope="col">SAVES</th>
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

    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Progress Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kol_progress_posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="kol_management_id" class="form-label">KOL Management</label>
                                <select class="form-select" id="kol_management_id" name="kol_management_id" required>
                                    <option value="">Pilih KOL Management</option>
                                    @foreach ($kolManagements as $kol)
                                        <option value="{{ $kol->id }}" data-ratecard-deal="{{ $kol->ratecard_deal }}">
                                            {{ $kol->kol_trx_no }} -
                                            {{ $kol->rawTiktokAccount->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-6">
                                <label for="link_post" class="form-label">Link Post</label>
                                <input type="text" class="form-control" id="link_post" name="link_post">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="deadline" class="form-label">Deadline</label>
                                <input type="date" class="form-control" id="deadline" name="deadline" required>
                            </div>
                            <div class="col-md-6">
                                <label for="targer_views" class="form-label">Target Views</label>
                                <input type="number" class="form-control" id="targer_views" name="targer_views" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="brief" class="form-label">Brief</label>
                                <textarea name="brief" class="form-control" id="brief"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Progress Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProgressPostForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Field KOL Management -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="editKolManagementId" class="form-label">KOL Management</label>
                                <select class="form-select" id="editKolManagementId" name="kol_management_id" required>
                                    <option value="">Pilih KOL Management</option>
                                    @foreach ($kolManagements as $kol)
                                        <option value="{{ $kol->id }}"
                                            data-ratecard-deal="{{ $kol->ratecard_deal }}">
                                            {{ $kol->kol_trx_no }} -
                                            {{ $kol->rawTiktokAccount->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Field Judul dan Link Post -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editTitle" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="editTitle" name="title" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editLinkPost" class="form-label">Link Post</label>
                                <input type="text" class="form-control" id="editLinkPost" name="link_post">
                            </div>
                        </div>

                        <!-- Field Deadline dan Target Views -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editDeadline" class="form-label">Deadline</label>
                                <input type="date" class="form-control" id="editDeadline" name="deadline" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editTargetViews" class="form-label">Target Views</label>
                                <input type="number" class="form-control" id="editTargetViews" name="target_views"
                                    required>
                            </div>
                        </div>

                        <!-- Field Brief -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="editBrief" class="form-label">Brief</label>
                                <textarea name="brief" class="form-control" id="editBrief"></textarea>
                            </div>
                        </div>

                        <!-- Tombol Update -->
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#kol_management_id').select2({
                placeholder: 'Pilih KOL Management',
                allowClear: true,
                dropdownParent: $('#createModal')
            });

            $('#kol_management_id').on('change', function() {
                var ratecardDeal = $(this).find(':selected').data('ratecard-deal');
                $('#ratecard_deal').val(ratecardDeal || '');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#kol_management_id').select2({
                placeholder: 'Pilih KOL Management',
                allowClear: true,
                dropdownParent: $('#createModal')
            });

            $('#bank_id').select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownParent: $('#createModal')
            });

            $('#editKolManagementId').select2({
                placeholder: 'Pilih KOL Management',
                allowClear: true,
                dropdownParent: $('#editModal')
            });

            $('#editBankId').select2({
                placeholder: 'Pilih Bank',
                allowClear: true,
                dropdownParent: $('#editModal')
            });
        });
    </script>

    <style>
        .select2-container {
            position: relative;
        }

        .select2-dropdown {
            z-index: 999999 !important;
        }
    </style>
    <script>
        function loadLandingpages(page = 1) {
            const search = $('#searchInput').val();
            const url = `/kol/progress/posts/load?page=${page}&search=${search}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(tiktokInvoice) {
                    const formattedDate = formatDate(tiktokInvoice.created_at);

                    const fileUrl =
                        `/storage/${tiktokInvoice.file_upload}`;

                    rows += `
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${tiktokInvoice.kol_management.kol_trx_no}</td>
                        <td>${tiktokInvoice.raw_tiktok_account.unique_id}</td>
                        <td>${tiktokInvoice.link_post}</td>
                        <td>${tiktokInvoice.views}</td>
                        <td>${tiktokInvoice.likes}</td>
                        <td>${tiktokInvoice.comments}</td>
                        <td>${tiktokInvoice.shares}</td>
                        <td>${tiktokInvoice.saves}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editKolInvoice(${tiktokInvoice.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteKolInvoice(${tiktokInvoice.id})">Delete</button>
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
                $('#paginationInfo').html(`Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        function formatDate(datetime) {
            const date = new Date(datetime);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;
        }

        function editKolInvoice(id) {
            $.get(`/kol/progress/posts/edit/${id}`, function(invoice) {
                $('#editModalLabel').text(`Edit Progress Post: ${invoice.raw_tiktok_account.unique_id}`);
                $('#editProgressPostForm').attr('action', `/kol/progress/posts/update/${id}`);
                $('#editKolManagementId').val(invoice.kol_management_id).trigger('change');
                $('#editTitle').val(invoice.title);
                $('#editLinkPost').val(invoice.link_post);
                $('#editDeadline').val(invoice.deadline);
                $('#editTargetViews').val(invoice.target_views);
                $('#editBrief').val(invoice.brief);
                $('#editModal').modal('show');
            }).fail(function(error) {
                console.error('Error fetching progress post data:', error);
                alert('Gagal mengambil data progress post. Silakan coba lagi.');
            });
        }


        function deleteKolInvoice(id) {
            if (confirm('Are you sure you want to delete this warehouse?')) {
                $.ajax({
                    url: `/kol/invoices/destroy/${id}`,
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
