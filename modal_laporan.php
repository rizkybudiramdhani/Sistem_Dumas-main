<?php
// ===============================================
// PERBAIKAN KRITIS #1: OUTPUT BUFFERING (Wajib di baris pertama)
// Ini harus ada di awal file untuk menangkap output dari include files.
ob_start();
// ===============================================

// ========================
// CONFIG & SESSION
// ========================
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ========================
// KONSTANTA & VARIABEL LINGKUNGAN
// ========================
$is_ajax_request = $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_laporan']);

include_once 'config/koneksi.php';
// Cek status login
$id_user_session = $_SESSION['user_id'] ?? null;
$is_logged_in = !empty($id_user_session);

// ===============================================
// PROSES BACKEND (Handle POST request AJAX)
// ===============================================
if ($is_ajax_request) {

    // Ambil semua output yang terkumpul hingga saat ini (jika ada output dari require_once)
    $unexpected_output = ob_get_clean();

    // Set header JSON (sekarang aman karena buffer sudah bersih)
    header('Content-Type: application/json');

    $response = ['success' => false, 'message' => ''];

    // Jika ada output tak terduga (Error/Warning PHP), ini merusak JSON.
    if (!empty($unexpected_output)) {
        // Log error dan berikan pesan yang jelas ke client
        error_log("Output tak terduga sebelum JSON: " . substr(trim($unexpected_output), 0, 100));
        $response['message'] = 'INTERNAL SERVER ERROR: Output PHP yang tidak terduga merusak respons. Cek file include/koneksi.';
        echo json_encode($response);
        exit;
    }

    try {
        // Validasi koneksi
        if (!isset($db) || !$db) {
            throw new Exception('Koneksi database gagal. Periksa config/koneksi.php.');
        }

        // Ambil input
        // Menggunakan trim() saja karena akan di-bind menggunakan Prepared Statements
        $judul = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $lokasi = trim($_POST['lokasi'] ?? '');
        $anonim = isset($_POST['anonim']) && $_POST['anonim'] == '1';

        // Validasi input wajib
        if (empty($judul) || empty($deskripsi) || empty($lokasi)) {
            throw new Exception('Judul, deskripsi, dan lokasi wajib diisi!');
        }

        // Tentukan user
        $id_user_db = null;
        $nama_pelapor = '';
        $no_hp_pelapor = '';

        if ($anonim) {
            // Mode anonim
            $nama_pelapor = trim($_POST['nama_anonim'] ?? '');
            $hp_input = trim($_POST['hp_anonim'] ?? '');
            $no_hp_pelapor = preg_replace('/[^0-9]/', '', $hp_input); // Ambil hanya digit

            if (empty($nama_pelapor) || empty($no_hp_pelapor)) {
                throw new Exception('Nama dan nomor HP wajib diisi untuk laporan anonim!');
            }
            $id_user_db = null;
        } elseif ($is_logged_in) {
            // Mode login (Ambil data dari sesi)
            $id_user_db = intval($id_user_session);
            $nama_pelapor = $_SESSION['nama'] ?? 'Pengguna Terdaftar';
            $no_hp_pelapor = $_SESSION['telepon'] ?? '';
        } else {
            // User tidak login dan tidak memilih anonim
            throw new Exception('Anda harus login atau centang "Kirim laporan secara anonim"');
        }

        // ========================
        // Upload files
        // ========================
        $upload_dir = 'uploads/';

        if (!file_exists($upload_dir) && !@mkdir($upload_dir, 0777, true)) {
            throw new Exception('Gagal membuat folder upload. Cek izin tulis (permissions).');
        }

        $gambar_files = [];
        $video_files = [];
        $allowed_img = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_vid = ['mp4', 'avi', 'mov', 'wmv', 'mkv'];
        $max_file_size = 10485760; // 10MB

        if (isset($_FILES['bukti']) && !empty($_FILES['bukti']['name'][0])) {
            $total = count($_FILES['bukti']['name']);

            if ($total > 5) {
                throw new Exception('Maksimal 5 file!');
            }

            for ($i = 0; $i < $total; $i++) {
                if ($_FILES['bukti']['error'][$i] === 0) {
                    $tmp = $_FILES['bukti']['tmp_name'][$i];
                    $size = $_FILES['bukti']['size'][$i];
                    $ext = strtolower(pathinfo($_FILES['bukti']['name'][$i], PATHINFO_EXTENSION));

                    if ($size > $max_file_size) {
                        throw new Exception("Ukuran file '{$_FILES['bukti']['name'][$i]}' maksimal 10MB!");
                    }

                    $filename = 'lapor_' . time() . '_' . uniqid() . '.' . $ext;
                    $destination = $upload_dir . $filename;

                    if (in_array($ext, $allowed_img)) {
                        // Cek mime type asli untuk keamanan
                        if (exif_imagetype($tmp) === false && !getimagesize($tmp)) {
                            throw new Exception('File bukan gambar valid!');
                        }
                        if (move_uploaded_file($tmp, $destination)) {
                            $gambar_files[] = $destination;
                        }
                    } elseif (in_array($ext, $allowed_vid)) {
                        if (move_uploaded_file($tmp, $destination)) {
                            $video_files[] = $destination;
                        }
                    } else {
                        throw new Exception("Format file '{$ext}' tidak didukung!");
                    }
                }
            }
        }

        $gambar_str = implode(',', $gambar_files);
        $video_str = implode(',', $video_files);

        // ========================
        // PERBAIKAN KRITIS #2: INSERT database menggunakan Prepared Statements
        // ========================
        $sql = "INSERT INTO tabel_laporan 
             (id_user, id_tim, laporan, lokasi, gambar, video, 
             tanggal_lapor, status_laporan, judul_laporan, 
             tanggapan_admin, tanggal_tanggapan, nama, no_hp) 
             VALUES (?, NULL, ?, ?, ?, ?, NOW(), 'baru', ?, 
             '', '0000-00-00 00:00:00', ?, ?)";

        if ($stmt = $db->prepare($sql)) {

            // Tipe data untuk binding
            $types = "ssssssss"; // (s untuk id_user jika NULL/string, sisanya string s)

            // Data parameter
            $params = [
                $id_user_db,
                $deskripsi,
                $lokasi,
                $gambar_str,
                $video_str,
                $judul,
                $nama_pelapor,
                $no_hp_pelapor
            ];

            // Perbaikan binding NULL/Integer (tetap menggunakan string 's' untuk NULL)
            $bind_names[] = $types;
            for ($i = 0; $i < count($params); $i++) {
                $bind_names[] = &$params[$i];
            }

            if (!call_user_func_array('mysqli_stmt_bind_param', array_merge([$stmt], $bind_names))) {
                throw new Exception('Gagal mengikat parameter: ' . $stmt->error);
            }

            if (!$stmt->execute()) {
                throw new Exception('Gagal menyimpan laporan ke database: ' . $stmt->error);
            }

            $stmt->close();

            $response['success'] = true;
            $response['message'] = 'Laporan berhasil dikirim! Tim kami akan segera menindaklanjuti.';
        } else {
            throw new Exception('Gagal menyiapkan query: ' . $db->error);
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
?>

<style>
    /* CSS Disesuaikan (Tinggalkan saja seperti yang Anda buat, sudah bagus!) */
    #modalLaporan .modal-content {
        background: #1a1a1a;
        border-radius: 20px;
        border: 1px solid #333;
        color: #ffffff;
    }

    #modalLaporan .modal-header {
        background: #1E40AF;
        border-radius: 20px 20px 0 0;
        padding: 25px;
        border-bottom: 3px solid #FFD700;
    }

    #modalLaporan .modal-title {
        color: #FFD700;
        font-weight: 700;
    }

    #modalLaporan .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 1;
        transition: transform 0.3s ease;
    }

    #modalLaporan .btn-close:hover {
        transform: rotate(90deg);
        opacity: 0.8;
    }

    #modalLaporan .modal-body {
        padding: 30px;
    }

    #laporanForm .form-label {
        color: #FFD700;
        font-weight: 600;
        margin-bottom: 8px;
    }

    #laporanForm .form-control {
        background: #2a2a2a;
        border: 1px solid #444;
        color: #ffffff;
        border-radius: 10px;
        padding: 12px;
    }

    #laporanForm .form-control:focus {
        background: #2a2a2a;
        border-color: #FFD700;
        color: #ffffff;
        box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.15);
    }

    #laporanForm .form-control::placeholder {
        color: #666;
    }

    #laporanForm textarea {
        min-height: 120px;
    }

    #laporanForm .form-check-input {
        background-color: #2a2a2a;
        border-color: #444;
        width: 20px;
        height: 20px;
    }

    #laporanForm .form-check-input:checked {
        background-color: #FFD700;
        border-color: #FFD700;
    }

    #laporanForm .form-check-label {
        color: #e0e0e0;
        margin-left: 8px;
    }

    .anonim-section {
        display: none;
        background: #2a2a2a;
        border: 1px solid #444;
        border-radius: 10px;
        padding: 20px;
        margin-top: 15px;
        flex-wrap: wrap;
        /* Tambahkan ini agar layout di dalamnya rapi */
    }

    .anonim-section.show {
        display: flex;
        /* Tampilkan sebagai flex agar input berdampingan */
    }

    #submitBtn {
        background: #FFD700;
        border: none;
        color: #1E40AF;
        font-weight: 700;
        border-radius: 12px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    #submitBtn:hover {
        background: #1E40AF;
        color: #FFD700;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }

    #submitBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #6c757d;
        color: #ffffff;
    }

    .alert {
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-success {
        background: #10b981;
        color: #ffffff;
        border-left: 4px solid #059669;
    }

    .alert-danger {
        background: #ef4444;
        color: #ffffff;
        border-left: 4px solid #dc2626;
    }

    .alert-info {
        background: #1E40AF;
        color: #FFD700;
        border-left: 4px solid #FFD700;
        font-weight: 600;
    }

    .form-text {
        color: #999 !important;
        font-size: 0.85rem;
    }
</style>

<div class="modal fade" id="modalLaporan" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-megaphone-fill me-2"></i>Laporan Pengaduan Masyarakat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div id="laporanMessage"></div>

                <?php if (!$is_logged_in): ?>
                    <div class="alert alert-info">
                        Anda belum login. Silakan login atau centang opsi anonim di bawah.
                    </div>
                <?php endif; ?>

                <form id="laporanForm" enctype="multipart/form-data">
                    <input type="hidden" name="submit_laporan" value="1">

                    <div class="mb-3">
                        <label for="judul" class="form-label">
                            <i class="bi bi-file-text me-2"></i>Judul Laporan <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Peredaran narkoba di Jalan Merdeka" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            <i class="bi bi-card-text me-2"></i>Deskripsi Lengkap <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Jelaskan detail kejadian..." required></textarea>
                        <div class="form-text">Semakin detail informasi, semakin mudah ditindaklanjuti</div>
                    </div>

                    <div class="mb-3">
                        <label for="lokasi" class="form-label">
                            <i class="bi bi-geo-alt-fill me-2"></i>Lokasi Kejadian <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Alamat lengkap kejadian" required>
                    </div>

                    <div class="mb-3">
                        <label for="bukti" class="form-label">
                            <i class="bi bi-camera-fill me-2"></i>Upload Bukti (Foto/Video)
                        </label>
                        <input type="file" class="form-control" id="bukti" name="bukti[]" multiple accept="image/*,video/*">
                        <div class="form-text">Maksimal 5 file, masing-masing 10MB</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="anonim" name="anonim" value="1" onchange="toggleAnonim()">
                            <label class="form-check-label" for="anonim">
                                <i class="bi bi-incognito me-2"></i>Kirim laporan secara anonim (tanpa login)
                            </label>
                        </div>
                    </div>

                    <div class="anonim-section" id="anonimSection">
                        <h6 class="text-warning mb-3 w-100">
                            <i class="bi bi-exclamation-triangle me-2"></i>Data Pelapor Anonim
                        </h6>

                        <div class="mb-3 col-md-6 pe-md-2">
                            <label for="nama_anonim" class="form-label">Nama Anda *</label>
                            <input type="text" class="form-control" id="nama_anonim" name="nama_anonim" placeholder="Nama lengkap">
                        </div>

                        <div class="mb-3 col-md-6 ps-md-2">
                            <label for="hp_anonim" class="form-label">Nomor HP *</label>
                            <input type="tel" class="form-control" id="hp_anonim" name="hp_anonim" placeholder="081234567890" pattern="[0-9]{10,13}">
                            <div class="form-text">Format: 081234567890 (10-13 digit)</div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none me-2"></span>
                            <i class="bi bi-send-fill me-2"></i>
                            <span id="btnText">Kirim Laporan</span>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function toggleAnonim() {
        const checkbox = document.getElementById('anonim');
        const section = document.getElementById('anonimSection');
        const namaInput = document.getElementById('nama_anonim');
        const hpInput = document.getElementById('hp_anonim');

        if (checkbox.checked) {
            section.classList.add('show');
            namaInput.required = true;
            hpInput.required = true;
        } else {
            section.classList.remove('show');
            // Reset required state
            namaInput.required = false;
            hpInput.required = false;
            // Bersihkan nilai saat non-anonim
            namaInput.value = '';
            hpInput.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleAnonim(); // Initial call to set state

        const laporanForm = document.getElementById('laporanForm');
        const modalLaporan = document.getElementById('modalLaporan');
        const submitBtn = document.getElementById('submitBtn');

        // Pastikan semua elemen ada sebelum menambahkan event listener
        if (!laporanForm || !modalLaporan || !submitBtn) {
            console.error('Element tidak ditemukan. Pastikan DOM sudah siap.');
            return;
        }

        // Pastikan tombol submit kembali normal saat modal dibuka/ditutup
        modalLaporan.addEventListener('hidden.bs.modal', function() {
            const messageDiv = document.getElementById('laporanMessage');
            const spinner = submitBtn.querySelector('.spinner-border');
            const btnText = document.getElementById('btnText');

            if (messageDiv) messageDiv.innerHTML = '';
            laporanForm.reset();
            toggleAnonim();
            submitBtn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
            if (btnText) btnText.textContent = 'Kirim Laporan';
        });

        laporanForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const spinner = submitBtn.querySelector('.spinner-border');
            const messageDiv = document.getElementById('laporanMessage');

            // Client-Side Validation
            const fileInput = document.getElementById('bukti');
            if (fileInput.files.length > 5) {
                messageDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Maksimal 5 file!</div>';
                return;
            }

            for (let i = 0; i < fileInput.files.length; i++) {
                if (fileInput.files[i].size > 10485760) {
                    messageDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Ukuran file maksimal 10MB!</div>';
                    return;
                }
            }

            // Loading
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.textContent = 'Mengirim...';
            messageDiv.innerHTML = '';

            // Submit
            fetch('modal_laporan.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server error: ' + response.status);
                    }
                    return response.text().then(text => {
                        // Mencoba parse JSON
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('JSON Parse Error:', e);
                            console.error('Response text:', text);
                            // Jika gagal, tampilkan teks mentah sebagai error
                            throw new Error('Respons bukan JSON valid. Cek console untuk detail lengkap.');
                        }
                    });
                })
                .then(data => {
                    if (data.success) {
                        messageDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i>' + data.message + '</div>';
                        laporanForm.reset();
                        toggleAnonim();

                        // Auto close modal setelah 3 detik
                        setTimeout(() => {
                            const modalInstance = bootstrap.Modal.getInstance(modalLaporan);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        }, 3000);
                    } else {
                        messageDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill me-2"></i>' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    messageDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi kesalahan: ' + error.message + '</div>';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Kirim Laporan';
                });
        });
    });
</script>

<?php
// ===============================================
// Wajib di akhir file
// ===============================================
if (!$is_ajax_request) {
    ob_end_flush();
}
?>