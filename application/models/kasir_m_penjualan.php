<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_m_penjualan extends CI_Model {

    public function __construct() {
        $this->db = $this->load->database('dbkasir', true);
    }

    public function nomor_invoice($id_kasir) {
        $this->db->where('id_kasir', $id_kasir);
        return $this->db->count_all_results('laporan_penjualan');
    }

    public function cari_pelanggan($keyword) {
        $this->db->select('id_pelanggan, nama_pelanggan, alamat_pelanggan, telepon_pelanggan, level, ekspedisi');
        $this->db->from('pelanggan');
        $this->db->where('level', 4);
        $this->db->like('nama_pelanggan', $keyword);
        $query = $this->db->get();

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function lihat_barang() {
        $query = $this->db->get('barang');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function lihat_nama_toko($id_kasir) {
        $this->db->select('nama_toko');
        $this->db->where('id_kasir', $id_kasir);
        $query = $this->db->get('kasir');

        if($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function tambah_laporan_penjualan($input_laporan_penjualan) {
        $this->db->insert('laporan_penjualan', $input_laporan_penjualan);

        if($this->db->affected_rows() >= 0) return 1;
        else return 0;
    }

    public function tambah_detail_penjualan($detail_penjualan) {
        $this->db->insert('detail_penjualan', $detail_penjualan);

        if($this->db->affected_rows() >= 0) return 1;
        else return 0;
    }

    public function perbarui_status_penjualan_lokal($id_invoice) {
        $this->db = $this->load->database('dbkasir', TRUE);

        $this->db->set('status_upload', 1);
        $this->db->where('id_invoice', $id_invoice);
        $this->db->update('laporan_penjualan');
    }
}
?>