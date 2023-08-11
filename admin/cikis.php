<?php
session_start();

if(isset($_POST['cikis-btn'])){
    session_destroy();
    session_commit();
    unset($_SESSION['kullanici']);
    unset($_SESSION['yetki']);
    // setcookie("kullanici","",time()-1);
    header('Location:giris.php');
}

?>