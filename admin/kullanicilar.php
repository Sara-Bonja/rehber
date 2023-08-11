<?php 
    include('guvenlik.php'); 
    include('includes/header.php');


    if(!isset($_SESSION['ekle-drm'])){
      $_SESSION['ekle-drm']='';
    }

    if(!($_SESSION['yetki']=='1'))
    {
        header('Location:index.php');
    }



    $sql_y = " SELECT * FROM rb_rehber.tbl_yetki";
    $sonuc_y = pg_query($db,$sql_y);

    
    $sql_liste = " SELECT *, 
    yetki,
      g.id AS kullanici_id,
      k.id as kisi_id
  FROM
      rb_rehber.tbl_kisi k
      LEFT JOIN rb_rehber.tbl_kullanici g ON k.id  = g.kisi_id
      LEFT JOIN rb_rehber.tbl_yetki y ON g.yetki_id  = y.id
  where k.silindi_mi = false and (g.silindi_mi is null or  g.silindi_mi  =false) and k.aktif_mi =true
  order by g.kullanici_adi , k.ad ,k.soyad  ;";//LIMIT 8
    $sonuc_liste = pg_query($db,$sql_liste);

    if(isset($_GET['kisi_id'])){
      $sql_isim = " SELECT *
  FROM
      rb_rehber.tbl_kisi k
  where k.silindi_mi='false' AND k.id=$_GET[kisi_id]";
      $sonuc_isim = pg_query($db,$sql_isim);
    }

    if(isset($_POST['ara-btn'])){
      $aranan = $_POST['aranan'];
      $arananbirimtur = $_POST['arananbirimtur'];
      $sql_ara = " select  
      case when length( birim_adi) < 40 then  birim_adi
       else concat( left(birim_adi,40),'...') end as birim_kisa,* from rb_rehber.tbl_birim 
       where  silindi_mi=false and birim_turu ='Merkez'";
    $sonuc_ara = pg_query($db,$sql_ara);
    }
    
    if(isset($_POST['ekle-btn']))
{
    $k_adi = $_POST['k-adi'];
    $yetki = $_POST['yetki'];
    $h_kisi_id = $_POST['kisi-id'];
    $aktif_mi=$_POST['aktif'];
    if($aktif_mi=='on'){
      $aktif_mi='t';
    }else{
      $aktif_mi='f';
    }

    $sql_ekle="INSERT INTO rb_rehber.tbl_kullanici (kullanici_adi,yetki_id,kisi_id,aktif_mi,silindi_mi) 
    VALUES ('$k_adi','$yetki','$h_kisi_id','$aktif_mi','false')";
    $sonuc_ekle = pg_query($db,$sql_ekle);

    if($sonuc_ekle)
    {
        $_SESSION['ekle-drm'] = "Kullanıcı  Eklendi!!";
    }
    else{
      $_SESSION['ekle-drm'] = "Kullanıcı  Eklenmedi!!";
    }
}

if(isset($_POST['update-btn']))
{
    $id=$_POST['h-id'];
    $k_adi = $_POST['k-adi'];
    $yetki = $_POST['yetki'];
    $kisi_id = $_POST['kisi-id'];
    $aktif_mi=$_POST['aktif'];
    if($aktif_mi=='on'){
      $aktif_mi='t';
    }else{
      $aktif_mi='f';
    }

    $sql_update=" UPDATE rb_rehber.tbl_kullanici
    SET kisi_id='$kisi_id', yetki_id='$yetki', kullanici_adi='$k_adi', aktif_mi='$aktif_mi'
    WHERE id='$id'";
    $sonuc_update = pg_query($db,$sql_update);

    if($sonuc_update)
    {
        $_SESSION['ekle-drm'] = "Kullanıcı  Güncellendi!";
    }
    else{
      $_SESSION['ekle-drm'] = "Kullanıcı  Güncellenmedi!!!";
    }

}


