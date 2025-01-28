<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); // Ubah dari 0 ke E_ALL
ini_set('display_errors', 1);

// database
$config['db'] = array(
    'host' => 'localhost',
    'name' => 'iyamyid_smm',
    'username' => 'iyamyid_smm',
    'password' => 'iyamyid_smm'
);

$conn = mysqli_connect($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['name']);
if (!$conn) {
    die("Koneksi Gagal : Silahkan Refresh Dalam Beberapa Waktu Kedepan Atau Hubungi Admin Jika Masih Tidak Bisa Akses Dalam 5 Menit.");
}
$config['web'] = array(
    'url' => 'https://iya.my.id/' // ex: http://domain.com/
);

// date & time
$date = date("Y-m-d");
$time = date("H:i:s");
require("lib/setting.php");

?>