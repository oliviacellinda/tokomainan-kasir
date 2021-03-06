<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1, user-scalable=no">
	<title>Sinkronisasi Data</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/dist/css/AdminLTE.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/dist/css/skins/skin-blue.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/Source_Sans_Pro/font.css');?>">
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <!-- Header -->
        <?php include('application/views/kasir_v_navbar_top.php');?>

		<div class="content-wrapper">
			<div class="container">
				<section class="content-header">
					<h1>Sinkronisasi Data</h1>
				</section> <!-- End content-header -->

				<section class="content">
                    <p id="pesanSinkronisasi">Sinkronisasi data sedang berlangsung.</p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" style="width:100%"></div>
                    </div>
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12"><p id="pesanProgress"></p></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12"><p><strong>Pesan Error:</strong></p></div>
                                <div id="pesanError" class="col-xs-12"><!-- Pesan error di sini --></div>
                            </div>
                        </div>
                    </div>
				</section> <!-- End content -->
			</div> <!-- End container -->
		</div> <!-- End content-wrapper -->
    </div> <!-- End wrapper -->
    
    <script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
	<script src="<?php echo base_url('assets/AdminLTE-2.4.2/dist/js/adminlte.min.js');?>"></script>

	<script>
	$(document).ready(function() {
		// Tandai menu Sinkronisasi sebagai menu aktif pada header
		$('#sinkronisasi').addClass('active');

        sinkronisasiPelanggan();

		function sinkronisasiPelanggan() {
            $('#pesanProgress').text('Sinkronisasi data pelanggan.');
            $.ajax({
                url     : 'sinkronisasi-pelanggan',
                dataType: 'json',
                success : function(data) {
                    if(data == 'cant connect') {
                        var pesan = '<p>Error sinkronisasi pelanggan: Tidak dapat terhubung dengan database pusat. Silakan mencoba kembali setelah beberapa saat.</p>';
                        $('#pesanError').append(pesan);
                    }
                    else {
                        if(data.length > 1) {
                            var pesan = '<p>Error sinkronisasi pelanggan: ' + data[1] + '</p>';
                            $('#pesanError').append(pesan);
                        }
                        if(data.length > 2) {
                            var pesan = '<p>Error sinkronisasi pelanggan: ' + data[2] + '</p>';
                            $('#pesanError').append(pesan);
                        }
                    }
                },
                error   : function(response) {
                    var pesan = '<p>Error sinkronisasi pelanggan. Silakan mencoba kembali setelah beberapa saat.</p>';
                    $('#pesanError').append(pesan);
                },
                complete: function() {
                    sinkronisasiBarang();
                }
            });
        } // End fungsi sinkronisasiPelanggan

        function sinkronisasiBarang() {
            $('#pesanProgress').text('Sinkronisasi data barang.');
            $.ajax({
                url     : 'sinkronisasi-barang',
                dataType: 'json',
                success : function(data) {
                    if(data == 'cant connect') {
                        var pesan = '<p>Error sinkronisasi barang: Tidak dapat terhubung dengan database pusat. Silakan mencoba kembali setelah beberapa saat.</p>';
                        $('#pesanError').append(pesan);
                    }
                    else {
                        if(data.length > 1) {
                            var pesan = '<p>Error sinkronisasi barang: ' + data[1] + '</p>';
                            $('#pesanError').append(pesan);
                        }
                        if(data.length > 2) {
                            var pesan = '<p>Error sinkronisasi barang: ' + data[2] + '</p>';
                            $('#pesanError').append(pesan);
                        }
                    }
                },
                error   : function(response) {
                    var pesan = '<p>Error sinkronisasi barang. Silakan mencoba kembali setelah beberapa saat.</p>';
                    $('#pesanError').append(pesan);
                },
                complete: function() {
                    sinkronisasiPenjualan();
                }
            });
        } // End fungsi sinkronisasiBarang

        function sinkronisasiPenjualan() {
            $('#pesanProgress').text('Sinkronisasi data penjualan.');
            $.ajax({
                url     : 'sinkronisasi-penjualan',
                // dataType: 'json',
                success : function(data) {
                    console.log(data);
                    if(data == 'cant connect') {
                        var pesan = '<p>Error sinkronisasi penjualan: Tidak dapat terhubung dengan database pusat. Silakan mencoba kembali setelah beberapa saat.</p>';
                        $('#pesanError').append(pesan);
                    }
                    else if(data > 0) {
                        var pesan = '<p>Error sinkronisasi penjualan: Gagal mengunggah ' + data + ' laporan penjualan.</p>';
                        $('#pesanError').append(pesan);
                    }
                },
                error   : function(response) {
                    // console.log(response.responseText);
                    var pesan = '<p>Error sinkronisasi penjualan. Silakan mencoba kembali setelah beberapa saat.</p>';
                    $('#pesanError').append(pesan);
                },
                complete: function() {
                    $('#pesanSinkronisasi').text('Sinkronisasi data selesai.');
                    $('.progress').remove();
                    $('#pesanProgress').text('Sinkronisasi data selesai.');
                }
            });
        } // End fungsi sinkronisasiPenjualan
	});
	</script>
</body>
</html>