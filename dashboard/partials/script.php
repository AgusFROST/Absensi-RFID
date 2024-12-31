<script src="/asset/js/bootstrap.min.js"></script>
<script src="/asset/js/dashboard.js"></script>

<script>
    <?php if (!empty($error)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Belum Login',
            text: '<?= $error ?>',
            showConfirmButton: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/authentication/login.php";
            }
        });
    <?php endif; ?>
</script>