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
    ?>
</body>

</html>