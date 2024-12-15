@extends('layouts.template')

@section('content')
<!-- Stats Cards Row -->
<div class="row mb-4">
    <!-- Total Sertifikasi Card -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card bg-primary text-white h-100">
            <div class="card-header p-2 border-0">
                <button class="btn btn-link text-white p-0 float-left" type="button" data-toggle="collapse" data-target="#totalSertifikasiDetail" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="card-body">
                <h4 class="card-title mb-4 font-weight-bold">Total Sertifikasi Tersimpan</h4>
                <h1 class="display-4 font-weight-bold text-center mb-4">{{ $totalSertifikasi }}</h1>
                <div class="collapse" id="totalSertifikasiDetail">
                    <p class="text-white mb-0">Total sertifikat dari kegiatan sertifikasi yang sudah diupload oleh dosen.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pelatihan Card -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card bg-primary text-white h-100">
            <div class="card-header p-2 border-0">
                <button class="btn btn-link text-white p-0 float-left" type="button" data-toggle="collapse" data-target="#totalPelatihanDetail" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="card-body">
                <h4 class="card-title mb-4 font-weight-bold">Total Pelatihan Tersimpan</h4>
                <h1 class="display-4 font-weight-bold text-center mb-4">{{ $totalPelatihan }}</h1>
                <div class="collapse" id="totalPelatihanDetail">
                    <p class="text-white mb-0">Total sertifikat dari kegiatan pelatihan yang sudah diupload oleh dosen.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Dosen Sertifikasi Card -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card bg-secondary text-white h-100">
            <div class="card-header p-2 border-0">
                <button class="btn btn-link text-white p-0 float-left" type="button" data-toggle="collapse" data-target="#dosenSertifikasiDetail" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="card-body">
                <h4 class="card-title mb-4 font-weight-bold">Jumlah Dosen Sertifikasi</h4>
                <h1 class="display-4 font-weight-bold text-center mb-4">{{ $totalDosenSertifikasi }}</h1>
                <div class="collapse" id="dosenSertifikasiDetail">
                    <p class="text-white mb-0">Jumlah dosen yang telah mengikuti kegiatan sertifikasi berdasarkan dosen yang telah mengupload sertifikat sertifikasi.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Dosen Pelatihan Card -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card bg-secondary text-white h-100">
            <div class="card-header p-2 border-0">
                <button class="btn btn-link text-white p-0 float-left" type="button" data-toggle="collapse" data-target="#dosenPelatihanDetail" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="card-body">
                <h4 class="card-title mb-4 font-weight-bold">Jumlah Dosen Pelatihan</h4>
                <h1 class="display-4 font-weight-bold text-center mb-4">{{ $totalDosenPelatihan }}</h1>
                <div class="collapse" id="dosenPelatihanDetail">
                    <p class="text-white mb-0">Jumlah dosen yang telah mengikuti kegiatan pelatihan berdasarkan dosen yang telah mengupload sertifikat pelatihan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
/* Card styling */
.card {
    position: relative;
    overflow: visible !important;
}

.card-body {
    display: flex;
    flex-direction: column;
    padding: 1.5rem;
}

/* Title and content styling */
.card-title {
    font-size: 1.25rem;
    line-height: 1.5;
}

.display-4 {
    font-size: 3rem;
}

/* Collapse/Dropdown styling */
.collapse {
    width: 100%;
    margin-top: 1rem;
    transition: all 0.3s ease-out;
}

.collapse p {
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Dropdown button styling */
.card-header {
    background: transparent;
}

.card-header button {
    transition: transform 0.3s;
}

.card-header button[aria-expanded="true"] {
    transform: rotate(180deg);
}

.card-header button:focus {
    outline: none;
    box-shadow: none;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    $('.collapse').on('show.bs.collapse', function () {
        $(this).closest('.card').find('.card-header button i').css('transform', 'rotate(180deg)');
    }).on('hide.bs.collapse', function () {
        $(this).closest('.card').find('.card-header button i').css('transform', 'rotate(0deg)');
    });
});
</script>
@endpush

    <!-- Second Row -->
    <div class="row">
        <!-- Jumlah Dosen Card -->
