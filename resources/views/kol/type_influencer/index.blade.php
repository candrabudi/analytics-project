@extends('layouts.app')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    {{-- <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <ul class="nav nav-tabs nav-tabs-header mb-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all-tab" data-bs-toggle="tab"
                                        href="javascript:void(0);" onclick="loadKol('all')">ALL</a>
                                </li>
                                @foreach ($categories as $ctg)
                                    <li class="nav-item">
                                        <a class="nav-link" id="{{ $ctg->id }}-tab" data-bs-toggle="tab"
                                            href="javascript:void(0);"
                                            onclick="loadKol('{{ $ctg->id }}')">{{ strtoupper($ctg->name) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="tab-content">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header justify-content-between">
                                <div class="card-title" id="kol-title">KOL ALL</div>
                                <div class="d-flex align-items-center w-80">
                                    <input class="form-control form-control-sm me-2 w-200" id="searchInput" type="text"
                                        placeholder="Search Here">
                                    <button class="btn btn-success me-2 btn-sm w-100" data-bs-toggle="modal"
                                        data-bs-target="#searchUsername">
                                        Tambah Data
                                    </button>

                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter
                                    </button>
                                    <div class="dropdown-menu p-4" aria-labelledby="dropdownMenuButton1">
                                        <div class="mb-3">
                                            <div>
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="payment_type"
                                                        value="all" checked>
                                                    <span class="form-check-label">All</span>
                                                </label>
                                                @foreach ($categories as $ct)
                                                    <label class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="payment_type"
                                                            value="{{ $ct->id }}">
                                                        <span class="form-check-label">{{ $ct->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button id="resetButton" class="btn btn-sm btn-light me-2">Reset</button>
                                            <button id="applyButton" class="btn btn-sm btn-primary">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>USERNAME</th>
                                            <th>FOLLOWER</th>
                                            <th>AVG VIEWS</th>
                                            <th>LABEL</th>
                                            <th>NOMOR</th>
                                            <th>CHAT</th>
                                            <th>CATATAN</th>
                                            <th>FILE</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-kol"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center">
                                <div id="paginationInfo"> Showing Entries </div>
                                <div class="ms-auto">
                                    <nav aria-label="Page navigation" class="pagination-style-4">
                                        <ul class="pagination mb-0" id="paginationLinks"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="viewFileModal" tabindex="-1" aria-labelledby="viewFileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewFileModalLabel">Lihat File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fileViewer"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchUsername" tabindex="-1" aria-labelledby="searchUsernameLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchUsernameLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kol.type_influencer.scrapeusername') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="unique_id" class="form-control" id="username"
                                placeholder="Masukkan Username">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.dropdown-menu', function(e) {
            e.stopPropagation();
        });
    </script>
    <script>
        $('#applyButton').on('click', function() {
            let selectedCategories = [];
            $('input[name="payment_type"]:checked').each(function() {
                selectedCategories.push($(this).val());
            });
            loadKol(selectedCategories);
            $('#dropdownMenuButton1').dropdown('toggle');
        });

        $('#resetButton').on('click', function() {
            $('input[name="payment_type"]').prop('checked', false);
            loadKol(['all']);
            $('#dropdownMenuButton1').dropdown('toggle');
        });

        let currentTier = 'all';

        function loadKol(tier, page = 1) {
            currentTier = tier;
            const search = $('#searchInput').val();

            // Kirim array tier yang dipilih
            const url =
                `/kol/master/load-list?page=${page}&search=${search}&tier=${Array.isArray(tier) ? tier.join(',') : tier}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolMaster) {
                    let assignCategories = kolMaster.assign_category?.length ?
                        kolMaster.assign_category.map(category =>
                            `<span class="badge bg-secondary">${category.name}</span>`
                        ).join(' ') : 'No Category';

                    let statusClass = '';
                    let statusLabel = kolMaster.status_call || 'Unknown';

                    switch (kolMaster.status_call) {
                        case 'pending':
                            statusClass = 'badge bg-warning';
                            break;
                        case 'response':
                            statusClass = 'badge bg-success';
                            break;
                        case 'no_response':
                            statusClass = 'badge bg-danger';
                            break;
                        default:
                            statusClass = 'badge bg-secondary';
                    }

                    rows += `
            <tr>
                <td>${kolMaster.unique_id || 'No ID'} <a href="https://tiktok.com/@${kolMaster.unique_id}" target="_blank"><i class="fa fa-link"></i></a></td>
                <td>
                    ${kolMaster.follower || 'No Data'}
                    <p>${assignCategories}</p>
                </td>
                <td>${kolMaster.avg_views || 'No Data'}</td>
                <td><span class="${statusClass}">${statusLabel}</span></td>
                <td class="editable" data-id="${kolMaster.id}" data-field="whatsapp_number">${kolMaster.whatsapp_number || 'No Number'}</td>
                <td><a href="https://wa.me/${kolMaster.whatsapp_number}" class="chat-link" target="_blank">Chat Sekarang</a></td>
                <td class="editable" data-id="${kolMaster.id}" data-field="notes">${kolMaster.notes || 'No Notes'}</td>
                <td class="editable" data-id="${kolMaster.id}" data-field="file_url">
                    ${kolMaster.file_url ? `<a href="javascript:void(0);" class="btn btn-secondary btn-sm view-file" data-file="${kolMaster.file_url}" data-bs-toggle="modal" data-bs-target="#viewFileModal">Lihat File</a>` : 'Belum Ada File'}
                </td>
                <td><button class="btn btn-sm btn-primary edit-btn" onclick="redirectToEdit(${kolMaster.id})">Edit</button></td>
            </tr>`;
                });

                $('#table-kol').html(rows);
                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}"><a class="page-link" href="javascript:void(0);" onclick="loadKol('${tier}', ${pageNumber})">${link.label}</a></li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(`Showing ${data.from} to ${data.to} of ${data.total} entries`);
                $('#kol-title').html(`KOL ${tier === 'all' ? 'ALL' : tier.toUpperCase()}`);
            });
        }

        function formatToIndonesianNumber(number) {
            number = number.replace(/\D/g, '');
            if (number.startsWith('0')) {
                number = '62' + number.substring(1);
            }

            return number;
        }

        $(document).on('dblclick', '.editable', function() {
            const $cell = $(this);
            const field = $cell.data('field');
            const id = $cell.data('id');
            const originalValue = $cell.text().trim();

            if ($cell.find('input').length > 0 || $cell.find('button').length > 0) return;

            if (field === 'file_url') {
                $cell.html(`
                    <input type="file" class="form-control" />
                    <button class="btn btn-success btn-sm save-edit">Save</button>
                    <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                `);
            } else {
                $cell.html(`
                    <input type="text" class="form-control" value="${originalValue}" />
                    <button class="btn btn-success btn-sm save-edit">Save</button>
                    <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                `);
            }

            $cell.find('.save-edit').on('click', function() {
                let newValue = field === 'file_url' ? $cell.find('input[type="file"]')[0].files[0] : $cell
                    .find('input').val();

                if (field === 'whatsapp_number') {
                    newValue = formatToIndonesianNumber(newValue);
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('id', id);
                formData.append('field', field);
                if (field === 'file_url') {
                    formData.append('file', newValue);
                } else {
                    formData.append('value', newValue);
                }

                $.ajax({
                    url: `/kol/master/update-field`,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            if (field === 'file_url') {
                                const displayValue =
                                    `<a href="javascript:void(0);" class="btn btn-secondary btn-sm view-file" data-file="${response.newValue}" data-bs-toggle="modal" data-bs-target="#viewFileModal">Lihat File</a>`;
                                $cell.html(displayValue);
                            } else if (field === 'whatsapp_number') {
                                const displayValue = response.newValue || 'No Data';
                                $cell.html(displayValue);

                                const formattedNumber = formatToIndonesianNumber(response
                                    .newValue);
                                $cell.next().find('a.chat-link').attr('href',
                                    `https://wa.me/${formattedNumber}`);
                            } else {
                                $cell.html(response.newValue || 'No Data');
                            }
                        } else {
                            alert('Gagal menyimpan perubahan');
                            $cell.html(originalValue);
                        }
                    }
                });
            });

            $cell.find('.cancel-edit').on('click', function() {
                $cell.html(originalValue);
            });
        });

        $(document).on('click', '.view-file', function() {
            const fileUrl = $(this).data('file');
            const fileExtension = fileUrl.split('.').pop().toLowerCase();
            let fileViewerContent = '';

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                fileViewerContent = `<img src="${fileUrl}" class="img-fluid" alt="file">`;
            } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
                fileViewerContent =
                    `<video controls class="w-100"><source src="${fileUrl}" type="video/${fileExtension}"></video>`;
            } else {
                fileViewerContent = `<iframe src="${fileUrl}" class="w-100" style="height: 500px;"></iframe>`;
            }

            $('#fileViewer').html(fileViewerContent);
        });

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                loadKol(currentTier);
            });
            loadKol('all');
        });
    </script>
    <script>
        function redirectToEdit(id) {
            const url = `/kol/type-influencer/edit/${id}`;
            window.location.href = url;
        }
    </script>
@endsection
