<?php

include('guvenlik.php');
include('includes/database.php');


//VERITABANI BAGLANTISI
$host="localhost";
$db="postgres";
$hesap="postgres";
$sifre="123456";

 $db = pg_connect("host=$host port=5432 dbname=$db user=$hesap password=$sifre");



if(isset($_POST['ekle-btn']))
{
    $ad=$_POST['birim-adi'];
    $yoksis=$_POST['yoksis'];
    $kadro=$_POST['kadro-tipi'];
    $aktif_mi=$_POST['aktif'];
    if($aktif_mi=='on'){
      $aktif_mi='t';
    }else{
      $aktif_mi='f';
    }
    if(isset($_POST['birim-turu1'])){
        $tur=$_POST['birim-turu1'];
    }else{ 
        $tur=$_POST['birim-turu2'];
    }

    $sql_ekle="INSERT INTO rb_rehber.tbl_birim (birim_adi,yoksis,kadro_tipi,birim_turu,aktif_mi) 
    VALUES ('$ad','$yoksis','$kadro','$tur','$aktif_mi')";
    $sonuc_ekle = pg_query($db,$sql_ekle);

    if($sonuc_ekle)
    {
        header('Location: birimler.php');
        $_SESSION['ekle-drm'] = "Birim  Eklendi!";
    }
    else{
      $_SESSION['ekle-drm'] = "Birim  Eklenmedi!!";
        header('Location: birimler.php');
    }
}


if(isset($_POST['update-btn']))
{
    $id=$_POST['hidden_id'];
    $ad=$_POST['birim-adi'];
    $tur=$_POST['birim-turu'];
    $yoksis=$_POST['yoksis'];
    $kadro=$_POST['kadro-tipi'];
    $aktif_mi=$_POST['aktif'];
    if($aktif_mi=='on'){
      $aktif_mi='t';
    }else{
      $aktif_mi='f';
    }


    $sql_update=" UPDATE rb_rehber.tbl_birim
    SET birim_adi='$ad', birim_turu='$tur', yoksis='$yoksis', kadro_tipi='$kadro', aktif_mi='$aktif_mi' 
    WHERE id='$id'";
    $sonuc_update = pg_query($db,$sql_update);

    if($sonuc_update)
    {
        header('Location: birimler.php');
        $_SESSION['ekle-drm'] = "Birim  Güncellendi!";
    }
    else{
      $_SESSION['ekle-drm'] = "Birim  Güncellenmedi!!!";
        header('Location: birimler.php');
    }

}
?>