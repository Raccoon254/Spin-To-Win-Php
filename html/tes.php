<?php
	$headers = ['Content-Type:application/json; charset=utf8'];
	$curl_post_data = array(
		"dan","ken","kevin"
	);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://955a-197-248-106-13.in.ngrok.io/callback.php");

	$data_string = json_encode($curl_post_data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	$r =json_decode( curl_exec($curl),true);
	curl_close($curl);
