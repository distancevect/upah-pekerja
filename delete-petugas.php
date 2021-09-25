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
		header("location: petugas.php");
	}
	elseif ($_POST["submit"] == "delete") {
		// ambil nilai form
		$nip = htmlentities(strip_tags(trim($_POST["nip"])));

		// filter nilai
		$nip = mysqli_real_escape_string($conn, $nip);

		// jalankan query delete
		$result = mysqli_query($conn, "DELETE FROM petugas WHERE nip='$nip'");

		// cek baris yang terpengaruh dari query delete
		$affected = mysqli_affected_rows($conn);

		// jika ada baris terpengaruh 1 baris
		if ($affected == 1) {
			$pesan = urldecode("Data dengan nip $nip berhasil di delete !");
			header("location: petugas.php?pesan=$pesan");
			
		}
	}
}


 ?>