<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'kasir_c_login/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['login']                       = 'kasir_c_login/login';
$route['proses-login']                = 'kasir_c_login/proses_login';
$route['logout']                      = 'kasir_c_login/proses_logout';

$route['penjualan']                   = 'kasir_c_penjualan/penjualan';
$route['nomor-invoice']               = 'kasir_c_penjualan/nomor_invoice';
$route['cari-pelanggan']              = 'kasir_c_penjualan/cari_pelanggan';
$route['penjualan-tambah-pelanggan']  = 'kasir_c_penjualan/penjualan_tambah_pelanggan';
$route['simpan-nota-lokal']           = 'kasir_c_penjualan/simpan_nota_lokal';
$route['simpan-nota-pusat']           = 'kasir_c_penjualan/simpan_nota_pusat';

$route['stok']                        = 'kasir_c_stok/stok';
$route['lihat-stok']                  = 'kasir_c_stok/lihat_stok';

$route['pelanggan']                   = 'kasir_c_pelanggan/pelanggan';
$route['lihat-pelanggan']             = 'kasir_c_pelanggan/lihat_pelanggan';
$route['tambah-pelanggan']            = 'kasir_c_pelanggan/tambah_pelanggan';
// $route['edit-pelanggan']              = 'kasir_c_pelanggan/edit_pelanggan';

$route['sinkronisasi-data']           = 'kasir_c_sinkronisasi/sinkronisasi_data';
$route['sinkronisasi-pelanggan']      = 'kasir_c_sinkronisasi/sinkronisasi_pelanggan';
$route['sinkronisasi-barang']         = 'kasir_c_sinkronisasi/sinkronisasi_barang';
$route['sinkronisasi-penjualan']      = 'kasir_c_sinkronisasi/sinkronisasi_penjualan';
