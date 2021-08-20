<?php
$db_conf = array("databasetype" => "mysql", "databasename"=> "laravel","host"=>"localhost", "port"=>"3306", "username"=>"root", "password"=>"");
$value = 7;

function getSumOfDigits($number)
{
    $number= gmp_strval($number);
    $sum = 0;
    for ($i = 0; $i < strlen($number); $i++) {
        $sum += $number[$i];
    }
    return $sum;
}

$dbh = new PDO("mysql:host=localhost;charset=utf8;", "root", "");

try{
    $dbh->exec("CREATE DATABASE contest");
    $sql = "CREATE TABLE contest.us3482 (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        value INT NOT NULL
        )";
    $dbh->exec($sql);
} catch (Exception $ex){
    echo $ex->getMessage();
}
    
// foreach(range(1, 1000000) as $id){
//     print("gello");
//     $dbh->exec("INSERT INTO contest.us3482 VALUES ($id, $value)") or die(print_r($dbh->errorInfo(), true));
//     $value = getSumOfDigits($value);
//     $value = gmp_pow($value, 2);
//     $value = gmp_add($value, 1);
// } 

$sqlScript = file('contents.sql');
