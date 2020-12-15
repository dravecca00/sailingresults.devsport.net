<?php
include ("database_local.php");
include ("funciones.php");

// evento id pasado desde la url redirect
$regatta_id = $_GET['id'];
$i=0;
$lista=null;
$clases = null;

$query = $db->query("SELECT * FROM events INNER JOIN regattas ON events.regatta_id=regattas.id WHERE events.regatta_id=".$regatta_id." ");
while ($row = $query->fetch_assoc()){
	$data[$i]=$row;
  $lista.="<div><a href='./event/".$row["event_id"]."-".slugify($data[0]["name"]." ".$row['event_name']." ".$row['gender'])."'>".$row["event_name"]." | ".$row["gender"]."</a></div>";
  $clases.= $row["event_name"]." | ";
	$i++;
}


//$datosEvent = buscarDatosEvent ($event_id);
//$datosRegatta = buscarDatosRegatta($regatta_id);

//var_dump($data);
//die();
?>
<!DOCTYPE html>
    <html>
    <head>
      <!-- Standard Meta -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

      <!-- Site Properties -->
      <title><?php echo ($data[0]["name"]." > ". $clases); ?></title>
      <base href="https://sailingresults.devsport.net/">

      <link rel="stylesheet" type="text/css" href="semantic/semantic.css">

      <script src="./lib/jquery.min.js"></script>
      <script src="./semantic/semantic.js"></script>

    </head>


    <body>
			<div class="ui container">
			   <div>
			  	<div>

      <h2 ><a class='ui button' href='./'>Results</a> <?php print $data[0]["name"]; ?></h2>
      <ul><?php print $lista; ?></ul>
      <hr>
    </div>
  </div>
</div>
<!-- end template -->

    </body>
</html>
