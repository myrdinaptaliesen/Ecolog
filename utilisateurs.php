<?php
include_once("pdo.php");


$sql = $pdo->prepare("SELECT idUtilisateur, nom, prenom, pseudo, mail, dateInscription FROM Utilisateurs");
$sql->execute();

$resultat = $sql->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];
    $mail = $_POST['mail'];

    $mdp_hache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    $req = $pdo->prepare('INSERT INTO utilisateurs(nom, prenom, pseudo, mdp, mail, dateInscription) VALUES(:nom, :prenom, :pseudo, :mdp, :mail, CURDATE())');
    $req->execute(array(
        'nom' => $nom,
        'prenom' => $prenom,
        'pseudo' => $pseudo,
        'mdp' => $mdp_hache,
        'mail' => $mail
    ));
}


include 'header.php';
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Gestion des Utilisateurs</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<form action="Utilisateurs.php" method="post">
<div>
    <input type="text" name="nom" placeholder="Nom">
    <input type="text" name="prenom" placeholder="Prénom">
    <input type="email" name="mail" placeholder="Adresse mail">
    </div>
    <div>
    <input type="text" name="pseudo" placeholder="Pseudo">
    <input type="password" name="mdp" placeholder="Mot de passe">
    </div>
    <input type="submit" value="Créer un nouvel utilisateur">
    
</form>

<table id="myTable">
    <thead>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Pseudo</th>
        <th>Date d'inscription</th>
        <th>Actions</th>
        <th class="deleteTh">Supprimer</th>
    </thead>
    <tbody>
        <?php
        foreach ($resultat as $key => $value) { ?>
            <tr>
                <td><?php echo $value['nom'] ?></td>
                <td><?php echo $value['prenom'] ?></td>
                <td><?php echo $value['pseudo'] ?></td>
                <td><?php echo $value['dateInscription'] ?></td>
                <td><a href="updateUtilisateur?id=<?php echo $value['idUtilisateur'] ?>">Modifier</a></td>
                <td><a href="deleteUtilisateur?id=<?php echo $value['idUtilisateur'] ?>" class="btn btn-danger btn-circle btn-sm deleteButton"><i class="fas fa-trash"></i></a></td>
            </tr>
        <?php } ?>
</table>
<?php
include 'footer.php';

?>