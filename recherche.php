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
<h1>Recherche d'une citation</h1>
<hr />
</body>
</html>

<?php

include 'connexpdo.php';

$base = 'citations';
$user = 'postgres';
$password = 'root';

$conn = connexpdo($base, $user, $password);

echo '<form action="" method="post">
<div class="form-group">
    <label for="exampleFormControlSelect1">Auteur</label>
    <select name="auteur" class="form-control" id="exampleFormControlSelect1">';

foreach($conn->query("SELECT nom from auteur") as $data)
{
    echo '<option>'.$data[0].'</option>';
}

echo    '</select>
  </div>';

echo '<div class="form-group">
    <label for="exampleFormControlSelect1">Siècle</label>
    <select name="siecle" class="form-control" id="exampleFormControlSelect1">';

foreach($conn->query("SELECT numero from siecle") as $data)
{
    echo '<option>'.$data[0].'</option>';
}

echo    '</select>
  </div>
<button type="submit" class="btn btn-primary">Rechercher</button></form>
';
if($_POST['auteur'] && $_POST['siecle']){
    $auteur = $_POST['auteur'];
    $siecle = (int)$_POST['siecle'];


    $recherche = "SELECT c.phrase, a.nom, a.prenom, s.numero
from citation c, auteur a, siecle s
where c.auteurid = a.id
and c.siecleid = s.id
and a.nom = '$auteur'
and s.numero = $siecle;";


    $sth = $conn->prepare($recherche);
    $sth->execute();
    $result = $sth->fetchAll();
}

$rank = 1;

if (empty($result) && $_POST['auteur']) {
    echo "<h1>Aucun élément trouvé. </h1>";
}
if (!empty($result)) {
    echo "<table class=\"table\">
<thead>
<tr>
<th scope='col'></th>
<th scope='col'>Citation</th>
<th scope='col'>Prénom</th>
<th scope='col'>Nom</th>
<th scope='col'>Siècle</th>
</tr>
</thead>";
    foreach ($result as $val) {
        echo "
<tbody>
    <tr>
        <th scope=\"row\">$rank</th>
        <td>$val[0]</td>
        <td>$val[2]</td>
        <td>$val[1]</td>
        <td>$val[3]</td>
    </tr>";
        $rank++;
    }
    echo "</tbody></table>";
}

//$query = 'SELECT * FROM citation JOIN auteur ON citation.auteurid = auteur.id JOIN siecle ON citation.siecleid = siecle.id WHERE siecle.numero = ? AND auteur.nom = ?';
//$result = $conn->prepare($query);
//$result->execute(array($siecle, $auteur));
//$res = $result->fetchALL();




?>