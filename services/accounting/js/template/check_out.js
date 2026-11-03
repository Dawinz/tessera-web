$(document).ready(function(){
	num_row_checked = $("#num_row").text();
	num_row_checked  = parseInt(num_row_checked)+1;
	const entires = [];
	$("#checkAll").click(function(){
		$(".check_buttons").show("fast" );
    $('input:checkbox').not(this).prop('checked', this.checked);
	
	let text = "";

for (let i = 1; i < num_row_checked; i++) {
	id_number = $('.checked'+i).attr('id');
	
 entires.push(id_number);
}

document.getElementById("selected_checked").innerHTML = entires;	
});

const new_selected_array = [];
$(".checked_signle").click(function(){
		$(".check_buttons").show("fast" );

	checked_signle_no = $(this).attr('id');
	selected_checked = $("#selected_checked").text();
	let selected_arry =  selected_checked.split(",");
	
let isExist = false;

for( let i = 0; i < selected_arry.length; i++ ) {
  	if( selected_arry[i] === checked_signle_no ) {
    	isExist = true;
    	break;
  	}
}

var return_ans = isExist;
if(return_ans == true){
	const index = selected_arry.indexOf(checked_signle_no);
const x = selected_arry.splice(index, 1);
$("#selected_checked").text(selected_arry);
}else{
	selected_checked = $("#selected_checked").text();
	if(selected_checked===""){
		let selected_arry =[];
	}else{
			let selected_arry =  selected_checked.split(",");

	}
	selected_arry.push(checked_signle_no);
	$("#selected_checked").text(selected_arry);

}
});

});