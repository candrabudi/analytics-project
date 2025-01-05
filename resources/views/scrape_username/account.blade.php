@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Profile Akun TikTok</h1>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12">
            <div class="card custom-card profile-card">
                <div class="card-body p-4 pb-0 position-relative">
                    <span class="avatar avatar-xxl avatar-rounded bg-info online">
                        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="">
                    </span>
                    <div class="mt-4 mb-3 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                        <div>
                            <h5 class="fw-semibold mb-1">{{ $tiktokAccount->nickname }}</h5>
                            <span class="d-block fw-medium text-muted mb-1">{{ $tiktokAccount->author_id }}</span>
                        </div>
                        <div class="d-flex mb-0 flex-wrap gap-4">
                            <div class="p-3 bg-light rounded d-flex align-items-center border gap-3">
                                <div class="main-card-icon primary">
                                    <div
                                        class="avatar avatar-lg bg-primary-transparent border border-primary border-opacity-10">
                                        <div class="avatar avatar-sm svg-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                                                <rect width="256" height="256" fill="none"></rect>
                                                <path
                                                    d="M208,40H48a8,8,0,0,0-8,8V208a8,8,0,0,0,8,8H208a8,8,0,0,0,8-8V48A8,8,0,0,0,208,40ZM57.78,216A72,72,0,0,1,128,160a40,40,0,1,1,40-40,40,40,0,0,1-40,40,72,72,0,0,1,70.22,56Z"
                                                    opacity="0.2"></path>
                                                <circle cx="128" cy="120" r="40" fill="none"
                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="16"></circle>
                                                <rect x="40" y="40" width="176" height="176" rx="8"
                                                    fill="none" stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="16"></rect>
                                                <path d="M57.78,216a72,72,0,0,1,140.44,0" fill="none"
                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="16"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="fw-semibold fs-20 mb-0">{{ $tiktokAccount->following ?? 0 }}</p>
                                    <p class="mb-0 fs-12 text-muted fw-medium">Followings</p>
                                </div>
                            </div>
                            <div class="p-3 bg-light rounded d-flex align-items-center border gap-3">
                                <div class="main-card-icon secondary">
                                    <div
                                        class="avatar avatar-lg bg-secondary-transparent border border-secondary border-opacity-10">
                                        <div class="avatar avatar-sm svg-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                                                <rect width="256" height="256" fill="none"></rect>
                                                <circle cx="84" cy="108" r="52" opacity="0.2"></circle>
                                                <path d="M10.23,200a88,88,0,0,1,147.54,0" fill="none"
                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="16"></path>
                                                <path d="M172,160a87.93,87.93,0,0,1,73.77,40" fill="none"
                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="16"></path>
                                                <circle cx="84" cy="108" r="52" fill="none"
                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="16"></circle>
                                                <path d="M152.69,59.7A52,52,0,1,1,172,160" fill="none"
                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="16"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="fw-semibold fs-20 mb-0">{{ $tiktokAccount->follower }}</p>
                                    <p class="mb-0 fs-12 text-muted fw-medium">Followers</p>
                                </div>
                            </div>
                            <div class="p-3 bg-light rounded d-flex align-items-center border gap-2">
                                <div class="main-card-icon success">
                                    <div
                                        class="avatar avatar-lg bg-success-transparent border border-success border-opacity-10">
                                        <div class="avatar avatar-sm svg-white">
                                            <i class="ri-folder-video-line" style="font-size: 20px;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="fw-semibold fs-20 mb-0">{{ $tiktokAccount->total_video }}</p>
                                    <p class="mb-0 fs-12 text-muted fw-medium">Total Video</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATA VIDEO AKUN</div>
                    <div class="d-flex flex-wrap gap-2">
                        <div>
                            <input class="form-control form-control-sm" id="searchInput" type="text"
                                placeholder="Search Here" aria-label=".form-control-sm example">
                        </div>
                        <div class="dropdown">
                            <a href="/scrape-username/account/video/{{ $tiktokAccount->tiktok_account_id }}"
                                class="btn btn-primary btn-sm btn-wave waves-effect waves-light" id="loadVideos">Ambil
                                data video</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">Video ID</th>
                                    <th scope="col">Total Comment</th>
                                    <th scope="col">Total Penonton</th>
                                    <th scope="col">Total Disukai</th>
                                    <th scope="col">Total Share</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Data will be loaded here dynamically -->
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
                                    <!-- Pagination links will be loaded here -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadVideos(page = 1) {
            const search = $('#searchInput').val();
            const url =
                `/scrape-username/account/video/load/{{ $tiktokAccount->tiktok_account_id }}?page=${page}&search=${search}`;

            $.get(url, function(data) {
                let rows = '';
                data.data.forEach(function(video) {
                    rows += `
                <tr>
                    <td>${video.video_id}</td>
                    <td>${video.comment_count}</td>
                    <td>${video.play_count}</td>
                    <td>${video.digg_count}</td>
                    <td>${video.share_count}</td>
                    <td><button class="btn btn-sm btn-primary">Aksi</button></td>
                </tr>`;
                });

                $('#tableBody').html(rows);

                // Update pagination
                const paginationLinks = data.links.map(link => {
                    let pageNumber = link.url ? new URL(link.url).searchParams.get('page') : 1;
                    return `<li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadVideos(${pageNumber})">${link.label}</a>
                    </li>`;
                }).join('');
                $('#paginationLinks').html(paginationLinks);
                $('#paginationInfo').html(
                    `Showing ${data.from} to ${data.to} of ${data.total} entries`);
            });
        }

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                loadVideos();
            });

            loadVideos();
        });
    </script>
@endsection
