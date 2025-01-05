@extends('layouts.app')

@section('content')
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Upload Raw Data</h1>
            <p class="mb-0 text-muted">Silakan unggah file data mentah dalam format .xlsx atau .csv</p>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <h5 class="card-title">Form Upload File Raw (xlsx, csv)</h5>
                </div>
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Pilih File</label>
                            <input class="form-control" type="file" id="formFile" name="file" accept=".xlsx, .csv"
                                required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="processFile()">Upload</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card mt-4 custom-card">
                <div class="card-body" id="jsonResult">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header justify-content-between">
                                    <div class="card-title">
                                        DATA IMPORT EXCEL
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge" style="font-size: 12px; line-height: 2; color: #333;">Total
                                            Data: <span id="total-count">0</span></span>
                                        <span class="badge" style="font-size: 12px; line-height: 2; color: #333;">SUKSES:
                                            <span id="success-count">0</span></span>
                                        <span class="badge" style="font-size: 12px; line-height: 2; color: #333;">ERROR:
                                            <span id="error-count">0</span></span>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i>
                                                </div>
                                                <input type="text" class="form-control flatpickr-input" id="date"
                                                    placeholder="Choose date" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button type="button" id="processDataButton" class="btn btn-success"
                                                onclick="processData()" disabled>Process Data</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body" id="jsonResultTable">
                                    <p class="text-muted">Hasil JSON akan ditampilkan di sini setelah file di-upload.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://php.spruko.com/mamix/mamix/assets/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="https://php.spruko.com/mamix/mamix/assets/libs/@simonwep/pickr/themes/nano.min.css">
    <link rel="stylesheet" href="https://php.spruko.com/mamix/mamix/assets/libs/flatpickr/flatpickr.min.css">
    <script src="https://php.spruko.com/mamix/mamix/assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="https://php.spruko.com/mamix/mamix/assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>

    <script src="https://php.spruko.com/mamix/mamix/assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js"></script>
    <script src="https://php.spruko.com/mamix/mamix/assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="https://php.spruko.com/mamix/mamix/assets/js/date&time_pickers.js"></script>
    <script>
        let processedData = [];

        function processFile() {
            const fileInput = document.getElementById('formFile');
            const file = fileInput.files[0];

            if (!file) {
                alert('Silakan pilih file terlebih dahulu!');
                return;
            }

            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (fileExtension === 'xlsx') {
                processXlsx(file).then(data => {
                    processedData = data.map(item => convertToLowercase(item));
                    displayJsonResult(processedData);
                    document.getElementById('processDataButton').disabled = false;
                }).catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan dalam pemrosesan file Excel.');
                });
            } else if (fileExtension === 'csv') {
                processCsv(file).then(data => {
                    processedData = data.map(item => convertToLowercase(item));
                    displayJsonResult(processedData);
                    document.getElementById('processDataButton').disabled = false;
                }).catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan dalam pemrosesan file CSV.');
                });
            } else {
                alert('File tidak didukung!');
            }
        }

        function processXlsx(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    try {
                        const data = new Uint8Array(event.target.result);
                        const workbook = XLSX.read(data, {
                            type: 'array'
                        });
                        const sheetName = workbook.SheetNames[0];
                        const worksheet = workbook.Sheets[sheetName];
                        const json = XLSX.utils.sheet_to_json(worksheet);
                        resolve(json);
                    } catch (error) {
                        reject(error);
                    }
                };
                reader.onerror = function(error) {
                    reject(error);
                };
                reader.readAsArrayBuffer(file);
            });
        }

        function processCsv(file) {
            return new Promise((resolve, reject) => {
                Papa.parse(file, {
                    complete: function(results) {
                        resolve(results.data);
                    },
                    error: function(error) {
                        reject(error);
                    }
                });
            });
        }

        function convertToLowercase(item) {
            const newItem = {};
            for (const key in item) {
                if (item.hasOwnProperty(key)) {
                    const newKey = key.toLowerCase().replace(/ /g, '_');
                    newItem[newKey] = item[key];
                }
            }
            return newItem;
        }


        function displayJsonResult(jsonData) {
            const resultContainer = document.getElementById('jsonResultTable');
            resultContainer.innerHTML = '';

            if (jsonData.length === 0) {
                resultContainer.innerHTML = '<p class="text-muted">Tidak ada data yang bisa ditampilkan.</p>';
                return;
            }

            const tableWrapper = document.createElement('div');
            tableWrapper.classList.add('table-responsive');

            const table = document.createElement('table');
            table.classList.add('table', 'table-bordered', 'table-striped', 'table-hover');

            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            for (const key in jsonData[0]) {
                const th = document.createElement('th');
                th.textContent = key;
                headerRow.appendChild(th);
            }
            thead.appendChild(headerRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            jsonData.forEach(item => {
                const row = document.createElement('tr');
                for (const key in item) {
                    const td = document.createElement('td');
                    td.textContent = item[key];
                    row.appendChild(td);
                }
                tbody.appendChild(row);
            });
            table.appendChild(tbody);

            tableWrapper.appendChild(table);
            resultContainer.appendChild(tableWrapper);
        }

        async function processData() {
            let successCount = 0;
            let errorCount = 0;
            const totalCount = processedData.length;

            // Get the selected date from the input field
            const selectedDate = $('#date').val();

            if (!selectedDate) {
                alert("Please select a date before processing the data.");
                return;
            }

            $('#total-count').text('Total Data: ' + totalCount);

            for (let i = 0; i < totalCount; i++) {
                const dataItem = processedData[i];
                let payload = {
                    _token: '{{ csrf_token() }}',
                    data: dataItem,
                    date: selectedDate // Include the selected date in the payload
                };

                if (i === 0) {
                    payload.is_delete = 1;
                }

                try {
                    await $.ajax({
                        url: '{{ route('process.file') }}',
                        method: 'POST',
                        data: payload
                    });

                    successCount++;
                    console.log('Data berhasil diproses: ', dataItem);

                } catch (error) {
                    errorCount++;
                    console.error('Error memproses data item: ', dataItem, error);
                }

                $('#success-count').text('Sukses: ' + successCount);
                $('#error-count').text('Error: ' + errorCount);
            }

            alert(`Proses selesai! Total: ${totalCount}, Sukses: ${successCount}, Error: ${errorCount}`);
        }
    </script>
@endsection
