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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= asset('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('plugins/toastr/toastr.min.css') ?>">

    <!-- jQuery -->
    <script src="<?= asset('plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= asset('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= asset('js/adminlte.min.js') ?>"></script>

    <script src="<?= asset('js/adminlte.min.js') ?>"></script>

    <!--axios-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"
            integrity="sha512-WrdC3CE9vf1nBf58JHepuWT4x24uTacky9fuzw2g/3L9JkihgwZ6Cfv+JGTtNyosOhEmttMtEZ6H3qJWfI7gIQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--sweetalert-->
    <script src="<?= asset('plugins/sweetalert2/sweetalert2.all.js') ?>"></script>

    <!-- toastr-->
    <script src="<?= asset('plugins/toastr/toastr.min.js') ?>"></script>
</head>