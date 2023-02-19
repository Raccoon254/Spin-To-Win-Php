
<?php 

	
	
	date_default_timezone_set('Africa/Nairobi');
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
	
	$headers = ['Content-Type:application/json; charset=utf8'];
	$des = "CustomerPaybillOnline";
	
	$curl = curl_init($access_token_url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_HEADER, FALSE);
	curl_setopt($curl, CURLOPT_USERPWD, 'JSRACgW9AJs62Jmim5ZwpTYsYpsq1TAu:AasPq5TpzZBnNUhQ');
    $result = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	$result = json_decode($result);
	curl_close($curl);
	echo $access_token = $result->access_token;  
	


	$stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];
	$Timestamp = date('YmdHis');
	$Password = base64_encode("174379bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919".$Timestamp);
	 
	$curl_post_data = array(
		'BusinessShortCode' => 174379,
		'Password' => $Password,
		'Timestamp' => $Timestamp,
		'TransactionType' => 'CustomerPayBillOnline',
		'Amount' => 1,
		'PartyA' => 254758481320,
		'PartyB' => 174379,
		'PhoneNumber' => 254758481320,
		'CallBackURL' => 'https://955a-197-248-106-13.in.ngrok.io/callback.php',
		'AccountReference' => "code",
		'TransactionDesc' => "des"
	);

	
    # initiating the transaction
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $initiate_url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
	$data_string = json_encode($curl_post_data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	$r =json_decode( curl_exec($curl),true);
	curl_close($curl);
	echo !isset($r["errorCode"]) ? ($r["ResponseCode"]==="0" ? "success" : "Failure") : "Failure";
