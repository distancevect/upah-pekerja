<?php
// buat koneksi
$conn = mysqli_connect("localhost", "root", "", "pekerja");

// buat query pekerjaan
$query_pekerjaan = mysqli_query($conn, "SELECT*FROM pekerjaan");

// buat query block
$query_block = mysqli_query($conn, "SELECT*FROM block");

// buat query hitung jumlah kodeblock
$query_num_block = mysqli_query($conn, "SELECT*FROM block WHERE kode_block LIKE '%HH%'");

$result_num_block = mysqli_num_rows($query_num_block);

echo $result_num_block;

if (isset($_POST["submit"])) {
	$kode_block = htmlentities(strip_tags(trim($_POST["kode_block"])));
}
elseif (!isset($_POST["submit"])) {
	$kode_block = "";
}


  ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		table {
			border-collapse: collapse;
		}
		th, td	{
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<form action="" method="post">
		<select name="kode_block">
			<option value="">PILIH BLOCK</option>
			<?php while ($result_block = mysqli_fetch_assoc($query_block)) : ?>
				<option value="<?php echo $result_block["kode_block"]; ?>"<?php if ($kode_block === $result_block["kode_block"]) {
					echo "selected";
				} ?>><?php echo $result_block["kode_block"]; ?></option>
			<?php endwhile ?>
		</select>
		<button name="submit" value="cek block !">cek</button>
	</form>
	<br>
	<br>
	<table cellpadding="5" cellspacing="0">
		<tr>
			<th>NO</th>
			<th>Kode Pekerjaan</th>
			<th>Kode Block</th>
			<th>Tanggal</th>
			<th>Pekerjaan</th>
		</tr>
		<?php $i=1; ?>
		<?php for ($i=1; $i <= $result_num_block; $i++) : ?>
		<?php while ($result = mysqli_fetch_assoc($query_pekerjaan)) : ?>
		<?php if ($result["kode_block"] === $kode_block) : ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $result["kode_pekerjaan"]; ?></td>
				<td><?php echo $result["kode_block"]; ?></td>
				<td><?php echo $result["tanggal"]; ?></td>
				<td><?php echo $result["pekerjaan"]; ?></td>
			</tr>
		<?php endif ?>
		<?php $i++; ?>
		<?php endwhile ?>
		<?php endfor ?>
		
	</table>
</body>
</html>