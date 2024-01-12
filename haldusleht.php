<?php
session_start();
ob_start();
require_once("conf2.php");
if (isset($_REQUEST["heatans"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid+1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["heatans"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
if (isset($_REQUEST["halbtans"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid-1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["halbtans"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
if (isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"]))
{
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO tantsud(tantsupaar,ava_paev) VALUES (?, NOW())");
    $kask->bind_param("s",$_REQUEST["paarinimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
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
if(isset($_REQUEST["komment"])){
    if(!empty($_REQUEST["uuskomment"])){
        global $yhendus;
        $kask = $yhendus->prepare("UPDATE tantsud SET kommentaarid=CONCAT(kommentaarid, ?) WHERE id=?");
        $kommentplus=$_REQUEST["uuskomment"]." \n";
        $kask->bind_param("si", $kommentplus, $_REQUEST["komment"]);
        $kask->execute();
        header("Location: $_SERVER[PHP_SELF]");
        exit();
        $yhendus->close();
    }
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
                <?php }
                if(isset($_SESSION['kasutaja'])){
                ?>
                <li><a href="haldusleht.php">Punktide lisamine</a></li>
                <?php }
                if(isset($_SESSION['kasutaja'])){
                    ?>
                <li><a href="logout.php">Logi välja</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
    <?php
if (isset($_SESSION['kasutaja'])){
    ?>
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
        <th>Kommentaar</th>
        <?php
        if (!isAdmin()){
        ?>
        <th>Tegevus</th>
        <?php }
        ?>
    </tr>
    <?php
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, avalik, kommentaarid FROM tantsud");
    $kask->bind_result($id,$tantsupaar,$punktid,$ava_paev,$avalik,$komment);
    $kask->execute();
    while($kask->fetch())
    {
        if ($avalik==1) {
            $tantsupaar = htmlspecialchars($tantsupaar);
            echo "<tr><td>$tantsupaar</td>";
            echo "<td>$punktid</td>";
            echo "<td>$ava_paev</td>";
            echo "<td>".nl2br(htmlspecialchars($komment))."</td>";
            if (!isAdmin()) {
                echo "<td>
            <form action='?'>
                <input type='hidden'  value='$id' name='komment'>
                <input type='text' name='uuskomment' id='uuskomment'>
                <input type='submit' value='OK'>
            </form>
            <a href='?heatans=$id'>Lisa punkt | </a><a href='?halbtans=$id'>Eemalda punkt</a></td>";
            }
            echo "</tr>";
        }
    }
    ?>
</table>
    <?php }
else
{
    ?>
    <nav>
        <div>
            <ul>
                <?php
                if(isset($_SESSION['kasutaja'])){
                    ?>
                    <li><a href="haldusleht.php">Punktide lisamine</a></li>
                    <li><a href="logout.php">Logi välja</a></li>
                    <?php
                } else {
                    ?>
                        <h1 class="h1">Tantsud tähtedega</h1>
                    <li>
                        <button onclick="openModal()">Logi sisse</button>
                        <div id="myModal" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal()">&times;</span>
                                <?php include 'login.php'; ?>
                            </div>
                        </div>
                        <script>
                            function openModal() {
                                document.getElementById('myModal').style.display = 'block';
                            }

                            function closeModal() {
                                document.getElementById('myModal').style.display = 'none';
                            }
                        </script>
                    </li>
                    <li>
                        <button onclick="openModal1()">Registreerimine</button>
                        <div id="myModal1" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal1()">&times;</span>
                                <?php include 'register.php'; ?>
                            </div>
                        </div>
                        <script>
                            function openModal1() {
                                document.getElementById('myModal1').style.display = 'block';
                            }

                            function closeModal1() {
                                document.getElementById('myModal1').style.display = 'none';
                            }
                        </script>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
    <?php
}
?>
</div>
</body>
</html>