<?php
session_start();
include '../connect.php';




class AddRemark{

public $manifest_id;
public $remark_text;
public $remark_html;
public $db;
public $response;
public $username;

public function set_details($manifest_id,$remark_text,$remark_html,$response,$username,$db){
 $this->manifest_id = $manifest_id ;
 $this->remark_text = $remark_text ;
 $this->remark_html = $remark_html ;
 $this->username = $username ;


  try {
      $response ="success";
      mysqli_query($db,"UPDATE vehicle_name SET remark_test='$this->remark_text',remark_html='$this->remark_html',username_remark='$this->username' WHERE id ='$this->manifest_id'");

   } catch (Exception $e) {

  $response ="Something Went Wrong please try again later";
  }


 $this->response = $response ;


 }

 function get_details() {
    echo $this->response ;
  }

}
$manifest_id = $_POST['id'];
$remark_text = base64_encode($_POST['remark_text']);
$remark_html = base64_encode($_POST['remark_html']);
$response ="";
$username=$_SESSION['username'];
$add_maniefst = new AddRemark();
$add_maniefst->set_details($manifest_id,$remark_text,$remark_html,$response,$username,$db);
$add_maniefst->get_details();

