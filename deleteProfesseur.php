<?php
include_once("pdo.php");
/////////////////////////////Système de suppression d'un produit/////////////////////////////////////


$professeur = $_GET['id'];


    $sql = "DELETE FROM professeurs WHERE idProfesseur=$professeur";
    $sth = $pdo->prepare($sql);
    $sth->execute();

    $sql2 = "DELETE FROM enseigne WHERE idProfesseur=$professeur";
    $sth2 = $pdo->prepare($sql2);
    $sth2->execute();


header("location:professeurs.php");
?>