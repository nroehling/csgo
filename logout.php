
<?php

    session_start();

    session_destroy();

    echo "<script>window.location.href='http://www.my-csgo.de/index.php'</script>";
    exit;
