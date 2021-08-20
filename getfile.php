<?php

$file = $_GET['file'];

if($file){
    $contents = file_get_contents($file);
}
echo($contents);