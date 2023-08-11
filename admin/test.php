<?php

// if (extension_loaded("pgsql")) {
//     echo "PostgreSQL arabirimi kurulu.";
// } else {
//     echo "PostgreSQL arabirimi kurulu değil.";
// }

// $baglanti_cumlesi = sprintf(
//     "host=%s port=%s dbname=%s user=%s password=%s",
//     "localhost",
//     "5432",
//     "rb_rehber",
//     "postgres",
//     "123456"
// );

// $baglanti = pg_connect($baglanti_cumlesi);
// $sorgu = pg_query($baglanti, "select version()");
// var_dump(pg_fetch_all($sorgu));
// pg_close($baglanti);


<!--Telefon Modal -->
<div class="modal fade" id="telefonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"><!--modal-dialog-centered-->
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Telefon Listesi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5></h5>
      <div class="table-responsive text-nowrap">
        <form action="telefonlar.php" method="post" id="myForm">
        <table class="table"> 
          <thead>
            <th colspan="5" class="text-center bg-secondary"><h5 class=" text-white"><?php //if(isset())echo $hh_id; ?>  AD   soyad</h5></th>
            <input type="text" name="hidden-id" value="<?php echo $hh_id; ?>" hidden>
          </thead>
          <thead>
            <th>TELEFON</th>
            <th>DAHİLİ</th>
            <th>MAC ADRESİ</th>
            <th>TELEFON MODELİ</th>
            <th>ARAMA YETKİSİ</th>
          </thead>
          <tbody>
            <?php 
            if(isset($_POST['tel-liste'])){
            while($row_tel_liste=pg_fetch_assoc($sonuc_tel_liste)){?>
              <tr>
                <td><?php echo $row_tel_liste['telefon']; ?></td>
                <td><?php echo $row_tel_liste['dahili']; ?></td>
                <td><?php echo $row_tel_liste['mac_adresi']; ?></td>
                <td><?php echo $row_tel_liste['telefon_modeli']; echo"5245"; ?></td>
                <td> 
                  <input class="form-check-input mt-0" type="checkbox" disabled readonly
                  <?php if($row_tel_liste['arama_yetkisi']=='t'){echo"checked";} ?>>
                </td>
              </tr>
            <?php } unset($_POST); } ?>
          </tbody>
        </table>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <a href="telefonlar.php"><button type="submit" class="btn btn-info t" name="duz-tel"> <i class="bx bx-edit-alt me-1"></i>DÜZENLE</button></a>
        </form>
      </div>
    </div>
  </div>
</div>



?>

<form action="cikis.php" method="get">
                                   <button type="submit" name="cikis-btn" class="btn btn-primary">
                                    Evet
                                  </button>
                                </form>