@extends('layouts.app')
@section('title', 'Kol Management')
@section('content')
    <style>
        .freeze-col {
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: white;
        }

        .freeze-col-1 {
            left: 0;
        }

        .freeze-col-2 {
            left: 100px;
        }

        .freeze-col-3 {
            left: 200px;
        }

        th,
        td {
            white-space: nowrap;
        }

        table {
            table-layout: auto;
            overflow-x: scroll;
            display: block;
        }
    </style>
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
                                    <th scope="col" class="freeze-col freeze-col-1">TANGGAL INPUT</th>
                                    <th scope="col" class="freeze-col freeze-col-2">USERNAME</th>
                                    <th scope="col" class="freeze-col freeze-col-3">FOLLOWERS</th>
                                    <th scope="col">WHATSAPP</th>
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
                                <!-- Data akan dimuat di sini -->
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
                                <select name="pic_id" id="pic_id" class="form-control">
                                    <option value="">Pilih PIC</option>
                                    @foreach ($picUsers as $pu)
                                        <option value="{{ $pu->id }}">{{ $pu->username }}</option>
                                    @endforeach
                                </select>
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
                        {{-- <div class="row">
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
                        </div> --}}
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


    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <form id="editKolForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="editModalLabel">Edit Data KOL Management</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="closeModalBtn"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_raw_tiktok_account_id" class="mb-3">Pilih User TikTok</label>
                                <select class="form-control" name="raw_tiktok_account_id"
                                    id="edit_raw_tiktok_account_id">
                                    <option></option>
                                    @foreach ($rawTikTokAccounts as $rawTiktok)
                                        <option value="{{ $rawTiktok->id }}">{{ $rawTiktok->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_pic_id" class="mb-3">PIC</label>
                                <select name="pic_id" id="edit_pic_id" class="form-control">
                                    <option value="">Pilih PIC</option>
                                    @foreach ($picUsers as $pu)
                                        <option value="{{ $pu->id }}">{{ $pu->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_platform" class="mb-3">Platform</label>
                                <select class="form-control" name="platform" id="edit_platform">
                                    <option value="Instagram">Instagram</option>
                                    <option value="TikTok" selected>TikTok</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="SnackVideo">SnackVideo</option>
                                    <option value="Youtube">Youtube</option>
                                    <option value="Google">Google</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_ratecard_kol" class="mb-3">Ratecard KOL</label>
                                <input type="number" class="form-control" name="ratecard_kol" id="edit_ratecard_kol"
                                    placeholder="Masukkan ratecard KOL">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_ratecard_deal" class="mb-3">Ratecard Deal</label>
                                <input type="number" class="form-control" name="ratecard_deal" id="edit_ratecard_deal"
                                    placeholder="Masukkan ratecard deal">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_target_views" class="mb-3">Target Views</label>
                                <input type="number" class="form-control" name="target_views" id="edit_target_views"
                                    placeholder="Masukkan target views">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_deal_post" class="mb-3">Deal Post</label>
                                <input type="number" step="0.01" class="form-control" name="deal_post"
                                    id="edit_deal_post" placeholder="Masukkan nilai deal post">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_deal_date" class="mb-3">Deal Date</label>
                                <input type="date" class="form-control" name="deal_date" id="edit_deal_date">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_notes" class="mb-3">Notes</label>
                            <textarea class="form-control" name="notes" id="edit_notes" rows="3"
                                placeholder="Tambahkan catatan jika ada"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                            <span id="editSubmitBtnText">Simpan</span>
                            <span id="editSubmitBtnLoader" class="spinner-border spinner-border-sm" role="status"
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#raw_tiktok_account_id').select2({
                    placeholder: "Pilih salah satu...",
                    allowClear: true,
                    dropdownParent: $('#staticBackdrop')
                });
                $('#raw_tiktok_account_id').on('select2:open', function() {
                    $('.select2-container--open').css('z-index', 999999999);
                });
            });
        </script>
    @endpush
    <script>
        $(document).ready(function() {


            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                console.log("Search value: ", value);
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#kolForm').on('submit', function(e) {
                e.preventDefault();
                $('#closeModalBtn').prop('disabled', true);
                $('#submitBtn').prop('disabled', true);
                $('#submitBtnLoader').show();
                $('#submitBtnText').text('Saving...');

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('kol.management.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        alert('Data saved successfully!');
                        $('#staticBackdrop').modal('hide');
                        $('#kolForm')[0].reset();
                        loadData();
                    },
                    error: function(xhr, status, error) {
                        alert('There was an error saving the data.');
                    },
                    complete: function() {
                        $('#closeModalBtn').prop('disabled', false);
                        $('#submitBtn').prop('disabled', false);
                        $('#submitBtnLoader').hide();
                        $('#submitBtnText').text('Simpan');
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#dateRange').daterangepicker({
                locale: {
                    format: 'DD MMMM YYYY',
                    firstDay: 1
                },
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
                minDate: '2020-01-01',
                maxDate: moment(),
                opens: 'left',
                autoUpdateInput: false
            });

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                const startDate = picker.startDate.format('YYYY-MM-DD');
                const endDate = picker.endDate.format('YYYY-MM-DD');
                loadData(1, startDate, endDate);
            });

            function loadData(page = 1, startDate, endDate) {
                const search = $('#searchInput').val();
                const dateRange = $('#dateRange').val();
                const url =
                    `/kol/management/list?page=${page}&search=${search}&start_date=${startDate}&end_date=${endDate}`;

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
                        let roundedCpv = (kolData.ratecard_deal / kolData.raw_tiktok_account
                            .avg_views).toFixed(
                            2);

                        let assignCategories = kolData.assign_category?.length ?
                            kolData.assign_category.map(category =>
                                `<span class="badge bg-secondary">${category.name}</span>`
                            ).join(' ') : 'No Category';

                        rows += `
                        <tr>
                            <td class="freeze-col freeze-col-1">
                                ${new Date(kolData.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
                            </td>
                            <td class="freeze-col freeze-col-2">${kolData.raw_tiktok_account.unique_id}</td>
                            <td class="freeze-col freeze-col-3">
                                ${kolData.raw_tiktok_account.follower}<br>
                                ${assignCategories}
                            </td>
                            <td>${kolData.raw_tiktok_account.whatsapp_number}</td>
                            <td>${kolData.ratecard_kol}</td>
                            <td>${kolData.ratecard_deal}</td>
                            <td>${kolData.target_views}</td>
                            <td>${kolData.raw_tiktok_account.avg_views}</td>
                            <td>${roundedCpv}</td>
                            <td>${((kolData.raw_tiktok_account.total_interactions / kolData.raw_tiktok_account.avg_views) * 100).toFixed(2)}%</td>
                            <td>0</td>
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

                    // Event listener untuk tombol Edit
                    $('.btn-edit').on('click', function() {
                        const kolId = $(this).data('id');

                        // Ambil data KOL berdasarkan ID yang dipilih
                        $.ajax({
                            url: '/kol/management/edit/' + kolId,
                            method: 'GET',
                            success: function(kolData) {
                                // Isi form dengan data yang ada
                                $('#edit_raw_tiktok_account_id').val(kolData
                                    .raw_tiktok_account_id);
                                $('#edit_pic_id').val(kolData.pic_id);
                                $('#edit_platform').val(kolData.platform);
                                $('#edit_ratecard_kol').val(kolData.ratecard_kol);
                                $('#edit_ratecard_deal').val(kolData.ratecard_deal);
                                $('#edit_target_views').val(kolData.target_views);
                                $('#edit_deal_post').val(kolData.deal_post);
                                $('#edit_deal_date').val(kolData.deal_date);
                                $('#edit_notes').val(kolData.notes);

                                // Tampilkan modal untuk edit
                                $('#editModal').modal('show');
                            },
                            error: function() {
                                alert('Gagal mengambil data');
                            }
                        });
                    });

                    // Kirim data saat form edit disubmit
                    $('#editKolForm').on('submit', function(e) {
                        e.preventDefault();

                        const kolId = $('.btn-edit').data('id');
                        const formData = $(this).serialize();

                        // Tampilkan loader saat submit
                        $('#editSubmitBtnLoader').show();
                        $('#editSubmitBtnText').hide();

                        // Kirim data ke server untuk disimpan
                        $.ajax({
                            url: '/kol/' +
                            kolId, // URL API untuk update data KOL berdasarkan ID
                            method: 'PUT',
                            data: formData,
                            success: function(response) {
                                $('#editSubmitBtnLoader').hide();
                                $('#editSubmitBtnText').show();
                                $('#editModal').modal('hide');
                                loadData(); // Reload data KOL
                            },
                            error: function() {
                                alert('Gagal menyimpan data');
                                $('#editSubmitBtnLoader').hide();
                                $('#editSubmitBtnText').show();
                            }
                        });
                    });

                    $(document).on('click', '.btn-approve', function() {
                        const kolId = $(this).data('id');

                        $.ajax({
                            url: '/kol/management/approve',
                            type: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            data: JSON.stringify({
                                id: kolId
                            }),
                            success: function(response) {
                                alert('Data approved successfully!');
                                loadData();
                            },
                            error: function(xhr, status, error) {
                                alert('There was an error approving the data.');
                            }
                        });
                    });

                    $(document).on('click', '.btn-reject', function() {
                        const kolId = $(this).data('id');

                        $.ajax({
                            url: '/kol/management/reject',
                            type: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            data: JSON.stringify({
                                id: kolId
                            }),
                            success: function(response) {
                                alert('Data rejected successfully!');
                                loadData();
                            },
                            error: function(xhr, status, error) {
                                alert('There was an error rejecting the data.');
                            }
                        });
                    });

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
            $('#searchInput').on('keyup', function() {
                loadData(1, '', '');
            });

            loadData(1, '', '');
        });
    </script>

    <script>
        $(document).ready(function() {
            // Fungsi untuk menangani klik pada tombol Approve
            $(document).on('click', '.btn-approve', function() {
                const kolId = $(this).data('id');
                $.ajax({
                    url: '/kol/management/approve',
                    type: 'POST',
                    data: {
                        id: kolId
                    },
                    success: function(response) {
                        alert('Data approved successfully!');
                        loadData();
                    },
                    error: function(xhr, status, error) {
                        alert('There was an error approving the data.');
                    }
                });
            });

            $(document).on('click', '.btn-reject', function() {
                const kolId = $(this).data('id');
                $.ajax({
                    url: '/kol/management/reject'
                    type: 'POST',
                    data: {
                        id: kolId
                    },
                    success: function(response) {
                        alert('Data rejected successfully!');
                        loadData();
                    },
                    error: function(xhr, status, error) {
                        alert('There was an error rejecting the data.');
                    }
                });
            });
        });
    </script>


@endsection
