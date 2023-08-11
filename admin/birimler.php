<?php 
    include('guvenlik.php'); 
    include('includes/header.php');
    include('includes/menu_navbar.php');

    if(!isset($_SESSION['ekle-drm'])){
      $_SESSION['ekle-drm']='';
    }

    if(isset($_GET['birim-ekle'])){
    $birim_t_ekle = $_GET['birim-ekle'];
    }

    $sql_birim_turu = " select distinct  birim_turu from rb_rehber.tbl_birim  where birim_turu notnull order by birim_turu;
    ";
    $sonuc_birim_turu = pg_query($db,$sql_birim_turu);

    $sql_birim = " select id, birim_adi  from rb_rehber.tbl_birim  where birim_turu = 'Merkez' and silindi_mi ='false' order by birim_adi;
    ";
    $sonuc_birim = pg_query($db,$sql_birim);

    $sql_kadro = " select id, birim_adi  from rb_rehber.tbl_birim  where birim_turu = 'Merkez' and silindi_mi ='false' order by birim_adi;
    ";
    $sonuc_kadro = pg_query($db,$sql_kadro);




    $sql_liste = " select  
    case when length( birim_adi) < 40 then  birim_adi
     else concat( left(birim_adi,40),'...') end as birim_kisa,* from rb_rehber.tbl_birim where  silindi_mi=false
    order by birim_adi ;";
    $sonuc_liste = pg_query($db,$sql_liste);
   

 if(isset($_POST['ara-btn'])){
    $aranan = $_POST['aranan'];

    $sql_ara = "SELECT  
    case when length( birim_adi) < 40 then  birim_adi
     else concat( left(birim_adi,40),'...') end as birim_kisa,*
FROM
    rb_rehber.tbl_birim b
where b.silindi_mi=false and
b.birim_adi::text  ilike '%$aranan%' or b.birim_turu::text ilike '%$aranan%' or b.kadro_tipi::text ilike '%$aranan%' or  b.yoksis::text ilike '%$aranan%' or b.id::text ilike '%$aranan%' 
order by b.id;";

    $sonuc_ara = pg_query($db,$sql_ara);
 }



if(isset($_POST['duzenle-btn'])){
  $h_id = $_POST['hidden-id'];

  $sql_duzenle = "SELECT *
FROM
  rb_rehber.tbl_birim b
  where b.id = $h_id
  ";

  $sonuc_duzenle = pg_query($db,$sql_duzenle);
}



if(isset($_POST['sil-btn'])){
  $h_id=$_POST['hidden-id'];
  $sql_sil="UPDATE rb_rehber.tbl_birim SET silindi_mi ='true' WHERE id='$h_id'";
  $sonuc_sil=pg_query($db,$sql_sil);
  if($sonuc_sil){
     $_SESSION['ekle-drm'] = "Birim  Silindi!";
  }else{
    $_SESSION['ekle-drm'] = "Birim  Silinmedi!!!";
  }
}


if(isset($_POST['tel-liste'])){
  echo 'tel liste';
  $hh_id=$_POST['hidden-id'];
  $sql_tel_liste = "SELECT * FROM rb_rehber.tbl_telefon WHERE kisi_id ='$hh_id'";
  $sonuc_tel_liste = pg_query($db,$sql_tel_liste);


}

