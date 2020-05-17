<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Titre de la page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<!-- A grey horizontal navbar that becomes vertical on small screens -->
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
<h1>La citation du jour</h1>
<hr />
</body>
</html>

<?php

include 'connexpdo.php';

$base = 'citations';
$user = 'postgres';
$password = 'root';

$conn = connexpdo($base, $user, $password);

foreach($conn->query("SELECT count(*) FROM citation") as $data)
    echo 'Il y a '.$data['count'].' citations répertoriées.<br /><br />';

echo 'Et voici l\'une d\'enter elles qui est générée aléatoirement : <br /><br />';
$randInt = rand (1 , 6);
foreach($conn->query(
    "SELECT citation.phrase, auteur.nom, auteur.prenom, siecle.numero
              FROM auteur, citation, siecle
              WHERE auteur.id = citation.auteurid
              AND siecle.id = citation.siecleid
              AND citation.id = ($randInt)") as $data)
{
    echo '<b>'.$data[0].'</b><br />';
    echo   $data[1].' '.$data[2].' ('.$data[3].'ème siècle)';
}
