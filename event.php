<?php
include ("database_local.php");
include ("funciones.php");

$lista=null;
$i=0;
// evento id pasado desde la url redirect
$event_id = $_GET['id'];

$query = $db->query("SELECT * FROM events INNER JOIN regattas ON events.regatta_id=regattas.id WHERE events.event_id=".$event_id." ");
$row = $query->fetch_assoc();
$regatta_id= $row["regatta_id"];
//echo $regatta_id;
//$datosEvent = buscarDatosEvent ($event_id);
//$datosRegatta = buscarDatosRegatta($regatta_id);

$query2 = $db->query("SELECT * FROM events INNER JOIN regattas ON events.regatta_id=regattas.id WHERE events.regatta_id=".$regatta_id." ");
while ($row2 = $query2->fetch_assoc()){
	$lista.="<a class='mini ui basic button' href='./event/".$row2["event_id"]."-".slugify($row2["name"]." ".$row2['event_name'])."'>".$row2["event_name"]."</a> ";
	$i++;
}

$m = datosRegatta($event_id, $db);
//var_dump($m);
/*
// para saber si hay medal y cuantos son
//$query = $db->query("SELECT * FROM result_regatta WHERE event_id=".$id." ORDER BY rank ASC");
$query3 = $db->query("SELECT * FROM result_regatta WHERE event_id='".$event_id."' ORDER BY `rank` ASC ");
$ret = [];
$i = 0;
while ($ro = $query3->fetch_array()) {
    foreach ($ro as $key => $value) {
			if ($value){$ret[$i][$key] = $value;}
          }
    $i++;
}

//calcular cantidad de regatas
$k=1; 
  while($ret[0]["R".$k.""]!= null){
    $k++;
  }
$totalRegatas = $k-1;
if($ret[4]["R".$totalRegatas.""]!= null && $ret[5]["R".$totalRegatas.""]== null){$m= 5;}else{
if($ret[9]["R".$totalRegatas.""]!= null && $ret[10]["R".$totalRegatas.""]== null){$m=10;}
if($ret[9]["R".$totalRegatas.""]!= null && $ret[10]["R".$totalRegatas.""]!= null){$m=null;}
}
//die();
*/
?>
<!DOCTYPE html>
    <html>
    <head>
      <!-- Standard Meta -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

      <!-- Site Properties -->
      <title><?php echo ($row["name"]). " > ".$row["event_name"]; ?></title>
      <base href="https://sailingresults.devsport.net/">
      <link rel="stylesheet" type="text/css" href="semantic/semantic.css">

      <script src="./lib/jquery.min.js"></script>
      <script src="./semantic/semantic.js"></script>

    </head>

    <body  >
			<div class="ui container">
				 <div class="ui panel">
      <h2><a class='ui primary button' href='./'>Results</a> <?php if($m['medal']!=null){?>
      <a class='ui warning button' href='./m.php?id=<?php echo $event_id;?>' target="_blank">Medal race</a> 
      <?php }?>
      <?php echo ($row["event_name"]." | ".$row["gender"]); ?> - <?php echo ($row["name"]); ?></h2>

			<?php echo $lista; ?>
      </div>
	   <!-- results list -->
     <?php include ("results.php"); ?>
      <!-- /results list -->
  
</div>
<!-- end template -->

    </body>
</html>
