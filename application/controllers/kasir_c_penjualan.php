<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_c_penjualan extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('custom_helper');
        $this->load->model('kasir_m_penjualan');
    }

    public function penjualan() {
        if($this->session->id_kasir == '') {
            header('Location: login');
            die();
        }
        else {
            $data['daftar_barang'] = $this->kasir_m_penjualan->lihat_barang();
            $data['nama_toko'] = $this->kasir_m_penjualan->lihat_nama_toko($this->session->id_kasir);
            
            $this->load->view('kasir_v_penjualan', $data);
        }
    }

    public function nomor_invoice() {
        $data = $this->kasir_m_penjualan->nomor_invoice($this->session->id_kasir);
        $data = $data + 1; // Untuk no urut inovice saat ini
        echo json_encode($data);
    }

    public function cari_pelanggan() {
        $keyword = $this->input->post('term');

        $data = $this->kasir_m_penjualan->cari_pelanggan($keyword);

        if($data != '') {
			// Ubah data sesuai dengan format autocomplete jquery-ui, jquery-ui membutuhkan informasi label dan value
			for($i=0; $i<count($data); $i++) {
				$response[$i]['label']      = $data[$i]['nama_pelanggan'];
                $response[$i]['value']      = $data[$i]['nama_pelanggan'];
                $response[$i]['id']         = $data[$i]['id_pelanggan'];
                $response[$i]['alamat']     = $data[$i]['alamat_pelanggan'];
                $response[$i]['telepon']    = $data[$i]['telepon_pelanggan'];
                $response[$i]['level']      = $data[$i]['level'];
                $response[$i]['ekspedisi']  = $data[$i]['ekspedisi'];
			}
			echo json_encode($response);
        }
        else {
            // Jika tidak ada data, tampilkan tulisan tambah pelanggan untuk memunculkan form pelanggan
            $response[0]['label'] = 'Pelanggan belum terdaftar';
            $response[0]['value'] = 'Pelanggan belum terdaftar';
            echo json_encode($response);
        }
    }

    public function penjualan_tambah_pelanggan() {
        $id_pelanggan = $this->session->id_kasir . strtolower( substr($this->input->post('nama_pelanggan'),0,3) ) . date('dmyHi');

        $data = http_build_query( 
            array(
                'id_pelanggan'        => $id_pelanggan,
                'nama_pelanggan'      => $this->input->post('nama_pelanggan'),
                'alamat_pelanggan'    => $this->input->post('alamat_pelanggan'),
                'ekspedisi'           => $this->input->post('ekspedisi'),
                'telepon_pelanggan'   => $this->input->post('telepon_pelanggan'),
                'maks_utang'          => 0,
                'level'               => 4,
                'tgl_modifikasi_data' => date('Y-m-d H:i:s')
            )
        );
        $opts = array(
            'http' => array(
                'method'    => 'POST',
                'header'    => 'Content-type: application/x-www-form-urlencoded',
                'content'   => $data
            )
        );
        $context = stream_context_create($opts);
        $result = @file_get_contents(url_admin().'kasir-tambah-pelanggan', false, $context);

        if($result === false) {
            echo json_encode('cant connect');
            exit();
        }
        else {
            if($result == '0') {
                echo json_encode('fail');
                exit();
            }
            else {
                $pesan = array(
                    'id_pelanggan'  => $id_pelanggan,
                    'level'         => 4,
                    'flag_error'    => 0 // untuk menandai ada tidaknya error selama proses sinkronisasi
                );

                $this->load->model('kasir_m_sinkronisasi');

                $status_insert_update = array();
                $status_delete = array();

                /* Sinkronisasi data pelanggan: insert atau update data baru dari database pusat */
                $this->load->helper('file');
                $tgl_modifikasi_skrg = read_file('tgl_modifikasi_data_pelanggan_lokal.txt');
        
                $data = http_build_query(
                    array(
                        'tgl_modifikasi_lokal' => $tgl_modifikasi_skrg
                    )
                );
                $opts = array(
                    'http' => array(
                        'method'    => 'POST',
                        'header'    => 'Content-type: application/x-www-form-urlencoded',
                        'content'   => $data
                    )
                );
                $context = stream_context_create($opts);
                $result = @file_get_contents(url_admin().'ambil-pelanggan-baru', false, $context);

                if($result === false) {
                    echo json_encode('cant connect to sync');
                    exit();
                }
                else {
                    $result = json_decode($result, true);

                    if($result != 'no data') {
                        $pelanggan_baru = $result;

                        for($i=0; $i<count($pelanggan_baru); $i++) {
                            $baris = array(
                                'id_pelanggan'      => $pelanggan_baru[$i]['id_pelanggan'],
                                'nama_pelanggan'    => $pelanggan_baru[$i]['nama_pelanggan'],
                                'alamat_pelanggan'  => $pelanggan_baru[$i]['alamat_pelanggan'],
                                'ekspedisi'         => $pelanggan_baru[$i]['ekspedisi'],
                                'telepon_pelanggan' => $pelanggan_baru[$i]['telepon_pelanggan'],
                                'maks_utang'        => $pelanggan_baru[$i]['maks_utang'],
                                'level'             => $pelanggan_baru[$i]['level']
                            );
                            $status_insert_update[] = $this->kasir_m_sinkronisasi->perbarui_pelanggan($baris);
                        }
                        
                        if( in_array(0, $status_insert_update) ) 
                            $pesan['flag_error'] = 1;
                        else 
                            write_file('tgl_modifikasi_data_pelanggan_lokal.txt', date('Y-m-d H:i:s'));
                    }
                }

                /* Sinkronisasi data pelanggan: delete data yang sudah dihapus dari database pusat */
                $opts = array(
                    'http' => array(
                        'header'    => 'Content-type: application/x-www-form-urlencoded'
                    )
                );
                $context = stream_context_create($opts);
                $result = @file_get_contents(url_admin().'daftar-pelanggan-dihapus', false, $context);
        
                if($result === false) {
                    echo json_encode('cant connect to sync');
                    exit();
                }
                else {
                    $result = json_decode($result, true);
        
                    if($result != 'no data') {
                        $daftar_pelanggan_dihapus = $result;
                        $id_seluruh_pelanggan = $this->kasir_m_sinkronisasi->id_seluruh_pelanggan();
        
                        for($i=0; $i<count($daftar_pelanggan_dihapus); $i++) $a[] = $daftar_pelanggan_dihapus[$i]['id_pelanggan'];
                        for($i=0; $i<count($id_seluruh_pelanggan); $i++) $b[] = $id_seluruh_pelanggan[$i]['id_pelanggan'];
        
                        // array_intersect tidak dapat membandingkan array 2 dimensi
                        $data_akan_dihapus = array_intersect($a, $b);
        
                        foreach($data_akan_dihapus as $key=>$value) {
                            $status_delete[] = $this->kasir_m_sinkronisasi->hapus_pelanggan($data_akan_dihapus[$key]);
                        }
        
                        if( in_array(0, $status_delete) )
                            $pesan['flag_error'] = 1;
                    }
                }

                echo json_encode($pesan);
            }
        }
    }

    public function simpan_nota_lokal() {
        $input_laporan_penjualan = array(
            'id_invoice'              => $this->input->post('nomorInvoice'),
            'tgl_invoice'             => $this->input->post('today'),
            'id_kasir'                => $this->session->id_kasir,
            'sub_total_penjualan'     => $this->input->post('subTotal'),
            'diskon_penjualan'        => $this->input->post('diskonTotal'),
            'status_diskon_penjualan' => $this->input->post('statusDiskonTotal'),
            'total_penjualan'         => $this->input->post('totalPenjualan'),
            'id_pelanggan'            => $this->input->post('idPelanggan'),
            'nama_pelanggan'          => $this->input->post('namaPelanggan'),
            'alamat_pelanggan'        => $this->input->post('alamatPelanggan'),
            'telepon_pelanggan'       => $this->input->post('teleponPelanggan'),
            'ekspedisi'               => $this->input->post('ekspedisiPelanggan'),
            'keterangan'              => $this->input->post('keterangan'),
            'status_upload'           => 0
        );        
        $detail_penjualan = json_decode($this->input->post('isiNotaString'), true); // true untuk mengubah object menjadi associative array

        $status_laporan = $this->kasir_m_penjualan->tambah_laporan_penjualan($input_laporan_penjualan);
        $status_detail = array();

        if($status_laporan == 1) {
            for($i=0; $i<count($detail_penjualan); $i++) {
                $baris = array(
                    'id_invoice'            => $this->input->post('nomorInvoice'),
                    'id_barang'             => $detail_penjualan[$i]['idBarang'],
                    'nama_barang'           => $detail_penjualan[$i]['namaBarang'],
                    'jumlah_dlm_koli'       => $detail_penjualan[$i]['jmlDlmKoli'],
                    'kemasan'               => $detail_penjualan[$i]['kemasan'],
                    'jumlah_barang'         => $detail_penjualan[$i]['jumlah'],
                    'harga_barang'          => $detail_penjualan[$i]['harga'],
                    'diskon_barang'         => $detail_penjualan[$i]['diskon'],
                    'status_diskon_barang'  => $detail_penjualan[$i]['statusDiskon'],
                    'total_harga_barang'    => $detail_penjualan[$i]['totalHarga']
                );
                $status_detail[] = $this->kasir_m_penjualan->tambah_detail_penjualan($baris);
            }
        }

        if( $status_laporan == 1 && !(in_array(0, $status_detail)) ) echo json_encode('success');
        else return json_encode('fail');
    }
    
    public function simpan_nota_pusat() {
        /* Catatan:
        | Format data dalam fungsi ini mengikuti format sinkronisasi data laporan penjualan
        | karena hanya menggunakan kembali yang sudah dibuat
        */

        $this->load->model('kasir_m_stok');
        $id_toko_temp = $this->kasir_m_stok->cek_toko($this->session->id_kasir);
        $id_toko = array($id_toko_temp);

        $laporan_penjualan[0] = array(
            'id_invoice'              => $this->input->post('nomorInvoice'),
            'tgl_invoice'             => $this->input->post('today'),
            'id_kasir'                => $this->session->id_kasir,
            'sub_total_penjualan'     => $this->input->post('subTotal'),
            'diskon_penjualan'        => $this->input->post('diskonTotal'),
            'status_diskon_penjualan' => $this->input->post('statusDiskonTotal'),
            'total_penjualan'         => $this->input->post('totalPenjualan'),
            'id_pelanggan'            => $this->input->post('idPelanggan'),
            'nama_pelanggan'          => $this->input->post('namaPelanggan'),
            'alamat_pelanggan'        => $this->input->post('alamatPelanggan'),
            'telepon_pelanggan'       => $this->input->post('teleponPelanggan'),
            'ekspedisi'               => $this->input->post('ekspedisiPelanggan'),
            'keterangan'              => $this->input->post('keterangan')
        );
        $isiNota = json_decode($this->input->post('isiNotaString'), true);
        for($i=0; $i<count($isiNota); $i++) {
            $detail_penjualan[0][$i] = array(
                'id_invoice'            => $this->input->post('nomorInvoice'),
                'id_barang'             => $isiNota[$i]['idBarang'],
                'nama_barang'           => $isiNota[$i]['namaBarang'],
                'jumlah_dlm_koli'       => $isiNota[$i]['jmlDlmKoli'],
                'kemasan'               => $isiNota[$i]['kemasan'],
                'jumlah_barang'         => $isiNota[$i]['jumlah'],
                'harga_barang'          => $isiNota[$i]['harga'],
                'diskon_barang'         => $isiNota[$i]['diskon'],
                'status_diskon_barang'  => $isiNota[$i]['statusDiskon'],
                'total_harga_barang'    => $isiNota[$i]['totalHarga']
            );
        }

        $data = http_build_query(
            array(
                'laporan_penjualan' => json_encode($laporan_penjualan),
                'detail_penjualan'  => json_encode($detail_penjualan),
                'id_toko'           => json_encode($id_toko)
            )
        );
        $opts = array(
            'http' => array(
                'method'    => 'POST',
                'header'    => 'Content-type: application/x-www-form-urlencoded',
                'content'   => $data
            )
        );
        $context = stream_context_create($opts);
        $result = @file_get_contents(url_admin().'tambah-laporan-penjualan', false, $context);

        if($result === false) {
            echo json_encode('cant connect');
            exit();
        }
        else {
            $status = json_decode($result, true);
            $status_laporan = $status[0];
            $status_detail = $status[1];
            $status_stok = $status[2];
            $status_update = array();
            $jumlah_error = 0;

            $this->load->model('kasir_m_sinkronisasi');

            for($i=0; $i<count($status_laporan); $i++) {
                // Jika proses tambah data penjualan dan detail, serta perbarui stok berhasil, ubah status penjualan di database kasir
                if( $status_laporan[$i] == 1 && !(in_array(0, $status_detail[$i])) && !(in_array(0, $status_stok[$i])) ) {
                    $status_update[] = $this->kasir_m_sinkronisasi->perbarui_status_laporan_penjualan($laporan_penjualan[$i]['id_invoice']);

                    /* Catatan:
                    | Untuk saat ini, tidak akan ada aksi yang dilakukan jika status_upload pada laporan penjualan dalam database kasir gagal.
                    | Jika gagal, status tetap bernilai 0 dan akan diperbarui saat sinkronisasi data berikutnya berhasil.
                    | Update stok akan dobel dan belum ada penyelesaian untuk kasus ini.
                    | Lihat: c_sinkronisasi.php -> function tambah_laporan_penjualan
                    */
                }

                // Jika ada yang gagal, hitung berapa banyak kesalahan yang terjadi
                if( $status_laporan[$i] == 0 || in_array(0, $status_detail[$i]) || in_array(0, $status_stok[$i]) ) {
                    $jumlah_error = $jumlah_error + 1;
                }
            }

            echo json_encode($jumlah_error);
        }
    }
}
?>