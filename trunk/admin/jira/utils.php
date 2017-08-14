<?php 

function get_projects() {
	$jira_url = "https://pillar.atlassian.net";
	$username = "chiefatlarge";
	$password = "p0ssibility";

	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_URL => $jira_url . '/rest/api/2/project',
		CURLOPT_USERPWD => $username . ':' . $password,
		CURLOPT_HTTPHEADER => array('Content-type: application/json'),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false
	));

	$result = curl_exec($ch);
	$error = curl_error ($ch );
	if( $error){
		echo $error;
		exit();
	}
	curl_close($ch);
	return json_decode($result);
}

function post_to($resource, $data) {
	$jira_url = "https://pillar.atlassian.net";
	$username = "chiefatlarge";
	$password = "p0ssibility";
	$jdata = json_encode($data);
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_POST => 1,
		CURLOPT_URL => $jira_url . '/rest/api/latest/' . $resource,
		CURLOPT_USERPWD => $username . ':' . $password,
		CURLOPT_POSTFIELDS => $jdata,
		CURLOPT_HTTPHEADER => array('Content-type: application/json'),
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false
	));
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result);
}
?>