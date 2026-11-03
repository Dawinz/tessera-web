<!-- begin snippet: js hide: false console: true babel: null -->

<!-- language: lang-js -->
<script>
     function testPdf(){
     
        //send the div to PDF
        html2canvas($("#DIV_ID_HERE"), { // DIV ID HERE
            onrendered: function(canvas) {
                var imgData = canvas.toDataURL('image/png'); 
                var doc = new jsPDF('landscape');
                doc.addImage(imgData, 'PDF', 10, 10);
                doc.save('sample-file.pdf'); //SAVE PDF FILE
            }
        });

    }
	</script>

<!-- language: lang-html -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.debug.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>

    <div id="DIV_ID_HERE">
     
     Test - This div to pdf
     <br><input type="button" value="print" onclick="testPdf();">
    <div>

<!-- end snippet -->
