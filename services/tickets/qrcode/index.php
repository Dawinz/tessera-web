
    <?php 
  include "meRaviQr/qrlib.php";
  include "config.php";
 $id="1";
      $qc =  "https://217.29.139.126/print_ticket.php?id=402";
    $qrUname = "classic coach";
    $qrImgName = "meravi".rand();
    if($qc=="" && $qrUname=="")
    {
      echo "<script>alert('Please Enter Your Name And Msg For QR Code');</script>";
    }
    elseif($qc=="")
    {
      echo "<script>alert('Please Enter QR Code Msg');</script>";
    }
    elseif($qrUname=="")
    {
      echo "<script>alert('Please Enter Your Name');</script>";
    }
    else
    {
    $dev = " ...Develop By Ravi Khadka";
    $final = $qc.$dev;
    $qrs = QRcode::png($final,"userQr/$qrImgName.png","H","3","3");
    $qrimage = $qrImgName.".png";
    $workDir = $_SERVER['HTTP_HOST'];
    $qrlink = $workDir."/qrcode".$qrImgName.".png";
    $insQr = $meravi->insertQr($qrUname,$final,$qrimage,$qrlink,$id);
  
  }
 
  ?>


