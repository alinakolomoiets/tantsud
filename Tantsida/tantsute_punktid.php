
<?php
require_once ('function_tantsida.php');

session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: login.php');
    exit();
}


//Otsi
/*if(isset($_REQUEST['otsisona'])){
    $otsisona=$_REQUEST['otsisona'];
}
$tantsud=kysiTansudAndmed($otsisona);*/
//Uue tantsupaari lisamine
if(!empty($_REQUEST['paarinimi'])){
    global $yhendus;
    $kaks=$yhendus->prepare("INSERT INTO tantsud (tantsupaar,pilt,avaliku_paev) VALUES(?,?,NOW())");
    $kaks->bind_param("ss",$_REQUEST['paarinimi'],$_REQUEST['pilt']);
    $kaks->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//kommentaaride lisamine
if(isSet($_REQUEST ['uuskomment'])){
    if(!empty($_REQUEST ['komment'])) {
    global $yhendus;
    $kaks = $yhendus->prepare("UPDATE tantsud SET kommentaarid=CONCAT(kommentaarid,?) WHERE id=?");
    $kommentplus=$_REQUEST['komment']."\n";
    $kaks->bind_param("si",$kommentplus,$_REQUEST ['uuskomment']);
    $kaks->execute();

    header("Location: $_SERVER[PHP_SELF]");
    }
}
//punktide lisamine
if(isSet($_REQUEST['punkt'])){
    global $yhendus;
    $kaks=$yhendus->prepare('UPDATE tantsud SET punktid=punktid+1 WHERE id=?');
    $kaks->bind_param("s",$_REQUEST['punkt']);
    $kaks->execute();

    header("Location: $_SERVER[PHP_SELF]");
}
//$tantsud=kysiTantsudeAndmed($sorttulp,$otsisona);
?>
<!DOCTYPE html>
<html lang="et">
<link rel="stylesheet" type="text/css"  href="style_tantsi.css">
<head>
    <h1>Tantsupaar TARpv21</h1>
    <ul><h3>Kasutaja leht</h3></ul>
<nav id="nav">
    <ul>
    <a href="tantsute_punktid.php">Kasutaja leht</a>
    <a href="admin.php">Admin leht</a>
    </ul>
</nav>

    <body>
    <table id="table">
        <tr>
            <th>
                <a href="tantsute_punktid.php?sorttulp=tantsupaar">Tantsupaar</a>
            </th>
            <th>
                <a href="tantsute_punktid.php?sorttulp=punktid">Punktid</a>
            </th>
            <th>
                Haldus
            </th>
            <th>
                Pilt
            </th>
            <th>
                Kommentaarid
            </th>
            <th>
                Uus kommentaarid
            </th>

        </tr>

        <!--tabeli sisu näitamine-->
        <?php
        global $yhendus ;
        $kaks=$yhendus->prepare('SELECT id , tantsupaar , punktid ,pilt ,kommentaarid FROM tantsud Where avalik=1');
        $kaks->bind_result( $id , $tantsupaar , $punktid,$pilt ,$kommentaarid);
        $kaks->execute();
        while($kaks->fetch()){
            echo "<tr>";
            echo "<td>".$tantsupaar."</td>";
            echo "<td>".$punktid."</td>";
            echo "<td><a href='?punkt=$id'>Lisa 1 punktid</a></td>";
            echo"<td><img src ='$pilt' alt='pilt' width='50%'></td>";
            $kommentaarid=nl2br(htmlspecialchars($kommentaarid));
            echo "<td>".$kommentaarid."</td>";
            echo "<td>
 <form action='?'>
 <input type='hidden' value='$id' name='uuskomment'>
 <input type='text' name='komment'>
 <input type='submit' value='OK'>
</form></td>";
            echo"</tr>";
        }
        ?>
    </table>
<div id="menu2">
    <h2>Uue tantsupaari lisamine</h2>
    <form action="?">
        <input type="text" placeholder="Tantsupaari nimed" name="paarinimi">
        <textarea name="pilt">Siia lisa pildi aadress</textarea>
        <input type="submit" value="OK">
    </form>
    <h2>Otsi tabel</h2>
    <form action="?">
        <input type="text" name="otsisona" placeholder="Otsi...">
    </form>

</div>
</div>
</body>
</head>
</html>