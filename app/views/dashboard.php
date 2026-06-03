<?php
$title = 'Dashboard';
require_once __DIR__ . '/header.php';
?>

<div class="page-header">
    <h1>Dashboard - Sistem Manajemen Parkir</h1>
    <p>Monitoring real-time penggunaan area parkir</p>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon total-vehicles">
            <i class="fas fa-motorcycle"></i>
        </div>
        <div class="stat-content">
            <h3>Total Motor</h3>
            <p class="stat-value"><?php echo $data['totalMotorcycles']; ?></p>
            <span class="stat-label">Sedang Parkir</span>
        </div>
    </div>
</div>

<section class="section">
    <h2 class="section-title">Status Area Parkir</h2>
    <div class="areas-grid">
        <?php foreach ($data['parkingAreas'] as $area): ?>
        <div class="area-card">
            <div class="area-header">
                <h3><?php echo htmlspecialchars($area['area_name']); ?></h3>
                <span class="area-code"><?php echo htmlspecialchars($area['area_code']); ?></span>
            </div>
            
            <div class="area-location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo htmlspecialchars($area['location']); ?>
            </div>
            
            <div class="capacity-info">
                <div class="capacity-row">
                    <span class="label"><i class="fas fa-motorcycle"></i> Motor</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo ($area['current_motorcycles'] / $area['motorcycle_capacity']) * 100; ?>%"></div>
                    </div>
                    <span class="capacity-text"><?php echo $area['current_motorcycles']; ?> / <?php echo $area['motorcycle_capacity']; ?></span>
                </div>
            </div>
            
            <div class="area-status <?php echo ($area['current_vehicles'] >= $area['total_capacity'] - 5) ? 'warning' : 'normal'; ?>">
                <?php 
                $percentage = ($area['current_vehicles'] / $area['total_capacity']) * 100;
                if ($percentage >= 90) {
                    echo '<span class="badge danger">Hampir Penuh</span>';
                } elseif ($percentage >= 70) {
                    echo '<span class="badge warning">Cukup Penuh</span>';
                } else {
                    echo '<span class="badge success">Tersedia</span>';
                }
                ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Recent Transactions -->
<   <h2 class="section-title">Aktivitas Terbaru</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Plat</th>
                    <th>Tipe Kendaraan</th>
                    <th>Pemilik</th>
                    <th>Area Parkir</th>
                    <th>Waktu Masuk</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['recentTransactions'])): ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada aktivitas</td>
                </tr>
                <?php else: ?>
                    <?php foreach (array_slice($data['recentTransactions'], 0, 10) as $trans): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($trans['plate_number']); ?></strong></td>
                        <td><?php echo htmlspecialchars($trans['type_name']); ?></td>
                        <td><?php echo htmlspecialchars($trans['owner_name'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($trans['area_name']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($trans['entry_time'])); ?></td>
                        <td>
                            <?php if ($trans['is_active']): ?>
                                <span class="badge success">Parkir</span>
                            <?php else: ?>
                                <span class="badge secondary">Keluar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
