<?php
include_once("pdo.php");
$id = $_GET['id'];
$sql = $pdo->prepare("SELECT * FROM utilisateurs WHERE idUtilisateur = $id");
$sql->execute();

$resultat = $sql->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $nomUtilisateur = $_POST['nomUtilisateur'];
    $prenomUtilisateur = $_POST['prenomUtilisateur'];
    $pseudo = $_POST['pseudo'];
    $mail = $_POST['mail'];

    $sql1 = $pdo->prepare("UPDATE utilisateurs SET nom = :nom, prenom = :prenom, pseudo = :pseudo, mail = :mail WHERE idUtilisateur = $id");
    $sql1->execute(array(
        ':nom' => $nomUtilisateur,
        ':prenom' => $prenomUtilisateur,
        ':pseudo' => $pseudo,
        ':mail' => $mail,
        ));


    header('location:utilisateurs.php');
}



include 'header.php';

    if ($_GET) {
        $id = $_GET['id'] ?>
        <form action="updateUtilisateur?id=<?php echo $id?>" method="post">
            <input type="text" name="nomUtilisateur" value="<?php echo $resultat['nom'] ?>">
            <input type="text" name="prenomUtilisateur" value="<?php echo $resultat['prenom'] ?>">
            <input type="text" name="pseudo" value="<?php echo $resultat['pseudo'] ?>">
            <input type="text" name="mail" value="<?php echo $resultat['mail'] ?>">
            <input type="submit" value="Modifier l'utilisateur">

        </form>
    <?php
    }

    include 'footer.php';
    ?>
    
