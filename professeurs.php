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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Ecolog <sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface de gestion
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Classes</span>
                </a>

            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Professeurs</span>
                </a>

            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Elèves</span>
                </a>

            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>



                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

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
                        <?php }
                        ?>
                        </tbody>
                    </table>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Nicolas Gicquel 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>


</body>

</html>