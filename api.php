<?php
header('Content-Type: application/json');

$altitude = rand(40, 450);
$speed = rand(90, 250);
$battery = rand(15, 100);
$temp = rand(85, 120);

$data = [
    "altitude" => $altitude,
    "speed" => $speed,
    "battery" => $battery,
    "temp" => $temp
];

echo json_encode($data);
?>