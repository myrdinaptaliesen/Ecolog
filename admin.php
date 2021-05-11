<?php
include_once("pdo.php");

$sth = $pdo->prepare("SELECT * FROM Classes");
    $sth->execute();


    $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);

    include 'header.php';
?>


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
<?php
include 'footer.php';
?>
      