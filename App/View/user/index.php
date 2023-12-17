<?php
_sessionSet('title', 'Kahve Dükkanı | Bilgilerim');

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
                <form id="user">
                    <input type="hidden" id="userId" value="<?= $data['user']['id'] ?>">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="userName">isim</label>
                            <input type="text" class="form-control" value="<?= $data['user']['name'] ?>" id="userName" name="userName" placeholder="Adınızı giriniz">
                        </div>
                        <div class="form-group">
                            <label for="userSurname">Soyisim</label>
                            <input type="text" class="form-control" value="<?= $data['user']['surname'] ?>" id="userSurname" name="userSurname" placeholder="Soyadınızı giriniz">
                        </div>
                        <div class="form-group">
                            <label for="userMail">E-posta</label>
                            <input type="email" class="form-control" value="<?= $data['user']['email'] ?>" id="userEmail" name="userEmail" placeholder="E-postanızı giriniz">
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Profili güncelle</button>
                    </div>
                </form>

            </div>
            <div class="card">
                <form id="changePassword">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="password">Güncel Şifre</label>
                            <input type="password" class="form-control"  id="password" name="password" placeholder="Enter your password">
                        </div>
                        <div class="form-group">
                            <label for="newPassword">Yeni şifre</label>
                            <input type="password" class="form-control"  id="newPassword" name="newPassword" placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label for="newPasswordAgain">Yeni şifreyi tekrar giriniz</label>
                            <input type="password" class="form-control"  id="newPasswordAgain" name="newPasswordAgain" placeholder="Enter new password again">
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Şifreyi güncelle</button>
                    </div>
                </form>

            </div>
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
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

<script>
    const user = document.getElementById('user')
    user.addEventListener('submit', (e) => {
        let userName = document.getElementById('userName').value
        let userSurname = document.getElementById('userSurname').value
        let userEmail = document.getElementById('userMail').value
        let userId = document.getElementById('userId').value

        let formData = new FormData();
        formData.append('userName', userName)
        formData.append('userSurname', userSurname)
        formData.append('userEmail', userEmail)
        formData.append('userId', userId)

        axios.post('<?= _link('user/edit') ?>', formData)
            .then(res => {
                if (res.data.redirect){
                    window.location.href = res.data.redirect;
                }
                Swal.fire(
                    res.data.title,
                    res.data.msg,
                    res.data.status
                )
            }).catch(err => {
            console.log(err)
        })
        e.preventDefault()
    })
    user.addEventListener('submit', (e) => {
        let userName = document.getElementById('userName').value
        let userSurname = document.getElementById('userSurname').value
        let userEmail = document.getElementById('userEmail').value

        let formData = new FormData();
        formData.append('userName', userName)
        formData.append('userSurname', userSurname)
        formData.append('userEmail', userEmail)

        axios.post('<?= _link('user/edit') ?>', formData)
            .then(res => {
                if (res.data.redirect){
                    window.location.href = res.data.redirect;
                }
                Swal.fire(
                    res.data.title,
                    res.data.msg,
                    res.data.status
                )
            }).catch(err => {
            console.log(err)
        })
        e.preventDefault()
    })

    const changePassword = document.getElementById('changePassword')
    changePassword.addEventListener('submit', (e) => {
        let password = document.getElementById('password').value
        let newPassword = document.getElementById('newPassword').value
        let newPasswordAgain = document.getElementById('newPasswordAgain').value
        let userId = document.getElementById('userId').value

        let formData = new FormData();
        formData.append('password', password)
        formData.append('newPassword', newPassword)
        formData.append('newPasswordAgain', newPasswordAgain)
        formData.append('userId', userId)

        axios.post('<?= _link('user/password') ?>', formData)
            .then(res => {
                if (res.data.redirect){
                    window.location.href = res.data.redirect;
                }
                Swal.fire(
                    res.data.title,
                    res.data.msg,
                    res.data.status
                )
            }).catch(err => {
            console.log(err)
        })
        e.preventDefault()
    })

</script>

</body>
</html>
