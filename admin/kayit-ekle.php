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
    $gorev=$_POST['gorev'];
    $unvan=$_POST['unvan'];
    $ad=$_POST['ad'];
    $soyad=$_POST['soyad'];
    $birim=$_POST['birim'];
    $birim_turu=$_POST['birim-turu'];
    $email=$_POST['email'];
    $aktif_mi=$_POST['aktif'];
    if($aktif_mi=='on'){
      $aktif_mi='t';
    }else{
      $aktif_mi='f';
    }

    $sql_ekle="INSERT INTO rb_rehber.tbl_kisi (ad,soyad,email,birim_id,unvan_id,gorev_id,aktif_mi) 
    VALUES ('$ad','$soyad','$email','$birim','$unvan','$gorev','$aktif_mi')";
    $sonuc_ekle = pg_query($db,$sql_ekle);

    if($sonuc_ekle)
    {
        header('Location: kayitlar.php');
        $_SESSION['ekle-drm'] = "Kayıt Eklendi!";
    }
    else{
      $_SESSION['ekle-drm'] = "Kayıt Eklenmedi!!";
        header('Location: kayitlar.php');
    }
}


if(isset($_POST['update-btn']))
{
    $id=$_POST['hidden_id'];
    $gorev=$_POST['gorev'];
    $unvan=$_POST['unvan'];
    $ad=$_POST['ad'];
    $soyad=$_POST['soyad'];
    $birim=$_POST['birim'];
    $birim_turu=$_POST['birim-turu'];
    $email=$_POST['email'];
    $aktif_mi=$_POST['aktif'];
    if($aktif_mi=='on'){
      $aktif_mi='t';
    }else{
      $aktif_mi='f';
    }


    $sql_update=" UPDATE rb_rehber.tbl_kisi 
    SET ad='$ad', soyad='$soyad', email='$email', gorev_id=$gorev, unvan_id=$unvan, birim_id=$birim, aktif_mi='$aktif_mi' 
    WHERE id=$id";
    $sonuc_update = pg_query($db,$sql_update);

  // echo $sql_update;
    if($sonuc_update)
    {
        $_SESSION['ekle-drm'] = "Kayıt Güncellendi!";
        header('Location: kayitlar.php');
    }
    else{
      $_SESSION['ekle-drm'] = "Kayıt Güncellenmedi!!!";
        header('Location: kayitlar.php');
    }

}
?>
