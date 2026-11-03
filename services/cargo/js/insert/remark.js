$(document).ready(function () {
  const quill = new Quill('#editor', {
    theme: 'snow'
  });
  error = "";
  $("#register_remark").click(function () {
    var id = $("#manifest_id").val();
    var remark_text = $.trim(quill.getText());
    var remark_html = quill.root.innerHTML;

    if (remark_text == "Add Remark") {

      error = "Please add remark";
    } else {
      error = "";
    }
    if (error == "") {
      $("#register_remarks").text("Please wait...")
      $.ajax({
        url: "insert/remark.php",
        method: "POST",
        data: { remark_text: remark_text, remark_html: remark_html, id: id },
        success: function (response) {
          //alert(response);
          $("#register_remarks").text("Register");
          if ($.trim(response) == "success") {
            $("#empTable").DataTable().ajax.reload();
            alertify.success("Remarks updated");

            alertify.set('notifier', 'position', 'top-right');
          } else {
            alertify.set('notifier', 'position', 'top-right');
            alertify.error('Some thing whent wrong');



          }


        }

      });
    } else {
      alertify.set('notifier', 'position', 'top-right');
      alertify.error('Fill Response');
    }
  });

  $("#remark_btn").click(function () {
    $("#remark").modal("show");
    manifest_id = $("#manifest_data").data("id");
    $("#manifest_id").val(window.atob(manifest_id));
    remark_html = $("#remark_html").text();
  var container = $("#editor").get(0); 
  existingQuill = Quill.find(container);

if (existingQuill) {
    existingQuill.root.innerHTML = remark_html;
} else {
 
    var quill = new Quill("#editor-id", { theme: "snow" });
}

  });

});