<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TP8_PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-sm bg-light">

    <!-- Links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="./citation.php">Informations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="recherche.php">Recherche</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="modification.php">Modification</a>
        </li>
    </ul>

</nav>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <form action="" method="post">
                <h1>Ajout</h1>
                <div class="form-group">
                    <label for="fname">Nom de l'auteur</label>
                    <input class="form-control" type="text" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="name">Prénom de l'auteur</label>
                    <input class="form-control" type="text" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="siecle">Siecle</label>
                    <input class="form-control" type="number" name="siecle" required>
                </div>
                <div class="form-group">
                    <label for="citation">Citation</label>
                    <input class="form-control" type="text" name="citation" required>
                </div>

                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>
</div>

<?php

include 'connexpdo.php';

$base = 'citations';
$user = 'postgres';
$password = 'root';

$conn = connexpdo($base, $user, $password);

$firstName = strtolower($_POST['firstName']);
$lastName = strtolower($_POST['lastName']);
$siecle = strtolower($_POST['siecle']);
$phrase = strtolower($_POST['citation']);

if (!empty($firstName) && !empty($lastName) && !empty($siecle) &&  !empty($phrase)) {
    $boolToAdd = true;

//=========================recuperation du nb d'auteurs, citation, et siecle pour les id

    // auteur
    $query = "select max(id) from auteur;";
    $sth = $conn->prepare($query);
    $sth->execute();
    $nb_auteur = $sth->fetchAll();

    // citation
    $query = "select max(id) from citation;";
    $sth = $conn->prepare($query);
    $sth->execute();
    $nb_citation = $sth->fetchAll();

    // siecle
    $query = "select max(id) from siecle;";
    $sth = $conn->prepare($query);
    $sth->execute();
    $nb_siecle = $sth->fetchAll();

// =============================== verification auteur

    $sth = $conn->prepare("Select nom, prenom from auteur;");
    $sth->execute();
    $result = $sth->fetchAll();

    foreach ($result as $loop) {
        if ($loop[0] == $firstName && $loop[1] == $lastName) {
            $boolToAdd = false;
            break;
        }
    }
    if ($boolToAdd) {
        $boolToAdd = false;
        $nb_auteur = $nb_auteur[0][0] + 1;
        $modif = "
    insert into auteur (id, nom, prenom)
        values (?,?,?);";
        $sqlR = $conn->prepare($modif);
        $sqlR->execute([$nb_auteur, $firstName, $lastName]) or die(print_r($conn->errorInfo(), TRUE));;
    }

// ============================ vérification siecle

    $numero = "Select numero from siecle;";
    $sth = $conn->prepare($numero);
    $sth->execute();
    $result = $sth->fetchAll();

    foreach ($result as $loop) {
        if ($loop[0] == $siecle) {
            $boolToAdd = false;
            break;
        }
    }
    if ($boolToAdd) {
        $boolToAdd = false;
        $nb_siecle = $nb_siecle[0][0] + 1;
        $modif = "
    insert into siecle (id, numero)
        values (?,?);";
        $sqlR = $conn->prepare($modif);
        $sqlR->execute([$nb_siecle, $siecle]) or die(print_r($conn->errorInfo(), TRUE));;
    }
//=====================================================================================================
//==================================== verification citation ==========================================

    $cit = "Select phrase from citation;";
    $sth = $conn->prepare($cit);
    $sth->execute();
    $result = $sth->fetchAll();

    foreach ($result as $loop) {
        if ($loop == $phrase) {
            $citation_existe = true;
        }
    }

    if (!$citation_existe) {

        $query = "select id from auteur where nom = '$firstName' and prenom = '$lastName'";
        $sth = $conn->prepare($query);
        $sth->execute();
        $auteurid = $sth->fetch();
        $auteurid = $auteurid[0];

        $query = "select id from siecle where numero = $siecle;";
        $sth = $conn->prepare($query);
        $sth->execute();
        $siecleid = $sth->fetch();
        $siecleid = $siecleid[0];

        $nb_citation = $nb_citation[0][0] + 1;
        $modif = "
    insert into citation (id, phrase, auteurid, siecleid)
        values (?,?,?,?);";
        $sqlR = $conn->prepare($modif);
        $sqlR->execute([$nb_citation, $phrase, $auteurid, $siecleid]) or die(print_r($conn->errorInfo(), TRUE));;
    }
}


?>
<br />
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <form action="" method="post">
                <h1>Suppression</h1>
                <div class="form-group">
                    <label>choissiez l'id d'une citation</label>
                    <input class="form-control" type="number" name="delete" required>
                </div>
                <button type="submit" class="btn btn-primary">Effacer</button>
            </form>
        </div>
    </div>
</div>

<?php

if (!empty($_POST['delete'])){
    $delete = (int)$_POST['delete'];
    $query = "delete from citation where id = $delete";
    $sth = $conn->prepare($query);
    $sth->execute();
    echo " <p>Citation supprimée ! </p>";
}

?>

</body>
</html>
