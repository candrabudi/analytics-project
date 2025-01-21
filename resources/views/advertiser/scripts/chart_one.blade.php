<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div id="chart"></div>

<script>
    // Gunakan AJAX untuk mengambil data dari API
    $.ajax({
        url: 'http://localhost:8000/advertiser/dashboard/chart-one', // URL API Anda
        method: 'GET',
        success: function(response) {
            // Jika data berhasil diambil, proses datanya
            const dates = response.map(item => item.upload_date);
            const totalImpressions = response.map(item => item.total_impressions || 0);
            const totalSpending = response.map(item => item.total_spent);
            const totalCostPerResult = response.map(item => item.total_cost);

            // Cari nilai minimum dan maksimum untuk menyesuaikan skala yaxis
            const minTotalImpressions = Math.min(...totalImpressions);
            const maxTotalImpressions = Math.max(...totalImpressions);
            const minTotalSpending = Math.min(...totalSpending);
            const maxTotalSpending = Math.max(...totalSpending);
            const minCostPerResult = Math.min(...totalCostPerResult);
            const maxCostPerResult = Math.max(...totalCostPerResult);

            // Konfigurasi opsi untuk ApexCharts
            var options = {
                series: [{
                    name: 'Total Impression',
                    data: totalImpressions
                }, {
                    name: 'Total Spending',
                    data: totalSpending
                }, {
                    name: 'Total Cost Per Result',
                    data: totalCostPerResult
                }],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    background: 'transparent',
                    animations: {
                        enabled: false // Nonaktifkan animasi default
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    dashArray: 0 // Hilangkan efek garis putus-putus
                },
                markers: {
                    size: 0 // Sembunyikan marker
                },
                xaxis: {
                    categories: dates,
                    labels: {
                        show: true // Tampilkan label sumbu X
                    },
                    axisBorder: {
                        show: false // Sembunyikan border sumbu X
                    },
                    axisTicks: {
                        show: false // Sembunyikan ticks sumbu X
                    }
                },
                yaxis: [{
                    seriesName: 'Total Impression',
                    title: {
                        text: 'Total Impression'
                    },
                    min: minTotalImpressions * 0.9, // Skala dinamis agar jarak lebih dekat
                    max: maxTotalImpressions * 1.1,
                    tickAmount: 5,
                    labels: {
                        show: true
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                }, {
                    seriesName: 'Total Spending',
                    opposite: true,
                    title: {
                        text: 'Total Spending'
                    },
                    min: minTotalSpending * 0.9,
                    max: maxTotalSpending * 1.1, // Corrected here
                    tickAmount: 5,
                    labels: {
                        show: true
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                }, {
                    seriesName: 'Total Cost Per Result',
                    opposite: true,
                    title: {
                        text: 'Total Cost Per Result'
                    },
                    min: minCostPerResult * 0.9,
                    max: maxCostPerResult * 1.1,
                    tickAmount: 5,
                    labels: {
                        show: true
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                }],
                grid: {
                    show: false // Sembunyikan grid
                },
                tooltip: {
                    enabled: true
                },
                colors: ['#546E7A', '#26A69A', '#FF5722'],
                legend: {
                    show: true
                }
            };

            // Render grafik menggunakan ApexCharts
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        },
        error: function(error) {
            console.error("Error fetching data from API:", error);
        }
    });
</script>
