<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_c_login extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('custom_helper');
        $this->load->model('kasir_m_login');
    }

    public function login() {
        if($this->session->id_kasir != '') {
            header('Location: penjualan');
            die();
        }
        else {
            $this->load->helper('file');
            $tgl_modifikasi_skrg = read_file('tgl_modifikasi_data_kasir_lokal.txt');

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
            $result = @file_get_contents(url_admin().'ambil-kasir-baru', false, $context);

            if($result === false) {
                $this->load->view('kasir_v_login_error');
            }
            else {
                $result = json_decode($result, true);

                if($result != 'no data') {
                    $daftar_kasir_baru = $result;

                    for($i=0; $i<count($daftar_kasir_baru); $i++) {
                        $baris = array(
                            'id_kasir'      => $daftar_kasir_baru[$i]['id_kasir'],
                            'password_kasir'=> $daftar_kasir_baru[$i]['password_kasir'],
                            'id_toko'       => $daftar_kasir_baru[$i]['id_toko'],
                            'nama_toko'     => $daftar_kasir_baru[$i]['nama_toko']
                        );
                        $this->kasir_m_login->perbarui_data_kasir($baris);
                    }

                    write_file('tgl_modifikasi_data_kasir_lokal.txt', date('Y-m-d H:i:s'));
                }

                $opts = array(
                    'http' => array(
                        'header'    => 'Content-type: application/x-www-form-urlencoded'
                    )
                );
                $context = stream_context_create($opts);
                $result = @file_get_contents(url_admin().'daftar-kasir-dihapus', false, $context);

                if($result === false) {
                    $this->load->view('kasir_v_login_error');
                }
                else {
                    $result = json_decode($result, true);

                    if($result != 'no data') {
                        $daftar_kasir_dihapus = $result;
                        $daftar_kasir_lokal = $this->kasir_m_login->daftar_kasir_lokal();

                        if($daftar_kasir_lokal != '') {
                            for($i=0; $i<count($daftar_kasir_dihapus); $i++) $a[] = $daftar_kasir_dihapus[$i]['id_kasir'];
                            for($i=0; $i<count($daftar_kasir_lokal); $i++) $b[] = $daftar_kasir_lokal[$i]['id_kasir'];

                            $kasir_akan_dihapus = array_intersect($a, $b);

                            foreach($kasir_akan_dihapus as $key=>$value) {
                                $this->kasir_m_login->hapus_kasir($kasir_akan_dihapus[$key]);
                            }
                        }
                    }

                    $this->load->view('kasir_v_login');
                }
            }
        }
    }

    public function proses_login() {
        $id_kasir = $this->input->post('username');
        $password_kasir = $this->input->post('password');

        $data = $this->kasir_m_login->login($id_kasir, $password_kasir);
        if($data == '') echo json_encode('no match');
        else {
            $this->session->set_userdata('id_kasir', $id_kasir);
            echo json_encode('found a match');
        }
    }

    public function proses_logout() {
        $this->session->sess_destroy();
        header('Location: login');
        die();
    }
}
?>