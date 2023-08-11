<?php

$host="localhost";
$db="postgres";
$hesap="postgres";
$sifre="123456";
        $db = pg_connect("host=$host port=5432 dbname=$db user=$hesap password=$sifre");


if (isset($_POST['birim-turu']) && $_POST['birim-turu']) {
  $tur = $_POST['birim-turu'];
  $sql = "SELECT id AS birim_id, birim_adi FROM rb_rehber.tbl_birim WHERE birim_turu = '$tur' AND aktif_mi='true' ORDER BY birim_adi";    
  $sonuc = pg_query($db,$sql);
      if(pg_num_rows($sonuc)>0){
          $createSelect="";
          while($row=pg_fetch_assoc($sonuc)){
          $createSelect .= '<option value="';
          $createSelect .= $row['birim_id'] ;
          $createSelect .= '">';
          $createSelect .=  $row['birim_adi'].'</option>';
          }
          
      }


  
  echo json_encode(array($createSelect));
} else {
  echo json_encode(array('success' => 0));
}







// header("Location: kayitlar.php");
// echo 'test';


// echo $_GET["birim_turu2"];
// if(isset($_GET['birim_turu2'])){
//     $birim_t = $_GET["birim_turu2"];
//     $sql = "SELECT id, birim_adi FROM rb_rehber.tbl_birim WHERE birim_turu = $birim_t AND aktif_mi='true' ORDER BY birim_adi";
//     $sonuc = pg_query($db,$sql);
//     echo "111111";
//     if(pg_num_rows($sonuc)>0){
//     $createSelect = '<select name="birim" class="select2 form-select" >';

//         // echo '<option value="">Birim SEciniz</option>';
//         foreach($row=pg_fetch_assoc($sonuc) as $birim){
//             // echo '<option value="'.$row['id'].'">'.$row['birim_adi'].'</option>';
//         $createSelect .= '<option value="';
//         $createSelect .= $row['id'] ;
//         $createSelect .= '">';
//         $createSelect .=  $row['birim_adi'].'</option>';
//         }
//         $createSelect = '</select>';

//     }else{
//         echo '<option value="">Birim yok</option>';
//     }
// }




?>