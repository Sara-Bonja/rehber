<?php 
    include('guvenlik.php'); 
    include('includes/header.php');
    include('includes/menu_navbar.php');

    if(!isset($_SESSION['ekle-drm'])){
      $_SESSION['ekle-drm']='';
    }


    $sql_g = " SELECT * FROM rb_rehber.tbl_gorev";
    $sonuc_g = pg_query($db,$sql_g);
    $sql_u = " SELECT * FROM rb_rehber.tbl_unvan";
    $sonuc_u = pg_query($db,$sql_u);
    $sql_b = " SELECT * FROM rb_rehber.tbl_birim";
    $sonuc_b = pg_query($db,$sql_b);
    $sql_birim_turu = " select distinct  birim_turu from rb_rehber.tbl_birim  where birim_turu notnull order by birim_turu;";
    $sonuc_birim_turu = pg_query($db,$sql_birim_turu);

    $sql_liste = " SELECT
    g.gorev_adi AS gorev,
    u.unvan_adi as unvan,
    b.birim_adi,
    k.ad,
    k.soyad,
    k.email,
    k.id,
    k.aktif_mi,
    case when length(birim_adi) < 28 then  birim_adi
     else concat( left(birim_adi,28),'...') end as birim_kisa
FROM
    rb_rehber.tbl_kisi k
    LEFT JOIN rb_rehber.tbl_gorev g ON k.gorev_id  = g.id
    LEFT JOIN rb_rehber.tbl_unvan u ON k.unvan_id  = u.id
    LEFT JOIN rb_rehber.tbl_birim b ON k.birim_id  = b.id
where k.silindi_mi=false
order by k.ad,k.soyad ;";
    $sonuc_liste = pg_query($db,$sql_liste);

    

 if(isset($_POST['ara-btn'])){
    $aranan = $_POST['aranan'];

    $sql_ara = "SELECT
    g.gorev_adi AS gorev,
    u.unvan_adi as unvan,
    b.birim_adi,
    k.ad,
    k.soyad,
    k.email,
    k.id,
    k.aktif_mi,
    case when length(birim_adi) < 28 then  birim_adi
     else concat( left(birim_adi,28),'...') end as birim_kisa
FROM
    rb_rehber.tbl_kisi k
    LEFT JOIN rb_rehber.tbl_gorev g ON k.gorev_id  = g.id
    LEFT JOIN rb_rehber.tbl_unvan u ON k.unvan_id  = u.id
   LEFT  JOIN rb_rehber.tbl_birim b ON k.birim_id  = b.id
where k.silindi_mi=false and
k.ad  like '%$aranan%' or k.soyad  like '%$aranan%' or k.email like '%$aranan%' or  g.gorev_adi like '%$aranan%' or b.birim_adi like '%$aranan%' or u.unvan_adi like '%$aranan%'
order by k.ad ,k.soyad;";

    $sonuc_ara = pg_query($db,$sql_ara);
 }



if(isset($_POST['duzenle-btn'])||isset($_GET['duzenle_id'])){
  
  if(isset($_GET['duzenle_id'])){
    $h_id= $_GET['duzenle_id'];
  }else{
  $h_id = $_POST['hidden-id'];
  }
  $sql_duzenle = "SELECT *,
  k.id as kisi_id
FROM
  rb_rehber.tbl_kisi k
  left JOIN rb_rehber.tbl_gorev g ON k.gorev_id  = g.id
  left JOIN rb_rehber.tbl_unvan u ON k.unvan_id  = u.id
  left JOIN rb_rehber.tbl_birim b ON k.birim_id  = b.id
  where k.id = $h_id
  ";

  $sonuc_duzenle = pg_query($db,$sql_duzenle);
}



if(isset($_POST['sil-btn'])||isset($_GET['sil_id'])){
  if(isset($_GET['sil_id'])){
  $h_id= $_GET['sil_id'];
  }else{
    $h_id=$_POST['hidden-id'];
  }
  $sql_sil="UPDATE rb_rehber.tbl_kisi SET silindi_mi ='true' WHERE id='$h_id'";
  $sonuc_sil=pg_query($db,$sql_sil);
  if($sonuc_sil){
     $_SESSION['ekle-drm'] = "Kayıt  Silindi!";
  }else{
    $_SESSION['ekle-drm'] = "Kayıt  Silinmedi!!!";
  }
}


