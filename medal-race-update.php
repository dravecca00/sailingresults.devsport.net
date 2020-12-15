<?php include ("./database_local.php"); 

//var_dump($_POST);
// recibe posts desde m.php 


$lid = $_POST["lid"];
$hashtext = $_POST["h"];
$camp = $_POST["j"];

/*
$camp = array(
    array(
        "team" => $_POST["T0"] ,
        "points" => $_POST["P0"],
        "medal" => 2
    ),
    array(
        "team" => $_POST["T1"] ,
        "points" => $_POST["P1"],
        "medal" => 4
    ),
    array(
        "team" => $_POST["T2"] ,
        "points" => $_POST["P2"],
        "medal" => 6
    ),
    array(
        "team" => $_POST["T3"] ,
        "points" => $_POST["P3"],
        "medal" => 8
    ),
    array(
        "team" => $_POST["T4"] ,
        "points" => $_POST["P4"],
        "medal" => 10
    ),
    array(
        "team" => $_POST["T5"] ,
        "points" => $_POST["P5"],
        "medal" => 12
    ),
    array(
        "team" => $_POST["T6"] ,
        "points" => $_POST["P6"],
        "medal" => 14
    ),
    array(
        "team" => $_POST["T7"] ,
        "points" => $_POST["P7"],
        "medal" => 16
    ),
    array(
        "team" => $_POST["T8"] ,
        "points" => $_POST["P8"],
        "medal" => 18
    ),
    array(
        "team" => $_POST["T9"] ,
        "points" => $_POST["P9"],
        "medal" => 20
    ),

);
*/
/*

[{"team": "ARG", "points": "65", "medal": "12"}, {"team": "AUS", "points": "74", "medal": "4"}, 
{"team": "AUT", "points": "72", "medal": "6"}, {"team": "NZL", "points": "79", "medal": "2"}, 
{"team": "ITA", "points": "70", "medal": "14"}, {"team": "FRA", "points": "83", "medal" : "10"}, 
{"team": "SUI", "points": "80", "medal": "20"}, {"team": "USA", "points": "98", "medal": "8"}, 
{"team": "GBR", "points": "91", "medal":"18"}, {"team": "BRA", "points": "101", "medal": "16"}]
*/
//$c = json_encode($camp);
$q = "UPDATE `medal` SET `teams`='$camp' WHERE id=$lid ";

$query = $db->query($q);

//$lid = $db->insert_id;

//header("Location: ./m.php?h=$hashtext&lid=$lid");

?>
