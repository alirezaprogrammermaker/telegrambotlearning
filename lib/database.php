<?php
define("host" , "localhost");
define("db_username" , "root");
define("db_password" , "");
define("db_database_name" , "telegram");
$charset = 'utf8mb4';
$option =[
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];

$dns = "mysql:host=".host.";dbname=".db_database_name.";charset=".$charset;
$db = new PDO($dns,db_username,db_password,$option);

