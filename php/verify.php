<?php
    session_start();
    if(!array_key_exists("login", $_SESSION)){
        echo '<script>window.location = "../index.php"</script>';
    }