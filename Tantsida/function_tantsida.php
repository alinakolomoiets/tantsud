
<?php
require_once('connect_tantsida.php');


function kysiTansudAndmed($sorttulp="tantsupaar", $otsisona=""){
    global $yhendus;
    $lubatudtulbad=array("tantsupaar", "kommentaarid", "punktid");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    $otsisona=addslashes(stripslashes($otsisona));}
?>