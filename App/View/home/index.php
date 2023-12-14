<?php
_sessionSet('title', 'Ecommerce App');
echo $data['header'];

//debug($data['products']);
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?= $data['navbar'] ?>
    <?= $data['sidebar'] ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="d-flex flex flex-wrap">
                    <!--Ürünler-->
                    <?php foreach ($data['products'] as $product): ?>
                    <div style="width: 20rem" class="card">
                        <img class="card-img-top img-thumbnail img-fluid rounded text-center" src="https://picsum.photos/id/63/400/400" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title badge badge-danger "><?= $product['product_title'] ?></h4>
                            <p class="card-text"><?= $product['product_description'] ?></p>
                            <p class="card-text"><?= $product['roast_level'] ?></p>
                            <p class="card-text"><?= $product['origin'] ?></p>
                            <p class="card-text"><?= $product['flavor_notes'] ?></p>
                            <p class="card-text"><?= $product['product_price'] ?>₺</p>
                            <button onclick="sepeteEkle(<?= $product['id'] ?>)"  class="btn btn-danger btn-block sepete-ekle">Sepete ekle</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
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
    <?= $data['footer'] ?>

<script>
    function sepeteEkle(product_id){
        let id = product_id;

        let formData = new FormData();
        formData.append('id', id);

        axios.post('<?= _link('addToCart') ?>', formData)
            .then(res => {
                toastr[res.data.status](res.data.title, res.data.msg)
            }).catch(err => {
            console.log(err)
        })
    }
</script>
</body>
</html>
