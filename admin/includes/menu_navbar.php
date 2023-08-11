<?php 
$sql_kullanici="select * ,
y.id as yetki_id
from rb_rehber.tbl_kullanici k
left join  rb_rehber.tbl_yetki y on k.yetki_id  = y.id
where k.kullanici_adi = '".$_SESSION['kullanici']."' and k.yetki_id =". $_SESSION['yetki'];
$sonuc_kullanici = pg_query($db,$sql_kullanici);
$row_kullanici = pg_fetch_assoc($sonuc_kullanici);
?>
<!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.php" class="app-brand-link">
              <!-- <span class="app-brand-logo demo"><img src="../../img/University_logo.png"></span> -->
            
              <h5 class="app-brand-text  menu-text fw-bolder ms-2 text-capitalize">Ahi Evran Rehber <br>Yönetim Paneli</h5>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item <?php if(basename($_SERVER['PHP_SELF'])=='index.php'){echo 'active';} ?>">
              <a href="index.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Anasayfa</div>
              </a>
            </li>
            <li class="menu-item <?php if(basename($_SERVER['PHP_SELF'])=='kayitlar.php'){echo 'active';} ?>">
              <a href="kayitlar.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements">Kayıt Ekle/Düzenle/Sil</div>
              </a>
            </li>
            <li class="menu-item <?php if(basename($_SERVER['PHP_SELF'])=='telefonlar.php'){echo 'active';} ?> ">
              <a href="telefonlar.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-phone"></i>
                <div data-i18n="Form Elements">Telefon Ekle/Düzenle/Sil</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-add-to-queue"></i>
                <div data-i18n="Layouts">Ekle</div>
              </a>

              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="birimler.php" class="menu-link">
                    <div data-i18n="Without menu">Birim Ekle</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="gorevler.php" class="menu-link">
                    <div data-i18n="Without navbar">Görev Ekle</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="unvanlar.php" class="menu-link">
                    <div data-i18n="Container">Unvan Ekle</div>
                  </a>
                </li>
              </ul>
              <li class="menu-item <?php if(basename($_SERVER['PHP_SELF'])=='kullanicilar.php'){echo 'active';} ?> ">
              <a href="kullanicilar.php?arabtn=1" class="menu-link">
              <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                <div data-i18n="Form Elements">Yetkili Kullanıcı Ekle/Sil</div>
              </a>
              </li>
            


            <!-- Layouts -->
            
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <!-- <i class="bx bx-search fs-4 lh-0"></i> -->
                  <!-- <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder=""
                    aria-label="Search..."
                  /> -->
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <?php echo $_SESSION['kullanici'];?> &nbsp;

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="assets/img/avatars/user1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="assets/img/avatars/user1.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?php echo $_SESSION['kullanici'];?></span>
                            <small class="text-muted"><?php echo $row_kullanici['yetki'] ?></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="kullanicilar.php?kisi_id=<?php echo $row_kullanici['id']; ?>">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">Hesabım</span>
                      </a>
                    </li>
                    <!-- <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li> -->
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#cikisModal">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Çıkış Yap</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->


          
<!-- Modal -->
<div class="modal fade" id="cikisModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog"><!--modal-dialog-centered-->
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Çıkış yapmak istiyor musunuz?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- <div class="modal-body">

      </div> -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hayır</button>
        <form action="cikis.php" method="post">
        <button type="submit" class="btn btn-primary" name="cikis-btn">Evet</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  var url = location.href;

$('.menu-sub').each(function() {
    var $dropdownmenu = $(this);
    $(this).find('li').each(function() {
        if( $(this).find('a').attr('href')== url ) {
            console.log( $dropdownmenu ); // this is your dropdown menu which you want to display
            console.log($($dropdownmenu).parents('li')); // this is the parent list item of the dropdown menu. Add collapse class or whatever that collapses its child list
        }
    });
});
</script>

<?php

?>