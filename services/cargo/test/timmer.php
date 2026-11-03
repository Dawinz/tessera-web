<div class="countdown"></div>
		 <script src="../js/vendor/jquery-1.12.4.min.js"></script>
<input type="submit" id="start" value="start">
<input type="submit" id="stop"  value="stop">
<div class="tet"></div>
<script>

let start = document.querySelector('#start');
let stop  = document.querySelector('#stop');

start.addEventListener('click', function() {
	let timerId = setInterval(function() {
		let date = new Date;
		timer2 = (date.getMinutes() + ' ' + date.getSeconds()); 
		$(".tet").text(timer2);
	}, 1000);
});

stop.addEventListener('click', function() {
	clearInterval(timerId);
});
</script>