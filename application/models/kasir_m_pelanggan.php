<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_m_pelanggan extends CI_Model {
    
    public function __construct() {
        $this->db = $this->load->database('dbkasir', true);
    }

    public function lihat_pelanggan() {
        // $this->db->where('level', 4);
        $query = $this->db->get('pelanggan');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    // public function edit_pelanggan($id_pelanggan, $nama_kolom, $nilai_baru) {
    //     $dbkasir = $this->load->database('dbkasir', TRUE);
        
    //     $dbkasir->set($nama_kolom, $nilai_baru);
    //     $dbkasir->where('id_pelanggan', $id_pelanggan);
    //     $dbkasir->update('pelanggan');
    // }
}
?>