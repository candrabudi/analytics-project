@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">TikTok Invoice</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATA TikTok Invoice</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example">
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                Tambah TikTok Invoice
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
                                    <th scope="col">NAMA BANK</th>
                                    <th scope="col">NAMA AKUN</th>
                                    <th scope="col">NOMOR AKUN</th>
                                    <th scope="col">FILE</th>
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
                    <h5 class="modal-title" id="createModalLabel">Tambah Invoice TikTok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kol_invoices.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label for="ratecard_deal" class="form-label">Ratecard Deal</label>
                                <input type="text" class="form-control" id="ratecard_deal" name="ratecard_deal" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bank_id" class="form-label">Bank</label>
                                <select class="form-select" id="bank_id" name="bank_id" required>
                                    <option value="">Pilih Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="account_name" class="form-label">Nama Akun Bank</label>
                                <input type="text" class="form-control" id="account_name" name="account_name" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_number" class="form-label">Nomor Rekening</label>
                                <input type="text" class="form-control" id="account_number" name="account_number"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="file_upload" class="form-label">Unggah File Invoice</label>
                                <input type="file" class="form-control" id="file_upload" name="file_upload">
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
                    <h5 class="modal-title" id="editModalLabel">Edit Invoice TikTok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTiktokInvoiceForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="editKolManagementId" class="form-label">KOL Management</label>
                                <select class="form-select" id="editKolManagementId" name="kol_management_id" required>
                                    <option value="">Pilih KOL Management</option>
                                    @foreach ($kolManagementsEdit as $kolEdit)
                                        <option value="{{ $kolEdit->id }}">{{ $kolEdit->kol_trx_no }} -
                                            {{ $kolEdit->rawTiktokAccount->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editBankId" class="form-label">Bank</label>
                                <select class="form-select" id="editBankId" name="bank_id" required>
                                    <option value="">Pilih Bank</option>
                                    @foreach ($banks as $bankEdit)
                                        <option value="{{ $bankEdit->id }}">{{ $bankEdit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editAccountName" class="form-label">Nama Akun Bank</label>
                                <input type="text" class="form-control" id="editAccountName" name="account_name"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editAccountNumber" class="form-label">Nomor Rekening</label>
                                <input type="text" class="form-control" id="editAccountNumber" name="account_number"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="editFileUpload" class="form-label">Unggah File Invoice</label>
                                <input type="file" class="form-control" id="editFileUpload" name="file_upload">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Invoice</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize select2 for the dropdowns
            $('#kol_management_id').select2({
                placeholder: 'Pilih KOL Management',
                allowClear: true,
                dropdownParent: $('#createModal')
            });

            // Event listener for KOL Management selection
            $('#kol_management_id').on('change', function() {
                // Get the selected option's ratecard deal data
                var ratecardDeal = $(this).find(':selected').data('ratecard-deal');
                // Set the value to the ratecard_deal input field
                $('#ratecard_deal').val(ratecardDeal || '');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize select2 for create modal
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
            const url = `/kol/invoices/load?page=${page}&search=${search}`;

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
                        <td>${tiktokInvoice.bank.name}</td>
                        <td>${tiktokInvoice.account_name}</td>
                        <td>${tiktokInvoice.account_number}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="showFile('${fileUrl}')">Lihat</button>
                            <a class="btn btn-sm btn-primary" href="${fileUrl}" download>Download</a>
                        </td>
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

        // Helper function to format date
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


        function showFile(fileUrl) {
            window.open(fileUrl, '_blank');
        }


        function editKolInvoice(id) {
            $.get(`/kol/invoices/edit/${id}`, function(invoice) {
                $('#editModalLabel').text(`Edit Invoice TikTok: ${invoice.raw_tiktok_account.unique_id}`);
                $('#editTiktokInvoiceForm').attr('action', `/kol/invoices/update/${id}`);

                $('#editKolManagementId').val(invoice.kol_management_id).trigger('change');
                $('#editBankId').val(invoice.bank_id).trigger('change');
                $('#editAccountName').val(invoice.account_name);
                $('#editAccountNumber').val(invoice.account_number);

                $('#editModal').modal('show');
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
