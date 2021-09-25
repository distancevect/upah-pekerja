<?php 
// buat koneksi ke database
include('koneksi.php');

// cek koneksi
if (!$conn) {
	die('koneksi gagal');
}
elseif ($conn) {

	// cek apakah tombol submit dari halaman absen.php tidak tervalidasi
	if ($_POST["submit"] != "delete") {
		// paksa user kembali ke halaman absen
		header("location: pekerjaan.php");
	}
	elseif ($_POST["submit"] == "delete") {
		// ambil nilai form
		$kode_pekerjaan = htmlentities(strip_tags(trim($_POST["kode_pekerjaan"])));

		// filter nilai
		$kode_pekerjaan = mysqli_real_escape_string($conn, $kode_pekerjaan);

		// jalankan query delete
		$result = mysqli_query($conn, "DELETE FROM pekerjaan WHERE kode_pekerjaan='$kode_pekerjaan'");

		// cek baris yang terpengaruh dari query delete
		$affected = mysqli_affected_rows($conn);

		// jika ada baris terpengaruh 1 baris
		if ($affected == 1) {
			$pesan = urldecode("Data dengan kode pekerjaan $kode_pekerjaan berhasil di delete !");
			header("location: pekerjaan.php?pesan=$pesan");
			
		}
	}
}


 ?>