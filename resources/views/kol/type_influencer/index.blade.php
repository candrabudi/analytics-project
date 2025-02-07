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
                        <div class="d-flex flex-wrap justify-content-start w-100">
                            @foreach ($categories as $ct)
                                <div class="category-item" onclick="toggleFilter('{{ $ct->id }}', this)">
                                    <div class="category-content">
                                        <div>
                                            <div class="fs-15 fw-medium text-dark">{{ $ct->name }}</div>
                                            <p class="mb-0 text-muted fs-12">{{ $ct->description }}</p>
                                        </div>
                                        <div class="lux-checkbox">
                                            <input type="checkbox" id="{{ $ct->id }}" class="filter-checkbox" style="display: none;" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <style>
                            .category-item {
                                width: 150px;
                                margin-right: 10px;
                                margin-bottom: 10px;
                                background-color: #ffffff;
                                border: 1px solid #ddd;
                                border-radius: 4px;
                                cursor: pointer;
                                transition: all 0.2s ease;
                            }
                        
                            .category-item:hover {
                                border-color: #007bff; /* Highlight border color on hover */
                            }
                        
                            .category-item.active {
                                background-color: #007bff; /* Background color when active */
                                color: white; /* Change text color when active */
                                border-color: #007bff; /* Border color when active */
                            }
                        
                            .category-content {
                                padding: 10px;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                            }
                        
                            .lux-checkbox input[type="checkbox"]:checked + label {
                                background-color: #007bff;
                                border-color: #007bff;
                            }
                        
                            .lux-checkbox input[type="checkbox"] {
                                display: inline-block; /* Visible checkbox */
                            }
                        </style>                        
                        
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
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>USERNAME</th>
                                            <th>FOLLOWER</th>
                                            <th>AVG VIEWS</th>
                                            <th>CPV</th>
                                            <th>LABEL</th>
                                            <th>NOMOR</th>
                                            <th>CATATAN</th>
                                            <th width="140">RATECARD</th>
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

    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">Edit Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" rows="5"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveNoteBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleFilter(filterType, cardElement) {
            var checkbox = document.getElementById(filterType);
            checkbox.checked = !checkbox.checked; // Toggle the checkbox state
    
            // Apply filter logic
            applyFilter();
    
            // Toggle active class on card
            if (checkbox.checked) {
                cardElement.classList.add('active'); // Add active class when checked
            } else {
                cardElement.classList.remove('active'); // Remove active class when unchecked
            }
        }
    
        function applyFilter() {
            var filters = {};
            document.querySelectorAll('.filter-checkbox').forEach(function(checkbox) {
                filters[checkbox.id] = checkbox.checked;
            });
            console.log(filters);
        }
    </script>

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

        let selectedCategories = [];

        function toggleFilter(filterType, cardElement) {
            var checkbox = document.getElementById(filterType);
            checkbox.checked = !checkbox.checked;

            // Toggle active class on card
            if (checkbox.checked) {
                cardElement.classList.add('active');
                selectedCategories.push(filterType); // Add category to selected filters
            } else {
                cardElement.classList.remove('active');
                selectedCategories = selectedCategories.filter(category => category !==
                filterType); // Remove category from selected filters
            }

            applyFilter();
        }

        function applyFilter() {
            // Apply filter logic
            loadKol(currentTier, 1, selectedCategories); // Pass selected categories to the loadKol function
        }

        let currentTier = 'all';

        function loadKol(tier, page = 1, categories = []) {
            currentTier = tier;
            const search = $('#searchInput').val();
            const url =
                `/kol/master/load-list?page=${page}&search=${search}&tier=${Array.isArray(tier) ? tier.join(',') : tier}&categories=${categories.join(',')}`;

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

                    let whatsappContent = kolMaster.whatsapp_number ?
                        `${kolMaster.whatsapp_number} <a href="https://wa.me/${kolMaster.whatsapp_number}" class="chat-link" target="_blank">
                        <i class="fab fa-whatsapp" style="color: green; font-size: 20px;"></i>
                    </a>` : '-';

                    rows += `
                    <tr>
                        <td>${kolMaster.unique_id || 'No ID'} <a href="https://tiktok.com/@${kolMaster.unique_id}" target="_blank"><i class="fa fa-link"></i></a></td>
                        <td>
                            ${kolMaster.follower || 'No Data'}
                            <p>${assignCategories}</p>
                        </td>
                        <td data-id="${kolMaster.id}" data-field="avg_views">${kolMaster.avg_views || 'No Data'}</td>
                        <td data-id="${kolMaster.id}" data-field="cpv">
                            ${(kolMaster.ratecard_kol && kolMaster.avg_views) ? (kolMaster.avg_views / kolMaster.ratecard_kol).toFixed(2) : '-'}
                        </td>
                        <td><span class="${statusClass}">${statusLabel}</span></td>
                        <td class="editable" data-id="${kolMaster.id}" data-field="whatsapp_number">${whatsappContent}</td>
                        <td>
                            <button class="btn btn-sm btn-primary note-edit-btn" data-id="${kolMaster.id}" data-notes="${kolMaster.notes || ''}">
                                Edit Notes
                            </button>
                        </td>
                        <td class="editable" data-id="${kolMaster.id}" data-field="ratecard_kol">
                            ${kolMaster.ratecard_kol ? formatCurrency(kolMaster.ratecard_kol) : '-'}
                        </td>
                        <td data-id="${kolMaster.id}" data-field="file_url">
                            ${kolMaster.file_url ? `<a href="javascript:void(0);" class="btn btn-secondary btn-sm view-file" data-file="${kolMaster.file_url}" data-bs-toggle="modal" data-bs-target="#viewFileModal">Lihat File</a>` : '-'}
                        </td>
                        <td><button class="btn btn-sm btn-primary edit-btn" onclick="redirectToEdit(${kolMaster.id})">Edit</button></td>
                    </tr>`;
                });

                $('#table-kol').html(rows);
                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}"><a class="page-link" href="javascript:void(0);" onclick="loadKol('${tier}', ${pageNumber}, ${JSON.stringify(categories)})">${link.label}</a></li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(`Showing ${data.from} to ${data.to} of ${data.total} entries`);
                $('#kol-title').html(`KOL ${tier === 'all' ? 'ALL' : tier.toUpperCase()}`);
            });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            }).format(amount);
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

            console.log(`Original value for field ${field}: ${originalValue}`);

            if (field === 'ratecard_kol') {
                const unformattedValue = parseFloat(originalValue.replace(/\D/g, '')) || 0;
                $cell.html(`
                    <input type="number" style="width: 120px;" class="form-control form-control-sm" value="${unformattedValue}" />
                    <button class="btn btn-success btn-sm save-edit">Save</button>
                    <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                `);

                $cell.find('.save-edit').on('click', function() {
                    const newValue = $cell.find('input').val();
                    console.log(`New value for ratecard_kol: ${newValue}`);

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('id', id);
                    formData.append('field', field);
                    formData.append('value', newValue);

                    $.ajax({
                        url: `/kol/master/update-field`,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log('AJAX Response:', response);

                            if (response.success) {
                                const formattedValue = formatCurrency(response.newValue);
                                $cell.html(formattedValue || '-');

                                const avgViews = parseFloat($(
                                        `td[data-id="${id}"][data-field="avg_views"]`)
                                    .text()) || 0;
                                const ratecardKol = parseFloat(response.newValue) || 0;
                                if (ratecardKol > 0) {
                                    const newCPV = (avgViews / ratecardKol).toFixed(2);
                                    console.log(`New CPV: ${newCPV}`);
                                    $(`td[data-id="${id}"][data-field="cpv"]`).html(newCPV);
                                } else {
                                    $(`td[data-id="${id}"][data-field="ratecard_kol"]`).html(
                                        '-');
                                }
                            } else {
                                alert('Failed to save changes');
                            }
                        }
                    });
                });

                $cell.find('.cancel-edit').on('click', function() {
                    $(`td[data-id="${id}"][data-field="ratecard_kol"]`).html('-');
                });
            } else {
                const originalHtml = $cell.html().trim();
                const originalValue = $cell.text().trim();

                if ($cell.find('input').length > 0 || $cell.find('button').length > 0) return;

                $cell.html(`
                    <input type="text" class="form-control form-control-sm" value="${originalValue}" style="width: 150px;" />
                    <button class="btn btn-success btn-sm save-edit">Save</button>
                    <button class="btn btn-secondary btn-sm cancel-edit">Cancel</button>
                `);

                $cell.find('.save-edit').on('click', function() {
                    let newValue = $cell.find('input').val();

                    if (field === 'whatsapp_number') {
                        newValue = formatToIndonesianNumber(newValue);
                    }

                    console.log(`New value for field ${field}: ${newValue}`);

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('id', id);
                    formData.append('field', field);
                    formData.append('value', newValue);

                    $.ajax({
                        url: `/kol/master/update-field`,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            console.log('AJAX Response:', response);

                            if (response.success) {
                                if (field === 'whatsapp_number') {
                                    const displayValue = response.newValue || '-';
                                    if (response.newValue) {
                                        const formattedNumber = formatToIndonesianNumber(
                                            response.newValue);
                                        $cell.html(`${formattedNumber} <a href="https://wa.me/${formattedNumber}" class="chat-link" target="_blank">
                                    <i class="fab fa-whatsapp" style="color: green; font-size: 20px;"></i></a>`);
                                    } else {
                                        $cell.html('-');
                                    }
                                } else {
                                    $cell.html(response.newValue || 'No Data');
                                }

                                const avgViews = parseFloat(response.avg_views) || 0;
                                const ratecardKol = parseFloat(response.newValue) || 0;

                                if (ratecardKol > 0) {
                                    const newCPV = (avgViews / ratecardKol).toFixed(2);
                                    console.log(`New CPV: ${newCPV}`);
                                    $(`td[data-id="${id}"][data-field="cpv"]`).html(newCPV);
                                } else {
                                    $(`td[data-id="${id}"][data-field="cpv"]`).html('-');
                                }

                            } else {
                                alert('Failed to save changes');
                                $cell.html(originalHtml);
                            }
                        }
                    });
                });

                $cell.find('.cancel-edit').on('click', function() {
                    $cell.html(originalHtml);
                });
            }
        });


        $(document).on('click', '.note-edit-btn', function() {
            const id = $(this).data('id');
            const currentNotes = $(this).data('notes');

            $('#noteModal').find('textarea').val(currentNotes);
            $('#noteModal').find('#saveNoteBtn').attr('data-id', id);
            $('#noteModal').modal('show');
        });

        $('#saveNoteBtn').on('click', function() {
            const id = $(this).data('id');
            const newNotes = $('#noteModal').find('textarea').val();

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('id', id);
            formData.append('field', 'notes');
            formData.append('value', newNotes);

            $.ajax({
                url: `/kol/master/update-field`,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $(`button[data-id="${id}"]`).data('notes', newNotes);
                        $('#noteModal').modal('hide');
                    } else {
                        alert('Gagal menyimpan catatan');
                    }
                }
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
