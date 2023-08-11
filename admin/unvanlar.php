<?php 
    include('guvenlik.php'); 
    include('includes/header.php');

    if(!isset($_SESSION['ekle-drm'])){
      $_SESSION['ekle-drm']='';
    }


    $sql_liste = " SELECT *
FROM
    rb_rehber.tbl_unvan u
where silindi_mi ='false'
order by u.id ;";
    $sonuc_liste = pg_query($db,$sql_liste);

    
    if(isset($_POST['ekle-btn']))
{
    $unvan=$_POST['unvan'];

    $sql_ekle="INSERT INTO rb_rehber.tbl_unvan (unvan_adi,silindi_mi) 
    VALUES ('$unvan','false')";
    $sonuc_ekle = pg_query($db,$sql_ekle);

    if($sonuc_ekle)
    {
        $_SESSION['ekle-drm'] = "Unvan  Eklendi!!";
    }
    else{
      $_SESSION['ekle-drm'] = "Unvan  Eklenmedi!!";
    }
}


if(isset($_POST['update-btn']))
{
    $u_id=$_POST['h-u-id'];
    $unvan=$_POST['unvan'];

    $sql_update=" UPDATE rb_rehber.tbl_unvan
    SET unvan_adi='$unvan'
    WHERE id='$u_id'";
    $sonuc_update = pg_query($db,$sql_update);

    if($sonuc_update)
    {
        $_SESSION['ekle-drm'] = "Unvan  Güncellendi!";
    }
    else{
      $_SESSION['ekle-drm'] = "Unvan  Güncellenmedi!!!";
    }

}


if(isset($_POST['duzenle-btn'])){
  $h_id = $_POST['hidden-id'];

  $sql_duzenle = "SELECT *
FROM
  rb_rehber.tbl_unvan u
  where u.id = $h_id
  ";

  $sonuc_duzenle = pg_query($db,$sql_duzenle);
}



if(isset($_POST['sil-btn'])){
  $u_id=$_POST['hidden-id'];
  $sql_sil="UPDATE rb_rehber.tbl_unvan
  SET silindi_mi='true'
  WHERE id='$u_id'";
  $sonuc_sil=pg_query($db,$sql_sil);
  if($sonuc_sil){
     $_SESSION['ekle-drm'] = "Unvan Silindi!";
  }else{
    $_SESSION['ekle-drm'] = "Unvan Silinmedi!!!";
  }
}
    include('includes/menu_navbar.php');

?>



          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Unvanlar /</span> Ekle - Düzenle - Sil</h4>

              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                      <a class="nav-link 
                      <?php if(!isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="unvanlar.php"><i class="bx bx-plus me-1"></i> Ekle</a>
                    </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav-item">
                      <span class="nav-link 
                      <?php if(isset($_POST['duzenle-btn'])){ echo "active";} ?>
                      " href="#"><i class="bx bx-edit me-1 "></i> Düzenle</span>
                    </li>
                  </ul>
                  <div class="card mb-4">
                    <h5 class="card-header"></h5>
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
                      <form id="formAccountSettings" method="POST"  action=" ">
                      <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label"> UNVAN ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="unvan"
                              value="<?php echo $row_duzenle['unvan_adi']?>"
                            />
                          </div>
                          <input type="hidden" name="h-u-id" value="<?php echo $row_duzenle['id']?>">
                        <div class="mt-2">
                            <br>
                            <button type="submit" class="btn btn-primary me-2" name="update-btn" value="Kaydet">Kaydet</button>
                          <a href="unvanlar.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
                        </div>
                      </form>
                    <?php
                      }
                  }else{
                    ?>
                      <form id="formAccountSettings" method="POST"  action=" ">
                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="firstName" class="form-label"> UNVAN ADI:</label>
                            <input
                              class="form-control"
                              type="text"
                              id="firstName"
                              name="unvan"
                            />
                          </div>
                        <div class="mt-2">
                            <br>
                            <button type="submit" class="btn btn-primary me-2" name="ekle-btn" value="Kaydet">Kaydet</button>
                          <a href="unvanlar.php"><button type="reset" class="btn btn-outline-secondary">Vazgeç</button></a>
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
                <h4 class="card-header text-primary ">
                Unvanlar
                </h4>
                <div class="table-responsive text-nowrap">
                      <table class="table text-center">
                        <thead>
                          <tr>
                            <th> UNVAN id</th>
                            <th>UNVAN</th>
                            <th>İŞLEMLER</th>
                          </tr>
                        </thead>
                        <tbody>
                    <?php while($row_liste = pg_fetch_assoc($sonuc_liste)){
                      ?>
                      <tr>
                        <td><span class="badge badge-center rounded-pill bg-label-secondary"><?php echo $row_liste['id'];?></span> </td>
                        <td><strong><?php echo $row_liste['unvan_adi'];?></strong></td>
                        <td>
                          <form action="" method="post">
                            <div class="" style="min-width: 8rem;">
                                <input type="text" hidden value="<?php echo $row_liste['id'];?>" name="hidden-id">
                                <button type="submit" class="btn badge btn-outline-info" name="duzenle-btn">
                                  <span class="tf-icons bx bx-edit-alt"></span>&nbsp;DÜZENLE
                                </button>
                                <button type="submit" class="btn badge btn-outline-danger" name="sil-btn">
                                  <span class="tf-icons bx bx-trash"></span>&nbsp;SİL
                                </button>
                          </div>
                        </form>
                        </td>
                      </tr>

                    <?php
                     }   ?>
                     </tbody>
                    <tfoot class="table-border-bottom-0">
                      <tr>
                      <th>Unvan id</th>
                        <th>Unvan</th>
                        <th>İŞLEMLER</th>
                      </tr>
                    </tfoot>
                  </table>
                    
                </div>
              </div>
                
            </div><br>
            <!-- / Content -->

          <!-- Content wrapper -->


<?php 
    include('includes/scripts_footer.php');
?>        