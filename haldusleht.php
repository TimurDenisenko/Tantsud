<?php
require_once("conf.php");
if (isset($_REQUEST["heatans"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid+1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["heatans"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST["halbtans"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid-1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["halbtans"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO tantsud(tantsupaar,ava_paev) VALUES (?, NOW())");
    $kask->bind_param("s",$_REQUEST["paarinimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
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
if (isset($_REQUEST["login"]))
{
    ?>
    <div class="modal">
        <?php require("login.php"); ?>
    </div>
    <?php
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
<header>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <?php
    } else {
        ?>
        <?php
    }
    ?>
</header>
<div>
    <nav>
        <div>
            <ul>
                <?php
                if(isAdmin()) {
                ?>
                <li><a href="adminLeht.php">Admnistreerimisleht</a></li>
                <?php } ?>
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
<h1>Tantsud tähtedega</h1>
<h2>Punktide lisamine</h2>
<form action="?">
    <label for="paarinimi">Lisa uue paar</label>
    <input type="text" name="paarinimi" id="paarinimi">
    <input type="submit" value="OK">
</form>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Ava päev</th>
        <?php
        if (!isAdmin()){
        ?>
        <th>Tegevus</th>
        <?php }
        ?>
    </tr>
    <?php
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, avalik FROM tantsud");
    $kask->bind_result($id,$tantsupaar,$punktid,$ava_paev,$avalik);
    $kask->execute();
    while($kask->fetch())
    {
        if ($avalik==1) {
            $tantsupaar = htmlspecialchars($tantsupaar);
            echo "<tr><td>$tantsupaar</td>";
            echo "<td>$punktid</td>";
            echo "<td>$ava_paev</td>";
            if (!isAdmin()) {
                echo "<td><a href='?heatans=$id'>Lisa punkt | </a><a href='?halbtans=$id'>Eemalda punkt</a></td>";
            }
            echo "</tr>";
        }
    }
    ?>
</table>
</div>
</body>
</html>