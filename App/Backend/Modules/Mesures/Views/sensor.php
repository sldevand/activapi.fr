<?php 

$graphObject=["sensor_id"=>$sensorID,"id"=>$id,"nom"=>$nom,"data"=>$listeMesures];
echo json_encode($graphObject, JSON_PRETTY_PRINT);
