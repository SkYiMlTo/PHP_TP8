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
</body>
</html>

<?php

include 'connexpdo.php';

$base = 'citations';
$user = 'postgres';
$password = 'root';

$conn = connexpdo($base, $user, $password);

echo "<h1>Auteurs de la DB</h1>";
echo '<table>
    <thead>
        <tr>
            <th>Prénom</th>
            <th>Nom</th>
        </tr>
    </thead>
    <tbody>';
foreach($conn->query("SELECT nom, prenom from auteur") as $data)
{
    echo '<tr>
            <td>'.$data[1].'</td>
            <td>'.$data[0].'</td>
        </tr>';
}
echo '
    </tbody>
</table>';


echo "<h1>Citations de la DB</h1>";
echo '<table>
    <tbody>';
foreach($conn->query("SELECT phrase from citation") as $data)
{
    echo '<tr>
            <td>'.$data[0].'</td>
        </tr>';
}
echo ' 
    </tbody>
</table>';


echo "<h1>Siècles de la DB</h1>";
echo '<table>
    <tbody>';
foreach($conn->query("SELECT numero from siecle") as $data)
{
    echo '<tr>
            <td>'.$data[0].'</td>
        </tr>';
}
echo ' 
    </tbody>
</table>';