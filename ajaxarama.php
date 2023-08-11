<?php 
include("admin/includes/database.php");

// if(isset($_GET['aranan'])){
    $aranan = $_GET['aranan'];
    $aranan = str_replace(' ', '', $aranan);
    $sql_ara = "select ad,
    soyad,
    telefon,
    dahili,
    email,
    unvan_adi,
    gorev_adi,
    birim_adi
    from 
    rb_rehber.tbl_kisi k 
    left join rb_rehber.tbl_birim b on k.birim_id = b.id 
    left join rb_rehber.tbl_gorev g on k.gorev_id = g.id 
    left join rb_rehber.tbl_unvan u on k.unvan_id = u.id 
    left join rb_rehber.tbl_telefon t on t.kisi_id = k.id 
    WHERE ad ilike '%$aranan%' or soyad ilike '%$aranan%' or telefon ilike '%$aranan%' or dahili ilike '%$aranan%' or birim_adi ilike '%$aranan%'
    and k.aktif_mi =true and k.silindi_mi =false and t.gorunsun_mu =true and t.silindi_mi =false
    order by k.ad, k.soyad, b.birim_adi
    limit 8 ";
    $sonuc_ara = pg_query($db,$sql_ara);

    if(pg_num_rows($sonuc_ara)>0){
        while($row_ara=pg_fetch_assoc($sonuc_ara)){
        echo "<tr><td>".$row_ara['unvan_adi']."<strong> ".$row_ara['ad']." ".$row_ara['soyad'] ."</strong> ".$row_ara['gorev_adi']."</td>";
        echo "<td>".$row_ara['birim_adi']."</td>";  
        echo "<td>".$row_ara['telefon']."</td>";  
        echo "<td>".$row_ara['dahili']."</td>";  
        echo "<td>".$row_ara['email']."</td></tr>";  
  }
    }else{
        echo "<tr col-span>tablo yok</tr>";
    }
// }


?>