<?php
session_start();
?>

<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html>
<head>

	<title>CHURDINE CRAFT - Edit Akun</title>
	<link rel="shortcut Icon" href="./img/churdine.jpg">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href="assets/ItemSlider/css/main-style.css" rel="stylesheet" />


</head>
<body>

<!-- Navbar -->
<?php include 'menu.php'; ?><br>

<?php
$id_pelanggan = $_SESSION["pelanggan"]['id_pelanggan'];
$ambil = $koneksi->query("SELECT * FROM pelanggan 
		WHERE id_pelanggan= '$id_pelanggan'");
	$pecah = $ambil->fetch_assoc();
	?>

	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="penl-title">Edit Akun</h3>
					</div>
					<div class="panel-body">
						<form method="post" class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-md-3">Nama</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="nama" value="<?php echo $pecah['nama_pelanggan']?>" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Email</label>
								<div class="col-md-7">
									<input type="email" class="form-control" name="email" value="<?php echo $pecah['email_pelanggan']?>" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Password</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="password" value="<?php echo $pecah['password_pelanggan']?>" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Alamat</label>
								<div class="col-md-7">
									<input type="textarea" class="form-control" name="alamat" value="<?php echo $pecah['alamat_pelanggan']?>" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">No.Telpon/HP</label>
								<div class="col-md-7">
									<input type="number" class="form-control" name="telepon" value="<?php echo $pecah['no_tlp_pelanggan']; ?>" required>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-7 col-md-offset-3">
									<button class="btn btn-primary" name="edit">Ubah Data</button>
								</div>
							</div>
						</form>

						<?php
						// jika ada tombol daftar(ditekan tombil daftar)
						if (isset($_POST["edit"]))
						{
							// mengambil isian nama,email,password,alamt,telepon
							$nama  = $_POST["nama"];
							$email = $_POST["email"];
							$password = $_POST["password"];
							$alamat = $_POST["alamat"];
							$telepon = $_POST["telepon"];

							// cek apakah email sudah digunakan
							$koneksi->query("UPDATE pelanggan SET email_pelanggan='$email', nama_pelanggan='$nama',
								password_pelanggan='$password', alamat_pelanggan='$alamat', no_tlp_pelanggan='$telepon'
								WHERE email_pelanggan='$email'");

							echo "<script>alert('edit data telah berhasil');</script>";
							echo "<script>location='akun.php?halaman=produk';</script>";
						}
						?>

					</div>
				</div>
			</div>
		</div>
	</div>

<br><br><br><br><br>

</body>
</html>