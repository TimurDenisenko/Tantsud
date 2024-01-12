<?php
session_start();
ob_start();
require_once("conf2.php");
global $yhendus;

//kontrollime kas väljad registreerimisvormis on täidetud
if (!empty($_POST['register_login']) && !empty($_POST['register_pass'])) {
    //eemaldame kasutaja sisestusest kahtlase pahna
    $login = htmlspecialchars(trim($_POST['register_login']));
    $pass = htmlspecialchars(trim($_POST['register_pass']));

    // Lisage siia vajalikud kontrollid, näiteks parooli tugevuse kontroll

    //SIIA UUS KONTROLL
    $cool = "kassjakoer";
    $kryp = crypt($pass, $cool);

    //kontrollime, kas andmebaasis on juba selline kasutajanimi
    $kasutaja_kontroll_kask = $yhendus->prepare("SELECT kasutaja FROM kasutaja WHERE kasutaja=?");
    $kasutaja_kontroll_kask->bind_param("s", $login);
    $kasutaja_kontroll_kask->execute();

    // kui kasutajanimi on juba võetud, siis väljastame veateate
    if ($kasutaja_kontroll_kask->fetch()) {
        echo "Kasutajanimi '$login' on juba võetud. Palun valige teine kasutajanimi.";
        $kasutaja_kontroll_kask->close();
        $yhendus->close();
        exit();
    }

    // kui kasutajanimi on vaba, siis lisame uue kasutaja andmebaasi
    $kasutaja_kontroll_kask->close();

    $kasutaja_lisamine_kask = $yhendus->prepare("INSERT INTO kasutaja (kasutaja, parool) VALUES (?, ?)");
    $kasutaja_lisamine_kask->bind_param("ss", $login, $kryp);
    $kasutaja_lisamine_kask->execute();

    echo "Registreerimine õnnestus! Logige nüüd sisse.";

    $kasutaja_lisamine_kask->close();
    $yhendus->close();
    exit();
}

?>

<h1>Registreeri</h1>
<form action="" method="post">
    Kasutajanimi: <input type="text" name="register_login"><br>
    Parool: <input type="password" name="register_pass"><br>
    <!-- Lisage siia muud väljad, mida soovite registreerimisvormis kasutada -->
    <input type="submit" value="Registreeri">
</form>
