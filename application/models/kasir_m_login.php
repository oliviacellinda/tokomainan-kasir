<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_m_login extends CI_Model {

    public function __construct() {
        $this->db = $this->load->database('dbkasir', true);
    }

    public function login($id_kasir, $password_kasir) {
        $this->db->where('id_kasir', $id_kasir);
        $this->db->where('password_kasir', $password_kasir);
        $query = $this->db->get('kasir');

        if($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function perbarui_data_kasir($data) {
        $query = 'INSERT INTO kasir (id_kasir, password_kasir, id_toko, nama_toko) ';
        $query .= 'VALUES (?, ?, ?, ?) ';
        $query .= 'ON DUPLICATE KEY UPDATE ';
        $query .= 'id_kasir=VALUES(id_kasir), password_kasir=VALUES(password_kasir), id_toko=VALUES(id_toko), nama_toko=VALUES(nama_toko)';
        $this->db->query($query, $data);
    }

    public function daftar_kasir_lokal() {
        $this->db->select('id_kasir');
        $query = $this->db->get('kasir');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function hapus_kasir($id_kasir) {
        $this->db->where('id_kasir', $id_kasir);
        $this->db->delete('kasir');
    }
}
?>