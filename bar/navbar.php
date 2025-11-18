<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg navbar-dark px-lg-5 fixed-top">
        <a href="index.php" class="navbar-brand d-flex align-items-center ms-4 ms-lg-0">
            <img src="imgg/trisula.png" alt="Trisula" style="height: 40px; width: auto; margin-right: 10px;" class="align-self-center">

            <h2 class="mb-0 text-primary text-uppercase" style="margin-top: 12px;">
                Trisula
            </h2>

        </a>


        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto p-4 p-lg-0">

            </div>

            <div class="d-none d-lg-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-white me-3">
                        <i class="bi bi-person-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['nama']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-outline-light py-2 px-4 me-3">
                        <i class="bi bi-box-arrow-right me-1"></i> LOGOUT
                    </a>
                <?php else: ?>
                    <button class="btn btn-primary py-2 px-4 me-3 btn-lapor-nav" type="button">
                        <i class="bi bi-megaphone-fill me-1"></i> LAPOR
                    </button>
                <?php endif; ?>

                <a class="btn btn-sm-square btn-outline-primary border-2 ms-1" href="https://twitter.com" target="_blank" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="btn btn-sm-square btn-outline-primary border-2 ms-1" href="https://facebook.com" target="_blank" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="btn btn-sm-square btn-outline-primary border-2 ms-1" href="https://instagram.com" target="_blank" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a class="btn btn-sm-square btn-outline-primary border-2 ms-1" href="https://youtube.com" target="_blank" title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const btnLapor = document.querySelector('.btn-lapor-nav');
        if (btnLapor) {
            btnLapor.addEventListener('click', function(e) {
                e.preventDefault();

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = new bootstrap.Modal(document.getElementById('modalLaporan'));
                    modal.show();
                } else {
                    console.error('Bootstrap JS Modal not loaded.');
                }
            });
        }


        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {

                    const navbarHeight = document.querySelector('.navbar.fixed-top').offsetHeight;
                    const offsetPosition = target.getBoundingClientRect().top + window.scrollY - navbarHeight;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>