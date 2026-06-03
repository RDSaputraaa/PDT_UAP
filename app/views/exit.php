<?php
$title = 'Keluar Kendaraan';
require_once __DIR__ . '/header.php';
?>

<div class="page-header">
    <h1>Pencatatan Keluar Kendaraan</h1>
    <p>Catat kendaraan yang keluar dari area parkir</p>
</div>

<?php if (isset($data['message']) && !empty($data['message'])): ?>
<div class="alert alert-<?php echo $data['message']['type']; ?>" role="alert">
    <i class="fas fa-<?php echo $data['message']['type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
    <?php echo htmlspecialchars($data['message']['text']); ?>
</div>
<?php endif; ?>

<?php if (isset($data['transaction']) && !empty($data['transaction'])): ?>
<div class="transaction-detail">
    <div class="detail-card">
        <h3>Detail Kendaraan Keluar</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="label">Nomor Plat:</span>
                <span class="value"><?php echo htmlspecialchars($data['transaction']['plate_number']); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Pemilik:</span>
                <span class="value"><?php echo htmlspecialchars($data['transaction']['owner_name'] ?? '-'); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Area Parkir:</span>
                <span class="value"><?php echo htmlspecialchars($data['transaction']['area_name']); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Waktu Masuk:</span>
                <span class="value"><?php echo date('d/m/Y H:i:s', strtotime($data['transaction']['entry_time'])); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Waktu Keluar:</span>
                <span class="value"><?php echo date('d/m/Y H:i:s', strtotime($data['transaction']['exit_time'])); ?></span>
            </div>
            <div class="detail-item">
                <span class="label">Durasi Parkir:</span>
                <span class="value">
                    <?php 
                    $minutes = $data['transaction']['duration_minutes'];
                    $hours = floor($minutes / 60);
                    $mins = $minutes % 60;
                    echo $hours . 'j ' . $mins . 'm';
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="" class="form-entry-exit">
        <div class="form-group">
            <label for="plate_number">Nomor Plat <span class="required">*</span></label>
            <input type="text" id="plate_number" name="plate_number" 
                   placeholder="Contoh: B 1234 CD" 
                   required 
                   autocomplete="off"
                   class="form-control input-large"
                   autofocus>
            <small>Nomor plat kendaraan yang akan keluar</small>
        </div>
        
        <div class="form-group">
            <label for="parking_area_id">Area Parkir <span class="required">*</span></label>
            <select id="parking_area_id" name="parking_area_id" required class="form-control">
                <option value="">-- Pilih Area Parkir --</option>
                <?php foreach ($data['parkingAreas'] as $area): ?>
                <option value="<?php echo $area['id']; ?>">
                    <?php echo htmlspecialchars($area['area_name']); ?> (<?php echo htmlspecialchars($area['area_code']); ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-danger btn-large">
                <i class="fas fa-sign-out-alt"></i> Catat Keluar
            </button>
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
