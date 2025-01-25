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
                    <button type="button" id="export-button" class="btn btn-success">Export to CSV</button>
                    <button id="insert-data-btn" class="btn btn-primary" style="display: none;">
                        Insert Data (<span id="selected-count">0</span>)
                    </button>
                </div>


            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">SCRAPE USERNAME</h5>
                    <form action="" class="d-flex gap-2 w-100" id="search-form">
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
                                    <th scope="col"><input type="checkbox" id="select-all"></th>
                                    <!-- Checkbox untuk pilih semua -->
                                    {{-- <th scope="col">USER ID</th> --}}
                                    <th scope="col">TIER</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">FOLLOWERS</th>
                                    <th scope="col">TOTAL VIDEOS</th>
                                    <th scope="col">AVERAGE</th>
                                    <th scope="col">TOTAL INTERACTIONS</th>
                                    <th scope="col">ENGAGEMENT RATE</th>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('export-button').addEventListener('click', function() {
                var table = document.querySelector('table');
                var wb = XLSX.utils.book_new();
        
                var rows = Array.from(table.querySelectorAll('tbody tr')).map(function(row) {
                    var cells = Array.from(row.querySelectorAll('td'));
                    var checkbox = row.querySelector('.select-row');
                    if (checkbox) {
                        var authorId = checkbox.getAttribute('data-author-id') || '';
                        var category = checkbox.getAttribute('data-category') || '';
                        var uniqueId = checkbox.getAttribute('data-unique-id') || '';
                        var follower = checkbox.getAttribute('data-follower') || '';
                        var totalVideo = checkbox.getAttribute('data-total-video') || '';
                        var average = checkbox.getAttribute('data-average') || '';
        
                        return [
                            authorId,
                            category,
                            uniqueId,
                            follower,
                            totalVideo,
                            average
                        ];
                    } else {
                        return ['', '', '', '', '', ''];
                    }
                });
        
                rows = rows.filter((row, index) => {
                    return index !== 0 || row.some(cell => cell !== '');
                });
        
                var header = ['USER ID', 'TIER', 'USERNAME', 'FOLLOWERS', 'TOTAL VIDEOS', 'AVERAGE'];
                var data = [header].concat(rows);
                var ws = XLSX.utils.aoa_to_sheet(data);
                XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                var keyword = document.getElementById('keyword-input').value;
                XLSX.writeFile(wb, "scrap_username_" + keyword + ".xlsx");
            });
        </script>
        
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
                const processedAuthorIds = new Set();

                while (hasMore && !isAborted) {
                    try {
                        const response = await fetch(
                            `https://tiktok-download-video1.p.rapidapi.com/feedSearch?keywords=${encodeURIComponent(keyword)}&count=${perPage}&cursor=${cursor}&region=ID&publish_time=0&sort_type=0`, {
                                method: 'GET',
                                headers: {
                                    'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                                    'x-rapidapi-key': '{{ $setting->rapid_api_key }}'
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
                                if (processedAuthorIds.has(video.author.id)) {
                                    return;
                                }

                                processedAuthorIds.add(video.author.id);

                                fetch(`https://tiktok-download-video1.p.rapidapi.com/userInfo?user_id=${video.author.id}`, {
                                        method: 'GET',
                                        headers: {
                                            'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                                            'x-rapidapi-key': '{{ $setting->rapid_api_key }}'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(userInfoData => {
                                        fetch(`https://tiktok-download-video1.p.rapidapi.com/userPublishVideo?user_id=${video.author.id}&count=12&cursor=0`, {
                                                method: 'GET',
                                                headers: {
                                                    'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                                                    'x-rapidapi-key': '{{ $setting->rapid_api_key }}'
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(publishedVideosData => {
                                                if (publishedVideosData.code === 0 && publishedVideosData
                                                    .data && publishedVideosData.data.videos) {
                                                    const videosToConsider = publishedVideosData.data.videos
                                                        .slice(0, 12);
                                                    const totalVideos = videosToConsider.length;

                                                    if (totalVideos > 0) {
                                                        const averagePlayCount = videosToConsider.reduce((
                                                            sum, video) => {
                                                            return sum + (video.play_count || 0);
                                                        }, 0) / totalVideos;


                                                        const totalInteractions = videosToConsider.reduce((sum, video) => {
                                                            return sum + (video.digg_count || 0) + (video.comment_count || 0) + (video.share_count || 0);
                                                        }, 0);

                                                        const existingAuthor = userInfoArray.find(
                                                            userInfo => userInfo.author_id ===
                                                            videosToConsider[0].author.id
                                                        );

                                                        if (!existingAuthor) {
                                                            const roundedAveragePlayCount = parseFloat(
                                                                averagePlayCount.toFixed(0));
                                                            let category = "-";
                                                            const followerCount = userInfoData.data.stats
                                                                .followerCount;
                                                            const followingCount = userInfoData.data.stats
                                                                .followingCount;
                                                            const videoCount = userInfoData.data.stats
                                                                .videoCount;
                                                            const heartCount = userInfoData.data.stats
                                                                .heartCount;
                                                            const diggCount = userInfoData.data.stats
                                                                .diggCount;

                                                            if (followerCount >= 1000) {
                                                                if (followerCount >= 1000000) {
                                                                    category = "mega";
                                                                } else if (followerCount >= 100000) {
                                                                    category = "macro";
                                                                } else if (followerCount >= 10000) {
                                                                    category = "micro";
                                                                } else if (followerCount >= 1000) {
                                                                    category = "nano";
                                                                }

                                                                userInfoArray.push({
                                                                    author_id: videosToConsider[0]
                                                                        .author.id,
                                                                    unique_id: userInfoData.data
                                                                        .user.uniqueId,
                                                                    nickname: userInfoData.data.user
                                                                        .nickname,
                                                                    follower: followerCount,
                                                                    total_video: userInfoData.data
                                                                        .stats.videoCount,
                                                                    average: roundedAveragePlayCount,
                                                                    category: category,
                                                                    totalInteractions: totalInteractions
                                                                });

                                                                fetch(`/scrape-username/insert/account`, {
                                                                    method: 'POST',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': document
                                                                            .querySelector(
                                                                                'meta[name="csrf-token"]'
                                                                            ).getAttribute(
                                                                                'content')
                                                                    },

                                                                    body: JSON.stringify({
                                                                        tiktok_search_id: searchId,
                                                                        author_id: videosToConsider[
                                                                                0].author
                                                                            .id,
                                                                        unique_id: userInfoData
                                                                            .data.user
                                                                            .uniqueId,
                                                                        nickname: userInfoData
                                                                            .data.user
                                                                            .nickname,
                                                                        follower: followerCount,
                                                                        following: followerCount,
                                                                        total_video: videoCount,
                                                                        like: heartCount,
                                                                        average: roundedAveragePlayCount,
                                                                        digg: diggCount,
                                                                        tier: category,
                                                                        totalInteractions: totalInteractions
                                                                    })
                                                                });

                                                                renderUserInfoTable([{
                                                                    author_id: videosToConsider[
                                                                        0].author.id,
                                                                    unique_id: userInfoData.data
                                                                        .user.uniqueId,
                                                                    nickname: userInfoData.data
                                                                        .user.nickname,
                                                                    follower: followerCount,
                                                                    following: followingCount,
                                                                    like: heartCount,
                                                                    total_video: videoCount,
                                                                    digg: diggCount,
                                                                    average: roundedAveragePlayCount,
                                                                    category: category, 
                                                                    totalInteractions: totalInteractions
                                                                }]);
                                                            }
                                                        }
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


            function showSuccessToast() {
                const successToast = new bootstrap.Toast(document.getElementById('solid-successToast'));
                successToast.show();
            }

            function showErrorToast() {
                const errorToast = new bootstrap.Toast(document.getElementById('solid-errorToast'));
                errorToast.show();
            }

            function getSelectedData(userInfoArray) {
                const selectedData = [];
                const rowCheckboxes = document.querySelectorAll('.select-row:checked');

                rowCheckboxes.forEach(checkbox => {
                    const authorID = checkbox.getAttribute('data-author-id');
                    const category = checkbox.getAttribute('data-category');
                    const uniqueID = checkbox.getAttribute('data-unique-id');
                    const follower = checkbox.getAttribute('data-follower');
                    const totalVideo = checkbox.getAttribute('data-total-video');
                    const average = checkbox.getAttribute('data-average');
                    const nickname = checkbox.getAttribute('data-nickname');
                    const following = checkbox.getAttribute('data-following');
                    const like = checkbox.getAttribute('data-like');
                    const totalInteractions = checkbox.getAttribute('data-total-interactions');

                    selectedData.push({
                        authorID: authorID,
                        category: category,
                        uniqueID: uniqueID,
                        follower: follower,
                        totalVideo: totalVideo,
                        average: average,
                        nickname: nickname,
                        following: following,
                        like: like,
                        totalInteractions: totalInteractions
                    });
                });

                return selectedData;
            }


            function insertSelectedData(selectedData) {
                if (selectedData.length > 0) {
                    console.log('Data yang dipilih:', selectedData);

                    fetch('/kol/master/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                data: selectedData
                            })
                        })
                        .then(response => response.json())
                        .then(result => {
                            console.log('Data berhasil diinsert:', result);
                            showSuccessToast();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showErrorToast();
                        });
                } else {
                    alert('Tidak ada data yang dipilih.');
                }
            }

            function renderUserInfoTable(userInfoArray) {
                const accountDataBody = document.getElementById('account-data-body');
                const numberFormatter = new Intl.NumberFormat('id-ID');

                userInfoArray.forEach(userInfo => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>
                            <input type="checkbox" 
                                class="select-row" 
                                data-author-id="${userInfo.author_id}" 
                                data-category="${userInfo.category}" 
                                data-unique-id="${userInfo.unique_id}"
                                data-follower="${userInfo.follower}"
                                data-total-video="${userInfo.total_video}"
                                data-average="${userInfo.average}"
                                data-nickname="${userInfo.nickname}"
                                data-like="${userInfo.like}"
                                data-following="${userInfo.following}"
                                data-total-interactions="${userInfo.totalInteractions}"
                            >
                        </td>
                        <td>${userInfo.category}</td>
                        <td>${userInfo.unique_id}</td>
                        <td>${numberFormatter.format(userInfo.follower)}</td>
                        <td>${numberFormatter.format(userInfo.total_video)}</td>
                        <td>${numberFormatter.format(userInfo.average)}</td>
                        <td>${numberFormatter.format(userInfo.totalInteractions)}</td>
                        <td>${userInfo.average ? ((userInfo.totalInteractions / userInfo.average) * 100).toFixed(2) : 'N/A'}%</td>
                    `;
                    accountDataBody.appendChild(newRow);
                });

                const selectAllCheckbox = document.getElementById('select-all');
                const insertDataButton = document.getElementById('insert-data-btn');
                const selectedCountSpan = document.getElementById('selected-count');

                function updateSelectedCount() {
                    const selectedCheckboxes = document.querySelectorAll('.select-row:checked');
                    const count = selectedCheckboxes.length;

                    if (count > 0) {
                        insertDataButton.style.display = 'inline-block';
                        selectedCountSpan.textContent = count;
                    } else {
                        insertDataButton.style.display = 'none';
                    }
                }

                selectAllCheckbox.addEventListener('change', function() {
                    const rowCheckboxes = document.querySelectorAll('.select-row');
                    rowCheckboxes.forEach(checkbox => {
                        console.log(checkbox);
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    updateSelectedCount();
                });

                document.getElementById('account-data-body').addEventListener('change', function(event) {
                    if (event.target.classList.contains('select-row')) {
                        updateSelectedCount();
                    }
                });

                if (!insertDataButton.hasAttribute('listener-added')) {
                    insertDataButton.setAttribute('listener-added', 'true');

                    insertDataButton.addEventListener('click', function() {
                        Swal.fire({
                            title: 'Apakah kamu yakin?',
                            text: "Apakah kamu yakin ingin memasukan data ini ke database raw?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, masukan!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const selectedData = getSelectedData(userInfoArray);
                                insertSelectedData(selectedData); // Proceed with data insertion
                                Swal.fire(
                                    'Data dimasukan!',
                                    'Data telah berhasil dimasukan ke database raw.',
                                    'success'
                                );
                            }
                        });
                    });
                }
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

            document.getElementById('start-button').addEventListener('click', function(event) {
                event.preventDefault();
                startProcess();
            });

            document.getElementById('keyword-input').addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    startProcess();
                }
            });

            document.getElementById('search-form').addEventListener('submit', function(event) {
                event.preventDefault();
            });
            document.getElementById('abort-button').addEventListener('click', abortProcess);
        </script>
    @endpush
@endsection
