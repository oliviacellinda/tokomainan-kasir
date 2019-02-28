<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_c_sinkronisasi extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('custom_helper');
        $this->load->model('kasir_m_sinkronisasi');
    }

    public function sinkronisasi_data() {
        if($this->session->id_kasir == '') {
            header('Location: login');
            exit();
        }
        else $this->load->view('kasir_v_sinkronisasi');
    }

    public function sinkronisasi_pelanggan() {
        $pesan = array('done'); // pesan yang akan dikembalikan ke view
        $status_insert_update = array();
        $status_delete = array();

        /* Tambah atau perbarui data pelanggan di database kasir -------------------------------------------------- */
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
            echo json_encode('cant connect');
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
                    array_push($pesan, 'Gagal menambah atau memperbarui ' . count( array_keys($status_insert_update, false) ) . ' data pelanggan.');
                else 
                    write_file('tgl_modifikasi_data_pelanggan_lokal.txt', date('Y-m-d H:i:s'));
            }
        }

        /* Hapus data pelanggan di database kasir -------------------------------------------------- */
        $opts = array(
            'http' => array(
                'header'    => 'Content-type: application/x-www-form-urlencoded'
            )
        );
        $context = stream_context_create($opts);
        $result = @file_get_contents(url_admin().'daftar-pelanggan-dihapus', false, $context);

        if($result === false) {
            echo json_encode('cant connect');
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
                    array_push($pesan, 'Gagal menghapus ' . count( array_keys($status_delete, false) ) . ' data pelanggan.');
            }

            echo json_encode($pesan);
        }
    }

    public function sinkronisasi_barang() {
        $pesan = array('done'); // pesan yang akan dikembalikan ke view
        $status_insert_update = array();
        $status_delete = array();

        /* Tambah atau perbarui data barang di database kasir -------------------------------------------------- */
        $this->load->helper('file');
        $tgl_modifikasi_skrg = read_file('tgl_modifikasi_data_barang_lokal.txt');
 
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
        $result = @file_get_contents(url_admin().'ambil-barang-baru', false, $context);

        if($result === false) {
            echo json_encode('cant connect');
            exit();
        }
        else {
            $result = json_decode($result, true);

            if($result != 'no data') {
                $barang_baru = $result;

                for($i=0; $i<count($barang_baru); $i++) {
                    $baris = array(
                        'id_barang'         => $barang_baru[$i]['id_barang'],
                        'jumlah_dlm_koli'   => $barang_baru[$i]['jumlah_dlm_koli'],
                        'nama_barang'       => $barang_baru[$i]['nama_barang'],
                        'kemasan'           => $barang_baru[$i]['kemasan'],
                        'fungsi'            => $barang_baru[$i]['fungsi'],
                        'harga_jual_1'      => $barang_baru[$i]['harga_jual_1'],
                        'harga_jual_2'      => $barang_baru[$i]['harga_jual_2'],
                        'harga_jual_3'      => $barang_baru[$i]['harga_jual_3'],
                        'harga_jual_4'      => $barang_baru[$i]['harga_jual_4']
                    );
                    $status_insert_update[] = $this->kasir_m_sinkronisasi->perbarui_barang($baris);
                }
                
                if( in_array(0, $status_insert_update) ) 
                    array_push($pesan, 'Gagal menambah atau memperbarui ' . count( array_keys($status_insert_update, false) ) . ' data barang.');
                else 
                    write_file('tgl_modifikasi_data_barang_lokal.txt', date('Y-m-d H:i:s'));
            }
        }

        /* Hapus data barang di database kasir -------------------------------------------------- */
        $opts = array(
            'http' => array(
                'header'    => 'Content-type: application/x-www-form-urlencoded'
            )
        );
        $context = stream_context_create($opts);
        $result = @file_get_contents(url_admin().'daftar-barang-dihapus', false, $context);

        if($result === false) {
            echo json_encode('cant connect');
            exit();
        }
        else {
            $result = json_decode($result, true);

            if($result != 'no data') {
                $daftar_barang_dihapus = $result;
                $id_seluruh_barang = $this->kasir_m_sinkronisasi->id_seluruh_barang();

                for($i=0; $i<count($daftar_barang_dihapus); $i++) $a[] = $daftar_barang_dihapus[$i]['id_barang'];
                for($i=0; $i<count($id_seluruh_barang); $i++) $b[] = $id_seluruh_barang[$i]['id_barang'];

                // array_intersect tidak dapat membandingkan array 2 dimensi
                $data_akan_dihapus = array_intersect($a, $b);

                foreach($data_akan_dihapus as $key=>$value) {
                    $status_delete[] = $this->kasir_m_sinkronisasi->hapus_barang($data_akan_dihapus[$key]);
                }

                if( in_array(0, $status_delete) )
                    array_push($pesan, 'Gagal menghapus ' . count( array_keys($status_delete, false) ) . ' data barang.');
            }

            echo json_encode($pesan);
        }
    }

    public function sinkronisasi_penjualan() {
        $jumlah_error = 0; // jumlah error yang terjadi selama proses, hitungan per laporan penjualan
        $status_update = array();

        $this->load->model('kasir_m_stok');

        $data_penjualan_blm_upload = $this->kasir_m_sinkronisasi->data_penjualan_blm_upload();

        if($data_penjualan_blm_upload != '') {
            for($i=0; $i<count($data_penjualan_blm_upload); $i++) {
                $id_toko[$i] = $this->kasir_m_stok->cek_toko($data_penjualan_blm_upload[$i]['id_kasir']);
                $detail_penjualan_blm_upload[$i] = $this->kasir_m_sinkronisasi->detail_penjualan_blm_upload($data_penjualan_blm_upload[$i]['id_invoice']);
            }
            
            $data = http_build_query(
                array(
                    'laporan_penjualan' => json_encode($data_penjualan_blm_upload),
                    'detail_penjualan'  => json_encode($detail_penjualan_blm_upload),
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

                for($i=0; $i<count($status_laporan); $i++) {
                    // Jika proses tambah data penjualan dan detail, serta perbarui stok berhasil, ubah status penjualan di database kasir
                    if( $status_laporan[$i] == 1 && !(in_array(0, $status_detail[$i])) && !(in_array(0, $status_stok[$i])) ) {
                        $status_update[] = $this->kasir_m_sinkronisasi->perbarui_status_laporan_penjualan($data_penjualan_blm_upload[$i]['id_invoice']);

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
            } // End else untuk ($result !== false)
        } // End if $data_penjualan_blm_upload != ''

        echo json_encode($jumlah_error);
    }
}
?>