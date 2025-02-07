@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">TikTok Invoice</h1>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATA TikTok Invoice</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label="Search">
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
                                    <th scope="col">STATUS INVOICE</th>
                                    <th scope="col">FILE</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tiktokInvoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $invoice->kolManagement->kol_trx_no }}</td>
                                        <td>{{ $invoice->rawTiktokAccount->unique_id }}</td>
                                        <td>{{ $invoice->bank ? $invoice->bank->name : '-' }}</td>
                                        <td>{{ $invoice->account_name ?? '-' }}</td>
                                        <td>{{ $invoice->account_number != 0 ? $invoice->account_number : '-' }}</td>
                                        <td>
                                            @if ($invoice->type == 'shipping_cost')
                                                <span class="badge bg-info text-dark">Invoice Pengiriman</span>
                                            @else
                                                <span class="badge bg-info text-dark">Invoice Influencer</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->status }}</td>
                                        <td>
                                            @if ($invoice->file_upload)
                                                <a href="{{ asset('storage/' . $invoice->file_upload) }}"
                                                    target="_blank">Download</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $invoice->id }}"
                                                data-trx="{{ $invoice->kolManagement->kol_trx_no }}"
                                                data-username="{{ $invoice->rawTiktokAccount->unique_id }}"
                                                data-bank="{{ $invoice->bank ? $invoice->bank->id : 0 }}"
                                                data-account_name="{{ $invoice->account_name }}"
                                                data-account_number="{{ $invoice->account_number }}"
                                                data-status="{{ $invoice->status }}"
                                                data-file_upload="{{ asset('storage/' . $invoice->file_upload) }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('kol_invoices.destroy', $invoice->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this invoice?');">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">No TikTok Invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        <div>
                            Showing {{ $tiktokInvoices->firstItem() }} to {{ $tiktokInvoices->lastItem() }} of
                            {{ $tiktokInvoices->total() }} entries
                        </div>
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-4">
                                {{ $tiktokInvoices->links() }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit TikTok Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="{{ route('kol_invoices.update', '') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="invoiceId" name="id">

                        <div class="mb-3">
                            <label for="editTrx" class="form-label">KOL TRX</label>
                            <input type="text" class="form-control" id="editTrx" name="kol_trx_no" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="editBank" class="form-label">Nama Bank</label>
                            <select class="form-control" id="editBank" name="bank_id">
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editAccountName" class="form-label">Nama Akun</label>
                            <input type="text" class="form-control" id="editAccountName" name="account_name">
                        </div>

                        <div class="mb-3">
                            <label for="editAccountNumber" class="form-label">Nomor Akun</label>
                            <input type="text" class="form-control" id="editAccountNumber" name="account_number">
                        </div>

                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-control" id="editStatus" name="status">
                                <option value="pending">Pending</option>
                                <option value="process">Process</option>
                                <option value="paid">Paid</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editFileUpload" class="form-label">File Upload</label>
                            <input type="file" class="form-control" id="editFileUpload" name="file_upload">
                            <a href="#" id="fileLink" target="_blank" style="display:none;">Download Current
                                File</a>
                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editBank').select2();
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const trx = $(this).data('trx');
                const username = $(this).data('username');
                const bank = $(this).data('bank');
                const account_name = $(this).data('account_name');
                const account_number = $(this).data('account_number');
                const file_upload = $(this).data('file_upload');
                const status = $(this).data('status'); // Get the status value

                $('#editForm').attr('action', `/kol/invoices/update/${id}`);
                $('#invoiceId').val(id);
                $('#editTrx').val(trx);
                $('#editUsername').val(username);
                $('#editBank').val(bank).trigger('change');
                $('#editAccountName').val(account_name);
                $('#editAccountNumber').val(account_number);
                $('#editStatus').val(status); // Set the status value

                if (file_upload) {
                    $('#fileLink').attr('href', file_upload).show();
                } else {
                    $('#fileLink').hide();
                }
                $('#editModal').modal('show');
            });

            $('#searchInput').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
@endsection
