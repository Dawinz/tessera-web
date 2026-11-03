<!DOCTYPE html>
<html lang="en">
<head>
<title>Datatable Example PHP MySQLi</title>

<link href="datatable/bootstrap.min.css" rel="stylesheet">
<link href="datatable/dataTables.bootstrap.css" rel="stylesheet">
<link href="datatable/dataTables.responsive.css" rel="stylesheet">

<style>
	.mytable{
		margin-left:50px;
		margin-top:30px;
		width:1000px;
	}
</style>
</head>
<body>
	<br>
	<div class="mytable">
	<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
		<thead>
            <tr>
				<th>UserId</th>
                <th>Firstname</th>
				<th>Lastname</th>
				<th>Address</th>
                <th>Birthday</th>
            </tr>
        </thead>
		<tbody>
			<?php
				include('conn.php');
				$query=mysqli_query($conn,"select * from `user`");
				while($row=mysqli_fetch_array($query)){
					?>
					<tr>
						<td><?php echo $row['userid']; ?></td>
						<td><?php echo $row['firstname']; ?></td>
						<td><?php echo $row['lastname']; ?></td>
						<td><?php echo $row['address']; ?></td>
						<td><?php echo $row['birthdate']; ?></td>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>
	</div>
	<script src="datatable/jquery.min.js"></script>
	<script src="datatable/jquery.dataTables.min.js"></script>
    <script src="datatable/dataTables.bootstrap.min.js"></script>
    <script src="datatable/dataTables.responsive.js"></script>
	<script src="datatable/bootstrap.min.js"></script>
	<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
</body>
</html>