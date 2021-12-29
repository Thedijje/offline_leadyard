<?php
  require FCPATH . 'vendor/autoload.php';


    function aws_credentials(){
        $params = array(
            'credentials' => array(
                'key'   =>  env('AWS_KEY'),
                'secret' => env('AWS_SECREATE')
            ),
            'region' => env('AWS_REGION'), // < your aws from SNS Topic region
            'version' => 'latest'
        );
        return $params;
    }


    /**
     * Retrieve list of object from given path on s3 bucket
     * Ref : https://docs.aws.amazon.com/AmazonS3/latest/dev/ListingObjectKeysUsingPHP.html
     * @param path strong
     * @return file_list array
     * 
     *  */

    function s3_list_object($path='',$obj=null){
        

        if(!s3_is_exist($path)){
            //return false;
        }

        $params = aws_credentials();
        $s3     = new \Aws\S3\S3Client($params); 

        $results = $s3->getPaginator('ListObjects', [
            'Bucket' => bucket_name(),
            'Prefix' => $path
        ]);

       
        if(!$results){
            return false;
        }
        
        if($obj!=null){
            return $results;
        }

        foreach ($results as $result) {
            
            
            if(!isset($result['Contents'])){
                continue;
            }


            foreach ($result['Contents'] as $object) {
                $file_list[]    =   $object['Key'];
            }
        }

        return (!empty($file_list)) ? array_reverse($file_list) : array();
    }

    /**
     * Retrieve object content from s3 bucket
     * Ref : https://docs.aws.amazon.com/AmazonS3/latest/dev/RetrieveObjSingleOpPHP.html
     * @param path string
     * @return result string
     * 
     ***/
    function s3_read_object($path=''){
        $params     =   aws_credentials();
        $s3         =   new \Aws\S3\S3Client($params);

        $result = $s3->getObject([
            'Bucket' => bucket_name(),
            'Key'    => $path
        ]);

        return $result;
        


    }


    /**
     * Upload file to s3 bucket
     * @param path string
     * @param sourceFile string
     * @return string
     * 
     *  */
    function s3_upload_file($path,$sourceFile){
        if(!file_exists($sourceFile)){
            log_message('error','ERROR unable to upload file to S3 as file '.$sourceFile.' does not exists');
            return false;
        }
        $params     =   aws_credentials();
        $s3         =   new \Aws\S3\S3Client($params); 
        $fileSize   =   filesize($sourceFile);
        $fileType   =   mime_content_type($sourceFile);
        
        try {
            $result =   $s3->putObject([
                'Bucket'		=> bucket_name(),
                'Key'    		=> $path,
                'SourceFile'	=> $sourceFile,
                'ContentLength' => $fileSize,
                'ContentType' 	=> $fileType,
            ]);
            $response =   array(
                'status'    =>  true,
                'value'     =>  $path
            );
            return $response;
        } catch (S3Exception $e) {
            // Catch an S3 specific exception.
            $response =   array(
                'status'    =>  false,
                'value'     =>  $e->getMessage()
            );
            return $response;
        }
    }

    function s3_is_exist($location){

        $server = $_SERVER['SERVER_NAME'] ?? '192.168.1.11';
        $allowed_server =   array('staging.mobi-hub.com','api.mobi-hub.com','app.mobi-hub.com', '192.168.1.11');
       
        if(!in_array($server,$allowed_server)){
            return true;
        }
        
        if(!is_string($location) || !is_string(bucket_name())){
            return false;
        }
        
        $params = aws_credentials();
        $s3     = new \Aws\S3\S3Client($params); 
        $result = $s3->doesObjectExist(bucket_name(), $location);
        
        if (!$result) {
            return FALSE;
        }
        return TRUE;
    }

    function check_s3_valid($url){
        $file = $url;
        
		$file_headers = @get_headers($file);
		
		if(!$file_headers || $file_headers[0] != 'HTTP/1.1 200 OK') {
				return $exists = false;
		}
		else {
				return $exists = true;
		}
    }

    function s3_del_file($location){

        $params = aws_credentials();
        $s3     = new \Aws\S3\S3Client($params); 
        try {
            $result = $s3->deleteObject([
                'Bucket' => bucket_name(),
                'Key'    => $location
            ]);

            $response =   array(
                'status'    =>  true,
                'value'     =>  'delted successfully'
            );

            return $response;
        } catch (S3Exception $e) {
            // Catch an S3 specific exception.
            $response =   array(
                'status'    =>  false,
                'value'     =>  $e->getMessage()
            );
            return $response;
        }

    }

    function aws_sms($msg,$mobile){
        $params = aws_credentials();
        $sns    = new \Aws\Sns\SnsClient($params); 
        
        $args = array(
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => 'REEVALO'
                ]
            ],
            "SMSType" => "Transational",
            "DefaultSenderID" => "REEVALO",
            "SenderID" => "REEVALO",
            "PhoneNumber" => $mobile,
            "Message" => $msg
        );
        $result = $sns->publish($args);
        $msgId  =   $result['MessageId'];

        if(!$msgId){
            return FALSE;
        }
        return $msgId;
        
        dd($result);
        // exit();
    }

    


    function send_ses($data,$attachment=NULL)
    {
    // Instantiate a new PHPMailer 

    $mail = new PHPMailer\PHPMailer\PHPMailer;

    // Tell PHPMailer to use SMTP
    $mail->isSMTP();

    // Replace sender@example.com with your "From" address. 
    // This address must be verified with Amazon SES.

    $mail->setFrom(strtolower($data['from']), $data['name']);

    // Replace recipient@example.com with a "To" address. If your account 
    // is still in the sandbox, this address must be verified.
    // Also note that you can include several addAddress() lines to send
    // email to multiple recipients.

    $mail->addAddress($data['to'], 'You');

    // Replace smtp_username with your Amazon SES SMTP user name.
    $mail->Username = env('SMTP_USER');

    // Replace smtp_password with your Amazon SES SMTP password.
    $mail->Password = env('SMTP_PASSWORD');
        
    // Specify a configuration set. If you do not want to use a configuration
    // set, comment or remove the next line.

    //$mail->addCustomHeader('X-SES-CONFIGURATION-SET', 'ConfigSet');
    
    
    // If you're using Amazon SES in a region other than US West (Oregon), 
    // replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP  
    // endpoint in the appropriate region.
    $mail->Host = env('SMTP_HOST');

    // The subject line of the email
    $mail->Subject = $data['subject'];

    // The HTML-formatted body of the email
    $mail->Body = $data['message'];

    //Add attachments one by one
    if($attachment!=NULL){
			if(!is_array($attachment)){
                
                $mail->addStringAttachment(file_get_contents($attachment), basename($attachment));

                if(file_exists($attachment)):
                    
                endif;
			}else{
				foreach($attachment as $key => $attach){
                    if(file_exists($attach)):
                        $mail->addStringAttachment(file_get_contents($attach), basename($attach));
                    endif;
				}
			}
		}
    
    // Tells PHPMailer to use SMTP authentication
    
    $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
        );
    
    $mail->SMTPDebug = 0; 

    $mail->SMTPAuth = true;

    // Enable TLS encryption over port 587

    $mail->SMTPSecure = 'tsl';
    $mail->Port = 587;
    
    //$mail->SMTPSecure = 'ssl';
    //$mail->Port = 465;    
    //465; 
    // Tells PHPMailer to send HTML-formatted email
    $mail->isHTML(true);

    // The alternative email body; this is only displayed when a recipient
    // opens the email in a non-HTML email client. The \r\n represents a 
    // line break.

    // $mail->AltBody = "Email Test\r\nThis email was sent through the 
    //     Amazon SES SMTP interface using the PHPMailer class.";
    // var_dump($mail->send());


    if(!$mail->send()) {
        return $mail->ErrorInfo;
    } else {
        return "sent";
    }
  }
