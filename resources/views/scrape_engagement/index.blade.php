@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Scrap Engagement</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Input Link Video TikTok
                    </div>
                </div>
                <div class="card-body">
                    <form id="videoMetricsForm">
                        <div class="relative w-full">
                            <textarea id="tiktokUrls" class="form-control" placeholder="Masukkan URL TikTok, satu per baris..." rows="10"></textarea>

                            <div class="mt-3 d-flex align-items-center">
                                <button type="button" id="processButton" class="btn btn-primary" disabled>
                                    Process
                                </button>
                                <button type="button" id="exportButton" class="btn btn-secondary ms-2">
                                    Export to Excel
                                </button>
                            </div>

                            <div class="mt-3">
                                <div class="progress">
                                    <div id="progressBar" class="progress-bar bg-success" role="progressbar"
                                        style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div id="progressText" class="mt-2 text-center">0% (0/0)</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        TikTok Video Metrics
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">URL</th>
                                    <th scope="col">Views</th>
                                    <th scope="col">Likes</th>
                                    <th scope="col">Comments</th>
                                    <th scope="col">Shares</th>
                                    <th scope="col">Saves</th>
                                </tr>
                            </thead>
                            <tbody id="products-table">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        {{-- <div>Showing 6 Entries <i class="bi bi-arrow-right ms-2 fw-semibold"></i></div> --}}
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-4">
                                <ul class="pagination mb-0" id="pagination">
                                    <!-- Pagination dynamically generated here -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            const processButton = document.getElementById('processButton');
            const textarea = document.getElementById('tiktokUrls');
            const tableBody = document.getElementById('products-table');
            const pagination = document.getElementById('pagination');
            const progressText = document.getElementById('progressText');
            let currentPage = 1;
            let perPage = 10;
            let allVideoMetrics = [];

            function removeUnwantedParams(url) {
                const urlObj = new URL(url);
                urlObj.searchParams.delete("is_from_webapp");
                urlObj.searchParams.delete("sender_device");
                return urlObj.toString();
            }

            textarea.addEventListener('input', () => {
                processButton.disabled = textarea.value.trim() === '';
            });

            processButton.addEventListener('click', async () => {
                const urls = textarea.value.trim().split('\n');
                const apiUrl = 'https://tiktok-download-video1.p.rapidapi.com/getVideo';
                const headers = {
                    'x-rapidapi-host': 'tiktok-download-video1.p.rapidapi.com',
                    'x-rapidapi-key': '{{ $generalSetting->rapid_api_key }}',
                };

                if (urls.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Textarea is empty. Please enter TikTok URLs.'
                    });
                    return;
                }

                textarea.disabled = true;
                processButton.disabled = true;

                let processedCount = 0;
                let failedUrls = [];
                allVideoMetrics = [];
                progressText.innerText = `0% (0/${urls.length})`;

                try {
                    for (const [index, url] of urls.entries()) {
                        if (url.trim() === '') continue;

                        const cleanedUrl = removeUnwantedParams(url.trim());

                        try {
                            const response = await axios.get(apiUrl, {
                                headers,
                                params: {
                                    url: cleanedUrl,
                                    hd: 1
                                }
                            });

                            const data = response.data.data;
                            allVideoMetrics.push({
                                url: cleanedUrl,
                                views: data.play_count,
                                likes: data.digg_count,
                                comments: data.comment_count,
                                shares: data.share_count,
                                saves: data.collect_count,
                            });

                            processedCount++;
                            const percentage = Math.floor((processedCount / urls.length) * 100);
                            progressText.innerText = `${percentage}% (${processedCount}/${urls.length})`;

                        } catch (error) {
                            console.error('Error fetching TikTok data for URL:', cleanedUrl, error);
                            allVideoMetrics.push({
                                url: cleanedUrl,
                                views: 0,
                                likes: 0,
                                comments: 0,
                                shares: 0,
                                saves: 0,
                            });

                            failedUrls.push(url);
                        }
                    }
                    displayTable();
                    textarea.value = '';
                } finally {
                    textarea.disabled = false;
                    processButton.disabled = false;
                }
            });

            function displayTable() {
                tableBody.innerHTML = '';

                const startIndex = (currentPage - 1) * perPage;
                const paginatedMetrics = allVideoMetrics.slice(startIndex, startIndex + perPage);

                paginatedMetrics.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${item.url}</td>
                    <td>${item.views}</td>
                    <td>${item.likes}</td>
                    <td>${item.comments}</td>
                    <td>${item.shares}</td>
                    <td>${item.saves}</td>
                `;
                    tableBody.appendChild(row);
                });

                updatePagination();
            }

            function updatePagination() {
                pagination.innerHTML = '';

                const totalPages = Math.ceil(allVideoMetrics.length / perPage);

                for (let i = 1; i <= totalPages; i++) {
                    const pageItem = document.createElement('li');
                    pageItem.classList.add('page-item');
                    pageItem.innerHTML = `
                    <button class="page-link ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>
                `;
                    pagination.appendChild(pageItem);
                }
            }

            function changePage(page) {
                currentPage = page;
                displayTable();
            }

            document.getElementById('exportButton').addEventListener('click', () => {
                if (allVideoMetrics.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'No data to export.'
                    });
                    return;
                }

                const workbook = XLSX.utils.book_new();
                const worksheet = XLSX.utils.json_to_sheet(allVideoMetrics);
                XLSX.utils.book_append_sheet(workbook, worksheet, 'TikTok Metrics');
                XLSX.writeFile(workbook, 'TikTok_Video_Metrics.xlsx');
            });
        </script>
    @endpush
@endsection
