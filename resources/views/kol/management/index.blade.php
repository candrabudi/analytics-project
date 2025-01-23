@extends('layouts.app')
@section('title', 'Kol Management')
@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">KOL Management</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">List KOL Management</div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example" style="height: 30px;">
                        </div>
                        <div>
                            <input type="text" id="dateRange" class="form-control form-control-sm"
                                placeholder="Select Date Range" style="height: 30px;">
                        </div>
                        <div>
                            <a type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">Tambah KOL</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">TANGGAL INPUT</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">FOLLOWERS</th>
                                    <th scope="col">WHATSAPP</th>
                                    <th scope="col">KATEGORI</th>
                                    <th scope="col">RATECARD KOL</th>
                                    <th scope="col">RATECARD DEAL</th>
                                    <th scope="col">TARGET VIEWS</th>
                                    <th scope="col">AVERAGE VIEWS AKUN</th>
                                    <th scope="col">CPV</th>
                                    <th scope="col">ENGAGEMENT RATE AKUN</th>
                                    <th scope="col">VIEWS ACHIEVED</th>
                                    <th scope="col">DEAL POST</th>
                                    <th scope="col">SETUJU ?</th>
                                    <th scope="col">STATUS BAYAR</th>
                                    <th scope="col">STATUS PENGIRIMAN</th>
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

    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <form id="kolForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Tambah Data KOL Management</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="closeModalBtn"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="raw_tiktok_account_id" class="mb-3">Pilih User TikTok</label>
                                <select class="form-control" name="raw_tiktok_account_id" id="raw_tiktok_account_id">
                                    <option></option>
                                    @foreach ($rawTikTokAccounts as $rawTiktok)
                                        <option value="{{ $rawTiktok->id }}">{{ $rawTiktok->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pic_id" class="mb-3">PIC</label>
                                <input type="text" class="form-control" name="pic_id" id="pic_id"
                                    placeholder="Masukkan ID PIC">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="platform" class="mb-3">Platform</label>
                                <select class="form-control" name="platform" id="platform">
                                    <option value="Instagram">Instagram</option>
                                    <option value="TikTok" selected>TikTok</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="SnackVideo">SnackVideo</option>
                                    <option value="Youtube">Youtube</option>
                                    <option value="Google">Google</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ratecard_kol" class="mb-3">Ratecard KOL</label>
                                <input type="number" class="form-control" name="ratecard_kol" id="ratecard_kol"
                                    placeholder="Masukkan ratecard KOL">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ratecard_deal" class="mb-3">Ratecard Deal</label>
                                <input type="number" class="form-control" name="ratecard_deal" id="ratecard_deal"
                                    placeholder="Masukkan ratecard deal">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="target_views" class="mb-3">Target Views</label>
                                <input type="number" class="form-control" name="target_views" id="target_views"
                                    placeholder="Masukkan target views">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="views_achieved" class="mb-3">Views Achieved</label>
                                <input type="number" class="form-control" name="views_achieved" id="views_achieved"
                                    placeholder="Masukkan views yang dicapai">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="mb-3">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="pending" selected>Pending</option>
                                    <option value="approved">Approved</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="deal_post" class="mb-3">Deal Post</label>
                                <input type="number" step="0.01" class="form-control" name="deal_post"
                                    id="deal_post" placeholder="Masukkan nilai deal post">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="deal_date" class="mb-3">Deal Date</label>
                                <input type="date" class="form-control" name="deal_date" id="deal_date">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes" class="mb-3">Notes</label>
                            <textarea class="form-control" name="notes" id="notes" rows="3"
                                placeholder="Tambahkan catatan jika ada"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="submitBtnText">Simpan</span>
                            <span id="submitBtnLoader" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true" style="display: none;"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#dateRange').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                opens: 'left'
            });

            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                console.log("Search value: ", value);
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#kolForm').on('submit', function(e) {
                e.preventDefault(); // Prevent form from submitting traditionally

                // Disable the close button and the submit button
                $('#closeModalBtn').prop('disabled', true);
                $('#submitBtn').prop('disabled', true);
                $('#submitBtnLoader').show(); // Show the loading spinner
                $('#submitBtnText').text('Saving...'); // Change button text to indicate processing

                let formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('kol.management.store') }}", // Adjust this URL to your route
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        // Success handling
                        alert('Data saved successfully!');
                        $('#staticBackdrop').modal('hide'); // Hide modal upon success
                        $('#kolForm')[0].reset(); // Reset the form
                    },
                    error: function(xhr, status, error) {
                        // Error handling
                        alert('There was an error saving the data.');
                    },
                    complete: function() {
                        // Re-enable the close button and submit button
                        $('#closeModalBtn').prop('disabled', false);
                        $('#submitBtn').prop('disabled', false);
                        $('#submitBtnLoader').hide(); // Hide the loading spinner
                        $('#submitBtnText').text('Simpan'); // Restore original button text
                    }
                });
            });
        });
    </script>

    <script>
        function loadData(page = 1) {
            const search = $('#searchInput').val();
            const url = `/kol/management/list?page=${page}&search=${search}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolData) {
                    let actionButtons = '';
                    if (kolData.status === 'pending') {
                        actionButtons += `
                            <button class="btn btn-success btn-sm btn-approve" data-id="${kolData.id}">Approve</button>
                            <button class="btn btn-danger btn-sm btn-reject" data-id="${kolData.id}">Reject</button>
                        `;
                    }
                    actionButtons += `
                        <button class="btn btn-warning btn-sm btn-edit" data-id="${kolData.id}">Edit</button>
                    `;
                    let roundedCpv = (kolData.ratecard_deal / kolData.raw_tiktok_account.avg_views).toFixed(2);
                    rows += `
                        <tr>
                            <td>${kolData.created_at}</td>
                            <td>${kolData.raw_tiktok_account.unique_id}</td>
                            <td>${kolData.raw_tiktok_account.follower}</td>
                            <td>${kolData.raw_tiktok_account.whatsapp}</td>
                            <td>${kolData.raw_tiktok_account.tier}</td>
                            <td>${kolData.ratecard_kol}</td>
                            <td>${kolData.ratecard_deal}</td>
                            <td>${kolData.target_views}</td>
                            <td>${kolData.raw_tiktok_account.avg_views}</td>
                            <td>${roundedCpv}</td>
                            <td>${kolData.deal_post}</td>
                            <td>${kolData.deal_post}</td>
                            <td>${kolData.deal_post}</td>
                            <td>
                                <span class="badge ${getBadgeClass(kolData.status)}">${kolData.status}</span>
                            </td>
                            <td>Belum Bayar</td>
                            <td>Belum Dikirim</td>
                            <td>
                                ${actionButtons}
                            </td>
                        </tr>`;
                });


                $('#tableBody').html(rows);

                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadData(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        function getBadgeClass(status) {
            switch (status.toLowerCase()) {
                case 'pending':
                    return 'bg-warning-transparent';
                case 'approved':
                    return 'bg-success-transparent';
                case 'rejected':
                    return 'bg-danger-transparent';
                case 'mega':
                    return 'bg-danger-transparent';
                default:
                    return 'light';
            }
        }

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                loadData();
            });

            loadData();
        });
    </script>

@endsection
