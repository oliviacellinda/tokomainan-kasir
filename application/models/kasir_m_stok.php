<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_m_stok extends CI_Model {

    public function __construct() {
        $this->db = $this->load->database('dbkasir', true);
    }

    public function cek_toko($id_kasir) {
        $this->db->select('id_toko');
        $this->db->where('id_kasir', $id_kasir);
        $query = $this->db->get('kasir');

        if($query->num_rows() > 0) {
            return $query->row_array();
        }
    } 
}
?>