<div class="col-md-4 mb-4">
    <div class="card h-100 rounded-lg">
        <div class="card-body p-4">
            <div class="d-flex flex-column">
                <!-- Header -->
                <div class="mb-4">
                    <h5 class="font-weight-bold mb-0">Jumlah Dosen</h5>
                    <small class="text-muted">Total Dosen yang ada di JTI</small>
                </div>
                
                <!-- Icon & Numbers (Left Aligned) -->
                <div class="d-flex flex-column align-items-start">
                    <!-- Multiple Person Icon -->
                    <svg width="128" height="150" viewBox="0 0 24 24" class="mb-3">
                        <path fill="#6B7280" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V18h14v-1.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05.02.01.03.03.04.04 1.14.83 1.93 1.94 1.93 3.41V18h6v-1.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                    
                    <!-- Number and Label -->
                    <h2 class="display-4 font-weight-bold mb-0" style="font-size: 3.5rem;">{{ $totalDosen }}</h2>
                    <span class="text-muted" style="font-size: 1.25rem;">Dosen</span>
                </div>
            </div>
        </div>
    </div>
 </div>
 
 <style>
 .card {
    background: #fff;
    border: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
 }
 
 .card-body {
    padding: 1.5rem;
 }
 
 .text-muted {
    color: #6B7280 !important;
 }
 
 h5.font-weight-bold {
    color: #111827;
    font-size: 1.50rem;
    line-height: 1.75rem;
 }

 small {
    color: #111827;
    font-size: 1rem;
    line-height: 1.75rem;
 }
 
 .display-4 {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
 }
 </style>

      <!-- Pie Chart -->
<div class="col-md-4 mb-4">
    <div class="card h-100 rounded-lg">
        <div class="card-body p-4">
            <!-- Header with description -->
            <div class="mb-4">
                <h5 class="font-weight-bold mb-1">Persentase Tahunan</h5>
                <small class="text-muted d-block mt-2">Perbandingan Jumlah Total sertifikat dan pelatihan yang telah dosen upload berdasarkan tahun lalu dan tahun ini.</small>
            </div>
                {{-- <div class="d-flex"> --}}
                
               <!-- Legend vertikal di atas -->
<div class="mb-3 d-flex flex-column align-items-start">
    <div class="d-flex align-items-center mb-2">
        <div style="width: 12px; height: 12px; background-color: #1e40af; margin-right: 8px;"></div>
        <span>Tahun {{ $currentYear }}</span>
    </div>
    <div class="d-flex align-items-center">
        <div style="width: 12px; height: 12px; background-color: #93c5fd; margin-right: 8px;"></div>
        <span>Tahun {{ $lastYear }}</span>
    </div>
</div>

                
                <!-- Chart -->
                <div style="height: 250px;">
                    <canvas id="yearlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
 
 @push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.register(ChartDataLabels);
    
    const ctx = document.getElementById('yearlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Tahun Ini', 'Tahun Lalu'],
            datasets: [{
                data: [{{ $percentThisYear }}, {{ $percentLastYear }}],
                backgroundColor: ['#1e40af', '#93c5fd'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,  // Mengaktifkan tooltip
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 10,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                },
                datalabels: {
                    color: '#ffffff',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        if (value > 0) {
                            return value + '%';
                        }
                        return '';
                    },
                    anchor: 'center',
                    align: 'center',
                    offset: 0
                }
            }
        }
    });
});
</script>
@endpush
 
 <style>
 .card {
    background: #fff;
    border: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
 }
 
 #yearlyChart {
    width: 100%;
    height: 100%;
 }
 </style>


<div class="col-md-4 mb-4">
    <div class="card h-100 rounded-lg">
        <div class="card-body p-4">
            <div class="d-flex flex-column">
                <h5 class="font-weight-bold mb-4">Tingkat Kontribusi Per Bulan</h5>
                <div class="contribution-list">
                    @foreach($monthlyData as $month => $count)
                        <div class="contribution-item d-flex align-items-center mb-3">
                            <div class="month-label">
                                {{ date("F", mktime(0, 0, 0, $month, 1)) }}
                            </div>
                            <div class="progress flex-grow-1 mx-2">
                                <div class="progress-bar bg-primary" 
                                    role="progressbar" 
                                    style="width: {{ $maxValue > 0 ? ($count / $maxValue) * 100 : 0 }}%">
                                </div>
                            </div>
                            <div class="count-label">
                                {{ $count }}
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart
    const ctx = document.getElementById('yearlyChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Tahun {{ $currentYear }}', 'Tahun {{ $lastYear }}'],
        datasets: [{
            data: [{{ $percentThisYear }}, {{ $percentLastYear }}],
            backgroundColor: ['#1e40af', '#93c5fd'],
            borderWidth: 0
        }]
    },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush  --}}

 {{-- <!-- Monthly Contribution -->
 <div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title">Tingkat Kontribusi Per Bulan</h5>
            <div class="progress-list">
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
// Pie Chart
const ctx = document.getElementById('yearlyChart').getContext('2d');
new Chart(ctx, {
type: 'pie',
data: {
labels: ['Tahun {{ $currentYear }}', 'Tahun {{ $lastYear }}'],
datasets: [{
    data: [{{ $percentThisYear }}, {{ $percentLastYear }}],
    backgroundColor: ['#1e40af', '#93c5fd'],
    borderWidth: 0
}]
},
options: {
    responsive: true,
    plugins: {
        legend: {
            position: 'bottom'
        }
    }
}
});
});
</script>
@endpush  --}}