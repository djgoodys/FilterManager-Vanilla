<?php
print_r($_POST);
if(isset($_POST['submit'])){
    // Get the submitted form data
    $email = $_POST['email'];
    $name = "no name";
    $subject = "FilterManager backup file";
    $message = "no message";
    $uploadStatus = 1;
        $Today = date("Y-m-d");
        $filename = "equipment_".$Today.".csv";
        $targetFilePath = getcwd()."/" . $filename;
        $toEmail = $email;
        // Sender
        $from = 'djgoodys@gmail.com';
        $fromName = 'FilterManager by DJ';
        // Subject
        $emailSubject = "Email attachment is data back up for ".$Today;
        // Message
        $htmlContent = "<h2>Contact Request Submitted</h2><p><b>Email:</b> ".$email."</p><p><b>Subject:</b>Back for ".$filename;
        // Header for sender info
        $headers = "From: $fromName"." <".$from.">";
        if(!empty($filename) && file_exists($filename)){
              
            $semi_rand = md5(time());
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
            // Headers for attachment
            $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
            // Multipart boundary
            $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
                "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";
            // Preparing attachment
            if(is_file($filename)){
            echo "beginning1".$filename;
                $message .= "--{$mime_boundary}\n";
                $fp =    @fopen($filename,"rb");
                $data =  @fread($fp,filesize($filename));
                @fclose($fp);
                $data = chunk_split(base64_encode($data));
                $message .= "Content-Type: application/octet-stream; name=\"".basename($filename)."\"\n" .
                    "Content-Description: ".basename($filename)."\n" .
                    "Content-Disposition: attachment;\n" . " filename=\"".basename($filename)."\"; size=".filesize($filename).";\n" .
                    "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            }
            $message .= "--{$mime_boundary}--";
            $returnpath = "-f" . $email;
            // Send email
            $mail = mail($toEmail, $emailSubject, $message, $headers, $returnpath);
            // Delete attachment file from the server
            @unlink($filename);
        }else{
            // Set content-type header for sending HTML email
            $headers .= "\r\n". "MIME-Version: 1.0";
            $headers .= "\r\n". "Content-type:text/html;charset=UTF-8";
            // Send email
            $mail = mail($toEmail, $emailSubject, $htmlContent, $headers);
        }
        // If mail sent
        if($mail){
            $statusMsg = "Your email attachment request has been submitted successfully !";
        }else{
            $statusMsg = "Your request submission failed, please try again.";
        }
    echo $statusMsg;
  
}
?>