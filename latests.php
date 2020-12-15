<?php
	$ret=[];
	$data=null;
	$value=null;
	$i=0;

    $query = $db->query("SELECT * FROM regattas ORDER BY regattas.date_1 DESC LIMIT 5");

	while ($row = $query->fetch_assoc()) {
        foreach ($row as $key => $value) {
            $ret[$i][$key] = $value;
            }
        $i++;
		$data.= "<li><a href='regatta/".$row['id']."-".slugify($row['name']." ".$row['city'])."'>".$row['name']."</a>, ".$row['city'].", ".$row['country']."</li>";
    }

    //return json data
   //var_dump ($ret);

   //or print list
   print $data;
?>
