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
                            <input class="form-control" type="file" id="formFile" name="file" accept=".xlsx, .csv" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="processFile()">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 custom-card">
                <div class="card-header justify-content-between">
                    <h5 class="card-title">Hasil JSON dari File</h5>
                </div>
                <div class="card-body" id="jsonResult">
                    <p class="text-muted">Hasil JSON akan ditampilkan di sini setelah file di-upload.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <script>
        function processFile() {
            const fileInput = document.getElementById('formFile');
            const file = fileInput.files[0];
    
            if (!file) {
                alert('Silakan pilih file terlebih dahulu!');
                return;
            }
    
            const fileExtension = file.name.split('.').pop().toLowerCase();
            let processedData = [];
    
            // Proses file berdasarkan tipe
            if (fileExtension === 'xlsx') {
                processXlsx(file).then(data => {
                    processedData = data.map(item => convertToLowercase(item));
                    sendDataToServer(processedData);
                }).catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan dalam pemrosesan file Excel.');
                });
            } else if (fileExtension === 'csv') {
                processCsv(file).then(data => {
                    processedData = data.map(item => convertToLowercase(item));
                    sendDataToServer(processedData);
                }).catch(error => {
                    console.error(error);
                    alert('Terjadi kesalahan dalam pemrosesan file CSV.');
                });
            } else {
                alert('File tidak didukung!');
            }
        }
    
        // Fungsi untuk mengubah key menjadi lowercase dan menggunakan underscore
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
    
        // Proses file .xlsx menggunakan xlsx.js
        function processXlsx(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    try {
                        const data = new Uint8Array(event.target.result);
                        const workbook = XLSX.read(data, { type: 'array' });
    
                        const sheetName = workbook.SheetNames[0];  // Ambil sheet pertama
                        const worksheet = workbook.Sheets[sheetName];
                        const json = XLSX.utils.sheet_to_json(worksheet);
                        resolve(json);  // Mengembalikan hasil sebagai Promise
                    } catch (error) {
                        reject(error);  // Jika terjadi kesalahan
                    }
                };
                reader.onerror = function(error) {
                    reject(error);
                };
                reader.readAsArrayBuffer(file);
            });
        }
    
        // Proses file .csv menggunakan PapaParse
        function processCsv(file) {
            return new Promise((resolve, reject) => {
                Papa.parse(file, {
                    complete: function(results) {
                        resolve(results.data);  // Mengembalikan hasil sebagai Promise
                    },
                    error: function(error) {
                        reject(error);  // Jika terjadi kesalahan
                    }
                });
            });
        }
    
        // Kirim data yang sudah diproses ke server
        function sendDataToServer(processedData) {
            console.log("Data sebelum dikirim:", JSON.stringify(processedData));
            console.log(processedData);
    
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('data', JSON.stringify(processedData));  // Mengirim data sebagai JSON
    
            $.ajax({
                url: '{{ route("process.file") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    displayJsonResult(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan dalam pengolahan file.');
                }
            });
        }
    
        // Tampilkan hasil JSON setelah file diproses
        function displayJsonResult(jsonData) {
            const resultContainer = document.getElementById('jsonResult');
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
    </script>
    
    
@endsection
