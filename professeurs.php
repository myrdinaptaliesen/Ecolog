<?php
include_once("pdo.php");

if ($_POST) {
    $nomProfesseur = $_POST['nomProfesseur'];
    $prenomProfesseur = $_POST['prenomProfesseur'];

    $sth = $pdo->prepare("
        INSERT INTO Professeurs(nomProfesseur, prenomProfesseur)
        VALUES (:nomProfesseur, :prenomProfesseur)
    ");

    $sth->execute(array(
        ':nomProfesseur' => $nomProfesseur,
        ':prenomProfesseur' => $prenomProfesseur
    ));
}

$sql2 = $pdo->prepare("SELECT * FROM classes");
$sql2->execute();


$resultat2 = $sql2->fetchAll(PDO::FETCH_ASSOC);

$sql3 = $pdo->prepare("SELECT MAX(idProfesseur) as lastIdProfesseur FROM `professeurs`");
$sql3->execute();


$resultat3 = $sql3->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    for ($i = 0; $i < count($resultat2); $i++) {
        $checkbox = "classe$i";
        if (isset($_POST[$checkbox])) {
            $idClasse = $_POST[$checkbox];
            $idProfesseur = $resultat3['lastIdProfesseur'];
            $sth = $pdo->prepare("
            INSERT INTO Enseigne(idProfesseur, idClasse)
            VALUES (:idProfesseur, :idClasse)
        ");

            $sth->execute(array(
                ':idProfesseur' => $idProfesseur,
                ':idClasse' => $idClasse
            ));
        }
    }
}

if ($_GET) {
    $id = $_GET['id'];
    $sql4 = $pdo->prepare("SELECT * FROM enseigne where idProfesseur =" . $id);
    $sql4->execute();


    $resultat4 = $sql4->fetch(PDO::FETCH_ASSOC);
}

$sql6 = $pdo->prepare("SELECT nomProfesseur, prenomProfesseur, idProfesseur FROM Professeurs");
$sql6->execute();


$resultat6 = $sql6->fetchAll(PDO::FETCH_ASSOC);

$sql7 = $pdo->prepare("SELECT nomClasse, idProfesseur FROM Classes INNER JOIN Enseigne ON classes.idClasse = enseigne.idClasse");
$sql7->execute();


$resultat7 = $sql7->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des classes</title>
</head>

<body>
<h1>Gestion des professeurs</h1>
    <form action="professeurs.php" method="post">
        <input type="text" name="nomProfesseur">
        <input type="text" name="prenomProfesseur">
        <?php
        foreach ($resultat2 as $key => $value) {

        ?>
            <input type="checkbox" name="classe<?php echo $key ?>" value="<?php echo $value['idClasse'] ?>">
            <label><?php echo $value['nomClasse'] ?></label>
        <?php
        }
        ?>
        <input type="submit" value="Créer un nouveau professeur">
    </form>

    <table>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Classe(s)</th>
        <th>Actions</th>
        <th>Supprimer</th>

        <?php
        foreach ($resultat6 as $key => $value6) { ?>
            <tr>
                <td><?php echo $value6['nomProfesseur'] ?></td>
                <td><?php echo $value6['prenomProfesseur'] ?></td>
                <td>
                    <?php
                    foreach ($resultat7 as $key => $value7) {
                        if ($value7['idProfesseur'] === $value6['idProfesseur']) {
                            echo $value7['nomClasse'];
                            echo "&nbsp";
                        }
                    }
                    ?>
                </td>
                <td><a href="updateProfesseur?=<?php echo $value6['idProfesseur'] ?>">Modifier</a></td>
                <td><a href="deleteProfesseur?=<?php echo $value6['idProfesseur'] ?>">X</a></td>
            </tr>
        <?php }
        ?>

    </table>

</body>

</html>