@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">DATA Performa Landingpage</div>
                    <div class="d-flex flex-wrap gap-2">
                        <form method="GET" action="{{ route('landingpages.performance') }}">
                            <div class="d-flex flex-wrap gap-2">
                                <!-- Month Filter -->
                                <div>
                                    <select name="month" class="form-control form-control-sm" id="monthFilter">
                                        @foreach(range(1, 12) as $month)
                                            <option value="{{ $month }}" {{ (request('month', $selectedMonth) == $month) ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create(null, $month)->format('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Year Filter -->
                                <div>
                                    <select name="year" class="form-control form-control-sm" id="yearFilter">
                                        @foreach(range(\Carbon\Carbon::now()->year - 5, \Carbon\Carbon::now()->year) as $year) <!-- Adjust year range if needed -->
                                            <option value="{{ $year }}" {{ (request('year', $selectedYear) == $year) ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                </div>
                            </div>
                        </form>
                        
                        
                    </div>
                </div>
                
                <div class="card-body p-2">
                    <div class="container">
                        <div class="scroll-container">
                            @foreach ($datas as $item)
                                @php
                                    // Initialize totals for each landing page
                                    $totalSpent = 0;
                                    $totalContact = 0;
                                    $totalCPContact = 0;
                                    $performanceCount = 0;
                                @endphp

                                <div class="card-container">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center sticky-header">
                                            <h5 class="mb-0">{{ $item['code'] }}</h5>
                                            <a href="{{ $item['link'] }}" target="_blank" class="btn btn-primary btn-sm">Lihat Landingpage</a>
                                        </div>
                                        <div class="card-header d-flex flex-column align-items-start sticky-header">

                                            @php
                                                // Initialize totals for each landing page
                                                $totalSpent = 0;
                                                $totalContact = 0;
                                                $totalCPContact = 0;
                                                $performanceCount = 0;
                                            @endphp

                                            @foreach ($item['performances'] as $detail)
                                                @php
                                                    $tanggalDetail = \Carbon\Carbon::parse($detail['tanggal']);
                                                    $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
                                                    $endOfMonth = \Carbon\Carbon::now()->endOfMonth();
                                                @endphp

                                                @if ($tanggalDetail->between($startOfMonth, $endOfMonth))
                                                    @php
                                                        // Calculate totals for spending and contacts
                                                        $totalSpent += (float) $detail['amount_spent'];
                                                        $totalContact += (int) $detail['contact'] ?? 0;
                                                        $totalCPContact += (float) $detail['total_performance'];
                                                        $performanceCount++;
                                                    @endphp
                                                @endif
                                            @endforeach

                                            <!-- Data Summary (below the code and link) -->
                                            <div class="summary" style="margin-top: 8px;">
                                                <p style="font-size: 12px; font-weight: 400; margin-bottom: 2px;">Spending LP: Rp
                                                    {{ number_format($totalSpent, 0, ',', '.') }}</p>
                                                <p style="font-size: 12px; font-weight: 400; margin-bottom: 2px;">AVG CPC: Rp
                                                    {{ $performanceCount > 0 ? number_format($totalSpent / $performanceCount, 0, ',', '.') : '-' }}
                                                </p>
                                                <p style="font-size: 12px; font-weight: 400; margin-bottom: 2px;">Jumlah Contact: {{ $totalContact }}</p>
                                            </div>
                                            
                                        </div>


                                        <div class="card-body table-scroll">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>CPContact</th>
                                                        <th>Kontak</th>
                                                        <th>Spent</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item['performances'] as $detail)
                                                        @php
                                                            $tanggalDetail = \Carbon\Carbon::parse($detail['tanggal']);
                                                            $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
                                                            $endOfMonth = \Carbon\Carbon::now()->endOfMonth();
                                                        @endphp

                                                        {{-- @if ($tanggalDetail->between($startOfMonth, $endOfMonth)) --}}
                                                            @php
                                                                // Calculate totals for spending and contacts
                                                                $totalSpent += (float) $detail['amount_spent'];
                                                                $totalContact += (int) $detail['contact'] ?? 0;
                                                                $totalCPContact += (float) $detail['total_performance'];
                                                                $performanceCount++;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $detail['tanggal'] }}</td>
                                                                <td>{{ number_format(round((float) $detail['total_performance']), 0, ',', '.') }}
                                                                </td>
                                                                <td>{{ $detail['contact'] ?? '-' }}</td>
                                                                <td>Rp
                                                                    {{ number_format((float) $detail['amount_spent'], 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        {{-- @endif --}}
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scroll-container {
            display: flex;
            overflow-x: auto;
            padding-bottom: 20px;
            gap: 15px;
        }

        .card-container {
            flex-shrink: 0;
            width: 600px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sticky-header {
            background-color: #f7f7f7;
            padding: 10px;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .table-scroll {
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
@endsection
