@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Sertifikasi</h6>
                    <h2 class="display-4"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary">
                <div class="card-body">
                    <h6 class="card-title">Total Pelatihan</h6>
                    <h2 class="display-4"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary">
                <div class="card-body">
                    <h6 class="card-title">Dosen Mengikuti Pelatihan</h6>
                    <h2 class="display-4"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary">
                <div class="card-body">
                    <h6 class="card-title">Dosen Mengikuti Sertifikasi</h6>
                    <h2 class="display-4"></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row">
        <!-- Jumlah Dosen Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Dosen</h5>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-2x mr-3 text-secondary"></i>
                        <h2 class="display-4 mb-0"></h2>
                    </div>
                    <p class="text-muted">Dosen</p>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Persentase Tahunan</h5>
                    <canvas id="yearlyChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Contribution -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tingkat Kontribusi Per Bulan</h5>
                    <div class="progress-list">
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
    </div>
</div>

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
@endsection