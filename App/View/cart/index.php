<?php
_sessionSet('title', 'Sepet');
echo $data['header'];
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
                        <h1 class="m-0"><?= _session('title') ?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"><?= _session('title') ?></a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="">
                    <div class="row">
                        <div class="col-md-8 d-flex flex-wrap">

                            <?php
                            if ($data['cartList'] && isset($data['cartList'])):
                                $totalPrice = 0;
                                foreach ($data['cartList'] as $key => $value):

                                    $totalPrice += $value['total_price'];
                                    ?>
                                    <div id="card_<?= $value['id'] ?>" class="card mb-3" style="max-width: 540px;">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="https://picsum.photos/id/63/400/400"
                                                     class="img-fluid rounded-start" alt="...">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= $value['title'] ?></h5>
                                                    <p class="card-text"><?= $value['description'] ?></p>
                                                    <div class="quantity-controls d-flex">
                                                        <button class="quantity-decrease btn btn-warning"
                                                                onclick="decreaseQuantity(<?= $value['id'] ?>)">-
                                                        </button>
                                                        <p class="card-text quantity"><span
                                                                    id="product_<?= $value['id'] ?>"><?= $value['quantity'] ?></span>
                                                            Adet </p>
                                                        <input id="total_stock_<?= $value['id'] ?>" type="hidden"
                                                               value="<?= $value['total_stock'] ?>">
                                                        <button class="quantity-increase btn btn-success"
                                                                onclick="increaseQuantity(<?= $value['id'] ?>)">+
                                                        </button>
                                                    </div>
                                                    <p class="card-text">Birim fiyatı: <span
                                                                id="price_<?= $value['id'] ?>"><?= $value['price'] ?></span>
                                                        ₺</p>
                                                    <p class="card-text">Toplam fiyat: <span
                                                                id="total_price_<?= $value['id'] ?>"><?= $value['total_price'] ?></span>
                                                        ₺</p>
                                                    <button title="Ürünü Sil"
                                                            onclick="removeProduct(<?= $value['id'] ?>)"
                                                            class="float-right btn btn-danger"><i
                                                                class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                endforeach; endif;
                            ?>
                        </div>
                        <div class="col-md-4">
                            <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvas"
                                 aria-labelledby="offcanvasLabel">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasLabel">Sipariş Özeti</h5>
                                </div>
                                <div class="offcanvas-body">
                                    <div class="row">
                                        <p>Sepet tutarı: <span id="total_price"><?= $totalPrice ?? '0' ?></span>₺</p>
                                        <p>Kargo Ücreti(500₺ Üzeri Bedava! ): <span id="cargo">54.99</span>₺</p>
                                    </div>
                                    <div id="coupon_row" class="row">
                                        <label for="">Kupon</label>
                                        <input id="coupon" type="text" placeholder="Kupon giriniz">
                                        <button class="btn btn-primary" id="couponButton" type="button"
                                                onclick="checkCoupon()">Gir
                                        </button>
                                    </div>
                                    <div id="summary_row" class="row">
                                        <p>Toplam ödenecek tutar: <span id="summary"></span></p>
                                    </div>
                                    <div class="row">
                                        <button onclick="finalizeOrder()" class="btn btn-primary mt-2"> Siparişi
                                            Tamamla
                                        </button>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Ürünler-->


                    <?php
                    //                    debug($data['cartList']);
                    ?>
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

        $(document).ready(function () {
            checkCargo()
        });

        function finalizeOrder() {

            $.ajax({
                type: 'POST',
                url: '<?=_link('finalizeOrder')?>',
                success: function (response) {
                    let res = JSON.parse(response)

                    if (res.status === "success") {

                        let timerInterval;
                        Swal.fire({
                            title: res.title,
                            html: res.msg,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: () => {
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                    timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href = res.redirect
                            }
                        });


                    } else {
                        toastr[res.status](res.title, res.msg)
                        if (res.redirect) {
                            setTimeout(() => {
                                window.location.href = res.redirect
                            }, 3000)
                        }
                    }
                },
                error: function (err) {
                    toastr[err.status](res.title, res.msg)
                }
            })
        }

        function checkCargo() {
            let total_price = parseFloat($('#total_price').text())

            let cargo = $('#cargo')
            let cargo_price = 0

            if (total_price > 500) {
                cargo.css('text-decoration', 'line-through');
                cargo.css('color', 'red');
                cargo_price = parseFloat(cargo.text())

                summary(total_price)
            } else if (total_price <= 0) {
                cargo.text('0')
                summary(total_price)

            } else {
                cargo.css('text-decoration', 'none');
                cargo_price = parseFloat(cargo.text())
                summary(total_price, cargo_price)

            }
        }

        function summary(totalPrice, cargo_price = 0, discount = false) {
            let summary_element = $('#summary')
            var total_price = parseFloat(totalPrice)

            if (!discount) {
                summary_element.text((total_price + cargo_price).toFixed(2))
            } else {
                if (total_price > 3000) {
                    let percantage = 25;
                    let discountAmount = parseFloat((total_price * percantage) / 100)
                    let discountedTotal = parseFloat(total_price - discountAmount)
                    summary_element.text(discountedTotal.toFixed(2))
                    return {
                        'description': '3000₺ Üzeri 1 Kilo kahve hediye ve ',
                        'discount_amount': discountAmount,
                        'percantege': percantage
                    }

                } else if (total_price > 2000) {
                    let percantage = 20;
                    let discountAmount = parseFloat((total_price * percantage) / 100)
                    let discountedTotal = parseFloat(total_price - discountAmount)

                    summary_element.text(discountedTotal.toFixed(2))
                    return {'description': '2000₺ Üzeri ', 'discount_amount': discountAmount, 'percantege': percantage}

                } else if (total_price > 1500) {
                    let percantage = 15;
                    let discountAmount = parseFloat((total_price * percantage) / 100)
                    let discountedTotal = parseFloat(total_price - discountAmount)

                    summary_element.text(discountedTotal.toFixed(2))
                    return {'description': '1500₺ Üzeri ', 'discount_amount': discountAmount, 'percantege': percantage}

                } else if (total_price > 1000) {

                    let percantage = 10;
                    let discountAmount = parseFloat((total_price * percantage) / 100)
                    let discountedTotal = parseFloat(total_price - discountAmount)

                    summary_element.text(discountedTotal.toFixed(2))

                    return {'description': '1000₺ Üzeri ', 'discount_amount': discountAmount, 'percantege': percantage}
                }
            }


        }

        function checkCoupon() {
            let summary_row = $('#summary_row')
            let couponElement = $('#coupon')
            let couponButton = $('#couponButton')
            let coupon = $('#coupon').val()
            let total_price = $('#total_price').text()
            let products = $('.card')

            if (total_price > 0 && products.length > 0) {
                $.ajax({
                    type: 'POST',
                    url: '<?=_link('checkCoupon')?>',
                    data: {coupon: coupon},
                    success: function (response) {
                        var res = JSON.parse(response)

                        if (res.status === "success") {

                            couponButton.prop('disabled', true);
                            couponElement.prop('disabled', true);
                            toastr[res.status](res.title, res.msg)
                            let discount = summary(total_price, 0, true)


                            var newItemP = $('<p>').text(discount.description + '%' + discount.percantege + ' İndirim! ').append($('<span>').css({
                                'color': 'red',
                                'text-decoration': 'line-through'
                            }).text(discount.discount_amount));
                            summary_row.prepend(newItemP)

                        } else {
                            toastr[res.status](res.title, res.msg)

                        }
                    },
                    error: function (err) {
                        toastr[err.status](res.title, res.msg)

                    }

                })
            }
        }

        function removeProduct(product_id) {
            var id = product_id;
            var card = $(`#card_${product_id}`)
            var product_total_price = parseFloat($(`#total_price_${product_id}`).text())
            var total_price = $('#total_price')


            let formData = new FormData();
            formData.append('id', id);

            axios.post('<?= 'removeFromCart' ?>', formData)
                .then(res => {

                    toastr[res.data.status](res.data.title, res.data.msg)

                    if (res.data.status == 'success') {
                        total_price.text((parseFloat(total_price.text()) - product_total_price).toFixed(2));
                        checkCargo()
                        card.remove();
                    }
                }).catch(err => {
                console.log(err)
            })
        }

        function decreaseQuantity(productId) {
            var quantityElement = $(`#product_${productId}`);
            var currentQuantity = parseInt(quantityElement.text());
            var price_element = $(`#price_${productId}`);
            var total_price_element = $(`#total_price_${productId}`);
            var stock = $(`#total_stock_${productId}`).val()
            var total_price = $('#total_price')


            if (currentQuantity > 1 && currentQuantity <= stock) {

                $.ajax({
                    type: 'POST',
                    url: '<?=_link('decreaseFromCart')?>',
                    data: {id: productId},
                    success: function (response) {
                        let res = JSON.parse(response)

                        if (res.status === "success") {
                            // ürün azaltıldı
                        } else {
                            console.error('Hata:', res.status);
                            // hata mesajı
                        }
                    },
                    error: function (err) {
                        console.error('Hata:', err);
                        // Hata mesajı
                    }
                })

                var unitPrice = parseFloat(price_element.text())

                // sayıyı azalt
                quantityElement.text(currentQuantity - 1);

                var updatedTotalPrice = (unitPrice * (currentQuantity - 1)).toFixed(2);
                total_price_element.text(updatedTotalPrice);

                total_price.text((parseFloat(total_price.text()) - unitPrice).toFixed(2))
                checkCargo()
            }
        }

        function increaseQuantity(productId) {
            var quantityElement = $(`#product_${productId}`);
            var priceElement = $(`#price_${productId}`);
            var totalPriceElement = $(`#total_price_${productId}`);
            var stock = $(`#total_stock_${productId}`).val()
            var total_price = $('#total_price')


            var currentQuantity = parseInt(quantityElement.text());
            var unitPrice = parseFloat(priceElement.text());


            if (currentQuantity < stock) {

                $.ajax({
                    type: 'POST',
                    url: '<?=_link('addToCart')?>',
                    data: {id: productId},
                    success: function (response) {
                        let res = JSON.parse(response)

                        if (res.status === "success") {
                            // Ürün arttırıldı
                        } else {
                            console.error('Error removing product:', res.status);
                            // Hata mesajı
                        }
                    },
                    error: function (err) {
                        console.error('Error removing product:', err);
                        // Hata mesajı
                    }
                })

                // sayıyı arttır
                quantityElement.text(currentQuantity + 1);

                // Update total price
                var updatedTotalPrice = (unitPrice * (currentQuantity + 1)).toFixed(2);
                totalPriceElement.text(updatedTotalPrice);

                total_price.text((parseFloat(total_price.text()) + unitPrice).toFixed(2));
                checkCargo()

            }
        }


    </script>
</body>
</html>
