<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge"> ÜRÜN SAYISI </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
<!--                        ÜRÜN FOTOSU EKLE -->
                        <img src="https://picsum.photos/id/63/100/100" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                ÜRÜN ADI
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">ÜRÜN AÇIKLAMA</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> ÜRÜN ADEDİ</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>

                <a href="#" class="dropdown-item dropdown-footer">SİPARİŞİ GÖRÜNTÜLE</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= _link('logout') ?>">
                <i class="fa fa-sign-out-alt"></i> Log Out
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

