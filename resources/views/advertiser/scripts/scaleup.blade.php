<script>
    async function fetchDataScaleUp(page = 1, searchTerm = '') {
        try {
            const response = await fetch(`/advertiser/dashboard/data-scale-up?page=${page}&search=${searchTerm}`);

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            const currentPage = data.current_page || 1;
            const totalPages = data.total_pages || 1;

            displayDataScaleUp(data.data);
            displayPaginationScaleUp(currentPage, totalPages);

        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    }

    function displayDataScaleUp(data) {
        const tableBody = document.getElementById('data-table-body-scaleup');
        tableBody.innerHTML = '';

        data.forEach(item => {
            const row = document.createElement('tr');

            const accountNameTd = document.createElement('td');
            accountNameTd.style.maxWidth = '40ch';
            accountNameTd.style.whiteSpace = 'nowrap';
            accountNameTd.style.overflow = 'hidden';
            accountNameTd.style.textOverflow = 'ellipsis';
            accountNameTd.title = item.account_name;
            accountNameTd.textContent = item.account_name;
            accountNameTd.addEventListener('click', () => {
                navigator.clipboard.writeText(item.account_name);
                alert('Account Name copied to clipboard!');
            });

            const campaignNameTd = document.createElement('td');
            campaignNameTd.style.maxWidth = '30ch';
            campaignNameTd.style.whiteSpace = 'nowrap';
            campaignNameTd.style.overflow = 'hidden';
            campaignNameTd.style.textOverflow = 'ellipsis';
            campaignNameTd.title = item.campaign_name;
            campaignNameTd.textContent = item.campaign_name;
            campaignNameTd.addEventListener('click', () => {
                navigator.clipboard.writeText(item.campaign_name);
                alert('Campaign Name copied to clipboard!');
            });

            const averageCostTd = document.createElement('td');
            averageCostTd.style.maxWidth = '40ch';
            averageCostTd.style.whiteSpace = 'nowrap';
            averageCostTd.style.overflow = 'hidden';
            averageCostTd.style.textOverflow = 'ellipsis';
            averageCostTd.title = item.formatted_average_cost;
            averageCostTd.textContent = item.formatted_average_cost;

            const totalAddsTd = document.createElement('td');
            totalAddsTd.style.maxWidth = '40ch';
            totalAddsTd.style.whiteSpace = 'nowrap';
            totalAddsTd.style.overflow = 'hidden';
            totalAddsTd.style.textOverflow = 'ellipsis';
            totalAddsTd.title = item.spending;
            totalAddsTd.textContent = item.spending;

            row.appendChild(accountNameTd);
            row.appendChild(campaignNameTd);
            row.appendChild(averageCostTd);
            row.appendChild(totalAddsTd);

            tableBody.appendChild(row);
        });
    }

    function displayPaginationScaleUp(currentPage, totalPages) {
        const paginationElement = document.getElementById('pagination-scaleup');
        if (!paginationElement) return;

        paginationElement.innerHTML = '';

        // "Prev" button
        const prevButton = document.createElement('li');
        prevButton.classList.add('page-item');
        if (currentPage === 1) {
            prevButton.classList.add('disabled');
        } else {
            prevButton.innerHTML =
                `<a class="page-link" href="javascript:void(0);" onclick="fetchDataScaleUp(${currentPage - 1})">Prev</a>`;
        }
        paginationElement.appendChild(prevButton);

        // Page number buttons
        for (let page = 1; page <= totalPages; page++) {
            const pageItem = document.createElement('li');
            pageItem.classList.add('page-item');

            if (page == currentPage) {
                pageItem.classList.add('active');
            }

            const pageLink = document.createElement('a');
            pageLink.classList.add('page-link');
            pageLink.href = 'javascript:void(0);';
            pageLink.textContent = page;

            pageLink.addEventListener('click', () => {
                fetchDataScaleUp(page);
            });

            pageItem.appendChild(pageLink);
            paginationElement.appendChild(pageItem);
        }

        const nextButton = document.createElement('li');
        nextButton.classList.add('page-item');
        if (currentPage === totalPages) {
            nextButton.classList.add('disabled');
        } else {
            nextButton.innerHTML =
                `<a class="page-link" href="javascript:void(0);" onclick="fetchDataScaleUp(${currentPage + 1})">Next</a>`;
        }
        paginationElement.appendChild(nextButton);
    }


    const searchField = document.getElementById('search-scaleup');
    if (searchField) {
        searchField.addEventListener('input', function(event) {
            const searchTerm = event.target.value;
            fetchDataScaleUp(1, searchTerm);
        });
    }

    // Initial fetch for the first page
    fetchDataScaleUp();
</script>
