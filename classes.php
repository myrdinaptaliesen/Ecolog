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

if ($_GET) {
    $id = $_GET['id'];
    $sql1 = $pdo->prepare("SELECT * FROM classes where idClasse =" . $id);
    $sql1->execute();


    $resultat = $sql1->fetch(PDO::FETCH_ASSOC);
    var_dump($resultat);
}

include 'header.php';
?>


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Gestion des classes</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>
<?php
if ($_GET) {
    $id = $_GET['id'] ?>
    <form action="classes.php" method="post">
        <input type="text" name="nomClass" value="<?php echo $resultat['nomClasse'] ?>">
        <input type="submit" value="Modifier la classe">
    </form>
<?php
} else {
?>
    <form action="classes.php" method="post">
        <input type="text" name="nomClass">
        <input type="submit" value="Créer une nouvelle classe">
    </form>
<?php }

include 'footer.php';
?>