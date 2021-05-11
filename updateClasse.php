<?php
include_once("pdo.php");
$id = $_GET['id'];
$sql = $pdo->prepare("SELECT * FROM classe WHERE idClasse = $id");
$sql->execute();

$resultat = $sql->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $nomClasse = $_POST['nomClasse'];
    $prenomProfesseur = $_POST['prenomProfesseur'];
    $sql1 = $pdo->prepare("UPDATE professeurs SET nomProfesseur = :nomProfesseur, prenomProfesseur = :prenomProfesseur WHERE idProfesseur = $id");
    $sql1->execute(array(
        ':nomProfesseur' => $nomProfesseur,
        ':prenomProfesseur' => $prenomProfesseur,
        ));



}

$sql2 = $pdo->prepare("SELECT * FROM classes");
$sql2->execute();


$resultat2 = $sql2->fetchAll(PDO::FETCH_ASSOC);

$sql3 = $pdo->prepare("SELECT MAX(idProfesseur) as lastIdProfesseur FROM `professeurs`");
$sql3->execute();


$resultat3 = $sql3->fetch(PDO::FETCH_ASSOC);


if ($_POST) {
    
    $sql5 = "DELETE FROM enseigne WHERE idProfesseur= $id";
    $sth = $pdo->prepare($sql5);
    $sth->execute();
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

    header("location: professeurs.php");
}
if ($_GET) {
    $id = $_GET['id'];
    $sql4 = $pdo->prepare("SELECT * FROM enseigne where idProfesseur =" . $id);
    $sql4->execute();


    $resultat4 = $sql4->fetch(PDO::FETCH_ASSOC);
}


include 'header.php';

    if ($_GET) {
        $id = $_GET['id'] ?>
        <form action="updateProfesseur?id=<?php echo $id?>" method="post">
            <input type="text" name="nomProfesseur" value="<?php echo $resultat['nomProfesseur'] ?>">
            <input type="text" name="prenomProfesseur" value="<?php echo $resultat['prenomProfesseur'] ?>">
            <div class="classInput">
            <?php
            foreach ($resultat2 as $key => $value) {
                if ($resultat4['idClasse'] === $value['idClasse']) {


            ?>
                    <input type="checkbox" name="classe<?php echo $key ?>" value="<?php echo $value['idClasse'] ?>"  checked>
                    <label><?php echo $value['nomClasse'] ?></label>
            <?php
                }else{ ?>
                    <input type="checkbox" name="classe<?php echo $key ?>" value="<?php echo $value['idClasse'] ?>">
                    <label><?php echo $value['nomClasse'] ?></label>
                <?php }
            }
            ?>
            </div>
            <input type="submit" value="Modifier le professeur">

        </form>
    <?php
    }

    include 'footer.php';
    ?>
    