if(isset($_POST['tel-liste'])){
  echo 'tel liste';
  $hh_id=$_POST['hidden-id'];
  $sql_tel_liste = "SELECT * FROM rb_rehber.tbl_telefon WHERE kisi_id ='$hh_id'";
  $sonuc_tel_liste = pg_query($db,$sql_tel_liste);

if($sonuc_tel_liste){
      echo $sql_tel_liste;
    }
}else{
  //echo "11111111111111111111111111111111";

}

if(isset($_POST['duz-tel'])){
  $duz_id = $_POST['hidden-id'];
  $sql_duz_tel = " SELECT * FROM rb_rehber.tbl_telefon t WHERE t.kisi_id='$duz_id' ";
  $sonuc_duz_tel = pg_query($db,$sql_duz_tel);
  header('Location:telefonlar.php');

}
?>

<!-- <script>
  $(document).ready(function() {
    // Reset the form when the modal is closed
    $('#telefonModal').on('hidden.bs.modal', function() {
      $('#myForm')[0].reset(); // Reset the form with ID 'myForm'
    });
  });
</script> -->

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


          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kayıtlar /</span> Ekle - Düzenle - Sil</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link 
                      <?php if(!isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="kayitlar.php"><i class="bx bx-plus me-1"></i> Ekle</a>
                    </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav-item">
                      <span class="nav-link 
                      <?php if(isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="#"><i class="bx bx-edit me-1 "></i> Düzenle</span>
                    </li>
                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header text-dark"><strong>Kişi Bilgileri</strong></h5>
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
                    <?php if(isset($_POST['duzenle-btn'])||isset($_GET['duzenle_id'])){
                      while($row_duzenle = pg_fetch_assoc($sonuc_duzenle)){?>
                      <form id="formAccountSettings" method="POST"  action="kayit-ekle.php">
                        <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">GÖREVİ:</label>
                            <select id="language" class="select2 form-select autofocus" name="gorev">
                              <option value="<?php echo $row_duzenle['gorev_id'];?>"><?php echo $row_duzenle['gorev_adi'];?></option>
                              <?php while($row_g = pg_fetch_assoc($sonuc_g)){?>
                              <option value="<?php echo $row_g['id'];  ?>"><?php echo $row_g['gorev_adi'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">UNVANI:</label>
                            <select id="language" class="select2 form-select" name="unvan">
                              <option value="<?php echo $row_duzenle['unvan_id'];?>"><?php echo $row_duzenle['unvan_adi'];?></option>
                              <?php while($row_u = pg_fetch_assoc($sonuc_u)){?>
                              <option value="<?php echo $row_u['id'];  ?>"><?php echo $row_u['unvan_adi'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="ad"
                              placeholder="Adı"
                              value="<?php echo $row_duzenle['ad']; ?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">SOYADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="soyad"
                              placeholder="Soyadı"
                              value="<?php echo $row_duzenle['soyad']; ?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">BİRİM TÜRÜ:</label>
                            <select id="tur" class="select2 form-select" name="birim-turu">
                              <option value="<?php echo $row_duzenle['birim_turu'];?>"><?php echo $row_duzenle['birim_turu'];?></option>
                              <?php while($row_birim_turu = pg_fetch_assoc($sonuc_birim_turu)){?>
                              <option value="<?php echo $row_birim_turu['birim_turu'];  ?>"><?php echo $row_birim_turu['birim_turu'];?></option>
                              <?php }?>
                            </select>
                        </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">BİRİMİ:</label>
                            <select id="birim" class="select2 form-select" name="birim">
                              <option value="<?php echo $row_duzenle['birim_id'];?>"><?php echo $row_duzenle['birim_adi'];?></option>
                            </select>
                        </div>
                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="email" id="email" name="email" placeholder="" value="<?php echo $row_duzenle['email']; ?>"/>
                          </div>
                          <div class="m-3 col-md-12">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox"  
                            <?php if($row_duzenle['aktif_mi']=='t'){echo 'checked';} ?>
                             name="aktif">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">AKTİF Mİ</label>
                          </div>
                          <input type="text" hidden name="hidden_id" value="<?php echo $row_duzenle['kisi_id'];?>">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2" name="update-btn" value="Kaydet">Kaydet</button>
                          <a href="kayitlar.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
                        </div>
                      </form>
                    <?php
                      }
                  }else{
                    ?>
                      <form id="formAccountSettings" method="POST"  action="kayit-ekle.php">
                        <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">GÖREVİ:</label>
                            <select id="language" class="select2 form-select autofocus" name="gorev">
                              <option value=""></option>
                              <?php while($row_g = pg_fetch_assoc($sonuc_g)){?>
                              <option value="<?php echo $row_g['id'];  ?>"><?php echo $row_g['gorev_adi'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">UNVANI:</label>
                            <select id="language" class="select2 form-select" name="unvan">
                              <option value=""></option>
                              <?php while($row_u = pg_fetch_assoc($sonuc_u)){?>
                              <option value="<?php echo $row_u['id'];  ?>"><?php echo $row_u['unvan_adi'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="ad"
                              placeholder="Adı"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">SOYADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="soyad"
                              placeholder="Soyadı"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">BİRİM TÜRÜ:</label>
                            <select id="tur" class="select2 form-select" name="birim-turu">
                              <option value=""></option>
                              <?php while($row_birim_turu = pg_fetch_assoc($sonuc_birim_turu)){?>
                              <option value="<?php echo $row_birim_turu['birim_turu'];  ?>"><?php echo $row_birim_turu['birim_turu'];?></option>
                              <?php }?>
                            </select>
                        </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">BİRİMİ:</label>
                            <select id="birim" class="select2 form-select" name="birim">
                              <option value="">Birim Seçiniz</option>
                            </select>
                        </div>
                          <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email" name="email" placeholder=""/>
                          </div>
                          <div class="m-3 col-md-6">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox" name="aktif">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">AKTİF Mİ</label>
                          </div>
                        <div class="mt-2">
                          
                            <button type="submit" class="btn btn-primary me-2" name="ekle-btn" value="Kaydet">Kaydet</button>
                          
                          <a href="kayitlar.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
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
                  <form action="" method="post">
                    <div class="navbar-nav align-items-center">
                      <div class="nav-item d-flex align-items-center">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input
                          type="text"
                          class="form-control border-0 shadow-none form-controler"
                          placeholder="Arama..."
                          name="aranan"
                          style="min-width: 300px;"
                        /> <button type="submit" class="btn badge rounded-pill bg-label-secondary form-controler" name="ara-btn" class="display:inline;">Ara</button>
                      </div>
                  </div>
                 
                </form>
                </div>
                <div class="table-responsive text-nowrap">
                  
                    <?php if(!isset($_POST['ara-btn'])){?>
                      <table class="table">
                        <thead>
                          <tr>
                            <th></th>
                            <th>Ad</th>
                            <th>Soyad</th>
                            <th>Email</th>
                            <th>Unvan</th>
                            <th>Görev</th>
                            <th>BİRİM</th>
                            <th>Telefon</th>
                            <th>Aktİf mİ</th>
                            <th class="text-center">İŞLEMLER</th>
                          </tr>
                        </thead>
                        <tbody>
                    <?php $count=1;
                     while($row_liste = pg_fetch_assoc($sonuc_liste)){
                      ?>
                      <tr>
                        <td><span class="badge badge-center bg-label-primary"><?php echo $count;?> </span></td>
                        <td><strong><?php echo $row_liste['ad'];?></strong></td>
                        <td><strong><?php echo $row_liste['soyad'];?></strong></td>
                        <td><?php echo $row_liste['email'];?></td>
                        <td><?php echo $row_liste['unvan'];?></td>
                        <td><?php echo $row_liste['gorev'];?></td>
                        <td><span data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<?php echo $row_liste['birim_adi'];?>">
                      <?php echo $row_liste['birim_kisa'];?></span></td>
                        <form action="" method="post">
                          <td><a href="#" style="text-decoration: underline;" data-bs-toggle="modal" data-bs-target="#telefonModal_<?php echo $row_liste['id'];?>">
                           Telefon
                          </a></td>
                          <input type="text" hidden value="<?php echo $row_liste['ad'];?>" name="hidden-ad">
                          <input type="text" hidden value="<?php echo $row_liste['soyad'];?>" name="hidden-soyad">
                        
                        <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                        <?php if($row_liste['aktif_mi']=='t'){echo"checked";} ?>>
                        </td>
                        <td>
                            <span name="duz-tel" class="btn badge bg-label-success me-1">
                            <a href="telefonlar.php?kisi_id=<?php echo $row_liste['id'];?>" style="color:white;" class=" badge-success text-success">
                            <i class="bx bx-edit-alt me-1"></i>TELEFON
                          </a></span>
                          &nbsp;&nbsp;
                          <div class="dropdown" style="display: inline;" >
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="min-width: 8rem;">
                                <input type="text" hidden value="<?php echo $row_liste['id'];?>" name="hidden-id">
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
                          <!-- Telefon Modal -->
                          <div class="modal fade" id="telefonModal_<?php echo $row_liste['id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg"><!--modal-dialog-centered-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Telefon Listesi</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <h5 class="text-center text-primary" style="font-size: x-large;">
                                    <?php echo "ID[".$row_liste['id']."]      ".$row_liste['ad']."  ".$row_liste['soyad']?>
                                  </h5>
                                <div class="table-responsive text-nowrap">
                                  <form action="" method="post" id="myForm">
                                  <div class="table table-border"> 
                                    <?php
                                      $sql_tel_liste = " SELECT * FROM rb_rehber.tbl_telefon WHERE kisi_id = $row_liste[id] ";
                                        $sonuc_tel_liste = pg_query($db,$sql_tel_liste);
                                    ?>
                                    <div class="trow bg-dark "> 
                                      <div class="tcolumn bg-secondary text-white">TELEFON <br> ID</div> 
                                      <div class="tcolumn bg-secondary text-white">TELEFON</div> 
                                      <div class="tcolumn bg-secondary text-white">DAHİLİ</div> 
                                      <div class="tcolumn bg-secondary text-white">MAC ADRESİ</div> 
                                      <div class="tcolumn bg-secondary text-white">TELEFON<br> MODELİ</div> 
                                      <div class="tcolumn bg-secondary text-white">ARAMA<br> YETKİSİ</div> 
                                      <div class="tcolumn bg-secondary text-white">ARAMADA<br> GÖRÜNSÜN MÜ</div> 
                                    </div> 
                                
                                      <?php 
                                      while($row_tel_liste=pg_fetch_assoc($sonuc_tel_liste)){?>
                                        <div class="trow">
                                          <div class="tcolumn"><?php echo $row_tel_liste['id']; ?></div>
                                          <div class="tcolumn"><?php echo $row_tel_liste['telefon']; ?></div>
                                          <div class="tcolumn"><?php echo $row_tel_liste['dahili']; ?></div>
                                          <div class="tcolumn"><?php echo $row_tel_liste['mac_adresi']; ?></div>
                                          <div class="tcolumn"><?php echo $row_tel_liste['telefon_modeli']; ?></div>
                                          <div class="tcolumn"><input class="form-check-input mt-0" type="checkbox" disabled readonly
                                          <?php if($row_tel_liste['arama_yetkisi']=='t'){echo"checked";} ?>>
                                          </div>
                                          <div class="tcolumn"><input class="form-check-input mt-0" type="checkbox" disabled readonly
                                          <?php if($row_tel_liste['gorunsun_mu']=='t'){echo"checked";} ?>>
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
                                  <a href="telefonlar.php?kisi_id=<?php echo$row_liste['id'];?>" class="text-white">
                                    <i class="bx bx-edit-alt me-1"></i><input type="button" value="DÜZENLE" class="btn btn-info">
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
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Email</th>
                        <th>Unvan</th>
                        <th>Görev</th>
                        <th>BİRİM</th>
                        <th>Telefon</th>
                        <th>Aktİf mİ</th>
                        <th class="text-center">İŞLEMLER</th>
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
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Email</th>
                        <th>Unvan</th>
                        <th>Görev</th>
                        <th>BİRİM</th>
                        <th>Telefon</th>
                        <th>Aktİf mİ</th>
                        <th class="text-center">İŞLEMLER</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $count=1;
                      while($row_ara = pg_fetch_assoc($sonuc_ara)){
                      ?>
                      <tr>
                        <td><span class="badge badge-center bg-label-primary"><?php echo $count;?> </span></td>
                        <td><strong><?php echo $row_ara['ad'];?></strong></td>
                        <td><strong><?php echo $row_ara['soyad'];?></strong></td>
                        <td><?php echo $row_ara['email'];?></td>
                        <td><?php echo $row_ara['unvan'];?></td>
                        <td><?php echo $row_ara['gorev'];?></td>
                        <td><span data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<?php echo $row_ara['birim_adi'];?>">
                        <?php echo $row_ara['birim_kisa'];?></span></td>
                        <form action="" method="post">
                          <td><a href="#" style="text-decoration: underline;" data-bs-toggle="modal" data-bs-target="#telefonModal_<?php echo $row_ara['id'];?>">
                           Telefon
                          </a></td>
                          <input type="text" hidden value="<?php echo $row_ara['ad'];?>" name="hidden-ad">
                          <input type="text" hidden value="<?php echo $row_ara['soyad'];?>" name="hidden-soyad">
                        
                        <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                        <?php if($row_ara['aktif_mi']=='t'){echo"checked";} ?>>
                        </td>
                        <td>
                            <span name="duz-tel" class="btn badge bg-label-success me-1">
                            <a href="telefonlar.php?kisi_id=<?php echo $row_ara['id'];?>" style="color:white;" class=" badge-success text-success">
                            <i class="bx bx-edit-alt me-1"></i>TELEFON
                          </a></span>
                          &nbsp;&nbsp;
                          <div class="dropdown" style="display: inline;" >
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" style="min-width: 8rem;">
                                <input type="text" hidden value="<?php echo $row_ara['id'];?>" name="hidden-id">
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
                      

<!-- Telefon Modal -->
<div class="modal fade" id="telefonModal_<?php echo $row_ara['id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"><!--modal-dialog-centered-->
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Telefon Listesi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 class="text-center text-primary" style="font-size: x-large;">
          <?php echo "ID[".$row_ara['id']."]      ".$row_ara['ad']."  ".$row_ara['soyad']?>
        </h5>
      <div class="table-responsive text-nowrap">
        <form action="" method="post" id="myForm">
        <div class="table table-border"> 
          <?php
             $sql_tel_liste = " SELECT * FROM rb_rehber.tbl_telefon WHERE kisi_id = $row_ara[id] ";
              $sonuc_tel_liste = pg_query($db,$sql_tel_liste);
          ?>
          <div class="trow bg-dark "> 
            <div class="tcolumn bg-secondary text-white">TELEFON <br> ID</div> 
            <div class="tcolumn bg-secondary text-white">TELEFON</div> 
            <div class="tcolumn bg-secondary text-white">DAHİLİ</div> 
            <div class="tcolumn bg-secondary text-white">MAC ADRESİ</div> 
            <div class="tcolumn bg-secondary text-white">TELEFON<br> MODELİ</div> 
            <div class="tcolumn bg-secondary text-white">ARAMA<br> YETKİSİ</div> 
            <div class="tcolumn bg-secondary text-white">ARAMADA<br> GÖRÜNSÜN MÜ</div> 
          </div> 
      
            <?php 
            while($row_tel_liste=pg_fetch_assoc($sonuc_tel_liste)){?>
              <div class="trow">
                <div class="tcolumn"><?php echo $row_tel_liste['id']; ?></div>
                <div class="tcolumn"><?php echo $row_tel_liste['telefon']; ?></div>
                <div class="tcolumn"><?php echo $row_tel_liste['dahili']; ?></div>
                <div class="tcolumn"><?php echo $row_tel_liste['mac_adresi']; ?></div>
                <div class="tcolumn"><?php echo $row_tel_liste['telefon_modeli']; ?></div>
                <div class="tcolumn"><input class="form-check-input mt-0" type="checkbox" disabled readonly
                <?php if($row_tel_liste['arama_yetkisi']=='t'){echo"checked";} ?>>
                </div>
                <div class="tcolumn"><input class="form-check-input mt-0" type="checkbox" disabled readonly
                <?php if($row_tel_liste['gorunsun_mu']=='t'){echo"checked";} ?>>
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
        <a href="telefonlar.php?kisi_id=<?php echo$row_liste['id'];?>" class="text-white">
          <i class="bx bx-edit-alt me-1"></i><input type="button" value="DÜZENLE" class="btn btn-info">
        </a>
        </form>
      </div>
    </div>
  </div>
</div>
                      <?php $count++;
                      }
                     ?>
                     </tbody>
                    <tfoot class="table-border-bottom-0">
                      <tr>
                      <th></th>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Email</th>
                        <th>Unvan</th>
                        <th>Görev</th>
                        <th>BİRİM</th>
                        <th>Telefon</th>
                        <th>Aktİf mİ</th>
                        <th class="text-center">İŞLEMLER</th>
                      </tr>
                    </tfoot>
                  </table>
                  <?php
                    }
                     ?>
                      
                    
                </div>
              </div>

            </div>
            <!-- / Content -->




<div class="modal fade" id="largeModal" tabindex="-1" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                    
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
              <form action="" method="post">
                <button type="submit" class="btn btn-info" name="duz-tel"> <i class="bx bx-edit-alt me-1"></i>DÜZENLE</button>
              </form>
              </div>
            </div>
          </div>
        </div>
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->


<?php 
    include('includes/scripts_footer.php');
?>        