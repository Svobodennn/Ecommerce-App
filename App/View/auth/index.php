<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Starter</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= asset('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('css/adminlte.min.css') ?>">
    <!--    Sweetalert-->
    <link rel="stylesheet" href="<?= asset('plugins/sweetalert2/sweetalert2.css') ?>">
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0)"><b>Kahve</b>Dükkanı</a>
    </div>
    <!-- /.login-logo -->
    <div id="loginCard" class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Oturum açmak için giriş yapınız</p>

            <form action="" id="login" method="post">
                <div class="input-group mb-3">
                    <input type="email" id="emailLogin" name="email" class="form-control" placeholder="Email"
                           value="melih@example.com">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="passwordLogin" name="password" class="form-control" placeholder="Şifre"
                           value="123123">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="button" onclick="login(e)" id="loginBtn" class="btn btn-primary btn-block">Giriş
                            yap
                        </button>
                    </div>

                    <!-- /.col -->
                </div>
                <hr>
                <div class="text-center">
                    <a onclick="showRegister()" href="#">Kayıt Ol</a>
                </div>
            </form>

        </div>
        <!-- /.login-card-body -->
    </div>

</div>

<div id="registerCard" class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-12">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Hesap oluştur</h1>
                    </div>
                    <form action="" id="register" method="post" class="user">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control form-control-user" id="name" name="name"
                                       placeholder="Ad">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control form-control-user" id="surname" name="surname"
                                       placeholder="Soyad">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-user" id="emailRegister"
                                   name="emailRegister"
                                   placeholder="Email">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user"
                                       id="passwordRegister" name="passwordRegister" placeholder="Şifre">
                            </div>
                            <div class="col-sm-6">
                                <input type="password" class="form-control form-control-user"
                                       id="passwordRepeat" name="passwordRepeat" placeholder="Şifre Tekrar">
                            </div>
                        </div>
                        <button type="button" onclick="register()" id="registerBtn"
                                class="btn btn-primary btn-user btn-block">
                            Kayıt Ol
                        </button>

                    </form>
                    <hr>
                    <div class="text-center">
                        <a onclick="showLogin()" class="small" href="#">Hesabın var mı? Giriş yap!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    $(document).ready(function () {
        $("#registerCard").hide();
    });

    function showRegister() {
        $("#loginCard").hide();
        $("#registerCard").show();
    }

    function showLogin() {
        $("#loginCard").show();
        $("#registerCard").hide();
    }


    function login() {

        let email = document.getElementById('emailLogin').value
        let password = document.getElementById('passwordLogin').value

        let formData = new FormData();
        formData.append('email', email)
        formData.append('password', password)

        axios.post('', formData)
            .then(res => {
                if (res.data.redirect) {
                    window.location.href = res.data.redirect;
                } else {
                    Swal.fire(
                        res.data.title,
                        res.data.msg,
                        res.data.status
                    )
                }
            }).catch(err => {
            console.log(err)
        })
    }

    function register() {
        let name = document.getElementById('name').value
        let surname = document.getElementById('surname').value
        let email = document.getElementById('emailRegister').value
        let password = document.getElementById('passwordRegister').value
        let passwordRepeat = document.getElementById('passwordRepeat').value

        let formData = new FormData();
        formData.append('name', name)
        formData.append('surname', surname)
        formData.append('emailRegister', email)
        formData.append('passwordRegister', password)
        formData.append('passwordRepeat', passwordRepeat)

        axios.post('<?= _link('register') ?>', formData)
            .then(res => {
                if (res.data.redirect) {
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
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = res.redirect
                        }
                    });


                } else {
                    Swal.fire(
                        res.data.title,
                        res.data.msg,
                        res.data.status
                    )
                })
            }).catch(err => {
            console.log(err)
        })
    }

</script>


</body>
</html>
