<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1, user-scalable=no">
	<title>Penjualan</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/jquery-ui-themes-1.12.1/themes/base/jquery-ui.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/dist/css/AdminLTE.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE-2.4.2/dist/css/skins/skin-blue.min.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/Source_Sans_Pro/font.css');?>">
	<style>
		.table > tbody > tr > td {
			vertical-align: middle;
		}
	</style>
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <!-- Header -->
        <?php include('application/views/kasir_v_navbar_top.php');?>

		<div class="content-wrapper">
			<div class="container">
				<section class="content-header">
					<h1>Nota : <span id="nomorInvoice"></span></h1>
				</section> <!-- End content-header -->

				<section class="content">
					<!-- Lokasi pesan pemberitahuan akan ditampilkan -->
					<div id="pesanPemberitahuan"></div>

                    <!-- Cari Pelanggan -->
                    <div class="box">
                        <div class="box-body">
							<div class="row">
								<div class="col-xs-3"><label>Nama Pelanggan</label></div>
								<div class="col-xs-3 col-sm-offset-1"><label>Keterangan</label></div>
							</div> <!-- End row -->

							<div class="row">
								<input type="hidden" name="id_pelanggan">
								<input type="hidden" name="alamat">
								<input type="hidden" name="telepon">
								<input type="hidden" name="level">
								<input type="hidden" name="ekspedisi">

								<!-- Nama Pelanggan -->
								<div class="col-xs-3">
									<div class="form-group">
										<input type="text" class="form-control" name="cari_pelanggan" placeholder="Nama Pelanggan" autocomplete="off">
									</div>
								</div>
								<div class="col-xs-1">
									<button id="btnTambahPelanggan" class="btn btn-success" data-toggle="modal" data-target="#modalFormPelanggan">
										<i class="fa fa-plus"></i>
									</button>
								</div>
								<!-- Keterangan -->
								<div class="col-xs-3">
									<div class="form-group">
										<input type="text" class="form-control" name="keterangan" placeholder="Keterangan" autocomplete="off">
									</div>
								</div>
								<div class="col-xs-5">
									<button id="btnLihatData" class="btn btn-primary pull-right" disabled data-toggle="modal" data-target="#modalDataBarang">
										<i class="fa fa-list-ul"></i> Lihat Data
									</button>
								</div>
							</div> <!-- End row -->
                        </div> <!-- End box-body -->
                    </div> <!-- End box -->

					<!-- Tabel -->
					<div class="box">
						<div class="box-body">
							<table id="tabelPenjualan" class="table table-bordered table-striped" style="width:100%;">
								<!-- Header Tabel -->
								<thead>
								<tr>
									<th>No.</th>
									<th>ID Barang</th>
									<th>Nama Barang</th>
									<th width="80px">Kemasan</th>
									<th>Jumlah (pcs)</th>
									<th width="80px">Harga (pcs)</th>
									<th>Diskon</th>
									<th width="80px">Total Harga</th>
									<th>Gambar</th>
									<th>Hapus</th>
								</tr>
								</thead>

								<!-- Isi tabel -->
								<tbody>
									<!-- Isi tabel dimuat melalui fungsi refreshTabel di bawah -->
								</tbody>
							</table>

							<div class="row">
								<div class="col-sm-4 col-sm-offset-8">
									<div class="col-xs-6 text-right"><p><strong>Sub Total :</strong></p></div>
									<div class="col-xs-6"><p>Rp. <span id="labelSubTotal"></span></p></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 col-sm-offset-8">
									<form class="form-horizontal" autocomplete="off">
										<div class="form-group">
											<label class="col-xs-6 control-label"><strong>Diskon :</strong></label>
											<div class="col-xs-6"><input type="text" class="form-control" name="diskonTotal" placeholder="Diskon Total" value="0" autocomplete="off"></div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 col-sm-offset-8">
									<div class="col-xs-6 text-right"><p><strong>Total :</strong></p></div>
									<div class="col-xs-6"><p>Rp. <span id="labelTotalPenjualan"></span></p></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 col-sm-offset-8">
									<button id="btnCetakNota" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Cetak Nota</button>
								</div>
							</div>

						</div> <!-- End box-body -->

						<!-- Disable terlebih dahulu tabel penjualan -->
						<div id="disableTabelPenjualan" class="overlay"></div>
					</div> <!-- End box -->
				</section> <!-- End content -->
			</div> <!-- End container -->
		</div> <!-- End content-wrapper -->
    </div> <!-- End wrapper -->

	<!-- Modal Form Pelanggan -->
	<div class="modal" id="modalFormPelanggan" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Judul Modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Tambah Pelanggan</h4>
                </div> <!-- End modal-header -->

                <!-- Isi Modal -->
                <div class="modal-body">
					<div id="pesanPemberitahuanModal"></div>
                    <div class="row">
                        <div class="col-xs-12">
                            <form id="formTambahPelanggan">
								<div class="form-group" id="formNama">
									<label>Nama</label>
									<input type="text" class="form-control" name="nama_pelanggan" placeholder="Nama" autocomplete="off">
								</div>
								<div class="form-group" id="formAlamat">
									<label>Alamat</label>
									<input type="text" class="form-control" name="alamat_pelanggan" placeholder="Alamat" autocomplete="off">
								</div>
								<div class="form-group" id="formEkspedisi">
									<label>Ekspedisi</label>
									<input type="text" class="form-control" name="ekspedisi_pelanggan" placeholder="Ekspedisi" autocomplete="off">
								</div>
								<div class="form-group" id="formTelepon">
									<label>Telepon</label>
									<input type="text" class="form-control" name="telepon_pelanggan" placeholder="Telepon" autocomplete="off">
								</div>

								<div id="loadingPelanggan" class="row"></div>
							</form>
                        </div> <!-- End col-xs-12 -->
                    </div> <!-- End row -->
                </div> <!-- End modal-body -->

                <!-- Kaki Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div> <!-- End modal-footer -->
            </div> <!-- End modal-content -->
        </div> <!-- End modal-dialog -->
    </div> <!-- End modal -->

	<!-- Modal Data Barang -->
    <div class="modal" id="modalDataBarang" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Judul Modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Daftar Barang</h4>
                </div> <!-- End modal-header -->

                <!-- Isi Modal -->
                <div class="modal-body">
					<div id="loadingBarang" class="row"></div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table id="tabelBarang" class="table table-bordered table-striped" style="width:100%">
								<thead>
								<tr>
									<th>Pilih</th>
									<th>ID Barang</th>
									<th>Nama Barang</th>
									<th>Jumlah dlm koli</th>
									<th>Kemasan</th>
									<th>Fungsi</th>
								</tr>
								</thead>
                                <tbody><!-- Isi tabel melalui ajax di bawah --></tbody>
                            </table>
                        </div> <!-- End col-xs-12 -->
                    </div> <!-- End row -->
                </div> <!-- End modal-body -->

                <!-- Kaki Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div> <!-- End modal-footer -->
            </div> <!-- End modal-content -->
        </div> <!-- End modal-dialog -->
    </div> <!-- End modal -->

	<!-- Modal Gambar -->
	<div class="modal" id="modalGambar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Judul Modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Gambar Barang</h4>
                </div> <!-- End modal-header -->

                <!-- Isi Modal -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12" id="gambarBarang">
						<i class="fa fa-spin fa-refresh"></i>
                        </div> <!-- End col-xs-12 -->
                    </div> <!-- End row -->
                </div> <!-- End modal-body -->

                <!-- Kaki Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div> <!-- End modal-footer -->
            </div> <!-- End modal-content -->
        </div> <!-- End modal-dialog -->
    </div> <!-- End modal -->
    
    <script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
	<script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/datatables.net/js/jquery.dataTables.min.js');?>"></script>
	<script src="<?php echo base_url('assets/AdminLTE-2.4.2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');?>"></script>
	<script src="<?php echo base_url('assets/absolute.js');?>"></script>
	<script src="<?php echo base_url('assets/jquery-ui-1.12.1/jquery-ui.min.js');?>"></script>
	<script src="<?php echo base_url('assets/jsPDF-master/dist/jspdf.debug.js');?>"></script>
	<script src="<?php echo base_url('assets/jsPDF-AutoTable-master/dist/jspdf.plugin.autotable.js');?>"></script>
	<script src="<?php echo base_url('assets/AdminLTE-2.4.2/dist/js/adminlte.min.js');?>"></script>

	<script>
	// Deklarasi variabel global
	var isiNota = new Array();
	var isiNotaPrint = new Array();
	var daftarBarang = JSON.parse('<?php echo json_encode($daftar_barang);?>');
	var namaToko = '<?php echo $nama_toko['nama_toko'];?>';
	var idBarangBaru, jumlahBaru, diskonBaru; // variabel yang digunakan untuk edit data nota

	// Ambil nilai baru dari input
	function ambilNilaiBaru(input) {
		// $(input).parent().parent()
		// input -> td -> tr
		// input yang memanggil fungsi ambilNilaiBaru saat ini
		idBarangBaru = $(input).parent().parent().find('input[name="id_barang"]').val();
		jumlahBaru = $(input).parent().parent().find('input[name="jumlah"]').val();
		diskonBaru = $(input).parent().parent().find('input[name="diskon"]').val();
	}

	$(document).ready(function() {
		// Tandai menu Penjualan sebagai menu aktif pada header
		$('#penjualan').addClass('active');
		// Fokus pada input pelanggan
		$('input[name="cari_pelanggan"]').focus();

		var tabelBarang = $('#tabelBarang').DataTable();

		var nomorType = $.fn.dataTable.absoluteOrder('');
		var kategoriType = $.fn.dataTable.absoluteOrder('');
		var tabelPenjualan = $('#tabelPenjualan').DataTable({
			'scrollX'		: true,
			'bInfo'			: false, // Untuk menghilangkan tulisan keterangan di bawah tabel
			'order'			: [[ 0, 'asc' ]],
			'columnDefs'	: [
				{ 'orderable' : false, 'targets' : [ 1, 2, 4, 5, 6, 7, 8 ] },
				{ 'targets' : 0, 'type' : nomorType },
				{ 'targets' : 3, 'type' : kategoriType }
			],
			'paging'		: false,
			'searching'		: false,
			'stateSave'		: true
		});

		$('input[name="cari_pelanggan"]').autocomplete({
			source		: function(request, response) {
				$.ajax({
					type	: 'post',
					url		: 'cari-pelanggan',
					dataType: 'json',
					data	: { term : request.term },
					success : function(data) {
						response(data);
					}
				});
			},
			// focus	: function(event, ui) {
			// 	event.preventDefault();
			// 	$(this).val(ui.item.label);
			// },
			select	: function(event, ui) {
				if(ui.item.value === 'Pelanggan belum terdaftar') {
					event.preventDefault();
					$('input[name="cari_pelanggan"]').val('');
				}
				else { 
					$('input[name="id_pelanggan"]').val(ui.item.id);
					$('input[name="alamat"]').val(ui.item.alamat);
					$('input[name="telepon"]').val(ui.item.telepon);
					$('input[name="level"]').val(ui.item.level);
					$('input[name="ekspedisi"]').val(ui.item.ekspedisi);
					$('#btnLihatData').removeAttr('disabled');
					$('#disableTabelPenjualan').removeClass('overlay');
				}
			}
		});

		nomorInvoiceBaru();
		refreshTabelBarang();
		refreshTabelPenjualan();

		// Fungsi untuk menentukan ID Invoice baru
		function nomorInvoiceBaru() {
			var today = new Date();
			var d = ( today.getDate() >= 10 ) ? today.getDate() : ( '0' + today.getDate() ); // getDate mengembalikan nilai antara 1-31
			var m = ( (today.getMonth() + 1) >= 10 ) ? today.getMonth() + 1 : ( '0' + (today.getMonth() + 1) ); // getMonth mengembalikan nilai antara 0-11
			var y = today.getFullYear();

			$.ajax({
				url		: 'nomor-invoice',
				dataType: 'json',
				success : function(data) {
					// Tentukan nomor invoice dgn format [id kasir]-[Ymd hari ini]-[no urut]
					$('#nomorInvoice').text('<?php echo $this->session->id_kasir;?>' + '-' + y + m + d + '-' + data);
				}
			});
		} // End fungsi nomorInvoiceBaru

		// Fungsi untuk memperoleh tanggal saat ini untuk ditampilkan dalam nta
		function tanggalSkrg() {
			var today = new Date();
			var d = ( today.getDate() >= 10 ) ? today.getDate() : ( '0' + today.getDate() ); // getDate mengembalikan nilai antara 1-31
			var m = ( (today.getMonth() + 1) >= 10 ) ? today.getMonth() + 1 : ( '0' + (today.getMonth() + 1) ); // getMonth mengembalikan nilai antara 0-11
			var y = today.getFullYear();
			var tanggal = d + '-' + m + '-' + y;
			tanggal = tanggal.toString();

			return tanggal;
		} // End fungsi tanggalSkrg

		// Fungsi untuk memperbarui modal data barang
		function refreshTabelBarang() {
			// Hapus isi data tabel
			$('#tabelBarang tbody').remove();
			
			// Buat variabel baru yang berisi HTML untuk isi data
			var isi = '<tbody>';
			if(daftarBarang.length > 0) {
				for(var i=0; i<daftarBarang.length; i++) {
					isi += '<tr>';
					isi += '<td><button id="btnPilihBarang" class="btn btn-xs btn-success">Pilih</button></td>';
					isi += '<td>'+daftarBarang[i].id_barang+'</td>';
					isi += '<td>'+daftarBarang[i].nama_barang+'</td>';
					isi += '<td>'+daftarBarang[i].jumlah_dlm_koli+'</td>';
					isi += '<td>'+daftarBarang[i].kemasan+'</td>';
					isi += '<td>'+daftarBarang[i].fungsi+'</td>';
					isi += '</tr>';
				}
			}
			isi += '</tbody>';

			// Tambahkan data baru ke dalam tabel
			$('#tabelBarang').append(isi);

			// Reinitialize DataTable
			tabelBarang.clear().destroy();
			tabelBarang = $('#tabelBarang').DataTable({
				'scrollX'		: true,
				'bInfo'			: false, // Untuk menghilangkan tulisan keterangan di bawah tabel
				'columnDefs'	: [
					{ 'orderable' : false, 'targets' : 0 }
				],
				'order'			: [[ 1, 'asc' ]]
			});
		} // End fungsi refreshTabelBarang

		// Fungsi untuk memperbarui tabel penjualan
		function refreshTabelPenjualan() {
			// Hapus isi data tabel
			$('#tabelPenjualan tbody').remove();

			// Buat variabel baru yang berisi HTML untuk isi data
			var isi = '<tbody>';
			isi += '<tr id="barisInput">';
			isi += '<td></td>';
			isi += '<td><input type="text" class="form-control" style="width:125px;" placeholder="ID Barang" name="id_barang" autocomplete="off"></td>';
			isi += '<td></td>';
			isi += '<td></td>';
			isi += '<td></td>';
			isi += '<td></td>';
			isi += '<td></td>';
			isi += '<td></td>';
			isi += '<td></td>';
			isi += '<td></td>';
			isi += '</tr>';
			if(isiNota.length != 0) {
				for(var i=0; i<isiNota.length; i++) {
					// Pengaturan untuk diskon
					var diskon = isiNota[i].diskon;
					diskon = (isiNota[i].statusDiskon == 'p') ? diskon+'%' : diskon;

					isi += '<tr>';
					isi += '<td>'+(i+1)+'</td>';
					isi += '<td><input type="text" class="form-control" style="width:125px;" placeholder="ID Barang" name="id_barang" value="'+isiNota[i].idBarang+'" onkeypress="ambilNilaiBaru(this)" autocomplete="off"></td>';
					isi += '<td>'+isiNota[i].namaBarang+' ('+isiNota[i].jmlDlmKoli+' pcs)</td>';
					isi += '<td>'+isiNota[i].kemasan+'</td>';
					isi += '<td><input type="text" class="form-control" style="width:100px;" placeholder="Jumlah (pcs)" name="jumlah" value="'+isiNota[i].jumlah+'" onkeypress="ambilNilaiBaru(this)" autocomplete="off"></td>';
					isi += '<td>'+isiNota[i].harga+'</td>';
					isi += '<td><input type="text" class="form-control" style="width:100px;" placeholder="Diskon" name="diskon" value="'+diskon+'" onkeypress="ambilNilaiBaru(this)" autocomplete="off"></td>';
					isi += '<td>'+isiNota[i].totalHarga+'</td>';
					isi += '<td><button id="btnGambar" class="btn btn-xs btn-info" data-id="'+isiNota[i].idBarang+'" data-toggle="modal" data-target="#modalGambar">Gambar</button></td>';
					isi += '<td><button id="btnHapus" class="btn btn-xs btn-danger" data-id="'+isiNota[i].idBarang+'"><i class="fa fa-times"></i></button></td>';
					isi += '</tr>';
				}
			}
			isi += '</tbody>';

			// Tambahkan ke tabel
			$('#tabelPenjualan').append(isi);

			// Reinitialize DataTable
			tabelPenjualan.clear().destroy();
			tabelPenjualan = $('#tabelPenjualan').DataTable({
				'scrollX'		: true,
				'bInfo'			: false, // Untuk menghilangkan tulisan keterangan di bawah tabel
				'order'			: [[ 0, 'asc' ]],
				'columnDefs'	: [
					{ 'orderable' : false, 'targets' : [ 1, 2, 4, 5, 6, 7, 8 ] },
					{ 'targets' : 0, 'type' : nomorType },
					{ 'targets' : 3, 'type' : kategoriType }
				],
				'paging'		: false,
				'searching'		: false,
				'stateSave'		: true,
				'fnPreDrawCallback'	: function(oSettings) {
					/* reset currData before each draw */
					currData = [];
				},
				'fnRowCallback'		: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
					/* push this row of data to currData array */
					currData.push(aData);
			
				},
				'fnDrawCallback'	: function(oSettings) {
					/* can now access sorted data array */
					// console.log(currData);
					isiNotaPrint = currData;
				}
			});
		} // End fungsi refreshTabelPenjualan

		// Fungsi untuk menampilkan pesan loading selama proses berlangsung
		function pesanLoading() {
			var loading = '<div class="overlay">';
			loading += '<i class="fa fa-refresh fa-spin"></i>';
			loading += '</div>';
			$('div[class="box"]').append(loading);
		} // End fungsi pesanLoading

		// Fungsi untuk menambahkan pesan pemberitahuan di atas tabel
		// Variabel jenis menampung nilai yang berisi informasi jenis alert yang diinginkan
		// Variabel pesan menampung string yang berisi pesan yang ingin disampaikan
		function pesanPemberitahuan(jenis, pesan) {
			// Hapus terlebih dahulu jika sudah ada pesan pemberitahuan sebelumnya
			$('.alert').remove();

			var alert = '<div class="alert alert-'+jenis+' alert-dismissible" role="alert">';
			alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			alert += pesan;
			alert += '</div>';
			$('#pesanPemberitahuan').append(alert);
		} // End fungsi pesanPemberitahuan

		// Fungsi untuk menampilkan pesan pemberitahuan pada modal tambah pelanggan
		function pesanPemberitahuanModal(jenis, pesan) {
			// Hapus terlebih dahulu jika sudah ada pesan pemberitahuan sebelumnya
			$('.alert').remove();

			var alert = '<div class="alert alert-'+jenis+' alert-dismissible" role="alert">';
			alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			alert += pesan;
			alert += '</div>';
			$('#pesanPemberitahuanModal').append(alert);
		} // End fungsi pesanPemberitahuan

		// Fungsi untuk menyesuaikan tombol Pilih dalam modal daftar barang
		function adjustTombolPilih() {
			if(isiNota.length != 0) {
				// Hapus isi data tabel
				$('#tabelBarang tbody').remove();
				
				// Buat variabel baru yang berisi HTML untuk isi data
				var isi = '<tbody>';
				if(daftarBarang.length > 0) {
					for(var i=0; i<daftarBarang.length; i++) {
						isi += '<tr>';

						// Cek apakah ID Barang pada iterasi saat ini sdh ada dalam nota
						var cek = isiNota.find(arr => arr.idBarang === daftarBarang[i].id_barang);
						if(cek != null) isi += '<td><button id="btnPilihBarang" class="btn btn-xs btn-success" disabled>Pilih</button></td>';
						else isi += '<td><button id="btnPilihBarang" class="btn btn-xs btn-success">Pilih</button></td>';

						isi += '<td>'+daftarBarang[i].id_barang+'</td>';
						isi += '<td>'+daftarBarang[i].nama_barang+'</td>';
						isi += '<td>'+daftarBarang[i].jumlah_dlm_koli+'</td>';
						isi += '<td>'+daftarBarang[i].kemasan+'</td>';
						isi += '<td>'+daftarBarang[i].fungsi+'</td>';
						isi += '</tr>';
					}
				}
				isi += '</tbody>';
			}
			else {
				// Hapus isi data tabel
				$('#tabelBarang tbody').remove();
				
				// Buat variabel baru yang berisi HTML untuk isi data
				var isi = '<tbody>';
				if(daftarBarang.length > 0) {
					for(var i=0; i<daftarBarang.length; i++) {
						isi += '<tr>';
						isi += '<td><button id="btnPilihBarang" class="btn btn-xs btn-success">Pilih</button></td>';
						isi += '<td>'+daftarBarang[i].id_barang+'</td>';
						isi += '<td>'+daftarBarang[i].nama_barang+'</td>';
						isi += '<td>'+daftarBarang[i].jumlah_dlm_koli+'</td>';
						isi += '<td>'+daftarBarang[i].kemasan+'</td>';
						isi += '<td>'+daftarBarang[i].fungsi+'</td>';
						isi += '</tr>';
					}
				}
				isi += '</tbody>';		
			}

			// Tambahkan data baru ke dalam tabel
			$('#tabelBarang').append(isi);

			// Reinitialize DataTable
			tabelBarang.clear().destroy();
			tabelBarang = $('#tabelBarang').DataTable({
				'scrollX'		: true,
				'bInfo'			: false, // Untuk menghilangkan tulisan keterangan di bawah tabel
				'columnDefs'	: [
					{ 'orderable' : false, 'targets' : 0 }
				],
				'order'			: [[ 1, 'asc' ]]
			});	
		} // End fungsi adjustTombolPilih

		// Fungsi untuk menghitung total penjualan
		function totalPenjualan() {
			var subTotal = 0;
			var diskonTotal = $('input[name="diskonTotal"]').val();
			var totalPenjualan = 0;

			for(var i=0; i<isiNota.length; i++) {
				subTotal = subTotal + parseInt(isiNota[i].totalHarga);
			}

			if( diskonTotal.indexOf('%') == -1 ) {
				totalPenjualan = subTotal - parseInt(diskonTotal);
			}
			else {
				totalPenjualan = subTotal * (100 - parseInt(diskonTotal)) / 100;
			}

			$('#labelSubTotal').text(subTotal);
			$('#labelTotalPenjualan').text(totalPenjualan);

		} // End fungsi totalPenjualan

		// Fungsi untuk menyimpan nota dalam database kasir
		function simpanNotaLokal(today, subTotal, diskonTotal, statusDiskonTotal, totalPenjualan, nomorInvoice, idPelanggan, namaPelanggan, alamatPelanggan, teleponPelanggan, ekspedisiPelanggan, keterangan, isiNotaString) {
			$.ajax({
				type	: 'post',
				url		: 'simpan-nota-lokal',
				dataType: 'json',
				data	: {
					today				: today,
					subTotal			: subTotal,
					diskonTotal 		: diskonTotal,
					statusDiskonTotal	: statusDiskonTotal,
					totalPenjualan		: totalPenjualan,
					nomorInvoice		: nomorInvoice,
					idPelanggan			: idPelanggan,
					namaPelanggan		: namaPelanggan,
					alamatPelanggan		: alamatPelanggan,
					teleponPelanggan	: teleponPelanggan,
					ekspedisiPelanggan	: ekspedisiPelanggan,
					keterangan			: keterangan,
					isiNotaString		: isiNotaString
				},
				success	: function(data) {
					// Jika berhasil simpan dalam database kasir, cetak nota dan simpan ke database pusat
					if(data == 'success') {
						cetakNota();

						// Simpan ke database pusat
						simpanNotaPusat(today, subTotal, diskonTotal, statusDiskonTotal, totalPenjualan, nomorInvoice, idPelanggan, namaPelanggan, alamatPelanggan, teleponPelanggan, ekspedisiPelanggan, keterangan, isiNotaString);
					}
					else {
						// Tampilkan pesan pemberitahuan, dan lakukan tindakan sesuai pilihan kasir
						var pesan = confirm('Data gagal disimpan dalam database! Tetap cetak nota?');
						if(pesan == true) {
							cetakNota();
						}

						// Simpan data ke database pusat
						simpanNotaPusat(today, subTotal, diskonTotal, statusDiskonTotal, totalPenjualan, nomorInvoice, idPelanggan, alamatPelanggan, teleponPelanggan, ekspedisiPelanggan, keterangan, isiNotaString);
					}
				},
				error	: function(response) {
					console.log(response.responseText);
					// Tampilkan pesan pemberitahuan, dan lakukan tindakan sesuai pilihan kasir
					var pesan = confirm('Data gagal disimpan dalam database! Tetap cetak nota?');
					if(pesan == true) {
						cetakNota();
					}

					// Simpan data ke database pusat
					simpanNotaPusat(today, subTotal, diskonTotal, statusDiskonTotal, totalPenjualan, nomorInvoice, idPelanggan, alamatPelanggan, teleponPelanggan, ekspedisiPelanggan, keterangan, isiNotaString);
				},
				complete: function() {
					refreshHalaman();
				}
			})
		} // End fungsi simpanNotaLokal

		// Fungsi untuk menyimpan nota dalam database admin
		function simpanNotaPusat(today, subTotal, diskonTotal, statusDiskonTotal, totalPenjualan, nomorInvoice, idPelanggan, namaPelanggan, alamatPelanggan, teleponPelanggan, ekspedisiPelanggan, keterangan, isiNotaString) {
			$.ajax({
				type	: 'post',
				url		: 'simpan-nota-pusat',
				dataType: 'json',
				data	: {
					today				: today,
					subTotal			: subTotal,
					diskonTotal 		: diskonTotal,
					statusDiskonTotal	: statusDiskonTotal,
					totalPenjualan		: totalPenjualan,
					nomorInvoice		: nomorInvoice,
					idPelanggan			: idPelanggan,
					namaPelanggan		: namaPelanggan,
					alamatPelanggan		: alamatPelanggan,
					teleponPelanggan	: teleponPelanggan,
					ekspedisiPelanggan	: ekspedisiPelanggan,
					keterangan			: keterangan,
					isiNotaString		: isiNotaString
				},
				success	: function(data) {
					// console.log(data);
				},
				error	: function(response) {
					// console.log(response.responseText);
				}
			})
		} // End fungsi simpanNotaPusat

		// Fungsi untuk mengambil dan menyusun data yang akan ditampilkan dalam nota, kemudian dicetak
		function cetakNota() {
			var jumlahHlm = Math.ceil(isiNota.length / 10);
			var kolom = ['No', 'Jumlah', 'Nama Barang', 'Kode Barang', 'Kemasan', 'Harga Satuan', 'Diskon', 'Total', 'Dus ke-'];
			var data = new Array();
			var nama = $('input[name="cari_pelanggan"]').val();
			var alamat = $('input[name="alamat"]').val();
			var telepon = $('input[name="telepon"]').val();
			var ekspedisi = $('input[name="ekspedisi"]').val();
			var nomorNota = $('#nomorInvoice').text();
			var pdf = new jsPDF('landscape', 'mm', 'a5');

			// for(var i=0; i<isiNota.length; i++) {
			// 	var diskon = (isiNota[i].statusDiskon == 'n') ? 'Rp. '+isiNota[i].diskon : isiNota[i].diskon+'%';
			// 	var baris = [ i+1, isiNota[i].jumlah, isiNota[i].namaBarang, isiNota[i].idBarang, 'Rp. '+isiNota[i].harga, diskon, 'Rp. '+isiNota[i].totalHarga, '' ];
			// 	data.push(baris);
			// }

			for(var i=1; i<isiNotaPrint.length; i++) {
				// Indeks isiNotaPrint
				// 0 : Nomor
				// 1 : ID Barang
				// 2 : Nama Barang
				// 3 : Kemasan
				// 4 : Jumlah
				// 5 : Harga
				// 6 : Diskon
				// 7 : Total Harga

				var idBarang = $( isiNotaPrint[i][1] ).val();
				var jumlah = $( isiNotaPrint[i][4] ).val();
				var diskon = $( isiNotaPrint[i][6] ).val();
				if(diskon.indexOf('%') == -1) diskon = 'Rp. ' + diskon;

				var baris = [ i, jumlah+' pcs', isiNotaPrint[i][2], idBarang, isiNotaPrint[i][3], 'Rp. '+isiNotaPrint[i][5], diskon, 'Rp. '+isiNotaPrint[i][7], '' ];
				data.push(baris);
			}
			// console.log(data);

			for(var i=0; i<jumlahHlm; i++) {
				var dataPerHlm = new Array();
				
				if(i+1 == jumlahHlm) {
					var batas = data.length % 10;
					for(j=i*10; j<(i*10)+batas; j++) {
						dataPerHlm.push(data[j]);
					}
				}
				else {
					for(j=i*10; j<(i+1)*10; j++) {
						dataPerHlm.push(data[j]);
					}
				}
				// console.log(dataPerHlm);

				// Nama toko
				pdf.setFontSize(18);
				pdf.text(namaToko, 10, 10, 'left');

				// Nomor nota
				pdf.setFontSize(12);
				pdf.text('No. Nota: '+nomorNota, 10, 20, 'left');

				// Tanggal nota
				pdf.setFontSize(8);
				pdf.text('Tanggal', 140, 10, 'left');
				pdf.setFontSize(8);
				pdf.text(tanggalSkrg(), 155, 10, 'left');

				// Nama pembeli
				pdf.setFontSize(8);
				pdf.text('Nama', 140, 15, 'left');
				pdf.setFontSize(8);
				pdf.text(nama, 155, 15, 'left');

				// Alamat pembeli
				pdf.setFontSize(8);
				pdf.text('Alamat', 140, 20, 'left');
				pdf.setFontSize(8);
				pdf.text(alamat, 155, 20, 'left');

				// Telepon pembeli
				pdf.setFontSize(8);
				pdf.text('Telepon', 140, 25, 'left');
				pdf.setFontSize(8);
				pdf.text(telepon, 155, 25, 'left');

				// Ekspedisi
				pdf.setFontSize(8);
				pdf.text('Ekspedisi', 140, 30, 'left');
				pdf.setFontSize(8);
				pdf.text(ekspedisi, 155, 30, 'left');

				pdf.autoTable(kolom, dataPerHlm, {
					startX	: 10,
					startY	: 35,
					theme	: 'grid',
					styles	: {
						overflow:'linebreak',
						fontSize: 8
					}
				});

				pdf.setFontSize(8);
				pdf.text((i+1).toString(), 105, 140, 'left');

				if(i+1 != jumlahHlm) {
					var subTotal = 0;
					for(var a=0; a<dataPerHlm.length; a++) {
						harga = dataPerHlm[a][7];
						harga = harga.substring(4);
						harga = parseInt(harga);
						subTotal = subTotal + harga;
					}
					subTotal = subTotal.toString();

					pdf.setFontSize(10);
					pdf.setFontStyle('bold');
					pdf.text('Subtotal', 150, 135, 'left');
					pdf.setFontSize(10);
					pdf.setFontStyle('normal');
					pdf.text('Rp. '+subTotal, 190, 135, 'right');

					pdf.addPage('landscape', 'a5');
				}
				else {
					var subTotalPenjualan = $('#labelSubTotal').text();
					var diskonTotal = $('input[name="diskonTotal"]').val();
					if( diskonTotal.indexOf('%') == -1 ) diskonTotal = 'Rp. ' + diskonTotal;
					else diskonTotal = diskonTotal;
					var totalPenjualan = $('#labelTotalPenjualan').text();
					
					// Sub total penjualan
					pdf.setFontSize(10);
					pdf.setFontStyle('bold');
					pdf.text('Subtotal', 150, 125, 'left');
					pdf.setFontSize(10);
					pdf.setFontStyle('normal');
					pdf.text('Rp. '+subTotalPenjualan, 190, 125, 'right');

					// Diskon total
					pdf.setFontSize(10);
					pdf.setFontStyle('bold');
					pdf.text('Diskon', 150, 130, 'left');
					pdf.setFontSize(10);
					pdf.setFontStyle('normal');
					pdf.text(diskonTotal, 190, 130, 'right');

					// Total penjualan
					pdf.setFontSize(10);
					pdf.setFontStyle('bold');
					pdf.text('Total', 150, 135, 'left');
					pdf.setFontSize(10);
					pdf.setFontStyle('normal');
					pdf.text('Rp. '+totalPenjualan, 190, 135, 'right');
				}
			}

			pdf.autoPrint();
			window.open(pdf.output('bloburl'), '_blank');
		} // End fungsi cetakNota

		// Fungsi untuk refresh halaman
		function refreshHalaman() {
			isiNota = new Array();
			refreshTabelPenjualan();
			refreshTabelBarang();
			nomorInvoiceBaru();
			$('input[name="cari_pelanggan"]').val('');
			$('input[name="cari_pelanggan"]').focus();
			$('input[name="id_pelanggan"]').val('');
			$('input[name="level_pelanggan"]').val('');
			$('input[name="keterangan"]').val('');
			$('#labelSubTotal').text('');
			$('input[name="diskonTotal"]').val(0);
			$('#labelTotalPenjualan').text('');
			$('#btnLihatData').prop('disabled', 'remove');
			$('#disableTabelPenjualan').addClass('overlay');
		} // End fungsi refreshHalaman

		// Fungsi untuk mengecek gambar
		function cekGambar(urlGambar, success, fail) {
			var img = new Image();
			img.src = urlGambar;
			img.onload = success; 
			img.onerror = fail;
		} // End fungsi cekGambar

		// Event handler untuk menambah pelanggan
		$('#modalFormPelanggan').on('shown.bs.modal', function() {
			$('input[name="nama_pelanggan"]').focus();
		});
		$('#modalFormPelanggan').on('keypress', 'input[name="nama_pelanggan"]', function(event) {
			if(event.keyCode === 13) {
				if( $(this).val() == '' ) {
					$('#formNama').addClass('has-error');
				}
				else {
					$('input[name="alamat_pelanggan"]').focus();
				}
			}
			else {
				$('#formNama').removeClass('has-error');
			}
		});
		$('#modalFormPelanggan').on('keypress', 'input[name="alamat_pelanggan"]', function(event) {
			if(event.keyCode === 13) {
				if( $(this).val() == '' ) {
					$('#formAlamat').addClass('has-error');
				}
				else {
					$('input[name="ekspedisi"]').focus();
				}
			}
			else {
				$('#formAlamat').removeClass('has-error');
			}
		});
		$('#modalFormPelanggan').on('keypress', 'input[name="ekspedisi"]', function(event) {
			if(event.keyCode === 13) {
				if( $(this).val() == '' ) {
					$('#formEkspedisi').addClass('has-error');
				}
				else {
					$('input[name="telepon_pelanggan"]').focus();
				}
			}
			else {
				$('#formEkspedisi').removeClass('has-error');
			}
		});
		$('#modalFormPelanggan').on('keypress', 'input[name="telepon_pelanggan"]', function(event) {
			if(event.keyCode === 13) {
				// Ambil data
				var nama_pelanggan = $('input[name="nama_pelanggan"]').val();
				var alamat_pelanggan = $('input[name="alamat_pelanggan"]').val();
				var ekspedisi = $('input[name="ekspedisi_pelanggan"]').val();
				var telepon_pelanggan = $('input[name="telepon_pelanggan"]').val();

				if(nama_pelanggan == '' || alamat_pelanggan == '' || ekspedisi == '' || telepon_pelanggan == '') {
					if(nama_pelanggan == '') $('#formNama').addClass('has-error');
					if(alamat_pelanggan == '') $('#formAlamat').addClass('has-error');
					if(ekspedisi == '') $('#formEkspedisi').addClass('has-error');
					if(telepon_pelanggan == '') $('#formTelepon').addClass('has-error');
				}
				else {
					// Progress bar selama proses tambah pelanggan
					var loading = '<div class="progress">';
					loading += '<div class="progress-bar progress-bar-success progress-bar-striped active progress-xs" style="width:100%"></div>';
					loading += '</div>';
					$('#loadingPelanggan').append(loading);

					// Disable semua input dalam form
					$('input[name="nama_pelanggan"]').prop('disabled', 'remove');
					$('input[name="alamat_pelanggan"]').prop('disabled', 'remove');
					$('input[name="ekspedisi_pelanggan"]').prop('disabled', 'remove');
					$('input[name="telepon_pelanggan"]').prop('disabled', 'remove');

					$.ajax({
						type	: 'post',
						url		: 'penjualan-tambah-pelanggan',
						dataType: 'json',
						data	: {
							nama_pelanggan		: nama_pelanggan,
							alamat_pelanggan	: alamat_pelanggan,
							ekspedisi			: ekspedisi,
							telepon_pelanggan	: telepon_pelanggan
						},
						success	: function(data) {
							// Reset value input
							$('input[name="nama_pelanggan"]').val('');
							$('input[name="alamat_pelanggan"]').val('');
							$('input[name="ekspedisi_pelanggan"]').val('');
							$('input[name="telepon_pelanggan"]').val('');
							
							// Hilangkan disable pada input
							$('input[name="nama_pelanggan"]').removeAttr('disabled');
							$('input[name="alamat_pelanggan"]').removeAttr('disabled');
							$('input[name="ekspedisi_pelanggan"]').removeAttr('disabled');
							$('input[name="telepon_pelanggan"]').removeAttr('disabled');

							// Hilangkan progress bar
							$('.progress').remove();

							var statusSimpan = 0; // variabel status untuk mengecek keberhasila simpan data baru
							var statusSinkronisasi = 0; // variabel status untuk mengecek keberhasilan sinkronisasi

							if(data == 'cant connect') {
								pesanPemberitahuanModal('warning', 'Tidak dapat terhubung dengan database pusat. Silakan mencoba kembali setelah beberapa saat.');
							}
							else if(data == 'fail') {
								pesanPemberitahuanModal('warning', 'Gagal menyimpan data pelanggan baru.');
							}
							else if(data == 'cant connect to sync') {
								pesanPemberitahuanModal('warning', 'Sinkronisasi data pelanggan gagal karena tidak dapat terhubung dengan database pusat.');
								statusSimpan = 1;
							}
							else {
								statusSimpan = 1;

								if(data.flag_error == 1) {
									pesanPemberitahuanModal('warning', 'Sinkronisasi data pelanggan gagal. Silakan mencoba kembali setelah beberapa saat.');
								}
								else statusSinkronisasi = 1;
							}

							if(statusSimpan == 1) {
								// Tentukan nilai pada input cari pelanggan
								$('input[name="cari_pelanggan"]').val(nama_pelanggan);
								$('input[name="id_pelanggan"]').val(data.id_pelanggan);
								$('input[name="alamat"]').val(alamat_pelanggan);
								$('input[name="telepon"]').val(telepon_pelanggan);
								$('input[name="level"]').val(data.level);
								$('input[name="ekspedisi"]').val(ekspedisi);

								// Hilangkan disable pada button Lihat Data
								$('#btnLihatData').removeAttr('disabled');

								// Hilangkan disable pada tabel penjualan
								$('#disableTabelPenjualan').removeClass('overlay');
							}

							if(statusSinkronisasi == 1) {
								// Tutup modal
								$('#modalFormPelanggan').modal('hide');
							}
						},
						error : function(response) {
							console.log(response.responseText);
							pesanPemberitahuanModal('warning', 'Gagal menyimpan data pelanggan baru.');
						}
					});
				}
			}
			else {
				$('#formTelepon').removeClass('has-error');
			}
		}); // End event handler menambah pelanggan

		// Ketika modal ditampilkan, sesuaikan button Pilih dengan isi nota
		$('#modalDataBarang').on('show.bs.modal', function() {
			adjustTombolPilih();
		}); // End event handler saat modal daftar barang ditampilkan

		// Setelah modal selesai ditampilkan, atur kembali lebar kolom tabel barang
		$('#modalDataBarang').on('shown.bs.modal', function() {
			// Atur kembali lebar kolom tabel saat modal muncul
			tabelBarang.columns.adjust();
		}); // End event handler saat modal daftar barang selesai ditampilkan

		// Event handler button Pilih dalam modal daftar barang
		$('#tabelBarang').on('click', '#btnPilihBarang', function() {
			// Level pelanggan
			var level = $('input[name="level"]').val();
			// Seluruh data yang berada di baris yang sama dengan tombol Pilih yang diklik
			var data = tabelBarang.row($(this).parents('tr')).data();
			var pilihan = {
				idBarang	: data[1],
				namaBarang	: data[2],
				jumlah		: 0,
				harga		: 0,
				diskon		: 0,
				statusDiskon: '',
				totalHarga	: 0,
				jmlDlmKoli	: data[3],
				kemasan		: data[4],
				fungsi		: data[5]
			};

			// Cek harga
			var temp = daftarBarang.find(arr => arr.id_barang === data[1]);
			switch(level) {
				case '1' : pilihan.harga = temp.harga_jual_1; break;
				case '2' : pilihan.harga = temp.harga_jual_2; break;
				case '3' : pilihan.harga = temp.harga_jual_3; break;
				case '4' : pilihan.harga = temp.harga_jual_4; break;
			}

			isiNota.push(pilihan);

			// Disable button Pilih pada baris data yang telah dipilih
			$(this).prop('disabled', 'remove');

			totalPenjualan();
		}); // End event klik button Pilih pada modal daftar barang

		// Event handler saat selesai memilih barang dan menutup modal
		$('#modalDataBarang').on('hidden.bs.modal', function() {
			pesanLoading();
			refreshTabelPenjualan();
			$('.overlay').remove();
		}); // End event handler saat modal daftar barang ditutup

		// Event handler tombol Enter di input ID Barang, untuk menambah daftar nota sesuai dengan ID Barang yang dimasukkan
		$('#tabelPenjualan').on('keypress', '#barisInput input[name="id_barang"]', function(event) {
			if(event.keyCode === 13) {
				pesanLoading();

				var id_barang = $('#barisInput input[name="id_barang"]').val();

				// Cek apakah barang sudah ada di daftar nota, tambahkan ke nota jika belum ada
				var cek = isiNota.find(arr => arr.idBarang === id_barang);
				if(cek == null) {
					// Cek apakah kode barang ada dalam daftar barang, tambahkan ke nota jika kode barang terdaftar
					var temp = daftarBarang.find(arr => arr.id_barang === id_barang);
					if(temp != null) {
						var input = {
							idBarang	: temp.id_barang,
							namaBarang	: temp.nama_barang,
							jumlah		: 0,
							harga		: 0,
							diskon		: 0,
							statusDiskon: '',
							totalHarga	: 0,
							jmlDlmKoli	: temp.jumlah_dlm_koli,
							kemasan		: temp.kemasan,
							fungsi		: temp.fungsi
						};
						var level = $('input[name="level"]').val();
						switch(level) {
							case '1' : input.harga = temp.harga_jual_1; break;
							case '2' : input.harga = temp.harga_jual_2; break;
							case '3' : input.harga = temp.harga_jual_3; break;
							case '4' : input.harga = temp.harga_jual_4; break;
						}
						isiNota.push(input);

						refreshTabelPenjualan();
					} // End if hasil pencarian tidak undefined
				} // End if barang belum ada di nota

				$('.overlay').remove();
			}
		}); // End event handler tekan tombol Enter di input ID Barang pada baris 
		
		// Event handler edit data dalam tabel penjualan (trigger dgn tombol Enter)
		$('#tabelPenjualan').on('keypress', 'td', function(event) {
			if(event.keyCode === 13) {
				// Cek apakah tombol ditekan pada barisan input data baru
				if( tabelPenjualan.row($(this).parents('tr')).id() != 'barisInput' ) {
					var baris = tabelPenjualan.cell(this).index().row;
					var dataBaris = tabelPenjualan.row($(this).parents('tr')).data();

					// Cek input yang dikenai event tombol Enter
					var cell = tabelPenjualan.cell(this).data();
					if( cell.indexOf('name="id_barang"') != -1 ) {
						var idBarang = idBarangBaru;

						// Cek apakah ID Barang baru sudah ada dalam nota
						var cekNota = isiNota.find(arr => arr.idBarang === idBarang);
						if(cekNota != null) {
							// Jika ada, kembalikan ID Barang ke nilai semula
							var idBarangSemula = cell.split('value="').pop();
							idBarangSemula = idBarangSemula.replace('" onkeypress="ambilNilaiBaru(this)" autocomplete="off">', '');
							$(this).find('input[name="id_barang"]').val(idBarangSemula);
						}
						else {
							// Cek apakah ID Barang baru ada dalam daftar barang
							var cekDaftar = daftarBarang.find(arr => arr.id_barang === idBarang);
							if(cekDaftar == null) {
								// Jika tidak ada, kembalikan ID Barang ke nilai semula
								var idBarangSemula = cell.split('value="').pop();
								idBarangSemula = idBarangSemula.replace('" onkeypress="ambilNilaiBaru(this)" autocomplete="off">', '');
								$(this).find('input[name="id_barang"]').val(idBarangSemula);
							}
							else {
								// Jika ada, perbarui isiNota dan refresh tabel penjualan
								var level = $('input[name="level"]').val();
								isiNota[baris-1].idBarang = cekDaftar.id_barang;
								isiNota[baris-1].namaBarang = cekDaftar.nama_barang;
								isiNota[baris-1].jumlah = 0;
								isiNota[baris-1].diskon = 0;
								isiNota[baris-1].statusDiskon = '';
								isiNota[baris-1].totalHarga = 0;

								switch(level) {
									case '1' : isiNota[baris-1].harga = cekDaftar.harga_jual_1; break;
									case '2' : isiNota[baris-1].harga = cekDaftar.harga_jual_2; break;
									case '3' : isiNota[baris-1].harga = cekDaftar.harga_jual_3; break;
									case '4' : isiNota[baris-1].harga = cekDaftar.harga_jual_4; break;
								}

								refreshTabelPenjualan();
							}
						}
					}
					else if( cell.indexOf('name="jumlah"') != -1 || cell.indexOf('name="diskon"') != -1 ) {
						var jumlah = jumlahBaru;
						var diskon = diskonBaru;
						var harga = tabelPenjualan.cell(baris, 5).data();
						var totalHarga = 0;

						// Cek apakah jumlah dan diskon kosong
						if( jumlah == '' || diskon == '' ) {
							// if(jumlah == '') $(this).closest('tr').find('input[name="jumlah"]').val(0);
							// if(diskon == '') $(this).closest('tr').find('input[name="diskon"]').val(0);
							refreshTabelPenjualan();
						}
						// Cek apakah jumlah dan diskon bukan angka
						else if( isNaN(parseInt(jumlah)) || isNaN(parseInt(diskon)) ) {
							// if( isNaN(parseInt(jumlah)) ) $(this).closest('tr').find('input[name="jumlah"]').val(0);
							// if( isNaN(parseInt(diskon)) ) $(this).closest('tr').find('input[name="diskon"]').val(0);
							refreshTabelPenjualan();
						}
						// Cek apakah jumlah dan diskon bernilai negatif
						else if( parseInt(jumlah) < 0 || parseInt(diskon) < 0 ) {
							// if( parseInt(jumlah) < 0 ) $(this).closest('tr').find('input[name="jumlah"]').val(0);
							// if( parseInt(diskon) < 0 ) $(this).closest('tr').find('input[name="diskon"]').val(0);
							refreshTabelPenjualan();
						}
						// Cek apakah diskon lebih besar dari 100%
						else if( diskon.indexOf('%') != -1 && (parseInt(diskon)) > 100 ) {
							// $(this).closest('tr').find('input[name="diskon"]').val(0);
							refreshTabelPenjualan();
						}
						// Cek apakah diskon melebihi harga satuan
						else if( (parseInt(diskon)) > (parseInt(harga)) ) {
							// $(this).closest('tr').find('input[name="diskon"]').val(0);
							refreshTabelPenjualan();
						}
						else {
							isiNota[baris-1].jumlah = parseInt(jumlah);
							isiNota[baris-1].diskon = parseInt(diskon);

							// Cek jenis diskon (persentase atau nominal)
							if( diskon.indexOf('%') == -1 ) {
								totalHarga = ( parseInt(jumlah) * (parseInt(harga) - parseInt(diskon)) ).toString();
								isiNota[baris-1].statusDiskon = 'n';
								isiNota[baris-1].totalHarga = totalHarga;
							}
							else {
								totalHarga = ( (parseInt(jumlah) * parseInt(harga)) * (100 - parseInt(diskon)) / 100 ).toString();
								isiNota[baris-1].statusDiskon = 'p';
								isiNota[baris-1].totalHarga = totalHarga;
							}
							
							// tabelPenjualan.cell(baris, 6).data(totalHarga);
							refreshTabelPenjualan();
						}
					}

					totalPenjualan();

				} // End if bukan baris input
			} // End if tombol yang ditekan adalah Enter
		}); // End event handler edit data dalam tabel penjualan

		// Event handler tombol Gambar dalam tabel penjualan
		$('#tabelPenjualan').on('click', '#btnGambar', function() {
			$('#gambarBarang').html('<i class="fa fa-spin fa-refresh"></i>');
		
			var baris = tabelPenjualan.row($(this).parent()).index();
			var idBarang = isiNota[baris-1].idBarang;
			var gambarJpg = '<?php echo url_admin()."assets/uploads/' + idBarang + '.jpg";?>';
			var gambarPng = '<?php echo url_admin()."assets/uploads/' + idBarang + '.png";?>';
			var gambarJpeg = '<?php echo url_admin()."assets/uploads/' + idBarang + '.jpeg";?>';
			var gambarDefault = '<?php echo base_url()."assets/image/default.jpg";?>';

			cekGambar(gambarJpg, function(){ $('#gambarBarang').html('<img src="'+gambarJpg+'" style="width: 100%"></img>'); }, function(){  } );
			cekGambar(gambarJpeg, function(){ $('#gambarBarang').html('<img src="'+gambarJpeg+'" style="width: 100%"></img>'); }, function(){  } );
			cekGambar(gambarPng, function(){ $('#gambarBarang').html('<img src="'+gambarPng+'" style="width: 100%"></img>'); }, function(){  } );

			if( $('#gambarBarang').html() == '<i class="fa fa-spin fa-refresh"></i>' ) $('#gambarBarang').html('<img src="'+gambarDefault+'" style="width: 100%"></img>');
		}); // End event handler tombol Gambar dalam tabel penjualan

		// Event handler tombol Hapus dalam tabel penjualan
		$('#tabelPenjualan').on('click', '#btnHapus', function() {
			var baris = tabelPenjualan.row($(this).parent()).index();
			// Hapus data dari isiNota, parameter 1 adalah posisi/indeks, parameter 2 adalah jumlah yg dihapus
			isiNota.splice(baris-1, 1);
			refreshTabelPenjualan();
			totalPenjualan();
		}); // End event handler tombol Hapus dalam tabel penjualan

		// Event handler untuk input diskon total
		$('input[name="diskonTotal"]').keypress(function(event) {
			if(event.keyCode === 13) {
				event.preventDefault();
				totalPenjualan();
			}
		}); // End event handler untuk input diskon total

		// Event handler tombol Cetak Nota
		$('#btnCetakNota').click(function() {
			// Ambil waktu saat ini dan sesuaikan dengan format database
			var today = new Date();
			var d = ( today.getDate() >= 10 ) ? today.getDate() : ( '0' + today.getDate() ); // getDate mengembalikan nilai antara 1-31
			var m = ( (today.getMonth() + 1) >= 10 ) ? today.getMonth() + 1 : ( '0' + (today.getMonth() + 1) ); // getMonth mengembalikan nilai antara 0-11
			var y = today.getFullYear();
			var h = ( today.getHours() >= 10 ) ? today.getHours() : ( '0' + today.getHours() ); // getHours mengembalikan nilai antara 0-23
			var i = ( today.getMinutes() >= 10 ) ? today.getMinutes() : ( '0' + today.getMinutes() ); // getMinutes mengembalikan nilai antara 0-59
			var s = ( today.getSeconds() >= 10 ) ? today.getSeconds() : ( '0' + today.getSeconds() ); // getSeconds mengembalikan nilai antara 0-59
			var today = y + '-' + m + '-' + d + ' ' + h + ':' + i + ':' + s;

			var subTotal = $('#labelSubTotal').text();
			var diskonTotal = $('input[name="diskonTotal"]').val();
			var statusDiskonTotal = ( diskonTotal.indexOf('%') != -1 ) ? 'p' : 'n';
			var totalPenjualan = $('#labelTotalPenjualan').text();
			var nomorInvoice = $('#nomorInvoice').text();
			var idPelanggan = $('input[name="id_pelanggan"]').val();
			var namaPelanggan = $('input[name="cari_pelanggan"]').val();
			var alamatPelanggan = $('input[name="alamat"]').val();
			var teleponPelanggan = $('input[name="telepon"]').val();
			var ekspedisiPelanggan = $('input[name="ekspedisi"]').val();
			var keterangan = $('input[name="keterangan"]').val();
			var isiNotaString = JSON.stringify(isiNota);
			
			simpanNotaLokal(today, subTotal, diskonTotal, statusDiskonTotal, totalPenjualan, nomorInvoice, idPelanggan, namaPelanggan, alamatPelanggan, teleponPelanggan, ekspedisiPelanggan, keterangan, isiNotaString);
			// console.log(isiNota);
		}); // End event handler tombol Cetak Nota
	});
	</script>
</body>
</html>