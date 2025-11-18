<?php
// Get id laporan from URL
$id_laporan = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_laporan == 0) {
    echo '<div class="alert alert-danger">ID Laporan tidak valid!</div>';
    exit;
}

// Get laporan detail
$query = "SELECT l.*, u.nama as nama_pelapor, u.email as email_pelapor
          FROM tabel_laporan l 
          LEFT JOIN tabel_users u ON l.id_user = u.id_users
          WHERE l.id_laporan = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id_laporan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo '<div class="alert alert-danger">Laporan tidak ditemukan!</div>';
    exit;
}

$laporan = mysqli_fetch_assoc($result);

// Get telepon from laporan table (no_hp column)
$telp_pelapor = $laporan['no_hp'];

// Handle update status
$success_message = '';
$error_message = '';

if (isset($_POST['update_status'])) {
    $status_baru = mysqli_real_escape_string($db, $_POST['status_baru']);
    $tanggapan = mysqli_real_escape_string($db, $_POST['tanggapan']);
    $petugas = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin';

    $query_update = "UPDATE tabel_laporan 
                     SET status_laporan = ?, 
                         tanggapan_admin = ?, 
                         tanggal_tanggapan = NOW() 
                     WHERE id_laporan = ?";
    $stmt_update = mysqli_prepare($db, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ssi", $status_baru, $tanggapan, $id_laporan);

    if (mysqli_stmt_execute($stmt_update)) {
        $success_message = 'Status berhasil diupdate!';

        // Refresh data
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $laporan = mysqli_fetch_assoc($result);
    } else {
        $error_message = 'Gagal update status!';
    }
}

// Status badge class
$status_class = 'secondary';
$status_icon = 'dw-file';
if ($laporan['status_laporan'] == 'baru') {
    $status_class = 'warning';
    $status_icon = 'dw-inbox';
} elseif (strpos($laporan['status_laporan'], 'diproses') !== false) {
    $status_class = 'info';
    $status_icon = 'dw-loading';
} elseif (strpos($laporan['status_laporan'], 'selesai') !== false) {
    $status_class = 'success';
    $status_icon = 'dw-checked';
}

// Parse images
$images = [];
if (!empty($laporan['gambar'])) {
    $images = explode(',', $laporan['gambar']);
}

// Get nama pelapor
$nama_pelapor = 'Anonim';
if (!empty($laporan['nama_pelapor'])) {
    $nama_pelapor = $laporan['nama_pelapor'];
} elseif (!empty($laporan['nama'])) {
    $nama_pelapor = $laporan['nama'];
}
?>

<style>
    .detail-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 20px;
    }

    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
    }

    .detail-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .detail-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        opacity: 0.9;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-badge-large {
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .info-section {
        margin-bottom: 30px;
    }

    .info-section h5 {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .info-value {
        font-weight: 600;
        color: #495057;
        font-size: 1rem;
    }

    .content-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #667eea;
        line-height: 1.8;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .gallery-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
        margin-top: 20px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding: 15px 20px;
        margin-bottom: 15px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 20px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 3px solid #fff;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .tanggapan-box {
        background: linear-gradient(135deg, #e0f7fa 0%, #e1f5fe 100%);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #00acc1;
    }

    .no-tanggapan {
        text-align: center;
        padding: 30px;
        color: #adb5bd;
    }

    /* Lightbox for images */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        cursor: pointer;
        z-index: 10000;
    }

    @media print {

        .action-buttons,
        .no-print {
            display: none !important;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header no-print">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Detail Pengaduan</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dash.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="dash.php?page=lihat-pengaduan">Lihat Pengaduan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <div class="action-buttons">
                <a href="dash.php?page=lihat-pengaduan" class="btn btn-secondary btn-action">
                    <i class="dw dw-left-arrow"></i> Kembali
                </a>
                <button class="btn btn-primary btn-action" onclick="window.print()">
                    <i class="dw dw-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
        <strong><i class="dw dw-checked"></i> Berhasil!</strong> <?php echo $success_message; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show no-print" role="alert">
        <strong><i class="dw dw-warning"></i> Error!</strong> <?php echo $error_message; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<!-- Detail Header -->
<div class="detail-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="detail-title"><?php echo htmlspecialchars($laporan['judul_laporan']); ?></div>
            <div class="detail-meta">
                <div class="meta-item">
                    <i class="dw dw-calendar1"></i>
                    <span><?php echo date('d F Y, H:i', strtotime($laporan['tanggal_lapor'])); ?> WIB</span>
                </div>
                <div class="meta-item">
                    <i class="dw dw-user1"></i>
                    <span><?php echo htmlspecialchars($nama_pelapor); ?></span>
                </div>
                <div class="meta-item">
                    <i class="dw dw-map"></i>
                    <span><?php echo htmlspecialchars($laporan['lokasi']); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <span class="status-badge-large badge-<?php echo $status_class; ?>">
                <i class="dw <?php echo $status_icon; ?>"></i>
                <?php echo ucfirst($laporan['status_laporan']); ?>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-md-8">

        <!-- Isi Laporan -->
        <div class="detail-card">
            <div class="info-section">
                <h5>📝 Isi Laporan</h5>
                <div class="content-box">
                    <?php echo nl2br(htmlspecialchars($laporan['laporan'])); ?>
                </div>
            </div>
        </div>

        <!-- Bukti Foto -->
        <?php if (!empty($images)): ?>
            <div class="detail-card">
                <div class="info-section">
                    <h5>📷 Bukti Foto/Dokumen</h5>
                    <div class="image-gallery">
                        <?php foreach ($images as $image):
                            $image = trim($image);
                            if (!empty($image) && file_exists($image)):
                        ?>
                                <div class="gallery-item" onclick="openLightbox('<?php echo htmlspecialchars($image); ?>')">
                                    <img src="<?php echo htmlspecialchars($image); ?>" alt="Bukti">
                                </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tanggapan Admin -->
        <div class="detail-card">
            <div class="info-section">
                <h5>💬 Tanggapan Petugas</h5>
                <?php if (!empty($laporan['tanggapan_admin'])): ?>
                    <div class="tanggapan-box">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Tanggapan:</strong>
                            <small class="text-muted">
                                <?php
                                if ($laporan['tanggal_tanggapan'] != '0000-00-00 00:00:00') {
                                    echo date('d M Y, H:i', strtotime($laporan['tanggal_tanggapan']));
                                }
                                ?>
                            </small>
                        </div>
                        <div><?php echo nl2br(htmlspecialchars($laporan['tanggapan_admin'])); ?></div>
                    </div>
                <?php else: ?>
                    <div class="no-tanggapan">
                        <i class="dw dw-chat" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mb-0 mt-2">Belum ada tanggapan dari petugas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Right Column -->
    <div class="col-md-4">

        <!-- Informasi Pelapor -->
        <div class="detail-card">
            <div class="info-section">
                <h5>👤 Informasi Pelapor</h5>

                <div class="info-item mb-3">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value"><?php echo htmlspecialchars($nama_pelapor); ?></div>
                </div>

                <div class="info-item mb-3">
                    <div class="info-label">No. HP/Telepon</div>
                    <div class="info-value"><?php echo htmlspecialchars($laporan['no_hp']); ?></div>
                </div>

                <?php if (!empty($laporan['email_pelapor'])): ?>
                    <div class="info-item mb-3">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($laporan['email_pelapor']); ?></div>
                    </div>
                <?php endif; ?>

                <div class="info-item">
                    <div class="info-label">Tanggal Lapor</div>
                    <div class="info-value">
                        <?php echo date('d M Y', strtotime($laporan['tanggal_lapor'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="detail-card">
            <div class="info-section">
                <h5>📊 Timeline Status</h5>
                <div class="timeline">
                    <div class="timeline-item">
                        <strong>Laporan Dibuat</strong>
                        <div class="small text-muted">
                            <?php echo date('d M Y, H:i', strtotime($laporan['tanggal_lapor'])); ?>
                        </div>
                        <div class="mt-2">Status: <span class="badge badge-warning">Baru</span></div>
                    </div>

                    <?php if ($laporan['tanggal_tanggapan'] != '0000-00-00 00:00:00'): ?>
                        <div class="timeline-item">
                            <strong>Ditanggapi Petugas</strong>
                            <div class="small text-muted">
                                <?php echo date('d M Y, H:i', strtotime($laporan['tanggal_tanggapan'])); ?>
                            </div>
                            <div class="mt-2">
                                Status: <span class="badge badge-<?php echo $status_class; ?>">
                                    <?php echo ucfirst($laporan['status_laporan']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Update Status (Only for Admin/Petugas) -->
        <div class="detail-card no-print">
            <div class="info-section">
                <h5>⚙️ Update Status</h5>
                <form method="POST">
                    <div class="form-group">
                        <label class="font-weight-600">Status Baru</label>
                        <select class="form-control" name="status_baru" required>
                            <?php
                            $session_nama = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : '';
                            if (strpos($session_nama, 'ditresnarkoba') !== false) {
                                echo '<option value="baru" ' . ($laporan['status_laporan'] == 'baru' ? 'selected' : '') . '>Baru</option>';
                            }
                            if (strpos($session_nama, 'ditsamapta') !== false) {
                                echo '<option value="diproses ditsamapta" ' . ($laporan['status_laporan'] == 'diproses ditsamapta' ? 'selected' : '') . '>Diproses Ditsamapta</option>';
                                echo '<option value="selesai ditsamapta" ' . ($laporan['status_laporan'] == 'selesai ditsamapta' ? 'selected' : '') . '>Selesai Ditsamapta</option>';
                            }
                            if (strpos($session_nama, 'ditbinmas') !== false) {
                                echo '<option value="diproses ditbinmas" ' . ($laporan['status_laporan'] == 'diproses ditbinmas' ? 'selected' : '') . '>Diproses Ditbinmas</option>';
                                echo '<option value="selesai ditbinmas" ' . ($laporan['status_laporan'] == 'selesai ditbinmas' ? 'selected' : '') . '>Selesai Ditbinmas</option>';
                            }
                            if (strpos($session_nama, 'ditresnarkoba') !== false) {
                                echo '<option value="diproses ditresnarkoba" ' . ($laporan['status_laporan'] == 'diproses ditresnarkoba' ? 'selected' : '') . '>Diproses Ditresnarkoba</option>';
                                echo '<option value="selesai" ' . ($laporan['status_laporan'] == 'selesai' ? 'selected' : '') . '>Selesai</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-600">Tanggapan/Keterangan</label>
                        <textarea class="form-control" name="tanggapan" rows="4"
                            placeholder="Berikan tanggapan atau keterangan..."
                            required><?php echo htmlspecialchars($laporan['tanggapan_admin']); ?></textarea>
                    </div>

                    <button type="submit" name="update_status" class="btn btn-primary btn-block">
                        <i class="dw dw-diskette"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img src="" id="lightbox-img" alt="Preview">
</div>

<script>
    function openLightbox(imageSrc) {
        document.getElementById('lightbox').classList.add('active');
        document.getElementById('lightbox-img').src = imageSrc;
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close lightbox on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });

    // Auto dismiss alert
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>