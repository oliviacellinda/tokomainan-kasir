<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1, user-scalable=no">
	<title>Login</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/dist/css/AdminLTE.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/Source_Sans_Pro/font.css');?>">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- Judul -->
        <div class="login-logo">
            <p>Toko Mainan <small>Kasir</small></p>
        </div>

        <!-- Kotak Login -->
        <div class="login-box-body">
            <!-- Subjudul -->
            <p class="login-box-msg">Sign in untuk mulai melakukan pengaturan.</p>

            <form autocomplete="off">
                <div class="form-group has-feedback" id="username">
                    <input type="text" class="form-control" name="id_kasir" placeholder="Username" required>
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback" id="password">
                    <input type="password" class="form-control" name="password_kasir" placeholder="Password" required>
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                <button id="btnLogin" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </form>
        </div> <!-- End login-box-body -->
    </div> <!-- End login-box -->

    <script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/AdminLTE-2.4.2/dist/js/adminlte.min.js');?>"></script>

    <script>
    $(document).ready(function() {
        // Arahkan kursor ke input username
        $('input[name="id_kasir"]').focus();

        $('input[name="id_kasir"]').keypress(function(event) {
            // Hapus tanda error pada input username saat mengetik
            // Digunakan setelah gagal login dan user mengetik ulang username
            $('#username').removeClass('has-error');

            // Arahkan kursor ke input password setelah menekan Enter
            if(event.keyCode === 13) {
                event.preventDefault();
                $('input[name="password_kasir"]').focus();
            }
        });

        $('input[name="password_kasir"]').keypress(function(event) {
            $('#password').removeClass('has-error');
        });
        
        $('form').submit(function(event) {
            event.preventDefault();

            // Ubah tombol Login untuk menampilkan pesan loading pada user
            $('#btnLogin').html('<i class="fa fa-refresh fa-spin"></i>');
            $('#btnLogin').addClass('disabled');

            prosesLogin();
        })

        function prosesLogin() {
            var username = $('input[name="id_kasir"').val();
            var password = $('input[name="password_kasir"').val();

            if(username != '' && password != '') {
                $.ajax({
                    type    : 'post',
                    url     : 'proses-login',
                    dataType: 'json',
                    data    : {
                        username : username,
                        password : password
                    },
                    success : function(response) {
                        // console.log(response);
                        if(response == 'no match') {
                            $('.help-block').remove();
                            $('form').append('<span class="help-block" style="color:#a94442">Username atau password Anda salah!</span>');
                            $('#username').addClass('has-error');
                            $('#password').addClass('has-error');
                            $('input[name="id_kasir"]').focus();
                            $('#btnLogin').html('Sign In');
                            $('#btnLogin').removeClass('disabled');
                        }
                        else if(response == 'found a match') {
                            window.location = 'sinkronisasi-data';
                        }
                    }
                });
            }
        }
    });
    </script>
</body>
</html>