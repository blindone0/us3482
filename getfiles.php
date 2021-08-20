<?php
$myfiles = array_diff(scandir("./"), array('.', '..')); 
echo json_encode($myfiles);