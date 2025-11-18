<?php
// Include database connection (sudah ada session_start di dalamnya)
include 'config/koneksi.php';

// Initialize error variable
$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tim']) && isset($_POST['pass'])) {

    // CSRF Token validation (optional, bisa di-comment kalau belum butuh)
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //     $error = "Invalid CSRF token!";
    // } else {

    $role = mysqli_real_escape_string($db, $_POST['tim']);
    $password = $_POST['pass'];

    // LOGIN TIM (Ditresnarkoba / Ditbinmas / Ditsamapta) - dari tabel_tim
    $query = "SELECT id_tim, nama, username, password, role
              FROM tabel_tim
              WHERE role = ?
              LIMIT 1";

    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $role);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $data['password'])) {
            // Set session untuk tim
            $_SESSION['login'] = true; // PENTING: untuk gate.php
            $_SESSION['id_tim'] = $data['id_tim'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['nama'] = $data['nama']; // Optional: untuk display
            $_SESSION['username'] = $data['username']; // Optional

            // Regenerate session ID untuk security
            session_regenerate_id(true);

            // Redirect ke dashboard - GANTI SESUAI KEBUTUHAN
            header("Location: dash.php"); // GANTI dari dashboard_A.php ke dash.php
            exit;
        } else {
            $error = "Role dan password tidak sesuai!";
        }
    } else {
        $error = "Role dan password tidak sesuai!";
    }
    mysqli_stmt_close($stmt);
    // }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Trisula</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
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
                            <h2 class="text-center text-primary">Login Trisula</h2>
                        </div>

                        <!-- Error Message Display -->
                        <div id="errorContainer"></div>

                        <form method="POST" id="loginForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <!-- Department Selection -->
                            <div class="select-role">
                                <div class="input-group custom">
                                    <select class="form-control form-control-lg" id="tim" name="tim" required autocomplete="off">
                                        <option value="">-- Pilih Tim --</option>
                                        <option value="ditresnarkoba">Ditresnarkoba</option>
                                        <option value="ditsamapta">Ditsamapta</option>
                                        <option value="ditbinmas">Ditbinmas</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="input-group custom">
                                <input type="password"
                                    class="form-control form-control-lg"
                                    name="pass"
                                    placeholder="Password"
                                    required
                                    autocomplete="current-password">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <input class="btn btn-primary btn-lg btn-block"
                                            type="submit"
                                            value="Sign In"
                                            id="submitBtn">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.value = 'Memproses...';

            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.value = 'Sign In';
            }, 3000);
        });
    </script>

    <!-- JS -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top',
            }
        });

        <?php if (!empty($error)): ?>
            notyf.error('<?php echo htmlspecialchars($error); ?>');
        <?php endif; ?>
    </script>
</body>

</html>