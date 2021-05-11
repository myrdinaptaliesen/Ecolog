<?php
include_once("pdo.php");

if ($_POST) {
    $nomClasse = $_POST['nomClass'];

    $sth = $pdo->prepare("
        INSERT INTO Classes(nomClasse)
        VALUES (:nomClasse)
    ");

    $sth->execute(array(
        ':nomClasse' => $nomClasse
    ));

}


    $sql1 = $pdo->prepare("SELECT * FROM classes");
    $sql1->execute();


    $resultat = $sql1->fetchAll(PDO::FETCH_ASSOC);



include 'header.php';
?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Gestion des classes</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

    <form action="classes.php" method="post">
        <input type="text" name="nomClass">
        <input type="submit" value="Créer une nouvelle classe">
    </form>


<table>
    <thead>
        <th>Libéllé de  la classe</th>
        <th>Action</th>
        <th>supprimer</th>
    </thead>
    <tbody>
    <?php
foreach ($resultat as $key => $value) {

    ?>
        <tr>
        <td><?php echo $value['nomClasse'] ?></td>
                <td><a href="updateClasse?id=<?php echo $value['idClasse'] ?>">Modifier</a></td>
                <td><a href="deleteClasse?id=<?php echo $value['idClasse'] ?>" class="btn btn-danger btn-circle btn-sm deleteButton"><i class="fas fa-trash"></i></a></td>
        </tr>
        <?php

}
        ?>
    </tbody>
</table>
<?php
include 'footer.php';
?>

