<?php 
    include('guvenlik.php'); 
    include('includes/header.php');
    include('includes/menu_navbar.php');

    if(!isset($_SESSION['ekle-drm'])){
      $_SESSION['ekle-drm']='';
    }

    
  if(isset($_GET['kisi_id'])){
    $global_k_id=$_GET['kisi_id'];
  }

    $sql_birim_turu = " select distinct  birim_turu from rb_rehber.tbl_birim  where birim_turu notnull order by birim_turu;
    ";
    $sonuc_birim_turu = pg_query($db,$sql_birim_turu);

    $sql_birim = " select id, birim_adi  from rb_rehber.tbl_birim  where silindi_mi ='false' order by birim_adi;
    ";
    $sonuc_birim = pg_query($db,$sql_birim);



  if(isset($_GET['kisi_id'])){
    $sql_liste = " SELECT *,
    t.id as t_id
  FROM
      rb_rehber.tbl_telefon t
      LEFT JOIN rb_rehber.tbl_birim b ON t.birim_id = b.id
  where t.silindi_mi='false' AND t.kisi_id=$global_k_id
  order by b.birim_adi ";
    $sonuc_liste = pg_query($db,$sql_liste);
  }

  if(isset($_GET['kisi_id'])){
    $sql_isim = " SELECT *
FROM
    rb_rehber.tbl_kisi k
where k.silindi_mi='false' AND k.id=$_GET[kisi_id]";
    $sonuc_isim = pg_query($db,$sql_isim);
  }


 if(isset($_POST['ara-btn'])){
    $aranan = $_POST['aranan'];
  if(!isset($_GET['kisi-id'])){
    header("Location: telefonlar.php?kisi_id='$aranan'");
  }else{
    $sql_ara = "SELECT *,
    t.id as t_id
    FROM
        rb_rehber.tbl_telefon t
    where t.silindi_mi=false and t.kisi_id='$global_k_id'
      LEFT JOIN rb_rehber.tbl_birim b ON t.birim_id = b.id
    t.telefon::text  like '%$aranan%' or t.dahili::text  like '%$aranan%' or t.mac_adresi::text like '%$aranan%' or  t.telefon_modeli::text like '%$aranan%' or t.id::text like '%$aranan%' or t.kisi_id::text like '%$aranan%'
    order by t.id";
  
    $sonuc_ara = pg_query($db,$sql_ara);
 }}

 
if(isset($_POST['duzenle-btn'])){
  // if(!isset($_GET['kisi_id'])){
    $h_id = $_POST['hidden-id'];
  // }else{
  //   $h_id = $global_k_id;
  // }
echo $h_id;
  $sql_duzenle = "SELECT *,
  b.id AS birim_id,
  t.id AS t_id
FROM
  rb_rehber.tbl_telefon t
  LEFT JOIN rb_rehber.tbl_birim b ON t.birim_id = b.id
  where t.id = $h_id
  ";
  $sonuc_duzenle = pg_query($db,$sql_duzenle);
}


if(isset($_POST['sil-btn'])){
  $h_id=$_POST['hidden-id'];
  $sql_sil="UPDATE rb_rehber.tbl_telefon t SET silindi_mi ='true' WHERE t.id='$h_id'";
  $sonuc_sil=pg_query($db,$sql_sil);
  if($sonuc_sil){
     $_SESSION['ekle-drm'] = "Telefon  Kaydı  Silindi!";
  }else{
    $_SESSION['ekle-drm'] = "Telefon  Kaydı  Silinmedi!!!";
  }
}


  if(isset($_GET['kisi_id'])){
    $global_k_id=$_GET['kisi_id'];
  }



?>
<style>
.div-table {
    display: table;
    width: 100%;
    overflow: scroll;
    background-color: #eee;
    border-spacing: 2px;
    border-radius:4px;
}
.trow {
    display: table-row
}
.tcolumn {
    display: table-cell;
    vertical-align: top;
    background-color: #fff;
    padding: 10px 8px
}
.tcolumn1 {
    width: 240px
}
.tcolumn4,
.tcolumn5,
.tcolumn6 {
    width: 80px
}
</style>
<script src="../js/jquery/jquery.min.js"></script>
<script>



