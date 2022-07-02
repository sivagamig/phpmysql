<?php
if(isset($_POST['contact_submit'])){
   $to = "raghupathi.mani@gmail.com";
   $subject = "Enquiry Details";
   $header = "From:".$_POST["mail"]."\r\n";
   $header .= "Cc:guhanapparel@outlook.com\r\n";
   $header .= "MIME-Version: 1.0\r\n";
   $header .= "Content-type: text/html; charset: utf8\r\n";
   $message  = "<html><head><meta http-equiv='content-type' content='text/html; charset=utf-8' /></head><body>";
   $message .= "<table><tr><td>name:</td><td>".$_POST["name"]."</td></tr>";
   // $message .= "<tr><td>number:</td><td>".$_POST["number"]."</td></tr>";
   $message .= "<tr><td>mail:</td><td>".$_POST["mail"]."</td></tr>";
   $message .= "<tr><td>message:</td><td>".$_POST["comment"]."</td></tr>";
   $message .= "</table></body></html>";
   $retval = mail ($to,$subject,$message, $header);
   if( $retval == true )
   {
      echo "Message sent successfully...";
   }
   else
   {
      echo "Message could not be sent...";
   }
   echo "<script>
    		window.location = '".$_SERVER['HTTP_REFERER']."';
		</script>";
}
?>