<?php
_sessionSet('title', 'Kahve Dükkanı | Siparişlerim');

?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= _session('title') ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= asset('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('css/adminlte.min.css') ?>">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?= $data['navbar'] ?>
    <?= $data['sidebar'] ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">

            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Son Siparişler</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                            <tr>
                                <th>Sipariş ID</th>
                                <th>Durumu</th>
                                <th>Ürünler</th>
                                <th>Sipariş Tarihi</th>
                                <th>Sipariş Toplamı</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($data['orders'] as $order):
                            ?>
                            <tr>
                                <td><a href="<?= _link('user/orders/details/').$order['order_id'] ?>"><?= $order['order_title'] ?></a></td>
                                <td><span class="badge badge-<?= $order['status'] == 'a' ? 'success' : 'danger'?>"><?= $order['status'] == 'a' ? 'Aktif' : 'Pasif'?></span></td>
                                <td>
                                    <div class="sparkbar" data-color="#00a65a" data-height="20"><?= $order['product_count'] ?> Ürün</div>
                                </td>
                                <td><?= (new DateTime($order['created_date']))->format('d') . ' ' // gün
                                    . _date((new DateTime($order['created_date']))->format('F')).' ' // türkçe ay
                                    .(new DateTime($order['created_date']))->format('Y H:i') // yıl ve saat ?></td>
                                <td><?= $order['summary'] ?>₺</td>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
            </div>

        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <?php
//                    debug($data['orders']);
                    ?>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Anything you want
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?= asset('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= asset('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= asset('js/adminlte.min.js') ?>"></script>

<!--axios-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"
        integrity="sha512-WrdC3CE9vf1nBf58JHepuWT4x24uTacky9fuzw2g/3L9JkihgwZ6Cfv+JGTtNyosOhEmttMtEZ6H3qJWfI7gIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!--sweetalert-->
<script src="<?= asset('plugins/sweetalert2/sweetalert2.all.js') ?>"></script>


</body>
</html>
