<!-- Modal Login - Modern Design -->
<div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-login-custom">
            
            <!-- Header -->
            <div class="modal-header-login">
                <div class="text-center w-100">
                    <div class="login-icon mb-3">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h4 class="modal-title fw-bold text-white mb-1" id="modalLoginLabel">Selamat Datang</h4>
                    <p class="text-white-50 mb-0">Silakan login untuk melanjutkan</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                
                <!-- Alert Messages -->
                <div id="loginMessage"></div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="login.php">
                    
                    <!-- Username/Phone Input -->
                    <div class="mb-3">
                        <label for="modalUsername" class="form-label fw-semibold">
                            <i class="bi bi-person-circle me-2"></i>Username atau Nomor Telepon
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-person text-primary"></i>
                            </span>
                            <input type="text" 
                                   class="form-control border-start-0 ps-0" 
                                   id="modalUsername" 
                                   name="username" 
                                   placeholder="Masukkan username atau nomor HP"
                                   required>
                        </div>
                        <small class="text-muted">Contoh: johndoe atau 081234567890</small>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="modalPassword" class="form-label fw-semibold">
                            <i class="bi bi-lock-fill me-2"></i>Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-key text-primary"></i>
                            </span>
                            <input type="password" 
                                   class="form-control border-start-0 border-end-0 ps-0" 
                                   id="modalPassword" 
                                   name="password" 
                                   placeholder="Masukkan password"
                                   required>
                            <span class="input-group-text bg-light border-start-0 cursor-pointer" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="modalRemember" name="remember">
                            <label class="form-check-label" for="modalRemember">
                                Ingat saya
                            </label>
                        </div>
                        <a href="forgot_password.php" class="text-decoration-none small">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-login w-100 py-3 mb-3" id="loginBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        <span id="loginBtnText">Masuk Sekarang</span>
                    </button>

                    <!-- Divider -->
                    <div class="divider-text mb-3">
                        <span>atau</span>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="mb-2 text-muted">Belum punya akun?</p>
                        <a href="register.php" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-person-plus me-2"></i>
                            Daftar Akun Baru
                        </a>
                    </div>

                </form>
            </div>

            <!-- Footer Info -->
            <div class="modal-footer-login">
                <div class="text-center w-100">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Data Anda aman dan terenkripsi
                    </small>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- CSS untuk Modal Login -->
<style>
.modal-login-custom {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 10px 50px rgba(0,0,0,0.3);
}

.modal-header-login {
    background: linear-gradient(135deg, #1E40AF, #1E3A8A);
    padding: 40px 30px 30px;
    border-bottom: none;
    position: relative;
}

.modal-header-login .btn-close {
    position: absolute;
    top: 15px;
    right: 15px;
}

.login-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 2.5rem;
    color: white;
}

.input-group-text {
    border-radius: 10px;
}

.form-control {
    border-radius: 10px;
    padding: 12px;
}

.input-group .form-control:focus {
    box-shadow: none;
    border-color: #1E40AF;
}

.input-group .input-group-text {
    background: transparent !important;
}

.btn-login {
    background: linear-gradient(135deg, #1E40AF, #1E3A8A);
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    color: white;
    transition: all 0.3s;
}

.btn-login:hover {
    background: linear-gradient(135deg, #1E3A8A, #1E40AF);
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(30, 64, 175, 0.4);
    color: white;
}

.btn-login:disabled {
    opacity: 0.7;
    transform: none;
}

.btn-outline-primary {
    border-radius: 12px;
    font-weight: 600;
    border-width: 2px;
}

.divider-text {
    position: relative;
    text-align: center;
}

.divider-text::before,
.divider-text::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 45%;
    height: 1px;
    background: #dee2e6;
}

.divider-text::before {
    left: 0;
}

.divider-text::after {
    right: 0;
}

.divider-text span {
    background: white;
    padding: 0 10px;
    color: #6c757d;
    font-size: 0.9rem;
}

.modal-footer-login {
    background: #f8f9fa;
    padding: 15px;
    border-top: 1px solid #dee2e6;
}

.cursor-pointer {
    cursor: pointer;
}

/* Alert Styles */
#loginMessage .alert {
    border-radius: 10px;
    border: none;
    margin-bottom: 20px;
}

#loginMessage .alert-success {
    background: #d1fae5;
    color: #065f46;
}

#loginMessage .alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

/* Loading Animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.spinner-border {
    animation: spin 0.75s linear infinite;
}
</style>

<!-- JavaScript untuk Fungsi Login -->
<script>
// Toggle Password Visibility
function togglePassword() {
    const passwordInput = document.getElementById('modalPassword');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}

// Login Form Submit Handler
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const spinner = loginBtn.querySelector('.spinner-border');
    const messageDiv = document.getElementById('loginMessage');

    // Show loading state
    loginBtn.disabled = true;
    spinner.classList.remove('d-none');
    loginBtnText.textContent = 'Memproses...';
    messageDiv.innerHTML = '';

    // Send AJAX request
    fetch('login.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success
            messageDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    ${data.message}
                </div>
            `;
            
            // Redirect after delay
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            }, 1500);
        } else {
            // Error
            messageDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    ${data.message}
                </div>
            `;
            
            // Reset button
            loginBtn.disabled = false;
            spinner.classList.add('d-none');
            loginBtnText.textContent = 'Masuk Sekarang';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Terjadi kesalahan sistem. Silakan coba lagi.
            </div>
        `;
        
        // Reset button
        loginBtn.disabled = false;
        spinner.classList.add('d-none');
        loginBtnText.textContent = 'Masuk Sekarang';
    });
});

// Clear messages when typing
document.getElementById('modalUsername').addEventListener('input', function() {
    document.getElementById('loginMessage').innerHTML = '';
});

document.getElementById('modalPassword').addEventListener('input', function() {
    document.getElementById('loginMessage').innerHTML = '';
});
</script>