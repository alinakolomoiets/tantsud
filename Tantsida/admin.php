<?php

session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ABlogin.php');
    exit();
}

require ('connect_tantsida.php');
//sesion algus
//punktide nulliks
if(isSet($_REQUEST['punkt0'])){
    global $yhendus;
    $kaks=$yhendus->prepare('UPDATE tantsud SET punktid=0 WHERE id=?');
    $kaks->bind_param("s",$_REQUEST['punkt0']);
    $kaks->execute();

    header("Location: $_SERVER[PHP_SELF]");
}
//peatamine
if(isSet($_REQUEST['peatamine'])){
    global $yhendus;
    $kaks=$yhendus->prepare('UPDATE tantsud SET avalik=0 WHERE id=?');
    $kaks->bind_param("i",$_REQUEST['peatamine']);
    $kaks->execute();
}
//naitamine
if(isSet($_REQUEST['naitamine'])){
    global $yhendus;
    $kaks=$yhendus->prepare('UPDATE tantsud SET avalik=1 WHERE id=?');
    $kaks->bind_param("i",$_REQUEST['naitamine']);
    $kaks->execute();
}
//Kustuta andmed
if(!empty($_REQUEST['andmed'])){
    global $yhendus;
    $kaks=$yhendus->prepare("DELETE FROM tantsud  WHERE id=?");
    $kaks->bind_param("s",$_REQUEST['andmed']);
    $kaks->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//Kustuta kommentaarid
if(isSet($_REQUEST['komment'])){
    global $yhendus;
    $kaks=$yhendus->prepare('UPDATE tantsud SET kommentaarid=" " WHERE id=?');
    $kaks->bind_param("s",$_REQUEST['komment']);
    $kaks->execute();

    header("Location: $_SERVER[PHP_SELF]");
}

?>
<!DOCTYPE html>
<html lang="et">
<link rel="stylesheet" type="text/css"  href="style_tantsi.css">
<head>
    <div>
        <?php
        echo $_SESSION['kasutaja']?> on sisse logitud
    <form action="logout.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
    </div>
    <h1>Tantsupaar</h1>
    <ul><h3>Kasutaja leht</h3></ul>
    <nav id="nav">
        <ul>
            <a href="tantsute_punktid.php">Kasutaja leht</a>
            <a href="admin.php">Admin leht</a>
        </ul>
    </nav>
</head>
<body>
<table id="table">
    <tr>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid
            <br>
            Punktid nulliks
        </th>
        <th>
            Pilt
        </th>
        <th>
            Kommentaarid
            <br>
            Kustuta kommentaarid
        </th>
        <th>
            AvaliKustamine status
        </th>
        <th>
           AvaliKustamise päev
        </th>

    </tr>
<?php
global $yhendus ;
$kaks=$yhendus->prepare('SELECT id , tantsupaar , punktid , pilt,kommentaarid , avaliku_paev , avalik FROM tantsud ');
$kaks->bind_result( $id , $tantsupaar , $punktid, $pilt ,$kommentaarid,$avaliku_paev,$avalik);
$kaks->execute();
while($kaks->fetch()){

    $tekst='Näita';
    $seisund='naitamine';
    $kasatajatekst='Kasutaja ei näe';
    if($avalik==1){
        $tekst='Peida';
        $seisund='peatamine';
        $kasatajatekst='Kasutaja ei näeb';}
    echo "<tr>";
    echo "<td>".$tantsupaar."<br><a href='?andmed=$id'>kustuta paari</a></td>";
    echo "<td>".$punktid."<br><a href='?punkt0=$id'>punktid nulliks</a></td>";
    echo"<td><img src ='$pilt' alt='pilt' width='50%'></td>";
    echo "<td>".$kommentaarid."<br><a href='?komment=$id'>kustuta kommentaari</a></td>";
    echo "<td>  $kasatajatekst<br>
    <a href='?$seisund=$id'>$tekst</a><br>
    </td>";
    echo "<td>".$avaliku_paev."</td>";}
    ?>
</table>
</body>
</html>