if(isset($_POST['duzenle-btn'])|| isset($_GET['kisi_id'])){
  if(!isset($_GET['kisi_id'])){
  $h_id = $_POST['hidden-kul-id'];
  }else{
    $h_id=$_GET['kisi_id'];
  }


  $sql_duzenle = "SELECT 
     k.id,
    k.kullanici_adi,
    g.ad,
    g.soyad,
    k.aktif_mi,
    y.yetki,
    k.kisi_id,
    k.yetki_id
FROM
    rb_rehber.tbl_kullanici k
    LEFT JOIN rb_rehber.tbl_kisi g ON k.kisi_id  = g.id
    LEFT JOIN rb_rehber.tbl_yetki y ON k.yetki_id  = y.id
where k.silindi_mi =false AND k.id = $h_id
  ";

  $sonuc_duzenle = pg_query($db,$sql_duzenle);
}



if(isset($_POST['sil-btn'])){
  $id=$_POST['hidden-kul-id'];
  $sql_sil="UPDATE rb_rehber.tbl_kullanici
  SET silindi_mi='true'
  WHERE id='$id'";
  $sonuc_sil=pg_query($db,$sql_sil);
  if($sonuc_sil){
     $_SESSION['ekle-drm'] = "Kullanıcı  Silindi!";
  }else{
    $_SESSION['ekle-drm'] = "Kullanıcı  Silinmedi!!!";
  }
}

if(isset($_POST['yeni-sifre'])){
  if($_POST['sifre']==$_POST['sifretekrar']){
    $id=$_POST['kullanici-id'];
    $sifre = md5($_POST['sifre']);

    $sql_sifre = " UPDATE rb_rehber.tbl_kullanici
    SET sifre='$sifre'
    WHERE id=$id";
    $sonuc_sifre = pg_query($db,$sql_sifre);
    if($sonuc_sifre){
      $_SESSION['ekle-drm'] = "Şifre  Kaydedildi!";
   }else{
     $_SESSION['ekle-drm'] = "Şifre  Kaydedilmedi!!!";
   }
  }
}

include('includes/menu_navbar.php');

?>
<script type="text/javascript">
  
  $(document).ready(function() {
    $('#aranan').on('input', function() {
        var aranan = $(this).val();

        $.ajax({
            url: 'arama.php',
            method: 'POST',
            data: { aranan: aranan },
            success: function(response) {
                $('#aramaSonuclari').html(response);
            }
        });
    });
});

