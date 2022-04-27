<?php
session_start();
include_once '../assets/conn/dbconnect.php';
$session= $_SESSION['patientSession'];
// $appid=null;
// $appdate=null;
if (isset($_GET['scheduleDate']) && isset($_GET['appid'])) {
	$appdate =$_GET['scheduleDate'];
	$appid = $_GET['appid'];
}
// on b.icPatient = a.icPatient
$res = mysqli_query($con,"SELECT a.*, b.* FROM doctorschedule a INNER JOIN patient b
WHERE a.scheduleDate='$appdate' AND scheduleId=$appid AND b.icPatient=$session");
$userRow=mysqli_fetch_array($res,MYSQLI_ASSOC);


	
//INSERT
if (isset($_POST['appointment'])) {
$patientIc = mysqli_real_escape_string($con,$userRow['icPatient']);
$scheduleid = mysqli_real_escape_string($con,$appid);
$symptom = mysqli_real_escape_string($con,$_POST['symptom']);
$comment = mysqli_real_escape_string($con,$_POST['comment']);
$avail = "notavail";


$query = "INSERT INTO appointment (  patientIc , scheduleId , appSymptom , appComment  )
VALUES ( '$patientIc', '$scheduleid', '$symptom', '$comment') ";

//update table appointment schedule
$sql = "UPDATE doctorschedule SET bookAvail = '$avail' WHERE scheduleId = $scheduleid" ;
$scheduleres=mysqli_query($con,$sql);
if ($scheduleres) {
	$btn= "disable";
} 


$result = mysqli_query($con,$query);
// echo $result;
if( $result )
{
?>
<script type="text/javascript">
alert('Appointment made successfully.');
</script>
<?php
header("Location: patientapplist.php");
}
else
{
	echo mysqli_error($con);
?>
<script type="text/javascript">
alert('Appointment booking fail. Please try again.');
</script>
<?php
header("Location: patientapplist.php");
}
//dapat dari generator end
}

$male="";
$female="";
if ($userRow['patientGender']=='male') {
$male = "checked";
}elseif ($userRow['patientGender']=='female') {
$female = "checked";
}
$single="";
$married="";
$separated="";
$divorced="";
$widowed="";
if ($userRow['patientMaritialStatus']=='single') {
$single = "checked";
}elseif ($userRow['patientMaritialStatus']=='married') {
$married = "checked";
}elseif ($userRow['patientMaritialStatus']=='separated') {
$separated = "checked";
}elseif ($userRow['patientMaritialStatus']=='divorced') {
$divorced = "checked";
}elseif ($userRow['patientMaritialStatus']=='widowed') {
$widowed = "checked";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		
		<title>Make Appoinment</title>
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/default/style.css" rel="stylesheet">
		<link href="assets/css/default/blocks.css" rcel="stylesheet">


		<link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />

	</head>
	<body>
<!-- navigation -->
<nav class="navbar navbar-default " role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="patient.php"><img alt="Brand" src="assets/img/logo.png" height="35px"></a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<ul class="nav navbar-nav">
							<li><a href="patient.php">Beranda</a></li>
							<li><a href="profile.php?patientId=<?php echo $userRow['icPatient']; ?>" >Profil</a></li>
							<li><a href="patientapplist.php?patientId=<?php echo $userRow['icPatient']; ?>">Pendaftaran</a></li>
						</ul>
					</ul>
					
					<ul class="nav navbar-nav navbar-right">

								<li>
									<a href="patientlogout.php?logout">Keluar </a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- navigation -->
		<div class="container">
			<section style="padding-bottom: 50px; padding-top: 50px;">
				<div class="row">
					<!-- start -->
					<!-- USER PROFILE ROW STARTS-->
					<div class="row">
						<div class="col-md-3 col-sm-3">
							
							<div class="user-wrapper">
								<div class="description">
									<h4><?php echo $userRow['patientFirstName']; ?> <?php echo $userRow['patientLastName']; ?></h4>
									<h5> <strong> Pasien </strong></h5>
									<p>
										Mohon isi data disamping dengan benar
									</p>
									<hr />
									<button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="patient/profile.php">Ubah Data</button>
								</div>
							</div>
						</div>
						
						<div class="col-md-9 col-sm-9  user-wrapper">
							<div class="description">
								
								
								<div class="panel panel-default">
									<div class="panel-body">
										
										
										<form class="form" role="form" method="POST" accept-charset="UTF-8">
											<div class="panel panel-default">
												<div class="panel-heading">Informasi Pasien</div>
												<div class="panel-body">
													
													Nama:  <?php echo $userRow['patientFirstName'] ?> <?php echo $userRow['patientLastName'] ?><br>
													No. KTP: <?php echo $userRow['icPatient'] ?><br>
													No. HP <?php echo $userRow['patientPhone'] ?><br>
													Alamat: <?php echo $userRow['patientAddress'] ?>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">Informasi Pendaftaran</div>
												<div class="panel-body">
													Hari: <?php echo $userRow['scheduleDay'] ?><br>
													Tanggal: <?php echo $userRow['scheduleDate'] ?><br>
													Waktu: <?php echo $userRow['startTime'] ?><br>
													Spesialisasi: <?php echo $userRow['endTime'] ?><br>
												</div>
											</div>
											
											<div class="form-group">
												<label for="recipient-name" class="control-label">Keluhan:</label>
												<input type="text" class="form-control" name="symptom" required>
											</div>
											<div class="form-group">
												<label for="message-text" class="control-label">Deskripsi:</label>
												<textarea class="form-control" name="comment" required></textarea>
											</div>
											<div class="form-group">
												<input type="submit" name="appointment" id="submit" class="btn btn-primary" value="Daftar">
											</div>
										</form>
									</div>
								</div>
								
							</div>
							
						</div>
					</div>
					<!-- USER PROFILE ROW END-->
					<!-- Large modal -->
						
						<!-- Modal -->
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Formulir</h4>
									</div>
									<div class="modal-body">
										<!-- form start -->
										<form action="<?php $_PHP_SELF ?>" method="post" >
											<table class="table table-user-information">
												<tbody>
													<tr>
														<td>No. KTP</td>
														<td><?php echo $userRow['icPatient']; ?></td>
													</tr>
													<tr>
														<td>Nama Depan</td>
														<td><input type="text" class="form-control" name="patientFirstName" value="<?php echo $userRow['patientFirstName']; ?>"  /></td>
													</tr>
													<tr>
														<td>Nama Belakang</td>
														<td><input type="text" class="form-control" name="patientLastName" value="<?php echo $userRow['patientLastName']; ?>"  /></td>
													</tr>
													
													<!-- radio button -->
													<tr>
														<td>Status</td>
														<td>
															<div class="radio">
																<label><input type="radio" name="patientMaritialStatus" value="single" <?php echo $single; ?>>Belum Kawin</label>
															</div>
															<div class="radio">
																<label><input type="radio" name="patientMaritialStatus" value="married" <?php echo $married; ?>>Kawin</label>
															</div>
															<div class="radio">
																<label><input type="radio" name="patientMaritialStatus" value="separated" <?php echo $separated; ?>>Cerai</label>
															</div>
															<div class="radio">
																<label><input type="radio" name="patientMaritialStatus" value="divorced" <?php echo $divorced; ?>>Duda</label>
															</div>
															<div class="radio">
																<label><input type="radio" name="patientMaritialStatus" value="widowed" <?php echo $widowed; ?>>Janda</label>
															</div>
														</td>
													</tr>
													<!-- radio button end -->
													<tr>
														<td>Tanggal Lahir</td>
														<!-- <td><input type="text" class="form-control" name="patientDOB" value="<?php echo $userRow['patientDOB']; ?>"  /></td> -->
														<td>
															<div class="form-group ">
																
																<div class="input-group">
																	<div class="input-group-addon">
																		<i class="fa fa-calendar">
																		</i>
																	</div>
																	<input class="form-control" id="patientDOB" name="patientDOB" placeholder="MM/DD/YYYY" type="text" value="<?php echo $userRow['patientDOB']; ?>"/>
																</div>
															</div>
														</td>
														
													</tr>
													<!-- radio button -->
													<tr>
														<td>Jenis Kelamin</td>
														<td>
															<div class="radio">
																<label><input type="radio" name="patientGender" value="male" <?php echo $male; ?>>Pria</label>
															</div>
															<div class="radio">
																<label><input type="radio" name="patientGender" value="female" <?php echo $female; ?>>Wanita</label>
															</div>
														</td>
													</tr>
													<!-- radio button end -->
													
													<tr>
														<td>No. HP</td>
														<td><input type="text" class="form-control" name="patientPhone" value="<?php echo $userRow['patientPhone']; ?>"  /></td>
													</tr>
													<tr>
														<td>Email</td>
														<td><input type="text" class="form-control" name="patientEmail" value="<?php echo $userRow['patientEmail']; ?>"  /></td>
													</tr>
													<tr>
														<td>Alamat</td>
														<td><textarea class="form-control" name="patientAddress"  ><?php echo $userRow['patientAddress']; ?></textarea></td>
													</tr>
													<tr>
														<td>
															<input type="submit" name="submit" class="btn btn-info" value="Perbarui"></td>
														</tr>
													</tbody>
													
												</table>
												
												
												
											</form>
											<!-- form end -->
										</div>
										
									</div>
								</div>
							</div>
							<br /><br/>
						</div>
						
					</div>
					<!-- ROW END -->
				</section>
				<!-- SECTION END -->
			</div>
					<!-- end -->
					<script src="assets/js/jquery.js"></script>
			<script src="assets/js/bootstrap.min.js"></script>
				</body>
			</html>