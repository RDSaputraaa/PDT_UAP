<?php
$title = 'Masuk Kendaraan';
require_once __DIR__ . '/header.php';
?>

<div class="page-header">
    <h1>Pencatatan Masuk Kendaraan</h1>
    <p>Catat kendaraan yang masuk ke area parkir</p>
</div>

<?php if (isset($data['message']) && !empty($data['message'])): ?>
<div class="alert alert-<?php echo $data['message']['type']; ?>" role="alert">
    <i class="fas fa-<?php echo $data['message']['type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
    <?php echo htmlspecialchars($data['message']['text']); ?>
    <?php if ($data['message']['type'] == 'success' && isset($data['transaction'])): ?>
        <br>
        <small>Plat: <?php echo htmlspecialchars($data['transaction']['plate']); ?> | Tipe: <?php echo htmlspecialchars($data['transaction']['type']); ?></small>
    <?php endif; ?>
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
            <small>Format: Contoh B 1234 CD (tanpa spasi atau dengan spasi)</small>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="vehicle_type_id">Tipe Kendaraan <span class="required">*</span></label>
                <select id="vehicle_type_id" name="vehicle_type_id" required class="form-control">
                    <option value="">-- Pilih Tipe Kendaraan --</option>
                    <?php foreach ($data['vehicleTypes'] as $type): ?>
                    <option value="<?php echo $type['id']; ?>">
                        <?php echo htmlspecialchars($type['type_name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
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
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="owner_name">Nama Pemilik</label>
                <input type="text" id="owner_name" name="owner_name" 
                       placeholder="Nama pemilik kendaraan"
                       class="form-control">
            </div>
            
            <div class="form-group">
                <label for="owner_phone">Nomor Telepon</label>
                <input type="tel" id="owner_phone" name="owner_phone" 
                       placeholder="Nomor telepon"
                       class="form-control">
            </div>
        </div>
        
        <div class="form-group">
            <label for="owner_email">Email</label>
            <input type="email" id="owner_email" name="owner_email" 
                   placeholder="Email pemilik"
                   class="form-control">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">
                <i class="fas fa-sign-in-alt"></i> Catat Masuk
            </button>
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </form>
</div>

<!-- Quick Info -->
<section class="section">
    <h2 class="section-title">Informasi Area Parkir</h2>
    <div class="areas-simple">
        <?php foreach ($data['parkingAreas'] as $area): ?>
        <div class="area-info-item">
            <div class="area-info-header">
                <h4><?php echo htmlspecialchars($area['area_name']); ?></h4>
                <span class="area-code"><?php echo htmlspecialchars($area['area_code']); ?></span>
            </div>
            <p class="area-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($area['location']); ?></p>
            <div class="capacity-simple">
                <div><i class="fas fa-motorcycle"></i> Motor: <?php echo $area['motorcycle_capacity']; ?> slot</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
