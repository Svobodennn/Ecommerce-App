<?php
_sessionSet('title', 'Kahve Dükkanı | Sipariş Özetini Yazdır');

?>
<!DOCTYPE html>
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
<body>
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <section class="invoice">
        <!-- Content Header (Page header) -->
        <div class="row">
            <div class="col-12">
                <!-- title row -->
                <div class="page-header">
                    <h2>
                        <i class="fas fa-globe"></i> Kahve Dükkanı
                        <small class="float-right">Dekont tarihi: <?= date('d-m-Y H:i') ?></small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>Kahve Dükkanı.</strong><br>
                    Adres satırı 1<br>
                    Adres satırı 2<br>
                    Adres satırı 3<br>
                    Email: kahvedunyasi@gmail.com
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong><?= _session('name') . ' ' . _session('surname') ?></strong><br>
                    <?= _session('email') ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Sipariş ID:</b> <?= $data['details'][0]['order_title'] ?><br>
                <b>Sipariş Tarihi: </b> <?= (new DateTime($data['details'][0]['created_date']))->format('d.m.Y H:i') ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ürün Id</th>
                        <th>Ürün</th>
                        <th>Açıklama</th>
                        <th>Adet</th>
                        <th>Adet</th>
                        <th>Toplam</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach ($data['details'][0]['products'] as $key => $value):
                        ?>
                        <tr>
                            <td><?= ++$count ?>-</td>
                            <td><?= $value['product_id'] ?></td>
                            <td><?= $value['product_title'] ?></td>
                            <td><?= $value['product_description'] ?></td>
                            <td><?= $value['quantity'] ?></td>
                            <td><?= $value['product_total'] ?>₺</td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">

            </div>
            <!-- /.col -->
            <div class="col-6">

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Ara Toplam:</th>
                            <td><?= $data['details'][0]['total'] ?>₺</td>
                        </tr>
                        <tr>
                            <th>Kargo ücreti:</th>
                            <td><?= $data['details'][0]['total'] > 500 ? "Ücretsiz" : "54.99₺" ?></td>
                        </tr>
                        <tr>
                            <th>Toplam indirim tutarı (Kupon):</th>
                            <td style="color: red"><?= $data['details'][0]['summary'] - $data['details'][0]['total'] ?>
                                ₺
                            </td>
                        </tr>
                        <tr>
                            <th>Toplam tutar:</th>
                            <td><?= $data['details'][0]['summary'] ?>₺</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
</div>


<!-- /.invoice -->
<!-- /.content -->

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
<script>
    $(document).ready(function() {
        window.print();
    });
</script>

</body>
</html>
