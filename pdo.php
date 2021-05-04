<?php
try{
    $pdo = new PDO('mysql:host=localhost;dbname=ecolog;port=3306','root','',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
}
     
catch(PDOException $e){
    echo "Erreur : " . $e->getMessage();
}