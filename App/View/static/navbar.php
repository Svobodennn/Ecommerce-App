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

        <?php
            if (_session('title') != 'Sepet'):
        ?>
                <li  class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-shopping-basket"></i>
                        <?php
                        if (isset($cartList)){
                            $count = 0;
                            foreach ($cartList as $product){
                                $count += $product['quantity'];
                            }
                        }
                        ?>

                        <span id="urunSayisi" class="badge badge-danger navbar-badge"> <?= @$count ?>  </span>

                    </a>
                    <div id="cartContainer" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <?php if (isset($cartList)): foreach ($cartList as $product): ?>
                            <a href="#" class="dropdown-item">
                                <!-- Message Start -->
                                <div class="media">
                                    <!--                        ÜRÜN FOTOSU EKLE -->
                                    <img src="https://picsum.photos/id/63/100/100" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title">
                                            <?= $product['title'] ?>
                                            <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                        </h3>
                                        <p class="text-sm"><?= $product['description'] ?></p>
                                        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> <?= $product['quantity'] ?></p>
                                    </div>
                                </div>
                                <!-- Message End -->
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endforeach; endif;  ?>

                        <?php
                        if (isset($cartList)):
                            ?>
                            <a href="<?= _link('cart') ?>" class="dropdown-item dropdown-footer">SİPARİŞİ GÖRÜNTÜLE</a>
                        <?php
                        else:
                            ?>
                            <a href="#" class="dropdown-item dropdown-footer bg-danger">Sepette Hiç ürün yok.</a>
                        <?php
                        endif;
                        ?>
                    </div>
                </li>

            <?php
            endif;
        ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= _link('logout') ?>">
                <i class="fa fa-sign-out-alt"></i> Çıkış Yap
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<script>

</script>