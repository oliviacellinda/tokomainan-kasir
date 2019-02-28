<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if( ! function_exists('url_admin') ) {
    function url_admin() {
        return 'http://www.rajamainan88.com/';
    }
}

if( ! function_exists('umur_stok_barang') ) {
    function umur_stok_barang($string_tanggal) {
        $today = new DateTime(date('Y-m-d'));
        $tanggal = new DateTime($string_tanggal);
        
        $interval = date_diff($today, $tanggal);
        return $interval->days;
        // days adalah properti dari objek DateInterval
        // fungsi date_diff di atas menghasilkan objek DateInterval
    }
}
?>