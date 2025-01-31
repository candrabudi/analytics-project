@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            position: relative;
        }

        .select2-dropdown {
            z-index: 999999 !important;
        }
    </style>
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">KOL Shipment</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATA SHIPMENT KOL</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example">
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                Tambah Gudang
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">TANGGAL PENGIRMAN</th>
                                    <th scope="col">KOL TRX</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">NAMA PENERIMA</th>
                                    <th scope="col">NOMOR RESI</th>
                                    <th scope="col">STATUS PENGIRIMAN</th>
                                    <th scope="col">PROVINSI</th>
                                    <th scope="col">KABUPATEN</th>
                                    <th scope="col">KECAMATAN</th>
                                    <th scope="col">DESA</th>
                                    <th scope="col">DETAIL ALAMAT</th>
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
                    <h5 class="modal-title" id="createModalLabel">Tambah Pengiriman Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kol_shipments.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kol_management_id" class="form-label">KOL Management</label>
                                <select class="form-select" id="kol_management_id" name="kol_management_id" required>
                                    <option value="">Pilih KOL Management</option>
                                    @foreach ($kolManagements as $kolManagement)
                                        <option value="{{ $kolManagement->id }}">{{ $kolManagement->kol_trx_no }} -
                                            {{ $kolManagement->rawTiktokAccount->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping_provider_id" class="form-label">Penyedia Jasa Pengiriman</label>
                                <select class="form-select" id="shipping_provider_id" name="shipping_provider_id" required>
                                    <option value="">Pilih Penyedia Pengiriman</option>
                                    @foreach ($shippingProviders as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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
                                <label for="shipment_number" class="form-label">Nomor Pengiriman</label>
                                <input type="text" class="form-control" id="shipment_number" name="shipment_number"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="receiver_name" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="receiver_name" name="receiver_name" required>
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

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" id="notes"></textarea>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Pengiriman Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editKolShipmentForm" method="POST">
                        @csrf
                        @method('PUT')
    
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editKolManagementId" class="form-label">KOL Management</label>
                                <select class="form-select" id="editKolManagementId" name="kol_management_id" readonly>
                                    <option value="">Pilih KOL Management</option>
                                    @foreach ($kolManagementsEdit as $kolManagementEdit)
                                        <option value="{{ $kolManagementEdit->id }}">{{ $kolManagementEdit->kol_trx_no }} - {{ $kolManagementEdit->rawTiktokAccount->unique_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editShippingProviderId" class="form-label">Penyedia Jasa Pengiriman</label>
                                <select class="form-select" id="editShippingProviderId" name="shipping_provider_id" required>
                                    <option value="">Pilih Penyedia Pengiriman</option>
                                    @foreach ($shippingProviders as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editWarehouseId" class="form-label">Gudang</label>
                                <select class="form-select" id="editWarehouseId" name="warehouse_id" required>
                                    <option value="">Pilih Gudang</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editShipmentNumber" class="form-label">Nomor Pengiriman</label>
                                <input type="text" class="form-control" id="editShipmentNumber" name="shipment_number" required>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editReceiverName" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="editReceiverName" name="receiver_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editProvinceId" class="form-label">Provinsi</label>
                                <select class="form-select" id="editProvinceId" name="province_id" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editRegencyId" class="form-label">Kabupaten</label>
                                <select class="form-select" id="editRegencyId" name="regency_id" required>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editDistrictId" class="form-label">Kecamatan</label>
                                <select class="form-select" id="editDistrictId" name="district_id" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editVillageId" class="form-label">Desa</label>
                                <select class="form-select" id="editVillageId" name="village_id" required>
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editDestinationAddress" class="form-label">Alamat Tujuan</label>
                                <textarea name="destination_address" class="form-control" id="editDestinationAddress" required></textarea>
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editShipmentDate" class="form-label">Tanggal Pengiriman</label>
                                <input type="date" class="form-control" id="editShipmentDate" name="shipment_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editStatus" class="form-label">Status Pengiriman</label>
                                <select class="form-select" id="editStatus" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="returned">Returned</option>
                                </select>
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label for="editNotes" class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" id="editNotes"></textarea>
                        </div>
    
                        <button type="submit" class="btn btn-primary">Update Pengiriman</button>
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
                dropdownParent: $('.col-md-6')
            });

            $('.select2-dropdown').css('z-index', '999999');
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
            const url = `/kol/shipments/load?page=${page}&search=${search}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolShipping) {
                    rows += `
            <tr>
                <td>${kolShipping.shipment_date}</td>
                <td>${kolShipping.kol_management.kol_trx_no}</td>
                <td>${kolShipping.raw_tiktok_account.unique_id}</td>
                <td>${kolShipping.receiver_name}</td>
                <td>${kolShipping.shipment_number}</td>
                <td>${kolShipping.status}</td>
                <td>${kolShipping.province.name}</td>
                <td>${kolShipping.regency.name}</td>
                <td>${kolShipping.district.name}</td>
                <td>${kolShipping.village.name}</td>
                <td>${kolShipping.destination_address}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editKolShipment(${kolShipping.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteKolShipment(${kolShipping.id})">Delete</button>
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

        function editKolShipment(id) {
            $.get(`/kol/shipments/edit/${id}`, function(shipment) {
                $('#editModalLabel').text(`Edit Pengiriman: ${shipment.shipment_number}`);
                $('#editKolShipmentForm').attr('action', `/kol/shipments/update/${id}`);

                $('#editKolManagementId').val(shipment.kol_management_id);
                $('#editShippingProviderId').val(shipment.shipping_provider_id);
                $('#editWarehouseId').val(shipment.warehouse_id);
                $('#editShipmentNumber').val(shipment.shipment_number);
                $('#editReceiverName').val(shipment.receiver_name);
                $('#editProvinceId').val(shipment.province_id);
                loadRegencies(shipment.province_id, shipment.regency_id);
                loadDistricts(shipment.regency_id, shipment.district_id);
                loadVillages(shipment.district_id, shipment.village_id);
                $('#editDestinationAddress').val(shipment.destination_address);
                $('#editShipmentDate').val(shipment.shipment_date);
                $('#editStatus').val(shipment.status);
                $('#editNotes').val(shipment.notes);

                $('#editModal').modal('show');
            });
        }

        function deleteKolShipment(id) {
            if (confirm('Are you sure you want to delete this warehouse?')) {
                $.ajax({
                    url: `/kol/shipments/destroy/${id}`,
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

        function loadRegencies(provinceId, selectedRegencyId = null) {
            if (provinceId) {
                $.ajax({
                    url: '/get-regencies/' + provinceId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#editRegencyId').html('<option value="">Pilih Kabupaten</option>');
                        $.each(data, function(key, value) {
                            $('#editRegencyId').append('<option value="' + value.id + '">' + value
                                .name + '</option>');
                        });
                        if (selectedRegencyId) {
                            $('#editRegencyId').val(selectedRegencyId);
                        }
                    }
                });
            }
        }

        function loadDistricts(regencyId, selectedDistrictId = null) {
            if (regencyId) {
                $.ajax({
                    url: '/get-districts/' + regencyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#editDistrictId').html('<option value="">Pilih Kecamatan</option>');
                        $.each(data, function(key, value) {
                            $('#editDistrictId').append('<option value="' + value.id + '">' + value
                                .name + '</option>');
                        });
                        if (selectedDistrictId) {
                            $('#editDistrictId').val(selectedDistrictId);
                        }
                    }
                });
            }
        }

        function loadVillages(districtId, selectedVillageId = null) {
            if (districtId) {
                $.ajax({
                    url: '/get-villages/' + districtId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#editVillageId').html('<option value="">Pilih Desa</option>');
                        $.each(data, function(key, value) {
                            $('#editVillageId').append('<option value="' + value.id + '">' + value
                                .name + '</option>');
                        });
                        if (selectedVillageId) {
                            $('#editVillageId').val(selectedVillageId);
                        }
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

    <script>
        $('#editProvinceId').on('change', function() {
            var provinceId = $(this).val();
            $('#editRegencyId').html('<option value="">Memuat...</option>');
            $('#editDistrictId').html('<option value="">Pilih Kecamatan</option>');
            $('#editVillageId').html('<option value="">Pilih Desa</option>');
            if (provinceId) {
                $.ajax({
                    url: '/get-regencies/' + provinceId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#editRegencyId').html('<option value="">Pilih Kabupaten</option>');
                        $.each(data, function(key, value) {
                            $('#editRegencyId').append('<option value="' + value.id + '">' +
                                value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#editRegencyId').html('<option value="">Pilih Kabupaten</option>');
            }
        });

        $('#editRegencyId').on('change', function() {
            var regencyId = $(this).val();
            $('#editDistrictId').html('<option value="">Memuat...</option>');
            $('#editVillageId').html('<option value="">Pilih Desa</option>');
            if (regencyId) {
                $.ajax({
                    url: '/get-districts/' + regencyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#editDistrictId').html('<option value="">Pilih Kecamatan</option>');
                        $.each(data, function(key, value) {
                            $('#editDistrictId').append('<option value="' + value.id + '">' +
                                value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#editDistrictId').html('<option value="">Pilih Kecamatan</option>');
            }
        });

        $('#editDistrictId').on('change', function() {
            var districtId = $(this).val();
            $('#editVillageId').html('<option value="">Memuat...</option>');
            if (districtId) {
                $.ajax({
                    url: '/get-villages/' + districtId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#editVillageId').html('<option value="">Pilih Desa</option>');
                        $.each(data, function(key, value) {
                            $('#editVillageId').append('<option value="' + value.id + '">' +
                                value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#editVillageId').html('<option value="">Pilih Desa</option>');
            }
        });
    </script>
@endsection
