<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
h2 {
color: #444;
font-family: sans-serif;
}
#wrap {
width: 750px;
margin: 0 auto;
min-height: 500px;
margin-top: -20px;
}
button {
width: 100%;
padding: 10px;
color: #444;
background-color: #f4f4f4;
cursor: pointer;
border: none;
margin: 2px;
}
button:hover {
background-color: #DDD;
}
button:active {
background-color: #777;
}
footer {
font-family: sans-serif;
font-size: 12px;
}
footer p {
color: #aaa;
}
footer p a {
color: yellowgreen;
text-decoration: none;
}
.title {
font-size: 57px;
font-weight: bold;
color: #555;
margin-bottom: 0;
}
.subtitle {
font-size: 14px;
color: #999;
margin-top: -10px;
margin-bottom: 20px;
}
pre {
padding: 10px;
background-color: #EEE;
}
.comment {
color: #AAA;
}
.string {
color: teal;
}
.tag {
color: blue;
}
.attr {
color: green;
}
.button_download {
display: block;
font-family: sans-serif;
cursor: pointer;
width: 60px;
padding: 10px 30px 10px 30px;
font-weight: bold;
font-size: 20px;
text-decoration: none;
text-align: center;
margin: 0 auto;
background-color: #444;
color: #EEE;
transition: all 0.3s;
-moz-transition: all 0.3s;
-webkit-transition: all 0.3s;
-o-transition: all 0.3s;
-ms-transition: all 0.3s;
}
.button_download:hover {
width: 480px;
background-color: yellowgreen;
color: #444;
}
.step {
color: #AAA;
}
</style>
<title>jQuery NotifIt Plugin Examples</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/notifIt.js"></script>
<link rel="stylesheet" type="text/css" href="css/notifIt.css">
</head>
<body>
<div id="wrap">
<p class="title">jQuery NotifIt Plugin Examples</p>
<div class="jquery-script-ads"><script type="text/javascript"><!--
google_ad_client = "ca-pub-2783044520727903";
/* jQuery_demo */
google_ad_slot = "2780937993";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript" src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
<button onclick="not1()">NotifIt! 1 (Default position, Success)</button>
<button onclick="not2()">NotifIt! 2 (Centered, error)</button>
<button onclick="not3()">NotifIt! 3 (Left, Warning)</button>
<button onclick="not4()">NotifIt! 4 (Centered, Info, Custom size)</button>
<button onclick="not5()">NotifIt! 5 (Centered, error, custom size, click-to-dismiss)</button>
<button onclick="not6()">NotifIt! 6 (Centered, warning, custom opacity)</button>
</div>
<script>
        function not1(){
            notif({
				msg: "<b>Success:</b> In 5 seconds i'll be gone",
				type: "success"
			});
        }
        function not2(){
            notif({
				msg: "<b>Oops!</b> A wild error appeared!",
				type: "error",
				position: "center"
			});
        }
        function not3(){
            notif({
				type: "warning",
				msg: "<b>Warning:</b> Be patient my friend.",
				position: "left"
			});
        }
        function not4(){
            notif({
				type: "info",
				msg: "<b>Info: </b>Some info here.",
				width: "all",
				height: 100,
				position: "center"
			});
        }
        function not5(){
            notif({
				type: "error",
				msg: "<b>Error: </b>This error will stay here until you click it.",
				position: "center",
				width: 500,
				height: 60,
				autohide: false
			});
        }
		function not6(){
			notif({
				type: "warning",
				msg: "Opacity is cool!",
				position: "center",
				opacity: 0.8
			});
		}
    </script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
