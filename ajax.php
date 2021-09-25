<?php 
// koneksi php
include('koneksi.php');

// ambil nilai di url
$tanggal_1 = $_GET["tanggal_1"];
$bulan_1 = $_GET["bulan_1"];
$tahun_1 = $_GET["tahun_1"];


$tanggal_2 = $_GET["tanggal_2"];
$bulan_2 = $_GET["bulan_2"];
$tahun_2 = $_GET["tahun_2"];

// gabungkan nilai string
$cek_tanggal_1 = $tahun_1."-".$bulan_1."-".$tanggal_1;

$cek_tanggal_2 = $tahun_2."-".$bulan_2."-".$tanggal_2;




// buat query untuk cek tanggal pekerjaan
$query = mysqli_query($conn, "SELECT 
							pekerjaan.kode_pekerjaan, 
							pekerjaan.tanggal,
							pekerjaan.pekerjaan,
							pekerjaan.harga,
							petugas.nama 
							FROM pekerjaan 
							INNER JOIN petugas ON petugas.nip = pekerjaan.nip
							WHERE tanggal BETWEEN '$cek_tanggal_1' AND '$cek_tanggal_2'");


// tampilkan hasil query
while ($result = mysqli_fetch_assoc($query)) {
	$data [] = $result;
}

// masukan semua nilai ke dalam object
echo json_encode($data);























 ?>