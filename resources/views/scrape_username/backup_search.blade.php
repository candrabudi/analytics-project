@extends('layouts.app')

@section('content')
    <style>
        .hidden {
            display: none;
        }

        .card-header form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .card-header input.form-control {
            flex-grow: 1;
            padding: 8px;
        }

        .card-header button {
            white-space: nowrap;
        }

        .hidden {
            display: none;
        }
    </style>
    <div class="row">
        <div class="container">
            <div class="card border-danger mb-3">
                <div class="card-header d-flex align-items-center">
                    <span class="badge bg-danger me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path d="M8 1.5a6.5 6.5 0 1 0 0 13 6.5 6.5 0 0 0 0-13M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8"></path>
                            <path
                                d="M5.5 6.65c0-.635.515-1.15 1.15-1.15H9.6c.635 0 1.15.515 1.15 1.15V9.6a1.15 1.15 0 0 1-1.15 1.15H6.65A1.15 1.15 0 0 1 5.5 9.6zM7 7v2.25h2.25V7z">
                            </path>
                        </svg>
                        Aborted
                    </span>
                    <p class="mb-0">The Actor process was aborted. You can resurrect it to continue where you left off.
                    </p>
                    <a href="#" class="ms-auto text-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M4.855.708c.5-.896 1.79-.896 2.29 0l4.675 8.35a1.312 1.312 0 0 1-1.146 1.955H1.33A1.313 1.313 0 0 1 .183 9.058zM6 7a1 1 0 0 0 1-1V4a1 1 0 1 0-2 0v2a1 1 0 0 0 1 1m0 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2">
                            </path>
                        </svg>
                        Report issue
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <h6>RESULTS</h6>
                            <a href="/actors/runs/uAn6RvNjB9qdbUDT7#output">73</a>
                        </div>
                        <div class="col">
                            <h6>REQUESTS</h6>
                            <a href="/actors/runs/uAn6RvNjB9qdbUDT7#request-queue">19 of 22 handled</a>
                        </div>
                        <div class="col">
                            <h6>Price</h6>
                            <span>$0.292</span>
                        </div>
                        <div class="col">
                            <h6>STARTED</h6>
                            <abbr title="2025-01-03T15:13:44.165Z">2025-01-03 22:13</abbr>
                        </div>
                        <div class="col">
                            <h6>DURATION</h6>
                            <abbr title="1 minute 25 seconds">1 m 25 s</abbr>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary">More details</button>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">SCRAP USERNAME</h5>
                    <form action="" class="d-flex gap-2 w-100">
                        <input class="form-control form-control-sm" type="text" placeholder="Cari data disini.."
                            aria-label="Cari data" id="keyword-input">
                        <button type="button" class="btn btn-primary" id="search-button">Cari Data</button>
                        <button type="button" class="btn btn-secondary hidden" id="stop-button">Stop</button>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">USER ID</th>
                                    <th scope="col">NAMA</th>
                                    <th scope="col">username</th>
                                    <th scope="col">Followers</th>
                                    <th scope="col">Total Videos</th>
                                </tr>
                            </thead>
                            <tbody id="account-data-body">
                                <tr id="loading-row" class="hidden">
                                    <td colspan="10" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p>Loading data, please wait...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer>
            let insertedCount = 0;
            let stopRequested = false;
            const totalResultsDesired = 6000;
            let currentCursor = 0;

            const searchButton = document.getElementById('search-button');
            const stopButton = document.getElementById('stop-button');
            const loadingRow = document.getElementById('loading-row');
            const accountDataBody = document.getElementById('account-data-body');
            const insertedCountText = document.getElementById('inserted-count');

            stopButton.classList.add('hidden');
            loadingRow.classList.add('hidden');

            async function startSearch() {
                const keyword = document.getElementById('keyword-input').value;
                if (!keyword) return;

                stopRequested = false;
                insertedCount = 0;
                searchButton.disabled = true;
                stopButton.classList.remove('hidden');
                insertedCountText.innerText = `Accounts Inserted: ${insertedCount}`;
                accountDataBody.innerHTML = '';
                loadingRow.classList.remove('hidden');

                const response = await fetch('/api/insertSearchData', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        keyword: keyword
                    })
                });

                const result = await response.json();
                if (result.status === 'success') {
                    const searchId = result.data.tiktok_search_id;
                    currentCursor = 0;
                    await fetchTiktokData(searchId, keyword);
                } else {
                    alert(result.message);
                }

                stopSearch();
            }

            async function fetchTiktokData(searchId, keyword) {
                if (insertedCount >= totalResultsDesired || stopRequested) {
                    stopSearch();
                    return;
                }

                const response = await fetch(
                    `https://tiktok-download-video1.p.rapidapi.com/feedSearch?keywords=${encodeURIComponent(keyword)}&count=30&cursor=${currentCursor}&region=ID&publish_time=0&sort_type=0`, {
                        method: 'GET',
                        headers: {
                            'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                            'x-rapidapi-key': 'fd95897d1fmsh16cd082ff4db73ep145e8fjsn5adfe90752d1'
                        }
                    });

                const result = await response.json();
                if (result.code === 0) {
                    const videos = result.data.videos;
                    if (videos.length === 0) {
                        console.log('No more videos available.');
                        return;
                    }

                    for (const video of videos) {
                        if (stopRequested) {
                            console.log('Process stopped by user.');
                            stopSearch();
                            return;
                        }
                        const accountData = {
                            tiktok_search_id: searchId,
                            author_id: video.author.id,
                            nickname: video.author.nickname,
                            verified: video.author.verified ? 1 : 0,
                            unique_id: video.author.unique_id,
                            avatar: video.author.avatar,
                            followers: video.author.followers || 0,
                            following: video.author.following || 0,
                            likes: video.author.likes || 0,
                            total_video: video.total_video || 0
                        };

                        const insertResult = await insertAccount(accountData);
                        if (insertResult) {
                            insertedCount++;
                            insertedCountText.innerText = `Accounts Inserted: ${insertedCount}`;
                            const row = `<tr>
                                <td>${insertedCount}</td>
                                <td>${accountData.author_id}</td>
                                <td>${accountData.nickname}</td>
                                <td>${accountData.verified ? 'Ya' : 'Tidak'}</td>
                                <td>${accountData.unique_id}</td>
                                <td><img src="${accountData.avatar}" alt="Avatar" style="width: 40px; height: 40px;"/></td>
                                <td>${accountData.followers}</td>
                                <td>${accountData.following}</td>
                                <td>${accountData.likes}</td>
                                <td>${accountData.total_video}</td>
                            </tr>`;
                            accountDataBody.insertAdjacentHTML('beforeend', row);
                        }
                    }

                    if (!stopRequested && result.data.hasMore) {
                        currentCursor += 30;
                        await fetchTiktokData(searchId, keyword);
                    } else {
                        stopSearch();
                    }
                } else {
                    console.error('Failed to fetch videos from TikTok API:', result.message);
                    stopSearch();
                }
            }

            async function insertAccount(accountData) {
                const response = await fetch('/api/insertAccountData', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(accountData)
                });

                const result = await response.json();
                return result.status === 'success';
            }

            function stopSearch() {
                stopRequested = true;
                searchButton.disabled = false;
                stopButton.classList.add('hidden');
                loadingRow.classList.add('hidden');

                Swal.fire({
                    icon: 'info',
                    title: 'Proses Dihentikan',
                    text: `Proses berhenti. Total akun yang berhasil di-insert: ${insertedCount}`,
                });
            }

            stopButton.addEventListener('click', stopSearch);
            searchButton.addEventListener('click', startSearch);
        </script>
    @endpush
@endsection
