<?php 

session_start();
$_SESSION['durum'] = '';

include('includes/database.php');



if(!$_SESSION['kullanici'])
{
    header('Location:giris.php');
}else{
    $simdi = time();
    if($simdi > $_SESSION['bitis']){
        session_destroy();
    }
}

?>