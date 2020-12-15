<?php
include ("database_local.php");
$data = array();
$lista='';
$listaregattas='';
$i=0;
// id pasado desde la lista de resultados
$q = filter_var ( $_REQUEST['q'], FILTER_SANITIZE_STRING);
if(str_word_count($q)>1){
    
                $d   = preg_split('/\s+/', $q);
                $f = reset($d);
                $l = end($d);

	            $result = $db->query("SELECT id, isaf_id, firstname, surname, nation FROM sailors WHERE (firstname LIKE '".$f."%' AND surname LIKE '".$l."%') ");

                
            }else{
                $result = $db->query("SELECT id, isaf_id, firstname, surname, nation FROM sailors WHERE (firstname LIKE '".$q."%' OR surname LIKE '".$q."%' OR isaf_id
                 LIKE '".$q."%') ");

                $resultregattas = $db->query("SELECT * FROM regattas WHERE (`name` LIKE '".$q."%' OR web LIKE '%".$q."%' OR location LIKE '%".$q."%' OR city LIKE '".$q."%' OR country LIKE '".$q."%')ORDER BY regattas.date_1 DESC");

            }


foreach ($result as $row) {
        $data[] = $row;
        $lista.="<div class='item'><a href='sailor.php?id=".$row['id']."'> ".$row['firstname']." ".$row['surname']." | ".$row['isaf_id']."</a></div>";
        }

foreach ($resultregattas as $row) {
        $data[] = $row;
        $listaregattas.="<div class='item'><i class='trophy icon'></i><div class='content'><a href='regatta.php?id=".$row['id']."'> ".$row['name'].", ".$row['city']." | ".$row['country']."</a></div></div>";
        }

if($_REQUEST['type']=='l'){
   echo("<ul class='ui list'>$lista</ul><ul class='ui list'>$listaregattas</ul>");
}else{
header('Content-Type: application/json');
echo json_encode($data);
}
?>