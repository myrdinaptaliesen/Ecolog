<?php
include_once("pdo.php");

//Je récupère toutes les classes pour les afficher dans les checkbox
$sql1 = $pdo->prepare("SELECT * FROM classes");
$sql1->execute();

$resultat1 = $sql1->fetchAll(PDO::FETCH_ASSOC);

//Je récupère tous les professeurs pour les afficher 
$sql2 = $pdo->prepare("SELECT nomProfesseur, prenomProfesseur, idProfesseur FROM Professeurs");
$sql2->execute();

$resultat2 = $sql2->fetchAll(PDO::FETCH_ASSOC);

//Je récupère toutes les classe et les id de professeurs qui lui sont attachées
$sql3 = $pdo->prepare("SELECT nomClasse, idProfesseur FROM Classes INNER JOIN Enseigne ON classes.idClasse = enseigne.idClasse");
$sql3->execute();


$resultat3 = $sql3->fetchAll(PDO::FETCH_ASSOC);

//Si le formlaire de création de professeur est envoyé, je créé un professeur
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

//Je récupère l'id du dernier professeur créer pour pouvoir dans un deuxième temps lui assigner une classe
$sql4 = $pdo->prepare("SELECT MAX(idProfesseur) as lastIdProfesseur FROM `professeurs`");
$sql4->execute();


$resultat4 = $sql4->fetch(PDO::FETCH_ASSOC);

//Si le formulaire est envoyé, j'assigne ou pas une classe au dernier professeur créé
if ($_POST) {
    for ($i = 0; $i < count($resultat1); $i++) {
        $checkbox = "classe$i";
        if (isset($_POST[$checkbox])) {
            $idClasse = $_POST[$checkbox];
            $idProfesseur = $resultat4['lastIdProfesseur'];
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
    header('location:professeurs.php');
}

include 'header.php';
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Gestion des professeurs</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<form action="professeurs.php" method="post">
    <input type="text" name="nomProfesseur" placeholder="Nom">
    <input type="text" name="prenomProfesseur" placeholder="Prénom">
    <?php
    foreach ($resultat1 as $key => $value) {

    ?>
        <input type="checkbox" name="classe<?php echo $key ?>" value="<?php echo $value['idClasse'] ?>">
        <label><?php echo $value['nomClasse'] ?></label>
    <?php
    }
    ?>
    <input type="submit" value="Créer un nouveau professeur">
</form>

<table id="myTable">
    <thead>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Classe(s)</th>
        <th>Actions</th>
        <th>Supprimer</th>
    </thead>
    <tbody>
        <?php
        foreach ($resultat2 as $key => $value2) { ?>
            <tr>
                <td><?php echo $value2['nomProfesseur'] ?></td>
                <td><?php echo $value2['prenomProfesseur'] ?></td>
                <td>
                    <?php
                    foreach ($resultat3 as $key => $value3) {
                        if ($value3['idProfesseur'] === $value2['idProfesseur']) {
                            echo $value3['nomClasse'];
                            echo "&nbsp";
                        }
                    }
                    ?>
                </td>
                <td><a href="updateProfesseur?id=<?php echo $value2['idProfesseur'] ?>">Modifier</a></td>
                <td><a href="deleteProfesseur?id=<?php echo $value2['idProfesseur'] ?>" class="btn btn-danger btn-circle btn-sm "><i class="fas fa-trash"></i></a></td>
            </tr>
        <?php } ?>
</table>
<?php
include 'footer.php';

?>