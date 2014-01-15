<?php
require_once("no-spam.class.php");


$mail = new emailService();
echo $mail->newEmail();//random email
for($i = 0; $i < count($mail->mails); $i++){
	echo "From: ".$mail->mails[$i][0].", subject: ".$mail->mails[$i][1].", message: ".$mail->mails[$i][2];
}
?>