function test1( testt){

  var bTuru = testt.value;
       if(bTuru!=null){
        
          $.ajax({
            type:'GET',
            url:'ajax.php?birim_turu2='+bTuru,
            data:{birim_turu:bTuru},
            success:function(data){
              // $('#birim').html(html);
              
            },
            error: function (jqXHR, exception) {
        var msg = 'fff';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        console.log(msg);
    },
          });
       }else{
        $('#birim').html('<option value="">Birim yok</option>');
       }
};



</script>
            

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Telefon /</span> Ekle - Düzenle - Sil</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link 
                      <?php if(!isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="telefonlar.php"><i class="bx bx-plus me-1"></i> Ekle</a>
                    </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav-item">
                      <span class="nav-link 
                      <?php if(isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="#"><i class="bx bx-edit me-1 "></i> Düzenle</span>
                    </li>
                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header text-primary">
                      <?php  
                    if(isset($_GET['kisi_id'])|| isset($_POST['duzenle-btn'])){
                        $row_isim=pg_fetch_assoc($sonuc_isim);
                        echo $row_isim['ad']."   ".$row_isim['soyad'];
                      } 
                      ?>  <strong class="text-dark">&nbsp;&nbsp;Telefon Bilgileri </strong>
                  </h5>
                    
                        <!-- Account -->
                    <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <div class="button-wrapper">
                          <h3 class="bg-dark"><?php echo $_SESSION['ekle-drm']; unset($_SESSION['ekle-drm']);?></h3>
                        </div>
                      </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                    <?php if(isset($_POST['duzenle-btn'])&& isset($_GET['kisi_id'])){
                      while($row_duzenle = pg_fetch_assoc($sonuc_duzenle)){?>
                      <form id="formAccountSettings" method="POST"  action="telefon-ekle.php">
                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">TELEFON:</label>
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="telefon"
                              placeholder=""
                              value="<?php echo $row_duzenle['telefon']; ?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">DAHİLİ:</label>
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="dahili"
                              placeholder="Soyadı"
                              value="<?php echo $row_duzenle['dahili']; ?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">TELEFON MODELİ:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="telefon-modeli"
                              placeholder="Soyadı"
                              value="<?php echo $row_duzenle['telefon_modeli']; ?>"
                            />
                        </div>
                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">MAC ADRESİ:</label>
                            <input class="form-control" type="text" id="email" name="mac-adresi" placeholder="" 
                            value="<?php echo $row_duzenle['mac_adresi']; ?>"/>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM TÜRÜ:</label>
                            <select id="tur" onchange="test1(this)" class="select2 form-select autofocus" name="birim-turu" style="display: block;">
                              <option value="<?php echo $row_duzenle['birim_turu']; ?>"><?php echo $row_duzenle['birim_turu']; ?></option>
                              <?php while($row_birim_turu = pg_fetch_assoc($sonuc_birim_turu)){?>
                              <option value="<?php echo $row_birim_turu['birim_turu'];  ?>"><?php echo $row_birim_turu['birim_turu'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM:</label>
                            <select class="select2 form-select" name="birim-adi" style="display: block;" id="birim">
                              <option value="<?php echo $row_duzenle['birim_id']; ?>"><?php echo $row_duzenle['birim_adi']; ?></option>
                            </select>
                          </div>
                          <div class="m-3 col-md-6">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox"  
                            <?php if($row_duzenle['arama_yetkisi']=='t'){echo 'checked';} ?>
                             name="arama-yetkisi">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">ARAMA YETKİSİ</label>
                          </div>
                          <div class="m-3 col-md-6">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox"  
                            <?php if($row_duzenle['gorunsun_mu']=='t'){echo 'checked';} ?>
                             name="gorunsun-mu">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">ARAMADA GÖRÜNSÜN MÜ</label>
                          </div>
                          <input type="text" hidden name="hidden_id" value="<?php echo $row_duzenle['t_id'];?>">
                          <input type="text" hidden name="h_k_id" value="<?php echo $row_duzenle['kisi_id'];?>">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2" name="update-btn" value="Kaydet">Kaydet</button>
                          <a href="telefonlar.php?kisi_id=<?php echo $row_duzenle['kisi_id'];?>"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
                        </div>
                      </form>
                    <?php
                      }}
                  else{
                    ?>
                      <form id="TelForm" method="POST"  action="telefon-ekle.php">
                        <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">TELEFON:</label>
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="telefon"
                              value=""
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">DAHİLİ:</label>
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="dahili"
                              value=""
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">TELEFON MODELİ:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="telefon-modeli"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">MAC ADRESİ:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="mac-adresi"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM TÜRÜ:</label>
                            <select id="tur" onchange="test1(this)" class="select2 form-select autofocus" name="birim-turu" style="display: block;">
                              <option value=""></option>
                              <?php while($row_birim_turu = pg_fetch_assoc($sonuc_birim_turu)){?>
                              <option value="<?php echo $row_birim_turu['birim_turu'];  ?>"><?php echo $row_birim_turu['birim_turu'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM:</label>
                            <select class="select2 form-select" name="birim-adi" style="display: block;" id="birim">
                              <option value=""> Birim turu Seciniz</option>
                            </select>
                          </div>
                          <div class="m-3 col-md-6">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox" name="arama-yetkisi">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">ARAMA YETKİSİ</label>
                          </div>
                          <div class="m-3 col-md-6">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox" name="gorunsun-mu">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">ARAMADA GÖRÜNSÜN MÜ</label>
                          </div>
                          <input type="hidden" name="hidden-k-id" value="<?php echo $global_k_id; ?>">
                        <div class="mt-2">
                          
                            <button type="submit" class="btn btn-primary me-2" name="ekle-btn" value="Kaydet">Kaydet</button>
                          
                          <a href="telefonlar.php?kisi_id=<?php echo $global_k_id;?>"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
                        </div>
                      </form>
                    <?php
                  } ?>
                      
                    </div>
                    <!-- /Account -->
                  </div>
<script type="text/javascript">
$(document).ready(function() {
    $('#tur').change(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: $(this).serialize(),
            success: function(response)
            {
                var jsonData = JSON.parse(response);
 
                // user is logged in successfully in the back-end 
                // let's redirect 
                if (jsonData)
                {
                  console.log(jsonData);
                    // location.href = 'ajax.php';
                    document.getElementById("birim").innerHTML =  jsonData;
                }
                else
                {
                    alert('Invalid Credentials!');
                }
           },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              alert("some error");
            }
       });
     });
});
</script>

                  
                </div>
              </div>
              <hr class="my-5" />
              <div class="card">
                <h5 class="card-header">
                <?php if(isset($_POST['ara-btn'])){
                  echo "'$aranan'";  echo " Arama  Sonucu";
                  }else{ echo "Arama";} ?>
                </h5>
                <div class="navbar-nav-right d-flex align-items-center m-3" id="navbar-collapse">
                  <form action="telefon-ekle.php" method="post">
                    <div class="navbar-nav align-items-center">
                      <div class="nav-item d-flex align-items-center">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input
                          type="text"
                          class="form-control border-0 shadow-none form-controler"
                          placeholder="Arama..."
                          name="aranan"
                        /> 
                          <button type="submit" class="btn badge rounded-pill bg-label-secondary form-controler" name="ara-btn" class="display:inline;">Ara</button>
                      </div>
                  </div>
                 
                </form>
                </div>
                <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>TELEFON</th>
                        <th>DAHİLİ</th>
                        <th>TELEFON <br>MODELİ</th>
                        <th>MAC <br>ADRESİ</th>
                        <th>BİRİM ADI</th>
                        <th>KİŞİ <br>ID</th>
                        <th>KİŞİ<br> BİLGİLERİ</th>
                        <th>ARAMA <br>YETKİSİ</th>
                        <th>ARAMADA <br>GÖRÜNSÜN MÜ</th>
                        <th class="text-center">İŞLEMLER</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(!isset($_GET['kisi_id'])){
                      ?>
                          
                      <span class="d-flex justify-content-center">
                        <h4>Kayıt Bulunmadı</h4><br>
                    </span>
                    <p class="text-center text-primary">Kişi ID yazarak telefonlar listelenecek</p>
                    

                      <?php
                    }else{
                    if(!isset($_POST['ara-btn'])){
                      $count=1;
                    while($row_liste = pg_fetch_assoc($sonuc_liste)){
                      ?>
                      <tr>
                        <form action="" method="post">
                          <td><span class="badge badge-center rounded-pill bg-label-secondary"><?php echo $count;?></span> </td>
                          <td><?php echo $row_liste['telefon'];?></td>
                          <td><?php echo $row_liste['dahili'];?></td>
                          <td><?php echo $row_liste['telefon_modeli'];?></td>
                          <td><?php echo $row_liste['mac_adresi'];?></td>
                          <td><?php echo $row_liste['birim_adi'];?></td>
                          <td><strong><?php echo $row_liste['kisi_id'];?></strong></td>
                          <td><a href="#" style="text-decoration: underline;" data-bs-toggle="modal" data-bs-target="#kisiModal_<?php echo $row_liste['kisi_id'];?>">
                          Kişi
                          </a></td>
                          <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                          <?php if($row_liste['arama_yetkisi']=='t'){echo"checked";} ?>
                          ></td>
                          <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                          <?php if($row_liste['gorunsun_mu']=='t'){echo"checked";} ?>
                          ></td>
                        <td>
                          <a href="kayitlar.php?id=<?php echo $row_liste['kisi_id'];?>">
                          <span class="badge bg-label-success me-1"><i class="bx bx-edit-alt me-1"></i>KİŞİ</span>
                          </a>
                          <div class="dropdown" style="display: inline;" >
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="min-width: 8rem;">
                                <input type="text" hidden value="<?php echo $row_liste['t_id'];?>" name="hidden-id">
                                <button type="submit" class="btn badge btn-outline-info dropdown-item" name="duzenle-btn">
                                  <span class="tf-icons bx bx-edit-alt"></span>&nbsp;DÜZENLE
                                </button><br>
                                <button type="submit" class="btn badge btn-outline-danger dropdown-item" name="sil-btn">
                                  <span class="tf-icons bx bx-trash"></span>&nbsp;SİL
                                </button>
                        </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                            <!-- kisi Modal -->
                          <div class="modal fade" id="kisiModal_<?php echo $row_liste['kisi_id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg"><!--modal-dialog-centered-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Kişi Bilgileri</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <!-- <h5 class="text-center text-primary" style="font-size: x-large;">
                                    <?php //echo "ID[".$row_liste['id']."]      ".$row_liste['ad']."  ".$row_liste['soyad']?>
                                  </h5> -->
                                <div class="table-responsive text-nowrap">
                                  <form action="" method="post" id="myForm">
                                  <div class="table table-border"> 
                                    <?php
                                      $sql_kisi_liste = " SELECT
                                      g.gorev_adi AS gorev,
                                      b.birim_adi AS birim,
                                      u.unvan_adi as unvan,
                                      k.ad,
                                      k.soyad,
                                      k.email,
                                      k.id,
                                      k.aktif_mi
                                  FROM
                                      rb_rehber.tbl_kisi k
                                      LEFT JOIN rb_rehber.tbl_gorev g ON k.gorev_id  = g.id
                                      LEFT JOIN rb_rehber.tbl_unvan u ON k.unvan_id  = u.id
                                     LEFT  JOIN rb_rehber.tbl_birim b ON k.birim_id  = b.id
                                  where k.silindi_mi=false and k.id = $row_liste[kisi_id] ";
                                        $sonuc_kisi_liste = pg_query($db,$sql_kisi_liste);
                                    ?>
                                    <div class="trow bg-dark "> 
                                      <div class="tcolumn bg-secondary text-white">KİŞİ <br> ID</div> 
                                      <div class="tcolumn bg-secondary text-white">AD</div> 
                                      <div class="tcolumn bg-secondary text-white">SOYAD</div> 
                                      <div class="tcolumn bg-secondary text-white">UNVAN</div> 
                                      <div class="tcolumn bg-secondary text-white">GÖREV</div>
                                      <div class="tcolumn bg-secondary text-white">BİRİM</div> 
                                      <div class="tcolumn bg-secondary text-white">EMAIL</div> 
                                      <div class="tcolumn bg-secondary text-white">AKTİF<br> Mİ</div> 
                                    </div> 
                                
                                      <?php 
                                      while($row_kisi_liste=pg_fetch_assoc($sonuc_kisi_liste)){?>
                                        <div class="trow">
                                          <div class="tcolumn"><?php echo $row_kisi_liste['id']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['ad']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['soyad']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['unvan']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['gorev']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['birim']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['email']; ?></div>
                                          <div class="tcolumn"><input class="form-check-input mt-0" type="checkbox" disabled readonly
                                          <?php if($row_kisi_liste['aktif_mi']=='t'){echo"checked";} ?>>
                                          </div>
                                        </div>
                                      <?php } 
                                      ?>
                                  </div>
                                </form>
                                </div>
                                </div>
                                <div class="modal-footer">
                                  <form action="telefonlar.php" method="get">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                  <a href="kayitlar.php?duzenle_id=<?php echo$row_liste['kisi_id'];?>" class="text-white">
                                    <i class="bx bx-edit-alt me-1"></i><input type="button" value="DÜZENLE" class="btn btn-info">
                                  </a>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>

                      <?php
                      $count++;
                     }}
                     else{
                      $count=1;
                      while($row_ara = pg_fetch_assoc($sonuc_ara)){
                      ?>
                      <tr>
                        <form action="" method="post">
                          <td><span class="badge badge-center rounded-pill bg-label-secondary"><?php echo $count;?></span> </td>
                          <td><?php echo $row_ara['telefon'];?></td>
                          <td><?php echo $row_ara['dahili'];?></td>
                          <td><?php echo $row_ara['telefon_modeli'];?></td>
                          <td><?php echo $row_ara['mac_adresi'];?></td>
                          <td><?php echo $row_ara['birim-adi'];?></td>
                          <td><strong><?php echo $row_ara['kisi_id'];$row_ara['id'];?></strong></td>
                          <td><a href="#" style="text-decoration: underline;" data-bs-toggle="modal" data-bs-target="#kisiModal_<?php echo $row_ara['kisi_id'];?>">
                          Kişi
                          </a></td>
                          <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                          <?php if($row_ara['arama_yetkisi']=='t'){echo"checked";} ?>
                          ></td>
                          <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                          <?php if($row_ara['gorunsun_mu']=='t'){echo"checked";} ?>
                          ></td>
                        <td>
                          <a href="kayitlar.php?id=<?php echo $row_ara['kisi_id'];?>">
                          <span class="badge bg-label-success me-1"><i class="bx bx-edit-alt me-1"></i>KİŞİ</span>
                          </a>
                          <div class="dropdown" style="display: inline;" >
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="min-width: 8rem;">
                                <input type="text" hidden value="<?php echo $row_ara['t_id'];?>" name="hidden-id">
                                <button type="submit" class="btn badge btn-outline-info dropdown-item" name="duzenle-btn">
                                  <span class="tf-icons bx bx-edit-alt"></span>&nbsp;DÜZENLE
                                </button><br>
                                <button type="submit" class="btn badge btn-outline-danger dropdown-item" name="sil-btn">
                                  <span class="tf-icons bx bx-trash"></span>&nbsp;SİL
                                </button>
                        </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                            <!-- Kisi Modal -->
                          <div class="modal fade" id="kisiModal_<?php echo $row_ara['kisi_id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg"><!--modal-dialog-centered-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Kişi Bilgileri</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <!-- <h5 class="text-center text-primary" style="font-size: x-large;">
                                    <?php //echo "ID[".$row_ara['id']."]      ".$row_ara['ad']."  ".$row_ara['soyad']?>
                                  </h5> -->
                                <div class="table-responsive text-nowrap">
                                  <form action="" method="post" id="myForm">
                                  <div class="table table-border"> 
                                    <?php
                                      $sql_kisi_liste = " SELECT
                                      g.gorev_adi AS gorev,
                                      b.birim_adi AS birim,
                                      u.unvan_adi as unvan,
                                      k.ad,
                                      k.soyad,
                                      k.email,
                                      k.id,
                                      k.aktif_mi
                                  FROM
                                      rb_rehber.tbl_kisi k
                                      LEFT JOIN rb_rehber.tbl_gorev g ON k.gorev_id  = g.id
                                      LEFT JOIN rb_rehber.tbl_unvan u ON k.unvan_id  = u.id
                                     LEFT  JOIN rb_rehber.tbl_birim b ON k.birim_id  = b.id
                                  where k.silindi_mi=false and k.id = $row_liste[kisi_id] ";
                                        $sonuc_kisi_liste = pg_query($db,$sql_kisi_liste);
                                    ?>
                                    <div class="trow bg-dark "> 
                                      <div class="tcolumn bg-secondary text-white">KİŞİ <br> ID</div> 
                                      <div class="tcolumn bg-secondary text-white">AD</div> 
                                      <div class="tcolumn bg-secondary text-white">SOYAD</div> 
                                      <div class="tcolumn bg-secondary text-white">UNVAN</div> 
                                      <div class="tcolumn bg-secondary text-white">GÖREV</div>
                                      <div class="tcolumn bg-secondary text-white">BİRİM</div> 
                                      <div class="tcolumn bg-secondary text-white">EMAIL</div> 
                                      <div class="tcolumn bg-secondary text-white">AKTİF<br> Mİ</div> 
                                    </div> 
                                
                                      <?php 
                                      while($row_kisi_liste=pg_fetch_assoc($sonuc_kisi_liste)){?>
                                        <div class="trow">
                                          <div class="tcolumn"><?php echo $row_kisi_liste['id']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['ad']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['soyad']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['unvan']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['gorev']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['birim']; ?></div>
                                          <div class="tcolumn"><?php echo $row_kisi_liste['email']; ?></div>
                                          <div class="tcolumn"><input class="form-check-input mt-0" type="checkbox" disabled readonly
                                          <?php if($row_kisi_liste['aktif_mi']=='t'){echo"checked";} ?>>
                                          </div>
                                        </div>
                                      <?php } 
                                      ?>
                                  </div>
                                </form>
                                </div>
                                </div>
                                <div class="modal-footer">
                                  <form action="telefonlar.php" method="get">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                  <a href="kayitlar.php?duzenle_id=<?php echo$row_ara['kisi_id'];?>" class="text-white">
                                    <i class="bx bx-edit-alt me-1"></i><input type="button" value="DÜZENLE" class="btn btn-info">
                                  </a>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                      <?php
                      $count++;
                      }
                      
                    }
                    //dis if else
                  }
                     ?>
                      
                    </tbody>
                    <tfoot class="table-border-bottom-0">
                      <tr>
                        <th></th>
                        <th>TELEFON</th>
                        <th>DAHİLİ</th>
                        <th>TELEFON <br>MODELİ</th>
                        <th>MAC <br>ADRESİ</th>
                        <th>BİRİM ADI</th>
                        <th>KİŞİ <br>ID</th>
                        <th>KİŞİ<br> BİLGİLERİ</th>
                        <th>ARAMA <br>YETKİSİ</th>
                        <th>ARAMADA <br>GÖRÜNSÜN MÜ</th>
                        <th class="text-center">İŞLEMLER</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

            </div>
            <!-- / Content -->

           



            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->


<?php 
    include('includes/scripts_footer.php');
?>        