window.addEventListener('load', function(){
  var ara = document.getElementById('ara-btn');

  var searchParams = new URLSearchParams(window.location.search);

 var param = searchParams.get('arabtn');

  if(ara && param==1){
    ara.click();
    console.log( "calisti");
  }
})
</script>


          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kullanıcılar /</span> Ekle - Düzenle - Sil</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link 
                      <?php if(!isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="kullanicilar.php"><i class="bx bx-plus me-1"></i> Ekle</a>
                    </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav-item">
                      <span class="nav-link 
                      <?php if(isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="#"><i class="bx bx-edit me-1 "></i> Düzenle</span>
                    </li>
                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header"><strong>Kullanıcı Bilgileri</strong></h5>
                    <!-- Account -->
                    <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="button-wrapper">
                          <h3 class="bg-dark text-white"><?php echo $_SESSION['ekle-drm']; unset($_SESSION['ekle-drm']);?></h3>
                        </div>
                      </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                    <?php if(isset($_POST['duzenle-btn'])|| !empty($sonuc_duzenle)){
                      while($row_duzenle = pg_fetch_assoc($sonuc_duzenle)){?>
                      <form id="formAccountSettings" method="POST"  action="">
                      <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label"> KULLANICI ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="k-adi"
                              value="<?php echo $row_duzenle['kullanici_adi'];?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">YETKİ:</label>
                            <select id="language" class="select2 form-select autofocus" name="yetki">
                              <?php while($row_y = pg_fetch_assoc($sonuc_y)){?>
                              <option value="<?php echo $row_y['id'];  ?>" <?php //if($row_duzenle['yetki_id']=$row_y['id']){echo "selected";}?>>
                              <?php echo $row_y['yetki'];?>
                            </option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">KİŞİ ADI:</label>
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="ad-soyad"
                              value="<?php echo $row_duzenle['ad']."  ".$row_duzenle['soyad'];?>"
                              disabled
                            />
                          </div>
                            <!-- <label for="firstName" class="form-label">KİŞİ ID:</label> -->
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="kisi-id"
                              value="<?php echo $row_duzenle['kisi_id'];?>"
                              hidden
                            />
                          <div class="m-3 col-md-12">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox"  
                            <?php if($row_duzenle['aktif_mi']=='t'){echo 'checked';} ?>
                             name="aktif">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">AKTİF Mİ</label>
                          </div>
                          <input type="hidden" name="h-id" value="<?php echo $row_duzenle['id']?>">
                        <div class="mt-2">
                            <br>
                            <button type="submit" class="btn btn-primary me-2" name="update-btn" value="Kaydet">Kaydet</button>
                          <a href="gorevler.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
                        </div>
                      </form>
                    <?php
                      }
                  }else{
                    ?>
                      <form id="formAccountSettings" method="POST"  action="">
                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label"> KULLANICI ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="k-adi"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">YETKİ:</label>
                            <select id="language" class="select2 form-select autofocus" name="yetki">
                              <option value="" disabled selected></option>
                              <?php while($row_y = pg_fetch_assoc($sonuc_y)){?>
                              <option value="<?php echo $row_y['id'];  ?>"><?php echo $row_y['yetki'];?></option>
                              <?php }?>
                            </select>
                          </div>
                            <!-- <label for="firstName" class="form-label">KİŞİ ID:</label> -->
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="kisi-id"
                              hidden
                              <?php if(isset($_GET['kisi_id'])){
                                echo "value='".$_GET['kisi_id']."'";
                              } ?>
                            />
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">KİŞİ ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="kisi-adi"
                              value="<?php if(isset($_GET['kisi_id'])){
                               $row_isim = pg_fetch_assoc($sonuc_isim); 
                               echo $row_isim['ad']."  ".$row_isim['soyad']; }
                               ?>"
                              disabled
                            />
                          </div>
                          <div class="m-3 col-md-12">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox" name="aktif">
                            &nbsp;&nbsp;&nbsp;
                            <label class="form-label">AKTİF Mİ</label>
                          </div>
                        <div class="mt-2">
                            <br>
                            <button type="submit" class="btn btn-primary me-2" name="ekle-btn" value="Kaydet">Kaydet</button>
                          <a href="kullanicilar.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
                        </div>
                      </form>
                    <?php
                  } ?>
                      
                    </div>
                    <!-- /Account -->
                  </div>
                  
                </div>
              </div>
              <hr class="my-5" />
              <div class="card">
                <h4 class="card-header text-dark ">
                Kullanıcılar
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </h4>
                <h5 class="card-header">Arama</h5>
                <div class="navbar-nav-right d-flex align-items-center m-3"  id="navbar-collapse">
                  <form action="kullanicilar.php" method="post" id="araForm">
                    <div class="navbar-nav align-items-center">
                      <div class="nav-item d-flex align-items-center">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input
                          id="aranan"
                          type="text"
                          class="form-control border-0 shadow-none form-controler"
                          placeholder="Arama..."
                          name="aranan"
                          style="min-width: 300px;"
                        /> <button type="submit" class="btn badge rounded-pill bg-label-secondary form-controler" name="ara-btn" class="display:inline;" id="ara-btn">Ara</button>
                      </div>
                  </div>
                </form>
                </div>
                <div class="table-responsive text-nowrap">
                    <?php if(!isset($_POST['ara-btn'])||!isset($_POST['aranan'])){?>
                      <table class="table text-center">
                        <thead>
                          <tr>
                            <th></th>
                            <th>ADI SOYADI</th>
                            <th>YETKİ</th>
                            <th>KULLANICI ADI</th>
                            <th>İŞLEMLER</th>
                          </tr>
                        </thead>
                        <tbody>
                    <?php 
                    $count=1;
                     while($row_liste = pg_fetch_assoc($sonuc_liste)){?>
                      <tr>
                        <td><span class="badge badge-center rounded-pill bg-label-<?php if($row_liste['kullanici_id']!=null){echo 'primary';}else{echo 'secondary';}?>"><?php echo $count;?></span></td>
                        <td><strong><?php echo $row_liste['ad']."   ". $row_liste['soyad'];?></strong></td>
                        <td><?php echo $row_liste['yetki'];?> </td>
                        <td><?php echo  $row_liste['kullanici_adi'];?> </td>
                        <td>
                          <form action="" method="post">
                          <div class="dropdown" style="display: inline;" >
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="min-width: 12rem;">
                                <input type="text" hidden value="<?php echo $row_liste['kullanici_id'];?>" name="hidden-kul-id">
                                <input type="text" hidden value="<?php echo $row_liste['kisi_id'];?>" name="hidden-kisi-id">
                                <?php if($row_liste['kullanici_id']!=null){?>
                                <button type="submit" class="btn badge btn-outline-info dropdown-item" name="duzenle-btn">
                                  <span class="tf-icons bx bx-edit-alt"></span>&nbsp;KULLANICI DÜZENLE
                                </button><br>
                                <button type="submit" class="btn badge btn-outline-danger dropdown-item" name="sil-btn">
                                  <span class="tf-icons bx bx-trash"></span>&nbsp; KULLANICI SİL
                                </button><br>
                                <?php } ?>
                                <?php if($row_liste['kullanici_id']!=null){?>
                                <a href="" class="" data-bs-toggle="modal" data-bs-target="#sifreModal_<?php echo $row_liste['kullanici_id'];?>" aria-pressed="true"> 
                                  <button type="submit" class="btn badge btn-outline-warning dropdown-item" name="sifre-btn">
                                  <span class="tf-icons bx bx-repost"></span>&nbsp;YENİ ŞİFRE
                                </button>
                                </a><br>
                                <?php }else{ ?>
                                <a href="kullanicilar.php?kisi_id=<?php echo $row_liste['kisi_id'];?>" class="btn badge btn-outline-primary dropdown-item">
                                    <i class="bx bx-plus me-1"></i><input type="button" value="" class="btn badge btn-outline-primary dropdown-item">KULLANICI EKLE
                                </a><br>
                               <?php } ?>
                                <a href="kayitlar.php?duzenle_id=<?php echo $row_liste['kisi_id'];?>" class="btn badge btn-outline-success dropdown-item">
                                    <i class="bx bx-edit-alt me-1"></i><input type="button" value="" class="btn badge btn-outline-success dropdown-item">KİŞİ DÜZENLE
                                </a><br>
                                <a href="kayitlar.php?sil_id=<?php echo $row_liste['kisi_id'];?>" class="btn badge btn-outline-success dropdown-item">
                                    <i class="bx bx-trash me-1"></i><input type="button" value="" class="btn badge btn-outline-success dropdown-item">KİŞİ SİL
                                </a><br>
                                
                          </div>
                          </div>
                            
                        </form>
                        </td>
                      </tr>

                          <!-- Yeni Sifre Modal -->
                          <div class="modal fade" id="sifreModal_<?php echo $row_liste['kullanici_id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg"><!--modal-dialog-centered-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Yeni Şifre İşlemleri</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <h5 class="text-center text-dark" style="font-size: x-large;">
                                    <?php echo " KULLANICI ID[".$row_liste['kullanici_id']."]    <br>  ".$row_liste['kullanici_adi']." <br> ".$row_liste['ad']."  ".$row_liste['soyad'];?>
                                  </h5>
                                <div class="table-responsive text-nowrap">
                                  <div class="alert alert-warning alert-dismissible" role="alert">
                                    Şifre en az 8 karakter olup büyük/küçük harf ve sayı içermeli!!
                                  </div>
                                  <form action="" method="post" id="myForm" onsubmit="return dogrulama()">
                                  <label for="email" class="form-label">YENİ ŞİFRE:</label>
                                  &nbsp;&nbsp;<span id="msgsifre" class="text-danger"></span>
                                  <input type="text" name="sifre" id="sifre" placeholder="Yeni Şifre Giriniz" class="form-control"><br>
                                  <label for="email" class="form-label">YENİ ŞİFRE DOĞRULAMA:</label>
                                  &nbsp;&nbsp;<span id="msgsifretkr" class="text-danger"></span>
                                  <input type="text" name="sifretekrar" id="sifretekrar" placeholder="Tekrar Şifre Giriniz" class="form-control"><br>
                                  <input type="hidden" name="kullanici-id" value="<?php echo $row_liste['id'];?>">
                                </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                  <a href="" class="text-white">
                                    <i class="bx bx-edit-alt me-1"></i><input type="submit" value="Kaydet" class="btn btn-warning" name="yeni-sifre"> 
                                  </a>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>


                    <?php $count++;
                     }   ?>
                     </tbody>
                    <tfoot class="table-border-bottom-0">
                      <tr>
                            <th></th>
                            <th>ADI SOYADI</th>
                            <th>YETKİ</th>
                            <th>KULLANICI ADI</th>
                            <th>İŞLEMLER</th>
                      </tr>
                    </tfoot>
                  </table>
                  
                  <?php
                    }
                     else{  ?>
                     <table class="table">
                    <thead>
                      <tr>
                            <th></th>
                            <th>ADI SOYADI</th>
                            <th>YETKİ</th>
                            <th>KULLANICI ADI</th>
                            <th>İŞLEMLER</th>
                      </tr>
                    </thead>
                    <tbody id="aramaSonuclari">
                    <?php
                    if(isset($_POST['ara-btn'])){
                    $count=1;
                     while($row_ara = pg_fetch_assoc($sonuc_ara)){?>
                      <tr>
                        <td><span class="badge badge-center rounded-pill bg-label-<?php if($row_ara['kullanici_id']!=null){echo 'primary';}else{echo 'secondary';}?>"><?php echo $count;?></span></td>
                        <td><strong><?php echo $row_ara['ad']."   ". $row_ara['soyad'];?></strong></td>
                        <td><?php echo $row_ara['yetki'];?> </td>
                        <td><?php echo $row_ara['kullanici_adi']; ?> </td>
                        <td>
                          <form action="" method="post">
                          <div class="dropdown" style="display: inline;" >
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="min-width: 12rem;">
                                <input type="text" hidden value="<?php echo $row_ara['kullanici_id'];?>" name="hidden-kul-id">
                                <input type="text" hidden value="<?php echo $row_ara['kisi_id'];?>" name="hidden-kisi-id">
                                <?php if($row_ara['kullanici_id']!=null){?>
                                <button type="submit" class="btn badge btn-outline-info dropdown-item" name="duzenle-btn">
                                  <span class="tf-icons bx bx-edit-alt"></span>&nbsp;KULLANICI DÜZENLE
                                </button><br>
                                <button type="submit" class="btn badge btn-outline-danger dropdown-item" name="sil-btn">
                                  <span class="tf-icons bx bx-trash"></span>&nbsp; KULLANICI SİL
                                </button><br>
                                <?php } ?>
                                <?php if($row_ara['kullanici_id']!=null){?>
                                <a href="" class="" data-bs-toggle="modal" data-bs-target="#sifreModal_<?php echo $row_ara['kullanici_id'];?>" aria-pressed="true"> 
                                  <button type="submit" class="btn badge btn-outline-warning dropdown-item" name="sifre-btn">
                                  <span class="tf-icons bx bx-repost"></span>&nbsp;YENİ ŞİFRE
                                </button>
                                </a><br>
                                <?php }else{ ?>
                                <a href="kullanicilar.php?kisi_id=<?php echo $row_ara['kisi_id'];?>" class="btn badge btn-outline-primary dropdown-item">
                                    <i class="bx bx-plus me-1"></i><input type="button" value="" class="btn badge btn-outline-primary dropdown-item">KULLANICI EKLE
                                </a><br>
                               <?php } ?>
                                <a href="kayitlar.php?duzenle_id=<?php echo $row_ara['kisi_id'];?>" class="btn badge btn-outline-success dropdown-item">
                                    <i class="bx bx-edit-alt me-1"></i><input type="button" value="" class="btn badge btn-outline-success dropdown-item">KİŞİ DÜZENLE
                                </a><br>
                                <a href="kayitlar.php?sil_id=<?php echo $row_ara['kisi_id'];?>" class="btn badge btn-outline-success dropdown-item">
                                    <i class="bx bx-trash me-1"></i><input type="button" value="" class="btn badge btn-outline-success dropdown-item">KİŞİ SİL
                                </a><br>
                                
                          </div>
                          </div>
                            
                        </form>
                        </td>
                      </tr>

                          <!-- Yeni Sifre Modal -->
                          <div class="modal fade" id="sifreModal_<?php echo $row_ara['kullanici_id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg"><!--modal-dialog-centered-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Yeni Şifre İşlemleri</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <h5 class="text-center text-dark" style="font-size: x-large;">
                                    <?php echo " KULLANICI ID[".$row_ara['kullanici_id']."]    <br>  ".$row_ara['kullanici_adi']." <br> ".$row_ara['ad']."  ".$row_ara['soyad'];?>
                                  </h5>
                                <div class="table-responsive text-nowrap">
                                  <div class="alert alert-warning alert-dismissible" role="alert">
                                    Şifre en az 8 karakter olup büyük/küçük harf ve sayı içermeli!!
                                  </div>
                                  <form action="" method="post" id="myForm" onsubmit="return dogrulama()">
                                  <label for="email" class="form-label">YENİ ŞİFRE:</label>
                                  &nbsp;&nbsp;<span id="msgsifre" class="text-danger"></span>
                                  <input type="text" name="sifre" id="sifre" placeholder="Yeni Şifre Giriniz" class="form-control"><br>
                                  <label for="email" class="form-label">YENİ ŞİFRE DOĞRULAMA:</label>
                                  &nbsp;&nbsp;<span id="msgsifretkr" class="text-danger"></span>
                                  <input type="text" name="sifretekrar" id="sifretekrar" placeholder="Tekrar Şifre Giriniz" class="form-control"><br>
                                  <input type="hidden" name="kullanici-id" value="<?php echo $row_ara['id'];?>">
                                </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                  <a href="" class="text-white">
                                    <i class="bx bx-edit-alt me-1"></i><input type="submit" value="Kaydet" class="btn btn-warning" name="yeni-sifre"> 
                                  </a>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>


                    <?php $count++;
                     }   }?>
                     </tbody>
                    <tfoot class="table-border-bottom-0">
                      <tr>
                            <th></th>
                            <th>ADI SOYADI</th>
                            <th>YETKİ</th>
                            <th>KULLANICI ADI</th>
                            <th>İŞLEMLER</th>
                      </tr>
                    </tfoot>
                  </table>
                  <?php
                    }
                     ?>
                      

                </div>
              </div>

            </div><br>
            <!-- / Content -->

          <!-- Content wrapper -->
<script type="text/javascript">
  function dogrulama(){
    var sifre = document.getElementById('sifre').value;
    var sifretkr = document.getElementById('sifretekrar').value;

    if(sifre == ""){
      document.getElementById('msgsifre').innerHTML = " * Şifreyi yazınız!";
      return false;
    }
    if(sifre.length<8){
      document.getElementById('msgsifre').innerHTML = " * Şifre en az 8 karakter olmalı!";
      return false;
    }
    if(sifre.search(/[0-9]/)==-1){
      document.getElementById('msgsifre').innerHTML = " * Şifre en az bir sayı içermeli!";
      return false;
    }
    if(sifre.search(/[a-z]/)==-1){
      document.getElementById('msgsifre').innerHTML = " * Şifre en az bir küçük harf içermeli!";
      return false;
    }
    if(sifre.search(/[A-Z]/)==-1){
      document.getElementById('msgsifre').innerHTML = " * Şifre en az bir büyük harf içermeli!";
      return false;
    }

    else{
      document.getElementById('msgsifre').innerHTML = "";
    }

    if(sifretkr != sifre){
      document.getElementById('msgsifretkr').innerHTML = " * Şifreler aynı değil!";
      return false;
    }else{
      document.getElementById('msgsifretkr').innerHTML = "";
    }
  }


  function dArama(){
    var aranan = document.getElementById('aranan').value;
    console.log(aranan);
    // document.getElementById('araForm').submit();
    
    <?php 

  //   $aranan =  $_POST['aranan'];
  //   $sql_ara = " SELECT *, 
  //   yetki,
  //     g.id AS kullanici_id,
  //     k.id as kisi_id
  // FROM
  //     rb_rehber.tbl_kisi k
  //     LEFT JOIN rb_rehber.tbl_kullanici g ON k.id  = g.kisi_id
  //     LEFT JOIN rb_rehber.tbl_yetki y ON g.yetki_id  = y.id
  // where k.silindi_mi = false and (g.silindi_mi is null or  g.silindi_mi  =false) and k.aktif_mi =true and (k.ad ilike '%$aranan%' or k.soyad ilike '%$aranan%')
  // order by g.kullanici_adi ,  g.id,k.id  ;";
  //   $sonuc_ara = pg_query($db,$sql_ara);

    ?>
  }
</script>
<?php 
    include('includes/scripts_footer.php');
?>        