<?php
header('Content-Type: application/json');
include ("database_local.php");
$lista='';
$i=0;
// id pasado desde la lista de resultados
$sailor_id = $_GET['id'];

$query = $db->query("SELECT * FROM sailors WHERE id=".$sailor_id." ");
$row = $query->fetch_assoc();

//var_dump($row);
//die();

$result = $db->query("SELECT rank, date_1 FROM result_regatta
INNER JOIN events ON result_regatta.event_id=events.event_id
INNER JOIN regattas ON events.regatta_id=regattas.id
WHERE result_regatta.Skipper_id=".$sailor_id."
ORDER BY regattas.date_1 ASC "
);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}


echo json_encode($data);

?>
