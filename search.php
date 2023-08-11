<?php 
include("admin/includes/database.php");

if(isset($_GET['arama'])){
  $araniyor = $_GET['arama'];
}

if(isset($_GET['arama'])){
  $aranan = $_GET['arama'];
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
}


if(isset($_GET['birim'])){
  $birim_id = $_GET['birim'];
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
  where k.birim_id= $birim_id
  and k.aktif_mi =true and k.silindi_mi =false and t.gorunsun_mu =true and t.silindi_mi =false
  order by k.ad, k.soyad, b.birim_adi
  limit 8 ";
  $sonuc_ara = pg_query($db,$sql_ara);

  
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="img/University_logo.png">
    <title>Telefon Rehberi Arama</title>

    <!-- Global stylesheets -->
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/devicons/css/devicons.min.css" rel="stylesheet">
    <link href="css/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- <link href="admin/assets/vendor/css/core.css" rel="stylesheet"> -->
    <script src="js/jquery/jquery.min.js"></script>
</head>
<script type="text/javascript">
  
  $(document).ready(function() {
    $('#arama').on('input', function() {
        var aranan = $(this).val();
                console.log(aranan);

        $.ajax({
            url: 'ajaxarama.php',
            method: 'GET',
            data: { aranan: aranan },
            success: function(response) {
                $('#aramaSonuclari').html(response);
            }
        });
    });
});


</script>

<body id="page-top">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
      <a class="navbar-brand js-scroll-trigger" href="https://www.ahievran.edu.tr/">
        <span class="d-block d-lg-none  mx-0 px-0 text-light" style="font-family: 'Saira Extra Condensed', serif; font-size:x-large;">
          <img src="img/University_logo.png" alt="" class="mr-3" height="35">
          <span class="text-dark">KAEÜ &nbsp;</span>Telefon Rehberi</span>
        <span class="d-none d-lg-block">
          <img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="img/University_logo.png" alt="" title="www.ahievran.edu.tr">
        </span>
      </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="index.php#about">ARAMA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="index.php#experience">AKADEMİK BİRİMLERİ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="index.php#portfolio">İDARİ BİRİMLERİ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="index.php#contact">İLETİŞİM</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid p-0">

    <!--====================================================
                        ARAMA
    ======================================================-->
      <section class=" p-lg-3 " id="about-bg">
          <div class="my-auto" style="text-align: center;">
              <!-- <img src="img/logo-s.png" class="img-fluid mb-3" alt=""> -->
              <h3 class="mb-2 pt-3">KIRŞEHİR AHİ EVRAN ÜNİVERSİTESİ
                <span class="text-primary">TELEFON REHBERİ</span>
              </h3>
              <!-- <div class="subheading mb-5">THE NEXT BIG IDEA IS WAITING FOR ITS NEXT BIG CHANGER WITH 
                  <a href="#">THEMSBIT</a>
              </div> -->
              <div class="row m-0 justify-content-center d-flex">
                <form class="m-0 ml-lg-2" style="width:100%;" method="get" action="">
                  <fieldset class="input-group-btn">
                    <div class="col-lg-10 ml-3 align-self-center">
                      <input type="text" class="form-control border-right btn-general btn-transparent" 
                      placeholder="Ad Soyad" style="background-color: #ffffff71; text-transform: none; "  
                      name="arama" id="arama" value="<?php if(isset($_GET['arama'])){echo $_GET['arama'];} ?>">
                    </div>
                    <div class="col-lg-2 col-form-label ">
                      <input type="submit" class="btn btn-primary d-inline-block btn-general btn-white" value="Ara" >
                    </div>
                  </fieldset>
                </form>
              </div >
        </div>
      </section>
      <section class="p-lg-3" style="background-color: aliceblue;">
        <div class="mr-4">
          <h3 class="text-center mt-3">
            <span class="text-primary" style=" text-transform: none;">"<?php if(isset($_GET['birim'])){echo "";}elseif(isset($_GET['arama'])){ echo $_GET['arama'];} ?>" </span>
            arama sonucu :</h3>
          <div class="mr-md-4 pr-1">
            <table class="table table-hover m-lg-5 m-md-2 table-border text-center" style="width: 95%;">
              <thead class="text-primary">
                <tr class="">
                  <th>Adı Soyadı</th>
                  <th>Birim</th>
                  <th>Telefon</th>
                  <th>Dahili</th>
                  <th>E-posta</th>
                </tr>
              </thead>
              <tbody class="table-group-divider text-dark"  id="aramaSonuclari">
                <?php
                  if(isset($_GET['birim'])||$aranan){
                  if(pg_num_rows($sonuc_ara)>0){
                    echo $sql_ara;
                    while($row_ara=pg_fetch_assoc($sonuc_ara)){
                    echo "<tr><td>".$row_ara['unvan_adi']."<strong> ".$row_ara['ad']." ".$row_ara['soyad'] ."</strong> ".$row_ara['gorev_adi']."</td>";
                    echo "<td>".$row_ara['birim_adi']."</td>";  
                    echo "<td>".$row_ara['telefon']."</td>";  
                    echo "<td>".$row_ara['dahili']."</td>";  
                    echo "<td>".$row_ara['email']."</td></tr>";  
              }
                }else{
                    echo "<tr col-span><td>Aradiginiz birim yok data</td></tr>";
                } 
              }
                ?>
                
              </tbody>
            </table>
          </div>
        </div>

      </section>






      
    <!-- Global javascript -->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/counter/jquery.waypoints.min.js"></script>
    <script src="js/counter/jquery.counterup.min.js"></script>
    <script src="js/custom.js"></script>
    <script>
        $(document).ready(function(){

        $(".filter-b").click(function(){
            var value = $(this).attr('data-filter');
            if(value == "all")
            { 
                $('.filter').show('1000');
            }
            else
            { 
                $(".filter").not('.'+value).hide('3000');
                $('.filter').filter('.'+value).show('3000');
            }
        });
        
        if ($(".filter-b").removeClass("active")) {
          $(this).removeClass("active");
        }
        $(this).addClass("active");
        });

        // SKILLS
        $(function () {
            $('.counter').counterUp({
                delay: 10,
                time: 2000
            });

        });
    </script> 
</body>

</html>
