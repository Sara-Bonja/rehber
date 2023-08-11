<?php
// include('includes/database.php');

$host="localhost";
$db="postgres";
$hesap="postgres";
$sifre="123456";
        $db = pg_connect("host=$host port=5432 dbname=$db user=$hesap password=$sifre");

if(isset($_POST['aranan'])){
    $aranan = $_POST['aranan'];

    $sql_ara = " SELECT *, 
    yetki,
      g.id AS kullanici_id,
      k.id as kisi_id
  FROM
      rb_rehber.tbl_kisi k
      LEFT JOIN rb_rehber.tbl_kullanici g ON k.id  = g.kisi_id
      LEFT JOIN rb_rehber.tbl_yetki y ON g.yetki_id  = y.id
  where k.silindi_mi = false and (g.silindi_mi is null or  g.silindi_mi  =false) and k.aktif_mi =true and (k.ad ilike '%$aranan%' or k.soyad ilike '%$aranan%')
  order by g.kullanici_adi ,  g.id,k.id  ;";
    $sonuc_ara = pg_query($db,$sql_ara);

    if(pg_num_rows($sonuc_ara)>0){
        $count=1;
        while($row_ara=pg_fetch_assoc($sonuc_ara)){
            echo "<tr>";
            echo "<td><span class='badge badge-center rounded-pill bg-label-";
            if($row_ara['kullanici_id']!=null){echo 'primary';}else{echo 'secondary';}
            echo "'>$count</span></td>";
            echo "<td><strong>".$row_ara['ad']."   ". $row_ara['soyad']."</strong></td>";
            echo "<td>".$row_ara['yetki']."</td>";
            echo "<td><strong>".$row_ara['kullanici_adi']."</strong></td>";
            echo "<td><form action='' method='post'>
            <div class='dropdown' style='display: inline;' >
              <button type='button' class='btn p-0 dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                <i class='bx bx-dots-vertical-rounded'></i>
              </button>
              <div class='dropdown-menu' style='min-width: 12rem;'>";
            echo "<input type='text' hidden value='".$row_ara['kullanici_id']."' name='hidden-kul-id'>
            <input type='text' hidden value='".$row_ara['kisi_id']."' name='hidden-kisi-id'>";
            if($row_ara['kullanici_id']!=null){
                echo "<button type='submit' class='btn badge btn-outline-info dropdown-item' name='duzenle-btn'>
                <span class='tf-icons bx bx-edit-alt'></span>&nbsp;KULLANICI DÜZENLE
              </button><br>
              <button type='submit' class='btn badge btn-outline-danger dropdown-item' name='sil-btn'>
                <span class='tf-icons bx bx-trash'></span>&nbsp; KULLANICI SİL
              </button><br>";
            }
            if($row_ara['kullanici_id']!=null){
                echo "<a href='' class='' data-bs-toggle='modal' data-bs-target='#sifreModal_". $row_ara['kullanici_id']."' aria-pressed='true'> 
                <button type='submit' class='btn badge btn-outline-warning dropdown-item' name='sifre-btn'>
                <span class='tf-icons bx bx-repost'></span>&nbsp;YENİ ŞİFRE
              </button>
              </a><br>";
            }else{
                echo "<a href='kullanicilar.php?kisi_id=".$row_ara['kisi_id']."' class='btn badge btn-outline-primary dropdown-item'>
                <i class='bx bx-plus me-1'></i><input type='button' value='' class='btn badge btn-outline-primary dropdown-item'>".$row_ara['kullanici_id']." KULLANICI EKLE
            </a><br>";
            }
            echo "<a href='kayitlar.php?duzenle_id=<?". $row_ara['kisi_id']."' class='btn badge btn-outline-success dropdown-item'>
                  <i class='bx bx-edit-alt me-1'></i><input type='button' value='' class='btn badge btn-outline-success dropdown-item'>KİŞİ DÜZENLE
              </a><br>
              <a href='kayitlar.php?sil_id=<?". $row_ara['kisi_id']."' class='btn badge btn-outline-success dropdown-item'>
                  <i class='bx bx-trash me-1'></i><input type='button' value'' class='btn badge btn-outline-success dropdown-item'>KİŞİ SİL
              </a><br>";

            echo "</div></div></form></td></tr>";
            //modal
            echo "<div class='modal fade' id='sifreModal_". $row_ara['kisi_id']." tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                    <div class='modal-dialog modal-lg'><!--modal-dialog-centered-->
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <h1 class='modal-title fs-5' id='exampleModalLabel'>Yeni Şifre İşlemleri</h1>
                          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>";
            echo "<div class='modal-body'>
            <h5 class='text-center text-dark' style='font-size: x-large;'>
               KULLANICI ID[".$row_ara['id']."]    <br>  ".$row_ara['kullanici_adi']." <br> ".$row_ara['ad']."  ".$row_ara['soyad']."
            </h5>
          <div class='table-responsive text-nowrap'>
            <div class='alert alert-warning alert-dismissible' role='alert'>
              Şifre en az 8 karakter olup büyük/küçük harf ve sayı içermeli!!
            </div>";
            echo "<form action=' method='post' id='myForm' onsubmit='return dogrulama()'>
            <label for='email' class='form-label'>YENİ ŞİFRE:</label>
            &nbsp;&nbsp;<span id='msgsifre' class='text-danger'></span>
            <input type='text' name='sifre' id='sifre' placeholder='Yeni Şifre Giriniz' class='form-control'><br>
            <label for='email' class='form-label'>YENİ ŞİFRE DOĞRULAMA:</label>
            &nbsp;&nbsp;<span id='msgsifretkr' class='text-danger'></span>
            <input type='text' name='sifretekrar' id='sifretekrar' placeholder='Tekrar Şifre Giriniz' class='form-control'><br>
            <input type='hidden' name='kullanici-id' value='". $row_liste['id']."'>
          </div>
          </div>";
          echo "<div class='modal-footer'>
          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Kapat</button>
          <a href=' class='text-white'>
            <i class='bx bx-edit-alt me-1'></i><input type='submit' value='Kaydet' class='btn btn-warning' name='yeni-sifre'> 
          </a>
          </form>
        </div>
      </div>
    </div>
  </div>";
  $count++;
  }
        echo "</table>";
    }else{
        echo "<tr col-span>tablo yok</tr>";
    }
}

?>