<?php

require_once __DIR__ . '/vendor/autoload.php';



$cek_waktu_1 = $_GET["cek_waktu_1"];
$cek_waktu_2 = $_GET["cek_waktu_2"];

include('koneksi.php');
$query = mysqli_query($conn, "SELECT 
							pekerjaan.kode_pekerjaan, 
							pekerjaan.tanggal,
							pekerjaan.kode_block,
							pekerjaan.pekerjaan,
							pekerjaan.harga,
							petugas.nama 
							FROM pekerjaan 
							INNER JOIN petugas ON petugas.nip = pekerjaan.nip
							WHERE tanggal BETWEEN '$cek_waktu_1' AND '$cek_waktu_2'");


$total = mysqli_query($conn, "SELECT 
							SUM(harga) AS total 
							FROM pekerjaan 
							INNER JOIN petugas ON petugas.nip = pekerjaan.nip
							WHERE tanggal BETWEEN '$cek_waktu_1' AND '$cek_waktu_2'");


$mpdf = new \Mpdf\Mpdf();
$html = '<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
	body {
		font-size : 13px;
		font-family : arial;
	}
	table {
		margin-bottom : 40px;
		border-collapse: collapse;
	}
	table, th, td {
 		border: 1px solid black;
	}
	tr td, h1 {
		text-align : center;
	}
	p {
		font-size : 16px;
	}
	img {
		width : 750px;
		height : 200px;
	}
	@page {
		margin-header: 5mm;
		margin-footer: 5mm;
	}
	</style>
</head>
<body>
	<img class="logo" src="foto/kop.jpg">

	<h3>LAPORAN PENGELUARAN UPAH PEKERJA</h3>

	<p>Tanggal : '.date("d  F  Y", strtotime($cek_waktu_1)).' - '.date("d  F  Y", strtotime($cek_waktu_2)).'<p>
	<table cellpadding="10" cellspacing="0">
		<tr>
			<th>NO</th>
			<th>Kode pekerjaan</th>
			<th>Tanggal</th>
			<th>Block</th>
			<th>Pekerjaan</th>
			<th>Harga</th>
			<th>Pengawas</th>
		</tr>';

		$i = 1;
		while ($result = mysqli_fetch_assoc($query)) {
			$html .= '<tr>
				<td>'.$i++.'</td>
				<td>'.$result["kode_pekerjaan"].'</td>
				<td>'.date("d - M - Y", strtotime($result["tanggal"])).'</td>
				<td>'.$result["kode_block"].'</td>
				<td>'.$result["pekerjaan"].'</td>
				<td>'."Rp.".number_format($result["harga"]).'</td>
				<td>'.$result["nama"].'</td>
			</tr>';
		}
		while ($hasil = mysqli_fetch_assoc($total)) {
			$html .= '<tr>
				<td colspan="5"><b>TOTAL</b></td>
				<td><b>'."Rp.".number_format($hasil["total"]).'</b></td>
				<td></td>
			</tr>';
		}
$html .= '</table>

<div class="footer">
	<p class="waktu">Pekanbaru, '.date("d F Y").'</p>
	<p class="jabatan">Disetujui</p>
	<br>
	<br>
	<br>
	<p class="name">Juprizal, S.ip.MM</p>
</div>
</body>
</html>';
$mpdf->WriteHTML($html);
$mpdf->Output();

?>
