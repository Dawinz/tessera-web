<style>
    @media (min-width: 576px) {
  .custom-modal-width {
    max-width: 800px; /* Replace with your desired width in px, %, or vw */
    width: 100%;
  }
   @media print {
      body > *:not(#print-area) { display: none; }
      #print-area { display: block !important; }
    }
}
</style>
<div class="modal user_manifest_charges fade bs-example-modal-center" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-modal-width">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="user_manifest_header"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
<button style="margin-bottom:20px;" class="btn btn-primary" onclick="printTable()">🖨️ Print Summary</button>
<br>
<div id="user_summary_table"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
 <script>
    function printTable() {
      const table = document.getElementById('print-area').outerHTML;

      // Use a hidden iframe - avoids popup blockers inside modals
      let iframe = document.getElementById('print-iframe');
      if (!iframe) {
        iframe = document.createElement('iframe');
        iframe.id = 'print-iframe';
        iframe.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:0;height:0;border:none;';
        document.body.appendChild(iframe);
      }

      const doc = iframe.contentDocument || iframe.contentWindow.document;
      doc.open();
      doc.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Table Print</title>
          <style>
            body { font-family: sans-serif; padding: 30px; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 10px 16px; text-align: left; }
            th { background: #4f46e5; color: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            tr:nth-child(even) { background: #f9f9f9; }
          </style>
        </head>
        <body>${table}</body>
        </html>
      `);
      doc.close();

      iframe.onload = () => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
      };
    }
  </script>
