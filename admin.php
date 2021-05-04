<?php
include_once("pdo.php");

$sth = $pdo->prepare("SELECT * FROM Classes");
    $sth->execute();


    $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
</head>
<body>
    <h2>Liste des classes</h2>
    <table>
        <th>Classe</th>
        <th>Action</th>
        <th>Supprimer</th>
        <?php
 foreach ($resultat as $key => $value) { ?>
    <tr>
        <td><?php echo $value['nomClasse'] ?></td>
        <td><a href="classe.php?id=<?php echo $value['idClasse'] ?>">Modifier</a></td>
        <td><a href="deleteClasse.php?id=<?php echo $value['idClasse'] ?>">Supprimer</a></td>
        <td></td>
    </tr>
<?php
}
?>
      
    </table>
</body>
</html>