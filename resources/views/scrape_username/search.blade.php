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
                        Informasi Data Scrape
                    </span>
                    <p class="mb-0">
                        <div id="loading-message" style="display: none;">Sedang memproses data...</div>
                    </p>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <h6>TOTAL HASIL DATA</h6>
                            <span id="results-count">0</span>
                        </div>
                        <div class="col">
                            <h6>PERMINTAAN</h6>
                            <span id="requests-handled">0</span> of <span id="requests-total">0</span> handled
                        </div>
                        <div class="col">
                            <h6>MULAI</h6>
                            <abbr id="started-time"></abbr>
                        </div>
                        <div class="col">
                            <h6>BERAKHIR</h6>
                            <abbr id="ended-time"></abbr>
                        </div>
                        <div class="col">
                            <h6>DURASI</h6>
                            <abbr id="duration"></abbr>
                        </div>
                    </div>
                    <div id="loading-message" style="display: none;">Processing data...</div>
                    <button type="button" id="abort-button" class="btn btn-danger" style="display: none;">Abort
                        Process</button>
                </div>


            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">SCRAPE USERNAME</h5>
                    <form action="" class="d-flex gap-2 w-100">
                        <input class="form-control form-control-sm" type="text" placeholder="Cari data disini.."
                            aria-label="Cari data" id="keyword-input">
                        <button type="button" id="start-button" class="btn btn-primary">Start Process</button>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">USER ID</th>
                                    <th scope="col">NAMA</th>
                                    <th scope="col">username</th>
                                    <th scope="col">Followers</th>
                                    <th scope="col">Total Videos</th>
                                    <th scope="col">Average</th>
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
        <script>
            let startTime;
            let intervalId;
            let requestsHandled = 0;
            let totalResults = 0;
            let isAborted = false;
            let cursor = 0;
            let hasMore = true;
            let searchId = null;
            let keyword = '';
            let resultsPerPage = 50;
            let currentPage = 1;
            let totalPages = 1;

            async function startProcess() {
                startTime = new Date();
                requestsHandled = 0;
                totalResults = 0;
                isAborted = false;
                cursor = 0;
                hasMore = true;
                currentPage = 1;

                keyword = document.getElementById('keyword-input').value;
                document.getElementById('results-count').textContent = '0';
                document.getElementById('requests-handled').textContent = '0';
                document.getElementById('requests-total').textContent = '0';
                document.getElementById('started-time').textContent = startTime.toLocaleString();
                document.getElementById('ended-time').textContent = '';
                document.getElementById('duration').textContent = '0 seconds';

                document.getElementById('loading-message').style.display = 'block';
                document.getElementById('abort-button').style.display = 'inline-block';

                intervalId = setInterval(updateDuration, 1000);

                try {
                    const response = await fetch('/scrape-username/search/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            keyword: keyword,
                            started_at: startTime.toISOString(),
                            results: totalResults,
                            requests_handled: requestsHandled,
                            status: 'process'
                        })
                    });

                    const data = await response.json();
                    searchId = data.data.id;
                    fetchTikTokData();
                } catch (error) {
                    console.error('Error starting search:', error);
                }
            }

            function updateDuration() {
                const currentTime = new Date();
                const duration = ((currentTime - startTime) / 1000).toFixed(2);
                document.getElementById('duration').textContent = `${duration} seconds`;
            }

            async function fetchTikTokData() {
                const perPage = 3;

                while (hasMore && !isAborted) {
                    try {
                        const response = await fetch(
                            `https://tiktok-download-video1.p.rapidapi.com/feedSearch?keywords=${encodeURIComponent(keyword)}&count=${perPage}&cursor=${cursor}&region=US&publish_time=0&sort_type=0`, {
                                method: 'GET',
                                headers: {
                                    'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                                    'x-rapidapi-key': {{ $setting->rapid_api_key }}
                                }
                            });

                        const data = await response.json();
                        const userInfoArray = [];

                        if (data.data && data.data.videos.length > 0) {
                            const resultsFromRequest = data.data.videos.length;
                            totalResults += resultsFromRequest;
                            requestsHandled++;
                            cursor = data.data.cursor;
                            hasMore = data.data.hasMore;

                            document.getElementById('results-count').textContent = totalResults;
                            document.getElementById('requests-handled').textContent = requestsHandled;
                            document.getElementById('requests-total').textContent = requestsHandled;

                            data.data.videos.forEach(video => {
                                fetch(`https://tiktok-download-video1.p.rapidapi.com/userInfo?user_id=${video.author.id}`, {
                                        method: 'GET',
                                        headers: {
                                            'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                                            'x-rapidapi-key': {{ $setting->rapid_api_key }}
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(userInfoData => {
                                        fetch(`https://tiktok-download-video1.p.rapidapi.com/userPublishVideo?user_id=${video.author.id}&count=10&cursor=0`, {
                                                method: 'GET',
                                                headers: {
                                                    'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                                                    'x-rapidapi-key': {{ $setting->rapid_api_key }}
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(publishedVideosData => {
                                                if (publishedVideosData.code === 0 && publishedVideosData
                                                    .data && publishedVideosData.data.videos) {
                                                    const totalPlayCount = publishedVideosData.data.videos
                                                        .reduce((sum, video) => {
                                                            return sum + (video.play_count || 0);
                                                        }, 0);

                                                    const existingAuthor = userInfoArray.find(userInfo =>
                                                        userInfo.author_id === video.author.id);
                                                    if (!existingAuthor) {
                                                        userInfoArray.push({
                                                            author_id: video.author.id,
                                                            unique_id: userInfoData.data.user
                                                                .uniqueId,
                                                            nickname: userInfoData.data.user
                                                                .nickname,
                                                            follower: userInfoData.data.stats
                                                                .followerCount,
                                                            total_video: userInfoData.data.stats
                                                                .videoCount,
                                                            average: totalPlayCount
                                                        });

                                                        fetch(`/scrape-username/insert/account`, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': document
                                                                    .querySelector(
                                                                        'meta[name="csrf-token"]')
                                                                    .getAttribute(
                                                                        'content')
                                                            },
                                                            body: JSON.stringify({
                                                                tiktok_search_id: searchId,
                                                                author_id: video.author.id,
                                                                unique_id: userInfoData.data
                                                                    .user.uniqueId,
                                                                nickname: userInfoData.data
                                                                    .user.nickname,
                                                                follower: userInfoData.data
                                                                    .stats
                                                                    .followerCount,
                                                                total_video: userInfoData
                                                                    .data.stats
                                                                    .videoCount,
                                                                average: totalPlayCount
                                                            })
                                                        });

                                                        renderUserInfoTable([{
                                                            author_id: video.author.id,
                                                            unique_id: userInfoData.data.user
                                                                .uniqueId,
                                                            nickname: userInfoData.data.user
                                                                .nickname,
                                                            follower: userInfoData.data.stats
                                                                .followerCount,
                                                            total_video: userInfoData.data.stats
                                                                .videoCount,
                                                            average: totalPlayCount
                                                        }]);
                                                    }
                                                }
                                            })
                                            .catch(error => console.error(
                                                'Error fetching user published videos API:', error));
                                    })
                                    .catch(error => console.error('Error fetching user info API:', error));
                            });
                        }

                        if (totalResults > resultsPerPage * currentPage) {
                            totalPages = Math.ceil(totalResults / resultsPerPage);
                            renderPagination();
                        }

                        if (!hasMore || isAborted) {
                            finishProcess();
                            break;
                        }

                        await new Promise(resolve => setTimeout(resolve, 1000));

                    } catch (error) {
                        console.error('Error fetching TikTok data:', error);
                        requestsHandled++;
                        document.getElementById('requests-handled').textContent = requestsHandled;

                        if (isAborted || !hasMore) {
                            finishProcess();
                            break;
                        }
                    }
                }
            }


            function renderUserInfoTable(userInfoArray) {
                const accountDataBody = document.getElementById('account-data-body');
                userInfoArray.forEach((userInfo, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${userInfo.author_id}</td>
                        <td>${userInfo.nickname}</td>
                        <td>${userInfo.unique_id}</td>
                        <td>${userInfo.follower}</td>
                        <td>${userInfo.total_video}</td>
                        <td>${userInfo.average}</td>
                    `;
                    accountDataBody.appendChild(newRow);
                });
            }

            function renderPagination() {
                const paginationContainer = document.getElementById('pagination-container');
                paginationContainer.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    pageButton.onclick = () => changePage(i);
                    paginationContainer.appendChild(pageButton);
                }
            }

            function changePage(page) {
                currentPage = page;
                document.getElementById('account-data-body').innerHTML = '';
                fetchTikTokData();
            }

            async function finishProcess() {
                clearInterval(intervalId);
                document.getElementById('loading-message').style.display = 'none';
                document.getElementById('abort-button').style.display = 'none';

                const endTime = new Date();
                const duration = ((endTime - startTime) / 1000).toFixed(2);
                document.getElementById('ended-time').textContent = endTime.toLocaleString();
                document.getElementById('duration').textContent = `${duration} seconds`;

                try {
                    await fetch(`/scrape-username/search/update/${searchId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            results: totalResults,
                            requests_handled: requestsHandled,
                            requests_total: requestsHandled,
                            duration_seconds: parseInt(duration),
                            status: isAborted ? 'abort' : 'success'
                        })
                    });
                    console.log('Search data updated successfully.');
                } catch (error) {
                    console.error('Error updating search data:', error);
                }
            }

            function abortProcess() {
                isAborted = true;
                clearInterval(intervalId);
                document.getElementById('loading-message').style.display = 'none';
                document.getElementById('abort-button').style.display = 'none';
                finishProcess();
            }

            document.getElementById('start-button').addEventListener('click', startProcess);
            document.getElementById('abort-button').addEventListener('click', abortProcess);
        </script>
    @endpush
@endsection
