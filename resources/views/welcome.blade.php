@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Sertifikasi Tersimpan</h6>
                    <h2 class="display-5 font-weight-bold"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Pelatihan Tersimpan</h6>
                    <h2 class="display-5 font-weight-bold"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">Dosen Mengikuti Pelatihan</h6>
                    <h2 class="display-5 font-weight-bold"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">Dosen Mengikuti Sertifikasi</h6>
                    <h2 class="display-5 font-weight-bold"></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row">
        <!-- Jumlah Dosen Card -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Dosen</h5>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-2x mr-3 text-secondary"></i>
                        <h2 class="display-4 mb-0 font-weight-bold"></h2>
                    </div>
                    <p class="text-muted">Dosen</p>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Persentase Tahunan</h5>
                    <canvas id="yearlyChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Contribution -->
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
            labels: ['Tahun 2024', 'Tahun 2023'],
            datasets: [{
                data: [80, 20],
                backgroundColor: [
                    '#1e3799',
                    '#d2deff'
                ]
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
@endpush