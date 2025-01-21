@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <ul class="nav nav-tabs nav-tabs-header mb-0" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#nano-tab" aria-selected="false" tabindex="-1">
                                        NANO
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#micro-tab" aria-selected="false" tabindex="-1">
                                        MICRO
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#macro-tab" aria-selected="false" tabindex="-1">
                                        MACRO
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#mega-tab" aria-selected="false" tabindex="-1">
                                        MEGA
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="tab-content">
                @include('kol.type_influencer.components.nano')
                @include('kol.type_influencer.components.micro')
                @include('kol.type_influencer.components.macro')
                @include('kol.type_influencer.components.mega')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function loadKolNano(page = 1) {
            const search = $('#searchInputNano').val();
            const url = `/kol/master/load-list?page=${page}&search=${search}&tier=nano`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolMaster) {
                    rows += `
                <tr>
                    <td class="text-center"><input class="form-check-input" type="checkbox" id="checkboxNoLabeljob2" value="" aria-label="..."></td>
                    <td>${kolMaster.unique_id}</td>
                    <td>${kolMaster.nickname}</td>
                    <td>${kolMaster.follower}</td>
                    <td>${kolMaster.following}</td>
                    <td>${kolMaster.like}</td>
                    <td>${kolMaster.total_video}</td>
                    <td>
                        <span class="badge ${getBadgeClass(kolMaster.tier)}">${kolMaster.tier}</span>
                    </td>
                </tr>`;
                });

                $('#table-nano').html(rows);

                const paginationNanoLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadKolNano(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationNanoLinks').html(paginationNanoLinks);
                $('#paginationNanoInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        function getBadgeClass(tier) {
            switch (tier.toLowerCase()) {
                case 'nano':
                    return 'bg-secondary-transparent';
                case 'micro':
                    return 'bg-primary-transparent';
                case 'macro':
                    return 'bg-warning-transparent';
                case 'mega':
                    return 'bg-danger-transparent';
                default:
                    return 'light';
            }
        }

        $(document).ready(function() {
            $('#searchInputNano').on('keyup', function() {
                loadKolNano();
            });

            loadKolNano();
        });
    </script>
    
    
    <script>
        function loadKolMicro(page = 1) {
            const search = $('#searchInputMicro').val();
            const url = `/kol/master/load-list?page=${page}&search=${search}&tier=micro`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolMaster) {
                    rows += `
                <tr>
                    <td class="text-center"><input class="form-check-input" type="checkbox" id="checkboxNoLabeljob2" value="" aria-label="..."></td>
                    <td>${kolMaster.unique_id}</td>
                    <td>${kolMaster.nickname}</td>
                    <td>${kolMaster.follower}</td>
                    <td>${kolMaster.following}</td>
                    <td>${kolMaster.like}</td>
                    <td>${kolMaster.total_video}</td>
                    <td>
                        <span class="badge ${getBadgeClass(kolMaster.tier)}">${kolMaster.tier}</span>
                    </td>
                </tr>`;
                });

                $('#table-micro').html(rows);

                const paginationMicroLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadKolMicro(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationMicroLinks').html(paginationMicroLinks);
                $('#paginationMicroInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        function getBadgeClass(tier) {
            switch (tier.toLowerCase()) {
                case 'nano':
                    return 'bg-secondary-transparent';
                case 'micro':
                    return 'bg-primary-transparent';
                case 'macro':
                    return 'bg-warning-transparent';
                case 'mega':
                    return 'bg-danger-transparent';
                default:
                    return 'light';
            }
        }

        $(document).ready(function() {
            $('#searchInputMicro').on('keyup', function() {
                loadKolMicro();
            });

            loadKolMicro();
        });
    </script>
    
    
    <script>
        function loadKolMacro(page = 1) {
            const search = $('#searchInputMacro').val();
            const url = `/kol/master/load-list?page=${page}&search=${search}&tier=macro`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolMaster) {
                    rows += `
                <tr>
                    <td class="text-center"><input class="form-check-input" type="checkbox" id="checkboxNoLabeljob2" value="" aria-label="..."></td>
                    <td>${kolMaster.unique_id}</td>
                    <td>${kolMaster.nickname}</td>
                    <td>${kolMaster.follower}</td>
                    <td>${kolMaster.following}</td>
                    <td>${kolMaster.like}</td>
                    <td>${kolMaster.total_video}</td>
                    <td>
                        <span class="badge ${getBadgeClass(kolMaster.tier)}">${kolMaster.tier}</span>
                    </td>
                </tr>`;
                });

                $('#table-macro').html(rows);

                const paginationMacroLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadKolMacro(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationMacroLinks').html(paginationMacroLinks);
                $('#paginationMacroInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        function getBadgeClass(tier) {
            switch (tier.toLowerCase()) {
                case 'nano':
                    return 'bg-secondary-transparent';
                case 'micro':
                    return 'bg-primary-transparent';
                case 'macro':
                    return 'bg-warning-transparent';
                case 'mega':
                    return 'bg-danger-transparent';
                default:
                    return 'light';
            }
        }

        $(document).ready(function() {
            $('#searchInputMacro').on('keyup', function() {
                loadKolMacro();
            });

            loadKolMacro();
        });
    </script>
    
    
    <script>
        function loadKolMega(page = 1) {
            const search = $('#searchInputMega').val();
            const url = `/kol/master/load-list?page=${page}&search=${search}&tier=mega`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(kolMaster) {
                    rows += `
                <tr>
                    <td class="text-center"><input class="form-check-input" type="checkbox" id="checkboxNoLabeljob2" value="" aria-label="..."></td>
                    <td><a href="${kolMaster.link_account}" target="_blank">${kolMaster.unique_id}</a></td>
                    <td>${kolMaster.nickname}</td>
                    <td>${kolMaster.follower}</td>
                    <td>${kolMaster.following}</td>
                    <td>${kolMaster.like}</td>
                    <td>${kolMaster.total_video}</td>
                    <td>
                        <span class="badge ${getBadgeClass(kolMaster.tier)}">${kolMaster.tier}</span>
                    </td>
                </tr>`;
                });

                $('#table-mega').html(rows);

                const paginationMegaLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadKolMega(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationMegaLinks').html(paginationMegaLinks);
                $('#paginationMegaInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        function getBadgeClass(tier) {
            switch (tier.toLowerCase()) {
                case 'nano':
                    return 'bg-secondary-transparent';
                case 'micro':
                    return 'bg-primary-transparent';
                case 'macro':
                    return 'bg-warning-transparent';
                case 'mega':
                    return 'bg-danger-transparent';
                default:
                    return 'light';
            }
        }

        $(document).ready(function() {
            $('#searchInputMega').on('keyup', function() {
                loadKolMega();
            });

            loadKolMega();
        });
    </script>
@endsection
