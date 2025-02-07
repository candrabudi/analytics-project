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
                        <div id="alertCard" class="alert alert-danger" style="display: none;">
                            <strong>Warning!</strong> Ratecard KOL diisi terlebih dahulu di database raw.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="raw_tiktok_account_id" class="mb-3">Pilih User TikTok</label>
                                <select class="form-control" name="raw_tiktok_account_id" id="raw_tiktok_account_id">
                                    <option></option>
                                    @foreach ($rawTikTokAccounts as $rawTiktok)
                                        <option value="{{ $rawTiktok->id }}" data-ratecard="{{ $rawTiktok->ratecard_kol }}">
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
                                    <option value="before">Setelah Pembayaran</option>
                                    <option value="after">Sebeleum Pembayaran</option>
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
                                    <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>
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
                                <label for="edit_deal_date" class="mb-3">Deal Date</label>
                                <input type="date" class="form-control" name="deal_date" id="edit_deal_date">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_ratecard_kol" class="mb-3">Ratecard KOL</label>
                                <input type="number" class="form-control" name="ratecard_kol" id="edit_ratecard_kol"
                                    placeholder="Masukkan ratecard KOL">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_ratecard_deal" class="mb-3">Ratecard Deal</label>
                                <input type="number" class="form-control" name="ratecard_deal" id="edit_ratecard_deal"
                                    placeholder="Masukkan ratecard deal">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_deal_post" class="mb-3">Deal Post</label>
                                <input type="number" step="0.01" class="form-control" name="deal_post"
                                    id="edit_deal_post" placeholder="Masukkan nilai deal post">
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

    <div class="modal fade" id="kolShipmentModal" tabindex="-1" aria-labelledby="kolShipmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kolShipmentModalLabel">Buat Kol Shipment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="kolShipmentForm">
                        <input type="hidden" id="kolShipmentId" name="kol_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="warehouse_id" class="form-label">Gudang</label>
                                <select class="form-select" id="warehouse_id" name="warehouse_id" required>
                                    <option value="">Pilih Gudang</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_provider_id" class="form-label">Penyedia Jasa Pengiriman</label>
                                <select class="form-select" id="shipping_provider_id" name="shipping_provider_id"
                                    required>
                                    <option value="">Pilih Penyedia Pengiriman</option>
                                    @foreach ($shippingProviders as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="receiver_name" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="receiver_name" name="receiver_name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">Provinsi</label>
                                <select class="form-select" id="province_id" name="province_id" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
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
                                <label for="destination_address" class="form-label">Alamat Tujuan</label>
                                <textarea name="destination_address" class="form-control" id="destination_address" required></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="shipment_date" class="form-label">Tanggal Pengiriman</label>
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

    <div class="modal fade" id="tiktokInvoiceModal" tabindex="-1" aria-labelledby="tiktokInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tiktokInvoiceModalLabel">Buat TikTok Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tiktokInvoiceForm">
                        @csrf
                        <input type="hidden" id="tiktokInvoiceId" name="kol_id">

                        <!-- Ratecard Deal and Bank Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tiktokRatecardDeal" class="form-label">Ratecard Deal</label>
                                <input type="text" class="form-control" id="tiktokRatecardDeal" name="ratecard_deal"
                                    readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="bank_id" class="form-label">Bank</label>
                                <select class="form-select" id="bank_id" name="bank_id" required>
                                    <option value="">Pilih Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_name" class="form-label">Nama Akun Bank</label>
                                <input type="text" class="form-control" id="account_name" name="account_name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="account_number" class="form-label">Nomor Rekening</label>
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



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    @push('scripts')
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
                        let paymentStatus = 'Belum Bayar';
                        let shippingStatus = 'Belum Dikirim';
                        let invoiceButton = '';
                        let shipmentButton = '';

                        if (kolData.status === 'pending') {
                            actionButtons += `
                                <button class="btn btn-success btn-sm btn-approve" data-id="${kolData.id}">Approve</button>
                                <button class="btn btn-danger btn-sm btn-reject" data-id="${kolData.id}">Reject</button>
                            `;
                        } else if (kolData.status === 'approved') {
                            paymentStatus = 'Sudah Bayar';
                            shippingStatus = 'Sudah Dikirim';
                        }

                        actionButtons += `
                            <button class="btn btn-warning btn-sm btn-edit" data-id="${kolData.id}">Edit</button>
                        `;

                        if (!kolData.kol_shipment) {
                            shipmentButton =
                                `<button class="btn btn-primary btn-sm btn-create-shipment" data-id="${kolData.id}" data-bs-target="#kolShipmentModal">Buat Pengiriman</button>`;
                            shippingStatus =
                                shipmentButton;
                        }else{
                            let shipmentStatus = kolData.kol_shipment.status;
                            if (shipmentStatus === 'pending') {
                                shipmentStatus = '<span class="badge bg-warning">Belum Dikirim</span>';
                            } else if (shipmentStatus === 'shipped') {
                                shipmentStatus = '<span class="badge bg-secondary">Sedang Dikirim</span>';
                            } else if (shipmentStatus === 'delivered') {
                                shipmentStatus =
                                    '<span class="badge bg-success">Diterima</span>';
                            } else {
                                shipmentStatus = '<span class="badge bg-danger">Retur</span>';
                            }
                            shippingStatus = shipmentStatus;
                        }

                        if (!kolData.tiktok_invoice) {
                            invoiceButton =
                                `<button class="btn btn-primary btn-sm btn-create-invoice" data-id="${kolData.id}" data-ratecard-deal="${kolData.ratecard_deal}" data-bs-target="#tiktokInvoiceModal">Buat Invoice</button>`;
                            paymentStatus = invoiceButton;
                        } else {
                            let invoiceStatus = kolData.tiktok_invoice.status; // Use let here
                            if (invoiceStatus === 'pending') {
                                invoiceStatus = '<span class="badge bg-warning">Belum Bayar</span>';
                            } else if (invoiceStatus === 'progress') {
                                invoiceStatus = '<span class="badge bg-secondary">Di Proses</span>';
                            } else if (invoiceStatus === 'approved') {
                                invoiceStatus =
                                    '<span class="badge bg-success">Disetujui</span>';
                            } else {
                                invoiceStatus = '<span class="badge bg-danger">Ditolak</span>';
                            }
                            paymentStatus = invoiceStatus;
                        }


                        let roundedCpv = (kolData.ratecard_deal / kolData.raw_tiktok_account
                            .avg_views).toFixed(2);

                        let assignCategories = kolData.assign_category?.length ?
                            kolData.assign_category.map(category =>
                                `<span class="badge bg-secondary">${category.name}</span>`
                            ).join(' ') : 'No Category';
                        const formattedDate = formatDate(kolData.created_at);
                        rows += `
                        <tr>
                            <td class="freeze-col freeze-col-1">
                                ${formattedDate}
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
                            <td>${paymentStatus}</td>
                            <td>${shippingStatus}</td>
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

                    $('.btn-edit').on('click', function() {
                        const kolId = $(this).data('id');
                        $.ajax({
                            url: '/kol/management/edit/' + kolId,
                            method: 'GET',
                            success: function(kolData) {
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

                                $('#editModal').modal('show');
                            },
                            error: function() {
                                alert('Gagal mengambil data');
                            }
                        });
                    });

                    $('#editKolForm').on('submit', function(e) {
                        e.preventDefault();

                        const kolId = $('.btn-edit').data('id');
                        const formData = $(this).serialize();
                        $('#editSubmitBtnLoader').show();
                        $('#editSubmitBtnText').hide();
                        $.ajax({
                            url: '/kol/' + kolId,
                            method: 'PUT',
                            data: formData,
                            success: function(response) {
                                $('#editSubmitBtnLoader').hide();
                                $('#editSubmitBtnText').show();
                                $('#editModal').modal('hide');
                                loadData();
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
                                    'meta[name="csrf-token"]').getAttribute('content')
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
                                    'meta[name="csrf-token"]').getAttribute('content')
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

                    $(document).on('click', '.btn-create-shipment', function() {
                        const kolId = $(this).data('id');
                        $('#kolShipmentId').val(kolId);
                        $('#kolShipmentModal').modal('show');
                    });

                    $(document).on('click', '.btn-create-invoice', function() {
                        const kolId = $(this).data('id');
                        const ratecardDeal = $(this).data('ratecard-deal');
                        $('#tiktokInvoiceId').val(kolId);
                        $('#tiktokRatecardDeal').val(ratecardDeal);
                        $('#tiktokInvoiceModal').modal('show');
                    });

                    $('#kolShipmentForm').on('submit', function(e) {
                        e.preventDefault();

                        const formData = {
                            _token: '{{ csrf_token() }}',
                            kol_id: $('#kolShipmentId').val(),
                            warehouse_id: $('#warehouse_id').val(),
                            shipping_provider_id: $('#shipping_provider_id').val(),
                            receiver_name: $('#receiver_name').val(),
                            province_id: $('#province_id').val(),
                            regency_id: $('#regency_id').val(),
                            district_id: $('#district_id').val(),
                            village_id: $('#village_id').val(),
                            destination_address: $('#destination_address').val(),
                            shipment_date: $('#shipment_date').val(),
                            status: $('#status').val(),
                            shipping_cost: $('#shipping_cost').val(),
                            notes: $('#notes').val(),
                        };

                        $.ajax({
                            url: '/kol/management/create-shipment',
                            method: 'POST',
                            data: formData,
                            success: function(response) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Kol Shipment Created Successfully!',
                                    icon: 'success',
                                    confirmButtonText: 'Okay'
                                });
                                $('#kolShipmentModal').modal('hide');
                                loadData
                            ();
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'There was an error creating the Kol Shipment.',
                                    icon: 'error',
                                    confirmButtonText: 'Try Again'
                                });
                            }
                        });
                    });


                    $('#tiktokInvoiceForm').on('submit', function(e) {
                        e.preventDefault();

                        const kolId = $('#tiktokInvoiceId').val();
                        const bankId = $('#bank_id').val();
                        const accountName = $('#account_name').val();
                        const accountNumber = $('#account_number').val();

                        $.ajax({
                            url: '/kol/management/create-invoice',
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                kol_id: kolId,
                                bank_id: bankId,
                                account_name: accountName,
                                account_number: accountNumber
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'TikTok Invoice Created Successfully!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                $('#tiktokInvoiceModal').modal('hide');
                                loadData();
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Error creating TikTok Invoice',
                                    icon: 'error',
                                    confirmButtonText: 'Try Again'
                                });
                            }
                        });
                    });


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

@endsection
