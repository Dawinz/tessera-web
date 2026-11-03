
const final_info = [];
const final_info_read = [];
$(document).on('keyup', '.r_no', function () {
  r_no_val = $(this).val();
  if (r_no_val.match(/[a-z]/i)) {

    str_r_no = r_no_val.slice(0, -1);
    $(this).val(str_r_no);

  } else {
    r_no_val_no = (r_no_val.length);
    if (r_no_val_no == 11) {
      str_r_no = r_no_val.slice(0, -1);
      $(this).val(str_r_no);
    }
  }
});

$(document).on('keyup', '.sender_no', function () {
  s_no_val = $(this).val();
  if (s_no_val.match(/[a-z]/i)) {

    str_s_no = s_no_val.slice(0, -1);
    $(this).val(str_s_no);

  } else {
    s_no_val_no = (s_no_val.length);
    if (s_no_val_no == 11) {
      str_s_no = s_no_val.slice(0, -1);
      $(this).val(str_s_no);
    }
  }
});
$('#submit_button').on('keypress', function (e) {
  if (e.which == 13) {

  }
});
$('#rgform').on('submit', function (event) {
  event.preventDefault();
  s_no = $("#s_no").val();
  s_name = $("#firstname").val();

  $("#s_no_new").val(s_no);
  $("#s_name_new").val(s_name);

  var error = '';


  s_no_val_no = (s_no.length);
  if ((s_no_val_no < 9) || (s_no_val_no > 11)) {
    error += "<li>Please Enter Phone number at  Row</li>";

    $("#s_no").css("border", "1px solid red");
  } else {
    $("#s_no").css("border", "1px solid #E2E5E8");
  }

  if (s_name == "") {
    $("#firstname").css("border", "1px solid red");

    error += "<li>Please Sender name</li>";


  } else {
    $("#firstname").css("border", "1px solid #E2E5E8");

  }




  count = 1;

  $('.r_firstname').each(function () {
    my_advc_id = $(this).attr('id');
    my_advc_id = my_advc_id.replace(/\D/g, '');

    if ($(this).val() == '') {

      error += "<li>Please Fill Reciever firstname, on place no: " + count + " Row</li>";
      $("#r_firstname" + my_advc_id + "").css("border", "1px solid red");

    } else {

      $("#r_firstname" + my_advc_id + "").css("border", "1px solid #E2E5E8");
    }

    count = count + 1;

  });


  count = 1;

  $('.r_no').each(function () {
    r_no_val = $(this).val();
    r_no_val_no = (r_no_val.length);
    my_advc_id = $(this).attr('id');
    my_advc_id = my_advc_id.replace(/\D/g, '');

    if ((r_no_val_no < 9) || (r_no_val_no > 11)) {
      error += "<li>Please Fill Firstname" + count + " Row</li>";
      $("#r_no" + my_advc_id + "").css("border", "1px solid red");
    } else {
      $("#r_no" + my_advc_id + "").css("border", "1px solid #E2E5E8");
    }
    count = count + 1;


  });






  count = 1;
  $("select[name='routs[]']").each(function () {

    if ($(this).val() == 'Choose Route') {



      my_advc_id = $(this).attr('id');
      my_advc_id = my_advc_id.replace(/\D/g, '');
      error += "<li>Please Fill Firstname" + count + " Row</li>";
      $(".route" + my_advc_id + "").css("border", "1px solid red");

    } else {

      $(".route" + my_advc_id + "").css("border", "1px solid #E2E5E8");
    }

    count = count + 1;

  });

  count = 1;
  $("select[name='status_em[]']").each(function () {

    my_advc_id = $(this).attr('id');
    my_advc_id = my_advc_id.replace(/\D/g, '');

    if ($(this).val() == 'Payment Status') {



      error += "<li>Please Fill Firstname" + count + " Row</li>";
      $(".status_em" + my_advc_id + "").css("border", "1px solid red");

    } else {
      $(".status_em" + my_advc_id + "").css("border", "1px solid #E2E5E8");
    }

    count = count + 1;

  });




  count = 1;

  $('.quantity').each(function () {
    my_advc_id = $(this).attr('id');
    my_advc_id = my_advc_id.replace(/\D/g, '');

    if ($(this).val() == '') {

      error += "<li>Please Fill Firstname" + count + " Row</li>";

      $("#quantity" + my_advc_id + "").css("border", "1px solid red");

    } else {

      $("#quantity" + my_advc_id + "").css("border", "1px solid #E2E5E8");
    }

    count = count + 1;

  });


  count = 1;

  $('.amount_fees').each(function () {
    my_advc_id = $(this).attr('id');
    my_advc_id = my_advc_id.replace(/\D/g, '');

    if ($(this).val() == '') {

      error += "<li>Please Fill Firstname" + count + " Row</li>";
      $("#amount_new" + my_advc_id + "").css("border", "1px solid red");

    } else {
      $("#amount_new" + my_advc_id + "").css("border", "1px solid #E2E5E8");

      if (error == '') {

        status_type = $("#status_em" + my_advc_id + " option:selected").text();
        route_name = $("#trip" + my_advc_id + " option:selected").text();
        amount_pin = $(this).val();
        amount_with_status = "<tr style='border-bottom:1px solid grey;border-top:1px solid grey;'><td>" + route_name + "</td><td>" + status_type + "</td><td>" + amount_pin + "</td></tr>";
        final_info_read.push(amount_with_status);
        amount_pin = amount_pin.replace(/,/g, "");
        final_info.push(amount_pin);
      } else {


      }





    }

    count = count + 1;

  });


  var total = 0;
  for (var i = 0; i < final_info.length; i++) {
    total += final_info[i] << 0;
  }



  if (error == '') {

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger"

      },
      buttonsStyling: false
    });
    swalWithBootstrapButtons.fire({
      title: "<strong>Clarification</strong>",
      icon: "info",
      html: "<table class='table'style='width:100%;'><tr style='border-bottom:1px solid grey;'><th>Route</th><th>Status</th><th>Amount</th></tr> " + final_info_read + "<tfoot><td></td><td><strong>Total</strong></td><td><strong>" + total + "</strong></td></tfoot></table>",

      icon: "warning",
      showCancelButton: true,
      focusConfirm: false,

      confirmButtonText: `
   <i class="uil uil-check me-2"></i> Proceed
  `,
      cancelButtonText: `
    <i class="uil uil-times me-2"></i> Cancel
  `,
    }).then((result) => {
      if (!result.isConfirmed) {

        final_info.length = 0;
        final_info_read.length = 0;
      } else {

        var form_data = $(this).serializeArray();
        var scode = $("#scode").val();
        var s_no = $("#s_no").val();

        form_data.push({ name: 'scode', value: scode });
        form_data.push({ name: 's_no', value: s_no });


        $.ajax({

          url: "insert_script.php",

          method: "POST",
          dataType: "json",
          data: form_data,

          beforeSend: function () {

            $("#submit_button").attr("disabled", true);
            $("#submit_button").val("inserting....");
          },

          success: function (data) {
          

            if (data.success) {
              const jsonText = JSON.stringify(data.data);
              var json_text = jsonText;

              printerName = "POS80-2";
              LabelprinterName = "CD410Label";
              //receipt(jsonText,printerName);
              lable(jsonText, LabelprinterName);
              //lable(jsonText);
             //$("#result_holder").html(receipt(jsonText));
            }
           
            $("#submit_button").attr("disabled", false);
            $("#submit_button").val("Register");
            final_info.length = 0;
            final_info_read.length = 0;
            //$("#rgform").trigger("reset");
            if (window.TesseraStates) {
              TesseraStates.toastOk("Cargo booked", data.success ? "Shipment registered successfully." : "Saved.");
            } else {
              alertify.success("Registered");
              alertify.set('notifier', 'position', 'bottom-right');
            }

            $("#insert").attr("disabled", false);
            $("#saving").text("Save");
            if ($.fn.DataTable && $.fn.DataTable.isDataTable("#empTable")) {
              $("#empTable").DataTable().ajax.reload();
            }
            //window.open("print_ticket_mulitple.php?id=" + data);


            //$('#item_table').find('tr:gt(5)').remove();
            //$("#firstname").val("");
            //$("#s_no").val("");






          }
        })
      }
    });


  }
  else {
    if (window.TesseraStates) {
      TesseraStates.toastWarn("Missing information", "Fill all required cargo fields.");
    } else {
      Swal.fire({
        title: "Blank",
        text: "Fill all information",
        icon: "error"
      });
    }
  }
