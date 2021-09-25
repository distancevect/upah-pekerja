<?php 
// buat koneksi ke database
include('koneksi.php');

// cek koneksi
if (!$conn) {
	die('koneksi gagal');
}
elseif ($conn) {

	// cek apakah tombol submit dari halaman jabatan.php tidak tervalidasi
	if ($_POST["submit"] != "delete") {
		// paksa user kembali ke halaman jabatan
		header("location: jabatan.php");
	}
	elseif ($_POST["submit"] == "delete") {
		// ambil nilai form
		$kode_jabatan = htmlentities(strip_tags(trim($_POST["kode_jabatan"])));

		// filter nilai
		$kode_jabatan = mysqli_real_escape_string($conn, $kode_jabatan);

		// jalankan query delete
		$result = mysqli_query($conn, "DELETE FROM jabatan WHERE kode_jabatan='$kode_jabatan'");

		// cek baris yang terpengaruh dari query delete
		$affected = mysqli_affected_rows($conn);

		// jika ada baris terpengaruh 1 baris
		if ($affected == 1) {
			$pesan = urldecode("Data dengan kode jabatan $kode_jabatan berhasil di delete !");
			header("location: jabatan.php?pesan=$pesan");
			
		}
	}
}


 ?>