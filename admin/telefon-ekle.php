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
    $k_id=$_POST['hidden-k-id'];
    $telefon=$_POST['telefon'];
    $dahili=$_POST['dahili'];
    $mac_adresi=$_POST['mac-adresi'];
    $telefon_modeli=$_POST['telefon-modeli'];
    $arama_yetkisi=$_POST['arama-yetkisi'];
    $gorunsun_mu=$_POST['gorunsun-mu'];
    $birim_id=$_POST['birim-adi'];
    if($arama_yetkisi=='on'){
      $arama_yetkisi='t';
    }else{
      $arama_yetkisi='f';
    }
    if($gorunsun_mu=='on'){
        $gorunsun_mu='t';
      }else{
        $gorunsun_mu='f';
      }

    $sql_ekle="INSERT INTO rb_rehber.tbl_telefon (telefon,dahili,mac_adresi,telefon_modeli,birim_id,arama_yetkisi,gorunsun_mu,kisi_id,silindi_mi) 
    VALUES ($telefon,$dahili,'$mac_adresi','$telefon_modeli',$birim_id,'$arama_yetkisi','$gorunsun_mu','$k_id','f')";
    $sonuc_ekle = pg_query($db,$sql_ekle);

    if($sonuc_ekle)
    {
        header("Location: telefonlar.php?kisi_id=$k_id");
        $_SESSION['ekle-drm'] = "Telefon  Kaydı  Eklendi!";
    }
    else{
      $_SESSION['ekle-drm'] = "Telefon  Kaydı  Eklenmedi!!";
        header("Location: index.php");
    }
}


if(isset($_POST['update-btn']))
{
    $t_id=$_POST['hidden_id'];
    $telefon=$_POST['telefon'];
    $dahili=$_POST['dahili'];
    $mac_adresi=$_POST['mac-adresi'];
    $telefon_modeli=$_POST['telefon-modeli'];
    $arama_yetkisi=$_POST['arama-yetkisi'];
    $birim_id=$_POST['birim-adi'];
    $gorunsun_mu=$_POST['gorunsun-mu'];
    if($arama_yetkisi=='on'){
      $arama_yetkisi='t';
    }else{
      $arama_yetkisi='f';
    }
    if($gorunsun_mu=='on'){
        $gorunsun_mu='t';
      }else{
        $gorunsun_mu='f';
      }
    $k_id=$_POST['h_k_id'];


    $sql_update=" UPDATE rb_rehber.tbl_telefon
    SET telefon='$telefon', dahili='$dahili', mac_adresi='$mac_adresi', telefon_modeli='$telefon_modeli',birim_id=$birim_id, arama_yetkisi='$arama_yetkisi', gorunsun_mu='$gorunsun_mu'
    WHERE id='$t_id'";
    $sonuc_update = pg_query($db,$sql_update);

    if($sonuc_update)
    {
        header("Location: telefonlar.php?kisi_id=$k_id");
        $_SESSION['ekle-drm'] = "Telefon  Kaydı  Güncellendi!";
    }
    else{
      $_SESSION['ekle-drm'] = "Telefon  Kaydı  Güncellenmedi!!!";
      header('Location: '.$_SERVER['PHP_SELF']);
    }

}


if(isset($_POST['ara-btn'])){
  $aranan = $_POST['aranan'];
if(!isset($_GET['kisi-id'])){
  header("Location: telefonlar.php?kisi_id=$aranan");
}else{
  $sql_ara = "SELECT *
  FROM
      rb_rehber.tbl_telefon t
  where t.silindi_mi=false and t.kisi_id='$global_k_id'
  t.telefon::text  like '%$aranan%' or t.dahili::text  like '%$aranan%' or t.mac_adresi::text like '%$aranan%' or  t.telefon_modeli::text like '%$aranan%' or t.id::text like '%$aranan%' or t.kisi_id::text like '%$aranan%'
  order by t.id";

  $sonuc_ara = pg_query($db,$sql_ara);
}}

?>
