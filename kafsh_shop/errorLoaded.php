<?php
if(isset($_GET['loginned'])):
?>
<script>
    Swal.fire({
        title: "Good job!",
        text: "Your login to site!",
        icon:"success"
    });
</script>
<?php endif; ?>
<?php if(isset($_GET['notuser'])):
?>
<script>
    Swal.fire({
        title: "oh!",
        text: "user is not available!",
        icon:"error"
    });
    </script>
    <?php
    endif;
    ?>
    <?php if(isset($_GET['logout'])):
?>
<script>
    Swal.fire({
        title: "Logout!",
        text: "Logout from site!",
        icon:"info"
    });
    </script>
    <?php
    endif;
    ?>