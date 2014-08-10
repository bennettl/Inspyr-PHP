<?php 
header("Content-Type: application/json");

require_once('http://bennettl.com/inspyr/lib/swagger-php/library/Swagger/Swagger.php');

use Swagger\Swagger;

$swagger = new Swagger('/project/root/top_level');
echo $swagger->getResource('/pet', array('output' => 'json'));

// require_once('inspyr-api.php');

// try {
//     $api = new Inspyr_API($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
//     echo $api->processAPI();
// } catch (Exception $e) {
//     echo json_encode(Array('error' => $e->getMessage()));
// }

?>