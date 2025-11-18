<?php
session_start();

include 'link/header.php';
?>
<!DOCTYPE html>
<html lang="id">

<body style="background-color:#0d1217 !important; color: white !important;">

    <?php include 'bar/navbar.php' ?>

    <?php include 'modal_login.php' ?>

    <?php include 'modal_laporan.php' ?>

    <?php include 'views/carousel.php' ?>

    <?php include 'views/about.php' ?>

    <?php include 'views/berita.php' ?>

    <?php include 'views/data.php' ?>

    <?php include 'views/service.php' ?>


    <a href="#" class="btn btn-primary btn-lg-square back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <?php include 'link/js.php' ?>

    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.back-to-top').fadeIn('slow');
            } else {
                $('.back-to-top').fadeOut('slow');
            }
        });

        $('.back-to-top').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 800, 'easeInOutExpo');
            return false;
        });

        $('.counter-value').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    $(this).text(Math.ceil(now).toLocaleString());
                }
            });
        });
    </script>

</body>

</html>