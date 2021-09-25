<?php 
if (!isset($_POST["submit"])) {
	header("location: index.php");
}


?>


<!DOCTYPE html>
<html>
<head>
	<title>web admin</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
</head>
<body>
	<div class="container">
		<?php include 'header.php'; ?>


		<!-- Content -->
		<div class="content">
			<div class="judul">
				<h1>Data karyawan</h1>
			</div>
			<div class="halaman">
				<div class="success">
					<?php echo $success; ?>
				</div>
				<div class="navigasi">
					
				</div>
				<div class="table">
					<div class="baris-kolom">
						<tr>
							<td>No</td>
							<td>Foto</td>
							<td>Nip</td>
							<td>Nama</td>
							<td>Jenis kelamin</td>
							<td>Tempat lahir</td>
							<td>Tanggal lahir</td>
							<td>Agama</td>
							<td>Status</td>
							<td>Alamat</td>
							<td>Jabatan</td>
						</tr>
					</div>
						<?php echo "nip : ".$_POST["nip"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "nama : ".$_POST["nama"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "kelamin : ".$_POST["kelamin"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "tanggal lahir : ".$_POST["tanggal"]."-".$_POST["bulan"]."-".$_POST["tahun"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "agama : ".$_POST["agama"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "status : ".$_POST["status"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "alamat : ".$_POST["alamat"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "jabatan : ".$_POST["jabatan"]; ?>
						<?php echo "<br>"; ?>
						<?php echo "foto : ".$_FILES["foto"]["name"]; ?>
						<?php echo "<br>"; ?>
				
				</div>
			</div>
		</div>
	</div>
</body>
</html>