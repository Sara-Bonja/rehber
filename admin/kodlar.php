<?php

include('guvenlik.php');

//KULLANICI GIRISI
if(isset($_POST['giris-btn'])){
    // $_POST = array_map(array($db,'quote'), $_POST);
    $kullanici_girisi=$_POST['kullanici'];
    $sifre_girisi=md5($_POST['sifre']);
    $sql = "SELECT * FROM rb_rehber.tbl_kullanici WHERE kullanici_adi='$kullanici_girisi' AND sifre='$sifre_girisi' ";
    $sonuc = pg_query($db,$sql);

 
    if($row = pg_fetch_assoc($sonuc))
    {
        header('Location: index.php');
        // setcookie('kullanici',"$kullanici_girisi",time()+3600);
        $_SESSION['kullanici'] = $kullanici_girisi;
        $_SESSION['basla'] = time();
        $_SESSION['bitis'] = $_SESSION['basla'] + (660*60); //11 saat
        $_SESSION['yetki'] = $row['yetki_id'];
        $_SESSION['durum'] = '';
    }
    else{
        $_SESSION['durum'] = 'Kullanıcı adı / Şifre geçersiz';
        header('Location: giris.php');
    }
}

?>