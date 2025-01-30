@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Manajemen Warehouse</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATA GUDANG</div>
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
                                    <th scope="col">NAMA</th>
                                    <th scope="col">PROVINSI</th>
                                    <th scope="col">KABUPATEN</th>
                                    <th scope="col">KECAMATAN</th>
                                    <th scope="col">DESA</th>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Landing Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('warehouses.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Gudang</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="province_id" class="form-label">Provinsi</label>
                            <select class="form-select" id="province_id" name="province_id" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="regency_id" class="form-label">Kabupaten</label>
                            <select class="form-select" id="regency_id" name="regency_id" required>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="district_id" class="form-label">Kecamatan</label>
                            <select class="form-select" id="district_id" name="district_id" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="village_id" class="form-label">Desa</label>
                            <select class="form-select" id="village_id" name="village_id" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="address_detail" class="form-label">Detail Alamat</label>
                            <textarea name="address_detail" class="form-control" id=""></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Gudang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editWarehouseForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Gudang</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="editProvinceId" class="form-label">Provinsi</label>
                            <select class="form-select" id="editProvinceId" name="province_id" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editRegencyId" class="form-label">Kabupaten</label>
                            <select class="form-select" id="editRegencyId" name="regency_id" required>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editDistrictId" class="form-label">Kecamatan</label>
                            <select class="form-select" id="editDistrictId" name="district_id" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editVillageId" class="form-label">Desa</label>
                            <select class="form-select" id="editVillageId" name="village_id" required>
                                <option value="">Pilih Desa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editAddressDetail" class="form-label">Detail Alamat</label>
                            <textarea name="address_detail" class="form-control" id="editAddressDetail"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Gudang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function loadLandingpages(page = 1) {
            const search = $('#searchInput').val();
            const url = `/warehouses/load?page=${page}&search=${search}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(warehouse) {
                    rows += `
            <tr>
                <td>${warehouse.name}</td>
                <td>${warehouse.province.name}</td>
                <td>${warehouse.regency.name}</td>
                <td>${warehouse.district.name}</td>
                <td>${warehouse.village.name}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editWarehouse(${warehouse.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteWarehouse(${warehouse.id})">Delete</button>
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

        function editWarehouse(id) {
            $.get(`/warehouses/edit/${id}`, function(warehouse) {
                $('#editModalLabel').text(`Edit Gudang: ${warehouse.name}`);
                $('#editWarehouseForm').attr('action', `/warehouses/update/${id}`);

                $('#editName').val(warehouse.name);

                $('#editProvinceId').val(warehouse.province_id);
                loadRegencies(warehouse.province_id, warehouse.regency_id);

                loadDistricts(warehouse.regency_id, warehouse.district_id);

                loadVillages(warehouse.district_id, warehouse.village_id);

                $('#editAddressDetail').val(warehouse.address_detail);

                $('#editModal').modal('show');
            });
        }

        function deleteWarehouse(id) {
            if (confirm('Are you sure you want to delete this warehouse?')) {
                $.ajax({
                    url: `/warehouses/destroy/${id}`,
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
