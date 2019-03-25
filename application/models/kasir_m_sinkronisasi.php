<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir_m_sinkronisasi extends CI_Model {

    public function __construct() {
        $this->db = $this->load->database('dbkasir', true);
    }

    public function perbarui_pelanggan($data) {
        $query = 'INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, alamat_pelanggan, telepon_pelanggan, maks_utang, level, ekspedisi) ';
        $query .= 'VALUES ("'.$data['id_pelanggan'].'","'.$data['nama_pelanggan'].'","'.$data['alamat_pelanggan'].'","'.$data['telepon_pelanggan'].'",'.$data['maks_utang'].',"'.$data['level'].'", "'.$data['ekspedisi'].'") ';
        $query .= 'ON DUPLICATE KEY UPDATE nama_pelanggan="'.$data['nama_pelanggan'].'", alamat_pelanggan="'.$data['alamat_pelanggan'].'", telepon_pelanggan="'.$data['telepon_pelanggan'].'", maks_utang='.$data['maks_utang'].', level="'.$data['level'].'", ekspedisi="'.$data['ekspedisi'].'"';
        $this->db->query($query);

        if($this->db->affected_rows() >= 0) return 1;
        else return 0;
    }

    public function id_seluruh_pelanggan() {
        $this->db->select('id_pelanggan');
        $query = $this->db->get('pelanggan');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function hapus_pelanggan($id_pelanggan) {
        $this->db->where('id_pelanggan', $id_pelanggan);
        $this->db->delete('pelanggan');

        if($this->db->affected_rows() >= 0) return 1;
        else return 0;
    }

    public function perbarui_barang($data) {
        $query = 'INSERT INTO barang (id_barang, nama_barang, jumlah_dlm_koli, kemasan, fungsi, harga_jual_1, harga_jual_2, harga_jual_3, harga_jual_4) ';
        $query .= 'VALUES ("'.$data['id_barang'].'", "'.$data['nama_barang'].'", '.$data['jumlah_dlm_koli'].', "'.$data['kemasan'].'", "'.$data['fungsi'].'", '.$data['harga_jual_1'].', '.$data['harga_jual_2'].', '.$data['harga_jual_3'].', '.$data['harga_jual_4'].') ';
        $query .= 'ON DUPLICATE KEY UPDATE id_barang="'.$data['id_barang'].'", nama_barang="'.$data['nama_barang'].'", jumlah_dlm_koli='.$data['jumlah_dlm_koli'].', kemasan="'.$data['kemasan'].'", fungsi="'.$data['fungsi'].'", harga_jual_1='.$data['harga_jual_1'].', harga_jual_2='.$data['harga_jual_2'].', harga_jual_3='.$data['harga_jual_3'].', harga_jual_4='.$data['harga_jual_4'];
        $this->db->query($query);

        if($this->db->affected_rows() >= 0) return 1;
        else return 0;
    }

    public function id_seluruh_barang() {
        $this->db->select('id_barang');
        $query = $this->db->get('barang');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function hapus_barang($id_barang) {
        $this->db->where('id_barang', $id_barang);
        $this->db->delete('barang');

        if($this->db->affected_rows() >= 0) return 1;
        else return 0;
    }

    public function data_penjualan_blm_upload() {
        $this->db->select('id_invoice, tgl_invoice, id_kasir, sub_total_penjualan, diskon_penjualan, status_diskon_penjualan, total_penjualan, id_pelanggan, nama_pelanggan, alamat_pelanggan, telepon_pelanggan, ekspedisi, keterangan');
        $this->db->where('status_upload', 0);
        $query = $this->db->get('laporan_penjualan');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function detail_penjualan_blm_upload($id_invoice) {
        $this->db->where('id_invoice', $id_invoice);
        $query = $this->db->get('detail_penjualan');

        if($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function perbarui_status_laporan_penjualan($id_invoice) {
        $this->db->set('status_upload', 1);
        $this->db->where('id_invoice', $id_invoice);
        $this->db->update('laporan_penjualan');
    }
}
?>