function tesseraBrowserPrint(html) {
  const frame = document.createElement('iframe');
  frame.setAttribute('title', 'Print luggage');
  frame.style.position = 'fixed';
  frame.style.width = '720px';
  frame.style.height = '960px';
  frame.style.left = '0';
  frame.style.top = '0';
  frame.style.border = '0';
  frame.style.background = '#fff';
  document.body.appendChild(frame);
  const doc = frame.contentDocument || frame.contentWindow.document;
  doc.open();
  doc.write(html);
  doc.close();
  setTimeout(function () {
    try {
      frame.contentWindow.focus();
      frame.contentWindow.print();
    } catch (e) {}
  }, 400);
}

async function lable(jsonText, printerName) {
  const data = JSON.parse(jsonText);
  var receiptHtml = `
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      background: #e9e9e9;
      color: #000;
      font-family: Arial, Helvetica, sans-serif;
    }

    .label {
      position: relative;
      width: 144mm;       /* matches the 150mm roll with a 3mm safety margin each side */
      height: 150mm;
      margin: 0 auto;
      padding: 2.5mm;
      overflow: hidden;
      background: #fff;
    }

    .brand {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 1mm;
      padding-bottom: 1.8mm;
      border-bottom: .55mm solid #000;
      margin-bottom: 2mm;
    }

    .brand-name {
      font-size: 6mm;
      line-height: .85;
      font-weight: 900;
      letter-spacing: 0;
    }

    .brand-sub {
      font-size: 2.9mm;
      line-height: 1;
      font-weight: 800;
    }

    .tag-chip {
      border: .45mm solid #000;
      border-radius: 999px;
      padding: 1.2mm 2mm;
      font-size: 3mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
    }

    .destination {
      margin-top: 0;
      border: .7mm solid #000;
      border-radius: 2mm;
      overflow: hidden;
      background: #fff;
    }

    .destination-label {
      display: block;
      padding: 1.3mm 2mm;
      font-size: 3.2mm;
      line-height: 1;
      font-weight: 900;
      text-align: center;
      text-transform: uppercase;
      text-decoration: underline;
    }

    .destination-name {
      display: block;
      padding: 2mm;
      font-size: 6.4mm;
      line-height: .9;
      font-weight: 900;
      text-align: center;
      letter-spacing: 0;
    }

    .route {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      align-items: center;
      gap: 1.5mm;
      margin: 1.6mm 0;
      font-size: 3.25mm;
      line-height: 1;
      font-weight: 900;
      text-align: center;
    }

    .arrow {
      display: inline-grid;
      place-items: center;
      width: 5mm;
      height: 5mm;
      border: .45mm solid #000;
      border-radius: 50%;
      background: #fff;
      font-size: 3.8mm;
      line-height: 1;
    }

    .ticket {
      display: grid;
      grid-template-columns: 18mm 1fr;
      border: .55mm solid #000;
      border-radius: 1.7mm;
      overflow: hidden;
      margin-bottom: 1.8mm;
     border:right:2px solid black;
    }

    .ticket span:first-child {
      display: grid;
      place-items: center;
     border-right:2px solid black;
      color: black;
      font-size: 3.2mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
    }

    .ticket span:last-child {
      padding: 1.6mm 2mm;
      font-size: 7mm;
      line-height: 1;
      font-weight: 900;
      text-align: center;
      background: #fff;
    }

    .barcode-box {
      padding: 1.6mm 1.8mm 1.2mm;
      border: .45mm solid #000;
      border-radius: 1.4mm;
      margin-bottom: 2.4mm;
      background: #fff;
    }

    .barcode-box b {
      display: block;
      margin-bottom: .8mm;
      font-size: 2.55mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
      text-align: center;
    }

    .barcode-box img {
      display: block;
      width: 100%;
      height: 12mm;
      object-fit: fill;
      image-rendering: pixelated;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.6mm;
      margin-bottom: 0;
    }

    .info {
      min-height: 10.5mm;
      padding: 1.2mm;
      border: .4mm solid #000;
      border-radius: 1.4mm;
      background: #fff;
    }

    .info b {
      display: block;
      margin-bottom: 1.2mm;
      font-size: 2.35mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
    }

    .info strong {
      display: block;
      font-size: 3.2mm;
      line-height: 1.15;
      font-weight: 900;
      word-break: break-word;
    }

    .bottom {
      display: grid;
      grid-template-columns: 23mm 1fr;
      gap: 2mm;
      align-items: stretch;
      margin: 0 0 1.7mm;
    }

    .qr-box {
      display: grid;
      place-items: center;
      border: .45mm solid #000;
      border-radius: 1.5mm;
      padding: 1.1mm;
      background: #fff;
    }

    .qr-box img {
      width: 19mm;
      height: 19mm;
      display: block;
      image-rendering: pixelated;
    }

    .mini-panel {
      display: grid;
      gap: 1.2mm;
    }

    .pieces {
      display: grid;
      grid-template-columns: 1fr 14mm;
      align-items: center;
      border: .45mm solid #000;
      border-radius: 1.5mm;
      overflow: hidden;

    }

    .pieces span {
      padding: 1.3mm;
      font-size: 3.1mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;

    }

    .pieces strong {
      display: grid;
      place-items: center;
      align-self: stretch;

      color: black;
      font-size: 4.8mm;
      line-height: 1;
      font-weight: 900;
      white-space: nowrap;
    }

    .scan-note {
      padding: 1.3mm;
      border: .4mm dashed #000;
      border-radius: 1.3mm;
      background: #fff;
      font-size: 2.55mm;
      line-height: 1.22;
      font-weight: 800;
      text-align: center;
      text-transform: uppercase;
    }

    .footer {
      position: static;
      margin-top: 1.8mm;
      padding: 1.3mm 1mm 0;
      border-top: .55mm solid #000;
      font-size: 2.55mm;
      line-height: 1.3;
      font-weight: 800;
      text-align: center;
      background: #fff;
    }

    .footer span {
      display: inline;
      margin-top: 0;
    }

    .footer span + span::before {
      content: " | ";
    }

    .label-body {
      display: grid;
     grid-template-columns: 72mm minmax(0, 1fr);
      gap: 2mm;
      align-items: start;
    }

    .side-panel {
      display: grid;
      gap: 1.7mm;
    }
  </style>
</head>
<body class="body_lable_class">
`;

  data.forEach(item => {
    qty = item.quantity;
    const data = JSON.parse(jsonText);
count = 0;
for (let i = 0; i < qty; i++) {
count ++;
      const route_name = item.route_name;
      const [from, to] = route_name.split(/\s+to\s+/i);
 receiptHtml+= `
  <main class="label">
    <header class="brand">
      <div>
        <div class="brand-name">SR CLASSICCOACH</div>
      </div>
       <div class="tag-chip">lUGGAGE TAG</div>

    </header>

    <div class="label-body">
      <div>
        <section class="destination">
          <span class="destination-label">Destination</span>
          <span class="destination-name">${to.toUpperCase()}</span>
        </section>

        <section class="route" aria-label="Route">
          <span>${from.toUpperCase()}</span>
          <span class="arro">➞</span>
          <span>${to.toUpperCase()}</span>
        </section>

        <section class="ticket" aria-label="Ticket number">
          <span>CODE</span>
          <span>${item.code}</span>
        </section>

        <section class="barcode-box" aria-label="Barcode for ticket ${item.code}">
          <b>Barcode Scan</b>
          <img src="/cargo/barcode/uploads/${item.code}.png" alt="Barcode for ticket ${item.code}">
        </section>
      </div>

      <div class="side-panel">
        <section class="bottom">
          <div class="qr-box">
            <img src="/cargo/qrcode/uploads/${item.code}.png" alt="QR code for luggage label ${item.code}">
          </div>
          <div class="mini-panel">
            <div class="pieces">
              <span>Pieces</span>
              <strong>${count} / ${item.quantity}</strong>
            </div>
            <div class="scan-note">Scan before loading and delivery</div>
          </div>
        </section>

        <section class="info-grid">
          <div class="info">
            <b>Receiver</b>
            <strong>${item.r_name.toUpperCase()}</strong>
          </div>
          <div class="info">
            <b>Receiver Phone</b>
            <strong>${item.r_no}</strong>
          </div>
          <div class="info">
            <b>Sender</b>
            <strong>${item.s_name.toUpperCase()}</strong>
          </div>
          <div class="info">
            <b>WT</b>
            <strong>${item.weight}${item.metric}</strong>
          </div>
        </section>
      </div>
    </div>

    <footer class="footer">
      SR Classic Transport LTD
      <span>ilala, lindi and Iringa</span>
      <span>+255 682 756 699</span>
      <span>operations@srclassiccoach.co.tz</span>
    </footer>
  </main>
`;
}
 });

  receiptHtml += `
</body>
</html>
`;

  // ---- Render off-screen (full Chrome rendering, so Grid/Flexbox and
  //      the QR/barcode images look exactly like they did in print
  //      preview before) ----
  const stage = document.createElement('div');
  stage.style.position = 'fixed';
  stage.style.left = '-99999px';
  stage.style.top = '0';
  document.body.appendChild(stage);

  const iframe = document.createElement('iframe');
  iframe.style.width = '600px';
  iframe.style.height = '10px';
  iframe.style.border = '0';
  stage.appendChild(iframe);

  return new Promise((resolve, reject) => {
    iframe.onload = async () => {
      try {
        const doc = iframe.contentDocument;

        // wait for every barcode/QR <img> to actually finish loading,
        // otherwise they'll rasterize blank
        const images = Array.from(doc.images);
        await Promise.all(images.map(img => img.complete ? Promise.resolve() : new Promise(res => {
          img.onload = img.onerror = res;
        })));

        // rasterize each .label separately, so every copy becomes its
        // own page instead of one giant stacked image
        const labelEls = Array.from(doc.querySelectorAll('.label'));
        const printData = [];

        for (const labelEl of labelEls) {
          const canvas = await html2canvas(labelEl, {
            scale: 3,               // sharper output on thermal printers
            backgroundColor: '#ffffff',
            useCORS: true
          });
          printData.push({
            type: 'pixel',
            format: 'image',
            flavor: 'base64',
            data: canvas.toDataURL('image/png').split(',')[1]
          });
        }

        let printed = false;
        try {
          if (typeof qz !== 'undefined' && qz.websocket && !qz.websocket.isActive()) {
            await qz.websocket.connect({ host: ['localhost'], retries: 0, delay: 0 });
          }
          if (typeof qz !== 'undefined' && qz.websocket && qz.websocket.isActive()) {
            const config = qz.configs.create(printerName, {
              size: { width: 144, height: 96 },
              units: 'mm',
              margins: 0,
              orientation: 'landscape',
              scaleContent: true,
              rasterize: true
            });
            await qz.print(config, printData);
            printed = true;
          }
        } catch (printErr) {
          printed = false;
        }
        if (!printed) {
          tesseraBrowserPrint(receiptHtml);
        }

        document.body.removeChild(stage);
        resolve();
      } catch (err) {
        document.body.removeChild(stage);
        tesseraBrowserPrint(receiptHtml);
        resolve();
      }
    };

    iframe.contentDocument.open();
    iframe.contentDocument.write(receiptHtml);
    iframe.contentDocument.close();
  });
}

