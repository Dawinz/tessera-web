
            <div class="table-responsive">
                                        <table class="table table-hover table-nowrap mb-0 align-middle table-check">
                                            <thead class="bg-light">
                                                <tr>
                                                 
                                                    <th>Ticket no</th>
                                                    <th>Name</th>
                                                    <th>Seat</th>
                                                    </tr>
												</thead>
												<tbody>
<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';

$print_id = $_POST['print_id'];
include '../connect.php';


$select14=mysqli_query($db,"SELECT id,seat_no,transfer_to,firstname,lastname  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND (print_id='$print_id')");
$select_num14=mysqli_num_rows($select14);
if($select_num14 !=0){
   	WHILE($get134=mysqli_fetch_array($select14)){
    	    $id =$get134['id'];
			$seat_no =$get134['seat_no'];
			$transfer_to =$get134['transfer_to'];
			$firstname =$get134['firstname'];
			$lastname =$get134['lastname'];
			?>
		<tr>
	
		<td><?php echo $id;?></td>
		<td><?php echo "$firstname $lastname";?></td>
		<td><?php echo "$transfer_to to $seat_no"?></td>
		</tr>	
			<?php
			
}
}else{

}



?>
</tbody>
<tfoot>
<tr >
<td></td>
<td>	

<a  target='_blank' href="print_multiple_ticket.php?print_id=<?php echo $print_id;?>"><button type="button" class="btn btn-soft-success" ><i class="uil uil-print"></i> Print</button></a>
</td>
<td></td>
</tr>
</tfoot>
</table>
