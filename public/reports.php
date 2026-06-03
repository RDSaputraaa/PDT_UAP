<?php
require_once dirname(__DIR__) . '/config/database.php';

require_once dirname(__DIR__) . '/app/models/ParkingArea.php';
require_once dirname(__DIR__) . '/app/models/ParkingTransaction.php';
require_once dirname(__DIR__) . '/app/models/Vehicle.php';

require_once dirname(__DIR__) . '/app/views/header.php';

$title = 'Laporan';

$parkingArea = new ParkingArea($conn);
$parkingTransaction = new ParkingTransaction($conn);

$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

$statistics = $parkingTransaction->getStatistics($start_date, $end_date);

$allAreas = $parkingArea->getAll();
?>

<div class="page-header">
    <h1>Laporan Penggunaan Parkir</h1>
    <p>Laporan statistik penggunaan area parkir</p>
</div>

<section class="section">
    <h2 class="section-title">Filter Laporan</h2>
    <form method="GET" action="" style="display: grid; grid-template-columns: 1fr 1fr 150px; gap: 15px; align-items: flex-end;">
        <div class="form-group">
            <label for="start_date">Dari Tanggal</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_date">Sampai Tanggal</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Filter
        </button>
    </form>
</section>

<section class="section">
    <h2 class="section-title">Statistik Penggunaan Area Parkir</h2>
    
    <?php if (empty($statistics)): ?>
        <p class="text-center">Tidak ada data untuk periode yang dipilih</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Area</th>
                        <th>Nama Area</th>
                        <th>Total Motor</th>
                        <th>Rata-rata Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotalMotorcycles = 0;
                    
                    foreach ($statistics as $stat):
                        $grandTotalMotorcycles += $stat['total_motorcycles'];
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($stat['area_code']); ?></strong></td>
                        <td><?php echo htmlspecialchars($stat['area_name']); ?></td>
                        <td><?php echo $stat['total_motorcycles']; ?></td>
                        <td>
                            <?php 
                            if ($stat['avg_duration']) {
                                $hours = floor($stat['avg_duration'] / 60);
                                $mins = round($stat['avg_duration'] % 60);
                                echo $hours . 'h ' . $mins . 'm';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr style="background: var(--light-bg); font-weight: bold;">
                        <td colspan="2">TOTAL</td>
                        <td><?php echo $grandTotalMotorcycles; ?></td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../../app/views/footer.php'; ?>
