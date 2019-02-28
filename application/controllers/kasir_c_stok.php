<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_c_stok extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('custom_helper');
    }

    public function stok() {
        if($this->session->id_kasir == '') {
            header('Location: login');
            die();
        }
        else $this->load->view('kasir_v_stok');
    }

    public function lihat_stok() {
        $this->load->model('kasir_m_stok');

        // Ambil data hanya di toko tempat kasir berada
        $id_toko = $this->kasir_m_stok->cek_toko($this->session->id_kasir);

        $data = http_build_query(
            array(
                'id_toko' => $id_toko['id_toko']
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
        $result = @file_get_contents(url_admin().'lihat-stok-barang', false, $context);

        if($result === false) {
            echo json_encode('cant connect');
            exit();
        }
        else {
            $result = json_decode($result, true);

            if($result != 'no data') {
                $daftar_stok_barang = $result;

                for($i=0; $i<count($daftar_stok_barang); $i++) {
                    $daftar_stok_barang[$i]['umur_barang'] = umur_stok_barang($daftar_stok_barang[$i]['tgl_modifikasi_data']);
                }

                echo json_encode($daftar_stok_barang);
            }
            else echo json_encode('no data');
        }
    }
}
?>