async function receipt(jsonText, printerName) {
  const data = JSON.parse(jsonText);
  var receiptHtml = `
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SR Classic Receipt DL60433O</title>
 <style>
    @page {
      size: 80mm auto;
      margin: 0;
    }

    * {
      box-sizing: border-box;
      max-width: 100%;
        box-sizing: border-box;
      /* force the printer to actually render background colors/fills
         instead of silently dropping them to save ink */
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
      color-adjust: exact;
    }

    .body_class {


      background: #e9e9e9;
      color: #000;
      font-family: Arial, Helvetica, sans-serif;
    }

    .receipt {
    width: 74mm;
      max-width: 74mm;
      margin: 0 auto;
      padding: 2mm;
      background: #fff;
      color: #000;
      overflow: hidden;
    }
    .brand {
      display: block;
      text-align: center;
      margin-bottom: 3.5mm;
    }

    .brand-name {
    font-size: 6.0mm;
      line-height: .85;
      font-weight: 900;
      letter-spacing: 0;
    }
   .brand-logo {
      display: block;
      width: 28mm;
      height: auto;
      margin: 0 auto 1.5mm;
    }
    .brand-sub {
      margin-left: 11mm;
      font-size: 3.4mm;
      line-height: 1;
      font-weight: 700;
    }

    .ticket-box {
      border: .55mm solid #000;
      border-radius: 2mm;
      padding: 2.0mm 2.1mm 2.2mm;
      margin-bottom: 3mm;
      display: grid;
      grid-template-columns: auto 1fr;
      align-items: center;
      gap: 3mm;
    }

    .ticket-label {
      font-size: 3.25mm;
      font-weight: 800;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .ticket-number {
      font-size: 6.2mm;
      line-height: 1;
      font-weight: 900;
      text-align: right;
      letter-spacing: .2mm;
      white-space: nowrap;
    }

    .rule {
      border: 0;
      border-top: .55mm solid #000;
      margin: 0 0 3.4mm;
    }

    .route-title {
      text-align: center;
      font-size: 3.2mm;
      line-height: 1;
      font-weight: 900;
      margin: 0 0 2mm;
    }

    .route-card {
      border: .55mm solid #000;
      border-radius: 1.7mm;
      display: grid;
      grid-template-columns: 1fr 1fr;
      margin-bottom: 4mm;
      overflow: hidden;
    }

    .route-card > div {
      padding: 2.3mm 2.5mm 3mm;
      min-height: 13mm;
    }

    .route-card > div + div {
      border-left: .45mm solid #000;
    }

    .label {
      display: block;
      font-size: 3.15mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
      margin-bottom: 3mm;
    }

    .value {
      font-size: 3mm;
      line-height: 1.1;
      font-weight: 900;
    }

    .perforation {
      position: relative;
      height: 6mm;
      margin: 0 -5.4mm 3.5mm;
      border-top: .4mm dashed #aaa;
    }

    .perforation::before,
    .perforation::after {
      content: "";
      position: absolute;
      top: -2.6mm;
      width: 4.8mm;
      height: 4.8mm;
      border: .45mm solid #000;
      border-radius: 50%;
      background: #fff;
    }

    .perforation::before {
      left: 4.8mm;
    }

    .perforation::after {
      right: 2mm;
    }

    .chips {
      display: flex;
      justify-content: space-between;
      margin-bottom: 3.5mm;
    }

    .chip {
      background: #000;
      color: #fff;
      border-radius: 1mm;
      padding: 1.15mm 2.2mm;
      font-size: 3.1mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4mm;
      margin-bottom: 4mm;
    }

    .field {
      margin-bottom: 3.2mm;
    }

    .field .label {
      margin-bottom: 1mm;
    }

    .field .value {
      font-size: 4mm;
    }

    .item-card {
      border: .45mm solid #000;
      border-radius: 1.7mm;
      padding: 2.4mm 2.5mm;
      display: grid;
      grid-template-columns: 1fr 15mm;
      gap: 2mm;
      align-items: center;
      margin-bottom: 2.2mm;
    }

    .item-name {
      display: block;
      margin-top: 2mm;
      font-size: 4mm;
      line-height: 1;
      font-weight: 900;
    }

    .weight {
      text-align: center;
    }

    .weight .value {
      font-size: 4mm;
    }

    .luggage-value {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 3mm;
      border: .45mm solid #000;
      border-radius: 1.7mm;
      padding: 2.1mm 2.5mm;
      margin-bottom: 4.2mm;
      background: #fff;
    }

    .luggage-value .value {
      font-size: 4.2mm;
      line-height: 1.1;
      font-weight: 900;
      word-break: break-word;
    }

    .section-chip {
      display: inline-block;
      background: #000;
      color: #fff;
      border-radius: 1mm;
      padding: 1.25mm 2.2mm;
      font-size: 3.2mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
      margin-bottom: 3.2mm;
    }

    .money-row {
      display: flex;
      justify-content: space-between;
      gap: 4mm;
      font-size: 4.2mm;
      line-height: 1.4;
      padding: 0 1.3mm;
    }

    .mono {
      font-family: "SFMono-Regular", Menlo, Consolas, monospace;
      letter-spacing: 0;
    }

    .total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 3mm;
      border-radius: 1.2mm;
      padding: 2.1mm 2.2mm;
      margin: 2.2mm 0 0;
      font-size: 4.4mm;
      line-height: 1;
      font-weight: 900;
    }

    .total-paid {
      background: #000;
      color: #fff;
    }

    .total-due {
      margin-top: 1.2mm;
      background: #fff;
      color: #000;
      border: .55mm solid #000;
    }

    .total-due-usd {
      margin-bottom: 4mm;
    }

    .issued {
      margin-bottom: 4mm;
    }

    .issued-row {
      display: grid;
      grid-template-columns: 11mm 1fr;
      gap: 2mm;
      font-size: 4mm;
      line-height: 1.45;
      padding: 0 1mm;
    }

    .issued-row b {
      font-size: 3.3mm;
    }

    .payment-status {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 3mm;
      border: .55mm solid #000;
      border-radius: 1.2mm;
      padding: 1.8mm 2.2mm;
      margin: 0 0 2.4mm;
      background: #fff;
    }

    .payment-status span {
      font-size: 3.2mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
    }

    .payment-status strong {
      font-size: 4.4mm;
      line-height: 1;
      font-weight: 900;
      text-transform: uppercase;
      text-align: right;
    }
    .qr-card {
      border: .45mm solid #000;
      border-radius: 1.7mm;
      display: grid;
      grid-template-columns: 22mm 1fr;
      gap: 4mm;
      padding: 3mm;
      align-items: center;
      margin-bottom: 4mm;
    }

    .qr-card img {
      width: 18mm;
      height: 18mm;
      image-rendering: pixelated;
      display: block;
    }

    .qr-title {
      font-size: 3.0mm;
      line-height: 1.1;
      font-weight: 900;
      margin-bottom: 2mm;
    }

    .qr-line {
      font-size: 3.0mm;
      line-height: 1.35;
    }

    .terms {
      border-top: .55mm solid #000;
      padding-top: 3mm;
      margin-top: 1mm;
      font-size: 3.35mm;
      line-height: 1.38;
    }

    .terms-title {
      display: block;
      text-align: center;
      font-size: 3.5mm;
      font-weight: 900;
      margin-bottom: 1mm;
    }

    .footer {
      text-align: center;
      margin-top: 3mm;
    }

    .footer strong {
      display: block;
      font-size: 4.7mm;
      line-height: 1.2;
      margin-bottom: 2.4mm;
    }

    .footer span {
      display: block;
      font-size: 2.75mm;
      line-height: 1.45;
    }
  </style>
</head>
`;
    data.forEach(item => {
      const route_name = item.route_name;
      const [from, to] = route_name.split(/\s+to\s+/i);
      total_amount = parseInt(item.paid) + parseInt(item.to_be);
      paid_c = currencyOutPut(item.paid);
      to_be_c = currencyOutPut(item.to_be);
      lag_Value_c = currencyOutPut(item.lag_Value);
      total_amount = currencyOutPut(total_amount);
      to_be_paid_currency = currencyOutPut(item.to_be_paid_value);




      receiptHtml += `
<div class='body_class'>
  <main class="receipt">
    <header class="brand">
      <div>
      <img class="brand-logo" src="assets/images/favicon.ico" alt="SR Classic logo">


      </div>
    </header>

    <section class="ticket-box" aria-label="Ticket number">
      <div class="ticket-label">Luggage No.</div>
      <div class="ticket-number">${item.code} </div>
    </section>

    <hr class="rule">

    <h1 class="route-title">${from.toUpperCase()} ➞ ${to.toUpperCase()}</h1>
    <section class="route-card">
      <div>
        <span class="label">From</span>
        <span class="value">${from.toUpperCase()}</span>
      </div>
      <div>
        <span class="label">To</span>
        <span class="value">${to.toUpperCase()}</span>
      </div>
    </section>



    <section class="chips" aria-label="Parcel summary">
      <span class="chip">Luggage(s)</span>
      <span class="chip">${item.quantity} PCS</span>
    </section>

    <section class="grid-2">
      <div>
        <div class="field">
          <span class="label">Sender</span>
          <span class="value">${item.s_name.toUpperCase()} </span>
        </div>
        <div class="field">
          <span class="label">Sender phone</span>
          <span class="value">${item.s_no} </span>
        </div>
      </div>
      <div>
        <div class="field">
          <span class="label">Receiver</span>
          <span class="value">${item.r_name.toUpperCase()} </span>
        </div>
        <div class="field">
          <span class="label">Receiver phone</span>
          <span class="value">${item.r_no} </span>
        </div>
      </div>
    </section>

    <section class="item-card">
      <div>
        <span class="label">Item details</span>
        <span class="item-name">${atob(item.detail)}</span>
      </div>
      <div class="weight">
        <span class="label">WT</span>
        <span class="value">${item.weight}${item.metric}</span>
      </div>
    </section>
 <section class="luggage-value" aria-label="Luggage value">
      <span class="label">Luggage Value</span>
      <span class="value">${lag_Value_c} ${item.currency_symbol}</span>
    </section>


    <section>
      <span class="section-chip"> Payment Summary</span>
      <div class="payment-status">
        <span>Payment Status</span>
        <strong>${item.status}</strong>
      </div>
      <div class="money-row">
        <span>Cash paid</span>
        <span class="mono">${paid_c}</span>
      </div>
      <div class="money-row">
        <span>To be paid</span>
        <span class="mono">${to_be_c}</span>
      </div>
      <div class="total">
        <span>TOTAL</span>
        <span class="mono">${total_amount} ${item.currency_symbol}</span>
      </div>
         <div class="total total-due total-due-usd"style="${item.visibility}">
        <span>TO BE PAID </span>
        <span class="mono">${item.to_be_paid_value} ${item.to_be_paid_currency}</span>
         </div>
    </section>

    <section class="issued">
      <span class="section-chip">Issued</span>
      <div class="issued-row">
        <b>By</b>
        <strong>${item.username}</strong>
      </div>
      <div class="issued-row">
        <b>On</b>
        <strong>${item.print_time}</strong>
      </div>

    </section>

    <section class="qr-card">
      <img src="/cargo/qrcode/uploads/${item.code}.png" alt="QR code for receipt ${item.code}">
      <div>
        <div class="qr-title">SCAN TO VERIFY</div>
        <div class="qr-line"><strong>Receipt ${item.code}</strong></div>
        <div class="qr-line">SR Classic Coach</div>
        <div class="qr-line mono">${total_amount} ${item.currency_symbol}</div>
      </div>
    </section>





    <footer class="footer">
      <strong>Original Receipt</strong>
      <span>${item.address_1} | ${item.contact_no1} </span>

       <span>${item.address_2} | ${item.contact_no2} </span>
      <span>classiccoachcoltd@gmail.com</span>
    </footer>
  </main>
</div>
`;
      //console.log(item.code, item.route_name, item.s_name, item.r_name, item.quantity, item.status);

    });
    receiptHtml += `
</html>
`;

    function currencyOutPut(amount) {
      const formattedAmount = Number(amount).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
      return formattedAmount;
    }

    // ---- Render off-screen (full Chrome rendering, so Grid/Flexbox
    //      and the QR/logo images look exactly like the print preview
    //      did before) ----
    const stage = document.createElement('div');
    stage.style.position = 'fixed';
    stage.style.left = '-99999px';
    stage.style.top = '0';
    document.body.appendChild(stage);

    const iframe = document.createElement('iframe');
    iframe.style.width = '320px';
    iframe.style.height = '10px';
    iframe.style.border = '0';
    stage.appendChild(iframe);

    return new Promise((resolve, reject) => {
      iframe.onload = async () => {
        try {
          const doc = iframe.contentDocument;
          const target = doc.querySelector('.body_class') || doc.body;

          // let the QR/logo <img> tags actually finish loading before
          // we snapshot the DOM, otherwise they'll rasterize blank
          const images = Array.from(doc.images);
          await Promise.all(images.map(img => img.complete ? Promise.resolve() : new Promise(res => {
            img.onload = img.onerror = res;
          })));

          const canvas = await html2canvas(target, {
            scale: 3,               // sharper output on thermal printers
            backgroundColor: '#ffffff',
            useCORS: true
          });

          const base64Data = canvas.toDataURL('image/png').split(',')[1];

          let printed = false;
          try {
            if (typeof qz !== 'undefined' && qz.websocket && !qz.websocket.isActive()) {
              await qz.websocket.connect({ host: ['localhost'], retries: 0, delay: 0 });
            }
            if (typeof qz !== 'undefined' && qz.websocket && qz.websocket.isActive()) {
              const config = qz.configs.create(printerName, {
                size: { width: 72, height: 250 },
                units: 'mm',
                margins: 0,
                scaleContent: true,
                rasterize: true
              });
              await qz.print(config, [{
                type: 'pixel',
                format: 'image',
                flavor: 'base64',
                data: base64Data
              }]);
              printed = true;
            }
          } catch (printErr) {
            printed = false;
          }
          if (!printed) {
            tesseraBrowserPrint(receiptHtml);
          }

          document.body.removeChild(stage);
          resolve();
        } catch (err) {
          document.body.removeChild(stage);
          tesseraBrowserPrint(receiptHtml);
          resolve();
        }
      };

      iframe.contentDocument.open();
      iframe.contentDocument.write(receiptHtml);
      iframe.contentDocument.close();
    });
  }


});
