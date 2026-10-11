<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stat bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $totalPeserta ?></h3>
                    <small>Total Peserta</small>
                </div>
                <i class="bi bi-people-fill fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">  
        <div class="card card-stat bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $totalGuru ?></h3>
                    <small>Total Guru</small>
                </div>
                <i class="bi bi-person-badge-fill fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat bg-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $totalSoal ?></h3>
                    <small>Total Soal</small>
                </div>
                <i class="bi bi-question-circle-fill fs-1"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat bg-danger">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $totalPaket ?></h3>
                    <small>Total Try Out</small>
                </div>
                <i class="bi bi-box-seam-fill fs-1"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header fw-semibold">Grafik Rata-rata Nilai per Paket Try Out</div>
    <div class="card-body">
        <canvas id="resultChart" height="100"></canvas>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const ctx = document.getElementById('resultChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [
                {
                    label: 'Rata-rata Nilai',
                    data: <?= json_encode($chartScores) ?>,
                    backgroundColor: '#4f6df5',
                    yAxisID: 'y',
                },
                {
                    label: 'Jumlah Peserta',
                    data: <?= json_encode($chartCounts) ?>,
                    backgroundColor: '#ffc107',
                    type: 'line',
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, position: 'left', title: { display: true, text: 'Nilai' } },
                y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Peserta' } },
            }
        }
    });
</script>
<?= $this->endSection() ?>