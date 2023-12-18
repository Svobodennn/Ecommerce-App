<?php
_sessionSet('title', 'Kahve Dükkanı | Sipariş Bilgileri');

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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sipariş Detayı</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <i class="fas fa-globe"></i> Kahve Dükkanı
                                        <small class="float-right">Tarih: <?= (new DateTime($data['details'][0]['created_date']))->format('d.m.Y H:i') ?></small>
                                    </h4>
                                </div>
                                <!-- /.col -->
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

                                        if (isset($data['details'][0]['bonus_product'])):

                                            foreach ($data['details'][0]['bonus_product'] as $value):
                                        ?>
                                        <tr class="bg-success">
                                            <td><?= ++$count ?>-</td>
                                            <td><?= $value['product_id'] ?></td>
                                            <td><?= $value['product_title'] ?></td>
                                            <td><?= $value['product_description'] ?></td>
                                            <td>1</td>
                                            <td class="">Hediye</td>
                                        </tr>
                                        <?php
                                            endforeach;
                                            endif;
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
                                                <td style="color: red"><?= $data['details'][0]['coupon'] ? $data['details'][0]['summary'] - $data['details'][0]['total'] : 0 ?>
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

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-6">
                                    <button onclick="cancelOrder(<?= $data['details'][0]['order_id'] ?>)" class="btn btn-sm btn-danger float-left"><i
                                                class="fa fa-trash"></i> Siparişi iptal et
                                    </button>
                                </div>
                                <div class="col-6">
                                    <a href="<?= _link('user/orders/details/print/').$data['details'][0]['order_id'] ?>" rel="noopener" target="#"
                                       class="btn btn-default float-right"><i class="fas fa-print"></i> Yazdır</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <?php
//                debug($data['details']);
        ?>
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

<script>
    function cancelOrder(orderId){
        Swal.fire({
            title: "Siparişi iptal etmek istediğinizden emin misiniz?",
            text: "Bu işlem geri alınamaz!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Evet, Siparişi iptal et!",
            cancelButtonText: "Hayır"
        }).then((result) => {
            if (result.isConfirmed) {
                let order_id = orderId
                let formData = new FormData()
                formData.append('order_id', order_id)

                axios.post('<?= _link('user/orders/cancelOrder') ?>', formData)
                    .then(res => {
                        Swal.fire({
                            title: res.data.title,
                            text: res.data.text,
                            icon: res.data.status
                        })
                            .then((result) =>{
                                if (result.isConfirmed)
                                    window.location.href = res.data.redirect
                            });
                    })


            }
        });
    }
</script>

</body>
</html>
