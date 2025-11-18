<?php
// BARIS KRUSIAL: Memulai sesi PHP agar variabel $_SESSION dapat digunakan
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

$error = null; // Inisialisasi variabel error

// Mengatur header untuk AJAX response (agar tidak ada masalah CORS/encoding)
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menggunakan 'username' untuk menangkap input yang bisa berupa username atau telepon
    $identifier = mysqli_real_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    // Query mencari user berdasarkan kolom username ATAU kolom telepon
    // Pastikan nama kolom 'telepon' sudah benar seperti di tabel
    $query = "SELECT * FROM tabel_users WHERE username = ? OR telepon = ?";
    $stmt = mysqli_prepare($db, $query);

    // Bind parameter untuk dua placeholder "s" (string, string)
    mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password yang sudah di-hash
        if (password_verify($password, $user['password'])) {
            // Setup Session setelah login berhasil
            $_SESSION['user_id'] = $user['id_users'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['status'] = $user['status'];
            $_SESSION['telepon'] = $user['telepon'];

            // Respon untuk AJAX
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => true, 'message' => 'Login berhasil']);
                exit;
            } else {
                // Redirect untuk non-AJAX request (normal form submit)
                header("Location: index.php");
                exit;
            }
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "Username atau Nomor Telepon tidak ditemukan";
    }

    // Respon Error untuk AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => $error]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Trisula</title>

    <link href="imgg/trisula.png" rel="icon">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>

<body class="login-page">

    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="vendors/images/login-page-img.png" alt="">
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Login Account</h2>
                        </div>
                        <form method="POST" action="">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <div class="input-group custom">
                                <input type="text" name="username" class="form-control form-control-lg" placeholder="Username atau Nomor Telepon" required>
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            <div class="input-group custom">
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="**********" required>
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Remember</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password"><a href="forgot-password.html">Forgot Password</a></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                                    </div>
                                    <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373"><a href="index.php" style="text-decoration : none">Back</a></div>
                                    <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">OR</div>
                                    <div class="input-group mb-0">
                                        <a class="btn btn-outline-primary btn-lg btn-block" href="register.php">Register To Create Account</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
</body>

</html>