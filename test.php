<?php 

// $date =  date("d-m-Y");

// $urutan = substr($date, 8, 2);

// echo $urutan;

// $nama = 0;

// echo trim($nama);

// include('koneksi.php');



// // menentukan page yang sedang aktif
// $page = isset($_GET['page']) ? $_GET['page'] : 1;
// // jumlah data/baris yang ingin ditampilkan perhalaman
// $limit = 10; // jumlah data = 10 
// // menentukan start data yang ditampilkan (offset)
// $offset = ($page - 1) * $limit; // awal data di mulai dari index 0
// // query select data digunakan untuk menghitung total data
// $query = mysqli_query($conn,"SELECT * FROM absen");
// $total_data = mysqli_num_rows($query); // jumlah data 26
// $total_pages = ceil($total_data/$limit); // di bulatkan total halaman 3
// // jumlah nomor yang ingin ditampilkan sebelum dan sesudah page aktif
// $total_number = 2;
// // mencari start halaman tampil sebelum page aktif
// $start_number = ($page > $total_number) ? $page - $total_number : 1;
// // mencari end halaman tampil sebelum page aktif
// $end_number = ($page < ($total_pages - $total_pages)) ? $page + $total_number : $total_pages;


// $query = mysqli_query($conn,"SELECT * FROM absen LIMIT $limit OFFSET $offset ");

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>AJAX</title>
 </head>
 <body>
	<table>
		<tr>
			<td>NIP </td>
		 	<td><input type="text" name="nip" id="nip" onkeyup="autofil()"></td>
		</tr>
		<tr>
			<td>Nama </td>
			<td><input type="text" name="nama" id="nama"></td>
		</tr>
		<tr><td>Agama </td>
			<td><input type="text" name="agama" id="agama"></td>
		</tr>
	</table>
	<script src="jquery-3.5.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript">
		function autofil() {
			var nip = $("#nip").val();
			$.ajax({
				url	: 'autofill_ajax.php',
				data : 'nip='+nip,
			})
			.success(function(data) {
				var json = data,
				obj = JSON.parse(json);
				$("#nama").val(obj.nama);
				$("#agama").val(obj.agama);

			});
			// alert('asd');
		}
	</script>
 </body>
 </html>


