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

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

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
                                    <th scope="col">TIPE</th>
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
                                @foreach ($kolManagements as $kolManagement)
                                    <tr>
                                        <td class="freeze-col freeze-col-1">
                                            {{ $kolManagement->created_at->format('Y-m-d') }}</td>
                                        <td class="freeze-col freeze-col-2">
                                            {{ $kolManagement->rawTiktokAccount->unique_id ?? '-' }}</td>
                                        <td class="freeze-col freeze-col-3">
                                            {{ $kolManagement->rawTiktokAccount->follower ?? '-' }}</td>
                                        <td>
                                            @if ($kolManagement->type_payment == 'after')
                                                <span class="badge bg-danger text-white">Pembayaran Setelah Posting</span>
                                            @else
                                                <span class="badge bg-danger text-white">Pembayaran Sebelum Posting</span>
                                            @endif
                                        </td>
                                        <td>{{ $kolManagement->rawTiktokAccount->whatsapp_number ?? '-' }}</td>
                                        <td>{{ $kolManagement->ratecard_kol }}</td>
                                        <td>{{ $kolManagement->ratecard_deal }}</td>
                                        <td>{{ $kolManagement->target_views }}</td>
                                        <td>{{ $kolManagement->rawTiktokAccount->avg_views ?? '-' }}</td>
                                        <td>{{ round($kolManagement->ratecard_deal / $kolManagement->rawTiktokAccount->avg_views) }}
                                        </td>
                                        <td>{{ round(($kolManagement->rawTiktokAccount->follower / $kolManagement->rawTiktokAccount->avg_views) * 100, 2) }}%
                                        </td>
                                        <td>{{ $kolManagement->views_achieved }}</td>
                                        <td>{{ $kolManagement->deal_post }}</td>
                                        <td>{{ $kolManagement->status }}</td>
                                        <td>
                                            @if ($kolManagement->type_payment == 'after')
                                                @if ($kolManagement->tiktokInvoice)
                                                    @if ($kolManagement->tiktokInvoice->status == 'pending')
                                                        <span class="badge bg-warning text-dark">Pengajuan Finance</span>
                                                    @elseif($kolManagement->tiktokInvoice->status == 'process')
                                                        <span class="badge bg-info text-dark">Pembayaran Diproses</span>
                                                    @elseif($kolManagement->tiktokInvoice->status == 'paid')
                                                        <span class="badge bg-success">Sudah Dibayarkan</span>
                                                    @else
                                                        <span class="badge bg-danger">Dibatalkan</span>
                                                    @endif
                                                @else
                                                    <a type="button" class="btn btn-primary btn-sm"
                                                        data-id="{{ $kolManagement->id }}"
                                                        data-ratecard="{{ $kolManagement->ratecard_deal }}"
                                                        data-bs-toggle="modal" data-bs-target="#tiktokInvoiceModal">
                                                        Buat Invoice
                                                    </a>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Pembayaran Setelah Selesai</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($kolManagement->kolShipment)
                                                {{ $kolManagement->kolShipment->status }}
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm openModal"
                                                    data-id="{{ $kolManagement->id }}"
                                                    data-ratecard="{{ $kolManagement->ratecard_deal }}">
                                                    Ajukan Pengiriman
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kolManagement->status == 'pending')
                                                <form action="{{ route('kol.management.approve', $kolManagement->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm" type="submit">Approve</button>
                                                </form>
                                                <form action="{{ route('kol.management.reject', $kolManagement->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button class="btn btn-danger btn-sm" type="submit">Reject</button>
                                                </form>
                                            @endif
                                            <button class="btn btn-warning btn-sm"
                                                data-id="{{ $kolManagement->id }}">Edit</button>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        <div id="paginationInfo">Showing {{ $kolManagements->firstItem() }} to
                            {{ $kolManagements->lastItem() }} of {{ $kolManagements->total() }} entries</div>
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-4">
                                {{ $kolManagements->links() }}
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
                        <div id="alertCard" class="alert alert-danger" style="display: none;">
                            <strong>Warning!</strong> Ratecard KOL diisi terlebih dahulu di database raw.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="raw_tiktok_account_id" class="mb-3">Pilih User TikTok</label>
                                <select class="form-control" name="raw_tiktok_account_id" id="raw_tiktok_account_id">
                                    <option></option>
                                    @foreach ($rawTikTokAccounts as $rawTiktok)
                                        <option value="{{ $rawTiktok->id }}"
                                            data-ratecard="{{ $rawTiktok->ratecard_kol }}">
                                            {{ $rawTiktok->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pic_id" class="mb-3">PIC</label>
                                <select name="pic_id" id="pic_id" class="form-control">
                                    <option value="">Pilih PIC</option>
                                    <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>
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
                                <label for="deal_date" class="mb-3">Deal Date</label>
                                <input type="date" class="form-control" name="deal_date" id="deal_date">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ratecard_kol" class="mb-3">Ratecard KOL</label>
                                <input type="number" class="form-control" name="ratecard_kol" id="ratecard_kol"
                                    placeholder="Terisi otomatis ketika memilih user tiktok" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ratecard_deal" class="mb-3">Ratecard Deal</label>
                                <input type="number" class="form-control" name="ratecard_deal" id="ratecard_deal"
                                    placeholder="Masukkan ratecard deal">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="deal_post" class="mb-3">Deal Post</label>
                                <input type="number" step="0.01" class="form-control" name="deal_post"
                                    id="deal_post" placeholder="Masukkan nilai deal post">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="target_views" class="mb-3">Target Views</label>
                                <input type="number" class="form-control" name="target_views" id="target_views"
                                    placeholder="Terisi otomatis ketika mengisi ratecard deal" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="deal_post" class="mb-3">Tipe Pembayar</label>
                                <select name="type_payment" class="form-control" id="">
                                    <option value="">Pilih Tipe Pembayaran</option>
                                    <option value="after">Setelah Pembayaran</option>
                                    <option value="before">Sebeleum Pembayaran</option>
                                </select>
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

    <div class="modal fade" id="tiktokInvoiceModal" tabindex="-1" aria-labelledby="tiktokInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tiktokInvoiceModalLabel">Buat TikTok Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tiktokInvoiceForm" action="{{ route('kol.management.store.invoice') }}" method="POST">
                        @csrf
                        <input type="hidden" id="tiktokInvoiceId" name="kol_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tiktokRatecardDeal" class="form-label">Ratecard
                                    Deal</label>
                                <input type="text" class="form-control" id="tiktokRatecardDeal" name="ratecard_deal"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="bank_id" class="form-label">Bank</label>
                                <select class="form-select" id="bank_id" name="bank_id" required>
                                    <option value="">Pilih Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_name" class="form-label">Nama Akun
                                    Bank</label>
                                <input type="text" class="form-control" id="account_name" name="account_name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="account_number" class="form-label">Nomor
                                    Rekening</label>
                                <input type="text" class="form-control" id="account_number" name="account_number"
                                    required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="kolShipmentModal" tabindex="-1" aria-labelledby="kolShipmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kolShipmentModalLabel">Buat Kol Shipment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="kolShipmentForm" method="POST" action="{{ route('kol.management.store.shipment') }}">
                        @csrf
                        <input type="hidden" id="kolShipmentId" name="kol_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="warehouse_id" class="form-label">Gudang</label>
                                <select class="form-select" id="warehouse_id" name="warehouse_id" required>
                                    <option value="">Pilih Gudang</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">
                                            {{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_provider_id" class="form-label">Penyedia Jasa
                                    Pengiriman</label>
                                <select class="form-select" id="shipping_provider_id" name="shipping_provider_id"
                                    required>
                                    <option value="">Pilih Penyedia Pengiriman</option>
                                    @foreach ($shippingProviders as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="receiver_name" class="form-label">Nama
                                    Penerima</label>
                                <input type="text" class="form-control" id="receiver_name" name="receiver_name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">Provinsi</label>
                                <select class="form-select" id="province_id" name="province_id" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="regency_id" class="form-label">Kabupaten</label>
                                <select class="form-select" id="regency_id" name="regency_id" required>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="district_id" class="form-label">Kecamatan</label>
                                <select class="form-select" id="district_id" name="district_id" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="village_id" class="form-label">Desa</label>
                                <select class="form-select" id="village_id" name="village_id" required>
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="destination_address" class="form-label">Alamat
                                    Tujuan</label>
                                <textarea name="destination_address" class="form-control" id="destination_address" required></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="shipment_date" class="form-label">Tanggal
                                    Pengiriman</label>
                                <input type="date" class="form-control" id="shipment_date" name="shipment_date"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status Pengiriman</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="returned">Returned</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="shipping_cost" class="form-label">Ongkir</label>
                                <input type="number" class="form-control" id="shipping_cost" name="shipping_cost"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" id="notes"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(document).ready(function() {

                $('#raw_tiktok_account_id').on('change', function() {
                    var ratecardKol = $(this).find('option:selected').data('ratecard');
                    $('#ratecard_kol').val(ratecardKol);
                    if (ratecardKol && ratecardKol != 0) {
                        $('#alertCard').hide();
                    }
                    var ratecardKol = $('#ratecard_kol').val();
                    if (!ratecardKol || ratecardKol == 0) {
                        $('#alertCard').show();
                    }
                });


                $('#ratecard_deal').on('input', function() {
                    var ratecardDeal = $(this).val();
                    if (ratecardDeal && !isNaN(ratecardDeal)) {
                        var targetViews = Math.ceil(ratecardDeal / 10);
                        $('#target_views').val(targetViews);
                    } else {
                        $('#target_views').val('');
                    }
                });

            });
        </script>



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
                            location.reload();
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
                $('a[data-bs-target="#tiktokInvoiceModal"]').on('click', function() {
                    var kolId = $(this).data('id');
                    var ratecardDeal = $(this).data('ratecard');
                    $('#tiktokInvoiceId').val(kolId);
                    $('#tiktokRatecardDeal').val(ratecardDeal);
                    $('#tiktokInvoiceModal').modal('show');
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('kolShipmentModal'));

                document.querySelectorAll('.openModal').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const kolId = this.getAttribute('data-id');
                        const ratecard = this.getAttribute('data-ratecard');
                        document.getElementById('kolShipmentId').value = kolId;
                        modal.show();
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#province_id').on('change', function() {
                    var provinceId = $(this).val();
                    $('#regency_id').html('<option value="">Memuat...</option>');
                    $('#district_id').html('<option value="">Pilih Kecamatan</option>');
                    $('#village_id').html('<option value="">Pilih Desa</option>');
                    if (provinceId) {
                        $.ajax({
                            url: '/get-regencies/' + provinceId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#regency_id').html('<option value="">Pilih Kabupaten</option>');
                                $.each(data, function(key, value) {
                                    $('#regency_id').append('<option value="' + value.id +
                                        '">' + value.name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#regency_id').html('<option value="">Pilih Kabupaten</option>');
                    }
                });

                $('#regency_id').on('change', function() {
                    var regencyId = $(this).val();
                    $('#district_id').html('<option value="">Memuat...</option>');
                    $('#village_id').html('<option value="">Pilih Desa</option>');
                    if (regencyId) {
                        $.ajax({
                            url: '/get-districts/' + regencyId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#district_id').html('<option value="">Pilih Kecamatan</option>');
                                $.each(data, function(key, value) {
                                    $('#district_id').append('<option value="' + value.id +
                                        '">' + value.name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#district_id').html('<option value="">Pilih Kecamatan</option>');
                    }
                });

                $('#district_id').on('change', function() {
                    var districtId = $(this).val();
                    $('#village_id').html('<option value="">Memuat...</option>');
                    if (districtId) {
                        $.ajax({
                            url: '/get-villages/' + districtId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#village_id').html('<option value="">Pilih Desa</option>');
                                $.each(data, function(key, value) {
                                    $('#village_id').append('<option value="' + value.id +
                                        '">' + value.name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#village_id').html('<option value="">Pilih Desa</option>');
                    }
                });
            });
        </script>
    @endpush
@endsection