if(isset($_POST['duz-tel'])){
  $duz_id = $_POST['hidden-id'];
  $sql_duz_tel = " SELECT * FROM rb_rehber.tbl_telefon t WHERE t.kisi_id='$duz_id' ";
  $sonuc_duz_tel = pg_query($db,$sql_duz_tel);
  header('Location:telefonlar.php');

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

<!-- <script>
    var count = 0
    var tur1=document.querySelector('#tur1')
    var tur2=document.querySelector('#tur2')
    function gorun(){
        if(count%2==0){
            tur1.style.display = "none"
            tur2.style.display = "block"
        }else{
            tur2.style.display = "none"
            tur1.style.display = "block"
        }
    }
</script> -->


          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Birimler /</span> Ekle - Düzenle - Sil</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link 
                      <?php if(!isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="birimler.php"><i class="bx bx-plus me-1"></i> Ekle</a>
                    </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav-item">
                      <span class="nav-link 
                      <?php if(isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="#"><i class="bx bx-edit me-1 "></i> Düzenle</span>
                    </li>
                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header"><strong>Birim Bilgileri</strong></h5>
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
                    <?php if(isset($_POST['duzenle-btn'])){
                      while($row_duzenle = pg_fetch_assoc($sonuc_duzenle)){?>
                      <form id="formAccountSettings" method="POST"  action="birim-ekle.php">
                        <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="birim-adi"
                              value="<?php echo $row_duzenle['birim_adi']; ?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM TÜRÜ:</label>
                            <select id="language" class="select2 form-select" name="birim-turu">
                              <option value="<?php echo $row_duzenle['birim_turu'];?>"><?php echo $row_duzenle['birim_turu'];?></option>
                              <?php while($row_birim_turu = pg_fetch_assoc($sonuc_birim_turu)){?>
                              <option value="<?php echo $row_birim_turu['birim_turu'];  ?>"><?php echo $row_birim_turu['birim_turu'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">YÖKSİS:</label>
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="yoksis"
                              value="<?php echo $row_duzenle['yoksis']; ?>"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label class="form-label" for="country">BİRİM TİPİ:</label>
                            <select id="country" class="select2 form-select" name="kadro-tipi">
                              <option value="<?php echo $row_duzenle['kadro_tipi'];?>"><?php echo $row_duzenle['kadro_tipi'];?></option>
                              <?php while($row_kadro = pg_fetch_assoc($sonuc_kadro)){?>
                              <option value="<?php echo $row_b['kadro_tipi'];  ?>"><?php echo $row_b['kadro_tipi'];?></option>
                              <?php }?>
                            </select>
                        </div>
                          <div class="m-3 col-md-12">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox"  
                            <?php if($row_duzenle['aktif_mi']=='t'){echo 'checked';} ?>
                             name="aktif">
                            &nbsp;&nbsp;&nbsp;
                            <label for="email" class="form-label">AKTİF Mİ</label>
                          </div>
                          <input type="text" hidden name="hidden_id" value="<?php echo $row_duzenle['id'];?>">
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2" name="update-btn" value="Kaydet">Kaydet</button>
                          <a href="birimler.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
                        </div>
                      </form>
                    <?php
                      }
                  }else{
                    ?>
                      <form id="formAccountSettings" method="POST"  action="birim-ekle.php">
                        <div class="row">
                            <?php 
                            if(!isset($_GET['birim-ekle'])){
                            ?>
                        <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="birim-adi"
                              placeholder="Eklemek istediğiniz birim"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM TÜRÜ:</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="birimler.php?birim-ekle=1"><label for="language" class="form-label text-primary badge rounded-pill bg-label-primary">YENİ TÜR EKLE</label></a>
                            <select  class="select2 form-select" name="birim-turu1" id="tur1">
                                <option value=""></option>
                              <?php while($row_birim_turu = pg_fetch_assoc($sonuc_birim_turu)){?>
                              <option value="<?php echo $row_birim_turu['birim_turu'];  ?>"><?php echo $row_birim_turu['birim_turu'];?></option>
                              <?php }?>
                            </select>
                          </div>
                          <?php }else{?>
                            <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">BİRİM TÜRÜ:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="birim-turu2"
                              placeholder="Eklemek istediğiniz birim türü"
                            />
                          </div> 
                          <?php } ?>
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label">YÖKSİS:</label>
                            <input
                              class="form-control"
                              type="tel"
                              id="firstName"
                              name="yoksis"
                            />
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="language" class="form-label">BİRİM TİPİ:</label>
                            <select  class="select2 form-select" name="kadro-tipi">
                              <option value="Akademik">Akademik</option>
                              <option value="İdari">İdari</option>
                              <option value="Diğer Personel">Diğer Personel</option>
                            </select>
                          </div>
                          <input type="hidden" name="parent-id" value="">
                          <div class="m-3 col-md-12">
                          &nbsp;
                            <input class="form-check-input mt-0" type="checkbox" name="aktif">
                            &nbsp;&nbsp;&nbsp;
                            <label  class="form-label">AKTİF Mİ</label>
                          </div>
                          
                        <div class="mt-2">
                          
                            <button type="submit" class="btn btn-primary me-2" name="ekle-btn" value="Kaydet">Kaydet</button>
                          
                          <a href="birimler.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
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
                            <th>id</th>
                            <th>BİRİM ADI</th>
                            <th>BİRİM TÜRÜ</th>
                            <th>YÖKSİS</th>
                            <th>KADRO TİPİ</th>
                            <th>Aktİf mİ</th>
                            <th class="text-center">İŞLEMLER</th>
                          </tr>
                        </thead>
                        <tbody>
                    <?php 
                    $count=1;
                    while($row_liste = pg_fetch_assoc($sonuc_liste)){
                      ?>
                      <tr>
                        <td><span class="badge badge-center rounded-pill bg-label-secondary"><?php echo $count;?></span> </td>
                        <td>
                          <strong data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<?php echo $row_liste['birim_adi'];?>">
                          <?php echo $row_liste['birim_kisa'];?>
                          </strong>
                        </td>
                        <td><?php echo $row_liste['birim_turu'];?></td>
                        <td><?php echo $row_liste['yoksis'];?></td>
                        <td><?php echo $row_liste['kadro_tipi'];?></td>
                        <form action="" method="post">
                          <!-- <td><a href="#" style="text-decoration: underline;" data-bs-toggle="modal" data-bs-target="#telefonModal_<?php echo $row_liste['id'];?>">
                           Telefon
                          </a></td> -->
                        <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                        <?php if($row_liste['aktif_mi']=='t'){echo"checked";} ?>>
                        </td>
                        <td>
                            <!-- <span name="duz-tel" class="btn badge bg-label-success me-1">
                            <a href="telefonlar.php?kisi_id=<?php echo $row_liste['id'];?>" style="color:white;" class=" badge-success text-success">
                            <i class="bx bx-edit-alt me-1"></i>TELEFON
                          </a></span> -->
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

                    <?php
                    $count++;
                     }   ?>
                     </tbody>
                    <tfoot class="table-border-bottom-0">
                      <tr>
                      <th>id</th>
                      <th>BİRİM ADI</th>
                    <th>BİRİM TÜRÜ</th>
                    <th>YÖKSİS</th>
                    <th>KADRO TİPİ</th>
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
                        <th>id</th>
                        <th>BİRİM ADI</th>
                        <th>BİRİM TÜRÜ</th>
                        <th>YÖKSİS</th>
                        <th>KADRO TİPİ</th>
                        <th>Aktİf mİ</th>
                        <th class="text-center">İŞLEMLER</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $count=1;
                      while($row_ara = pg_fetch_assoc($sonuc_ara)){
                      ?>
                      <tr>
                        <td><span class="badge badge-center rounded-pill bg-label-secondary"><?php echo $count;?></span></td>
                        <td>
                          <strong data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="" data-bs-original-title="<?php echo $row_ara['birim_adi'];?>">
                          <?php echo $row_ara['birim_kisa'];?>
                          </strong>
                        </td>
                        <td><?php echo $row_ara['birim_turu'];?></td>
                        <td><?php echo $row_ara['yoksis'];?></td>
                        <td><?php echo $row_ara['kadro_tipi'];?></td>
                        <td> <input class="form-check-input mt-0" type="checkbox" disabled readonly
                        <?php if($row_ara['aktif_mi']=='t'){echo"checked";} ?>>
                        </td>
                        <td>
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
                      <?php
                      $count++;
                      }
                     ?>
                     </tbody>
                    <tfoot class="table-border-bottom-0">
                      <tr>
                      <th>id</th>
                        <th>BİRİM ADI</th>
                        <th>BİRİM TÜRÜ</th>
                        <th>YÖKSİS</th>
                        <th>KADRO TİPİ</th>
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