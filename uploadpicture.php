<?php
header('Access-Control-Allow-Origin: *');

$name = $_FILES["file"]["name"];
$name = str_replace(" ", "", $name);
$name = str_replace("-", "", $name);
$name = str_replace("_", "", $name);
$milliseconds = round(microtime(true) * 1000) . $name;

move_uploaded_file($_FILES["file"]["tmp_name"],
	"upload/" . $milliseconds);
//   echo  "upload/" . $_FILES["file"]["name"];

$url = "/storage/app/public/images" . $milliseconds;
$response = array(
	'uploaded' => true,
	'error' => false,
	'value2' => "http:/acnure.com/" . $url,
);
echo json_encode($response);

?>