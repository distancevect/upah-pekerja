<?php 
// buat koneksi ke database
include('koneksi.php');

// cek koneksi
if (!$conn) {
	die('koneksi gagal');
}
elseif ($conn) {

	// cek apakah tombol submit dari halaman block.php tidak tervalidasi
	if ($_POST["submit"] != "delete") {
		// paksa user kembali ke halaman block
		header("location: block.php");
	}
	elseif ($_POST["submit"] == "delete") {
		// ambil nilai form
		$kode_block = htmlentities(strip_tags(trim($_POST["kode_block"])));

		// filter nilai
		$kode_block = mysqli_real_escape_string($conn, $kode_block);

		// jalankan query delete
		$result = mysqli_query($conn, "DELETE FROM block WHERE kode_block='$kode_block'");

		// cek baris yang terpengaruh dari query delete
		$affected = mysqli_affected_rows($conn);

		// jika ada baris terpengaruh 1 baris
		if ($affected == 1) {
			$pesan = urldecode("Data dengan kode block $kode_block berhasil di delete !");
			header("location: block.php?pesan=$pesan");
			
		}
	}
}


 ?>