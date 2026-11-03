<style>
div {
  spanst-style-type: none;
  margin-top: -8px;
  padding: 0;
  overflow: hidden;
  
}

span {
  float: left;
}

.available {
color:black;
padding:12px;
margin-left:4px;
width:20px;
background-image: url("armchair2.png");
background-size: contain;
background-repeat: no-repeat;
}
.center {
color:black;
padding:12px;
margin-left:4px;
width:20px;
background-size: contain;
background-repeat: no-repeat;
}
.seat_no{
position:relative;
bottom:6px;
font-size:14px;
text-align: center;

}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
 <?php
 function writeMsg() {

$x = 0;

while($x <= 11) {
$alphabet = range('A', 'Z');

$letter =  $alphabet[$x]; 


	
$li1 =  array('<div><span class="first available"><span class="seat_no">'.$letter.'1</span></span>', '<span class="first available"><span class="seat_no">'.$letter.'2</span></span>','<span class="first center"><span class="seat_no">-</span></span>','<span class="first available"><span class="seat_no">'.$letter.'3</span></span>','<span class="first available"><span class="seat_no">'.$letter.'4</span></span></div>');
$li1 =  implode($li1);
 ${'li1' . 'x'}= $li1;


echo "$li1<br>";
  $x++;
}
}

writeMsg();

?> 


<script>
$(document).ready(function(){
$(".first").click(function(){
	name = $(this).text();
	alert(name);
})
});
</script>