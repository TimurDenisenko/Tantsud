
<?php
require_once("conf.php");
if (isset($_REQUEST["punktid0"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=0 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["punktid0"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST["naitamine"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["naitamine"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST["peitmine"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=0 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["peitmine"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST["login"]))
{
    ?>
    <div class="modal">
    <?php require("login.php"); ?>
    </div>
<?php
}
function isAdmin()
{
    if(isset($_SESSION['onAdmin']))
    {
        if ($_SESSION['onAdmin']==1)
            return true;
        else
            return false;
    }
    else
        return false;
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tantsud täthtedega</title>
    <link rel="stylesheet" href="tantatht.css">
</head>
<body>
<?php
session_start();?>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <?php
    } ?>
<div>
    <?php
    if(isAdmin()){
    ?>
    <nav>
        <div>
            <ul>
                <li><a href="adminLeht.php">Admnistreerimisleht</a></li>
                <li><a href="haldusleht.php">Punktide lisamine</a></li>
                <?php
                if(isset($_SESSION['kasutaja'])){
                ?>
                <li><a href="logout.php">Logi välja</a></li>
                    <?php
                } else {
                    ?>
                <li> <a href="login.php">Logi sisse</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
<h1>Tantsud tähtedega</h1>
<h2>Administreerimisleht</h2>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Paev</th>
        <th>Avalik</th>
        <th>Kommentaar</th>
        <th>Tegevus</th>
        <th>Näita/Peita</th>
    </tr>
    <?php
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id, tantsupaar, punktid,ava_paev,kommentaarid,avalik FROM tantsud");
    $kask->bind_result($id,$tantsupaar,$punktid,$ava_paev,$komment,$avalik);
    $kask->execute();
    while($kask->fetch())
    {
        $tekst="Näita";
        $seisund="naitamine";
        if($avalik==1){
            $tekst="Peida";
            $seisund="peitmine";
        }
        $tantsupaar=htmlspecialchars($tantsupaar);
        echo "<tr><td>$tantsupaar</td>";
        echo "<td>$punktid</td>";
        echo "<td>$ava_paev</td>";
        echo "<td>$avalik</td>";
        echo "<td>$komment</td>";
        echo "<td><a href='?punktid0=$id'>Punktid Nulliks</a></td>";
        echo "<td><a href='?$seisund=$id'>$tekst</a> </td>";
        echo "</tr>";
    }
    ?>
</table>
</div>
<?php }
else
{
    ?>
    <nav>
        <div>
            <ul>
                <li><a href="haldusleht.php">Punktide lisamine</a></li>
                <?php
                if(isset($_SESSION['kasutaja'])){
                    ?>
                <li><a href="logout.php">Logi välja</a></li>
                    <?php
                } else {
                    ?>
                <li> <a href="?login">Logi sisse</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
    <?php
}
?>
</body>
</html>

