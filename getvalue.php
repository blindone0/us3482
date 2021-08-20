<?php
$id = $_GET['id'];
if($id){
    $dbh = new PDO("mysql:host=localhost;dbname=contest;charset=utf8;", "root", "");
    $sql = "select value from us3482 where id='$id'";
    foreach ($dbh->query($sql) as $row) {
        print $row[0];
    }
}