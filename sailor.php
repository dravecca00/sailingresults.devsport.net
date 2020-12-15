<?php
include ("database_local.php");
include ("funciones.php");

$lista='';
$i=0;
// id pasado desde la lista de resultados
$sailor_id = $_GET['id'];
$conquien='';

$query = $db->query("SELECT * FROM sailors WHERE id=".$sailor_id." ");
$row = $query->fetch_assoc();

//var_dump($row);
//die();

$query2 = $db->query("SELECT * FROM result_regatta
INNER JOIN events ON result_regatta.event_id=events.event_id
INNER JOIN regattas ON events.regatta_id=regattas.id
WHERE (result_regatta.Skipper_id=$sailor_id OR result_regatta.Crew_id=$sailor_id )
ORDER BY regattas.date_1 DESC "
);

while ($row2 = $query2->fetch_assoc()){
	//var_dump($row2);
	//die();
	if($row2['Skipper_id']==$sailor_id){//es timonel, chequea si hay tripu
		if($row2['CrewName']!=''){// hay tripu, se arma el texto con el tripu
			if(isset($row2['Crew_id'])){ $conquien = "crew: <a href='./sailor/".$row2['Crew_id']."-".slugify($row2['CrewName']." ".$row2['CrewSurname'])."'>".$row2['CrewName']." ".$row2['CrewSurname']."</a>";
			}else{$conquien = "crew: ".$row2['CrewName']." ".$row2['CrewSurname'];}
			
		}else{$conquien='';}
	}
	if($row2['Crew_id']==$sailor_id){//es trupu, chequea timo
		if($row2['SkipperName']!=''){// hay timo
			if(isset($row2['Skipper_id'])){ $conquien = "skipper: <a href='./sailor/".$row2['Skipper_id']."-".slugify($row2['SkipperName']." ".$row2['SkipperSurname'])."'>".$row2['SkipperName']." ".$row2['SkipperSurname']."</a>";
			}else{$conquien = "skipper: ".$row2['SkipperName']." ".$row2['SkipperSurname'];}
		}else{$conquien='';}
	}
	$lista.="<tr><td>".$row2["rank"]. "</td><td><a href='event/".$row2["event_id"]."-".slugify($row2['name']." ".$row2['event_name'])."'>" .$row2["name"]."</a></td><td>".$row2["event_name"]."</td><td>".$conquien."</td><td> ".$row2["date_1"]."</td> ";
	$i++;
$gender=$row2["gender"];
}

$lista="<table class='ui unstackable single line table'>".$lista."</table>";

$url_avatar="";

//buscamos imagen en wikimedia
// https://en.wikipedia.org/w/api.php?format=json&action=query&prop=images&titles=Santiago_Lange
$namesurname = urlencode(str_replace(" ","_",$row['firstname']."_".$row['surname']));

$nombrecompleto = $row['firstname']." ".$row['surname'] ;
/*
$wikiapi = "https://en.wikipedia.org/api/rest_v1/page/media/$namesurname";

$res =@file_get_contents($wikiapi);
if($res != false){
	$res = json_decode($res);
	$f=$res->items;
	$url_avatar=$f[0]->srcset[0]->src;
}


*/

//////////*
/*
switch ($gender) {
	case 'Men':
		// code...
		$url_avatar="./images/matthew.png";
		break;
	case 'Women':
		// code...
		$url_avatar="./images/kristy.png";
		break;
	default:
		// code...
		$url_avatar="./images/image.png";
		break;
}

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
		<title><?php echo ($row["firstname"]." ".$row["surname"]); ?></title>
		<meta name="description" content="Sailor regattas results: <?php echo ($nombrecompleto.", ".$row['yacht_club']); ?>. Latest events competed. 
		Find the medal race calculator and more results."/>

		<base href="https://sailingresults.devsport.net/">
		<link rel="stylesheet" type="text/css" href="semantic/semantic.css">

		<script src="./lib/jquery.min.js"></script>
		<script src="./semantic/semantic.js"></script>
		<script src="./lib/Chart.bundle.min.js"></script>
		<style type="text/css">
				#chart-container {
					width: 100%;
					height: auto;
							}
		</style>
</head>
<body>

	<div class="ui container">
	<div class="ui one item menu">
		<a href="https://sailingresults.devsport.net/" title="More sailing results and medal race calculator">Sailing Results</a>
		
		</div>
	<div class="ui two column stackable grid">
		<div class="six wide column">
			<div class="ui link cards">
			<div class="card">
				<div class="image">
					<img src="<?php echo($url_avatar); ?>">
				</div>
				<div class="content">
					<div class="header"><?php print($nombrecompleto); ?></div>
					<div class="meta">
						<a><?php echo ($row["isaf_id"]." | ".$row["nation"]); ?></a>
					</div>
					<div class="description">
					<?php if(isset($row["yacht_club"]) AND $row["yacht_club"] !=''){
							print($row["yacht_club"]);
							};

							if(isset($row["yacht_club_location"]) AND $row["yacht_club_location"] !=''){
							print(" - ".$row["yacht_club_location"]);
						};?>
					</div>
				</div>
				<div class="extra content">
				<span class="right floated">
					<?php if(isset($row["campaign_website"]) AND $row["campaign_website"] !=''){
						$url_info = parse_url($row["campaign_website"]);
						if($url_info['scheme']==''){$website = "http://".$row["campaign_website"];}else{$website = $row["campaign_website"];}
							//print_r($url_info);

							print("<p>Campaign Website: <a href='".$website ."' target=_blank>".$row["campaign_website"]."</a></p>");
						};?>
				</span>
          		</div>
			</div>
		</div>
	</div>

	<div class="ten wide column">
		<?php print $lista; ?>
	</div>
	</div>

	<div id="chart-container">
			<canvas id="graphCanvas"></canvas>
	</div>
	
	<script>
        $(document).ready(function () {
            showGraph();
        });


        function showGraph()
        {
            {
                $.post("json.php?id=<?php echo $sailor_id;?>",
                function (data)
                {
                    console.log(data);
                     var date_1 = [];
                    var rank = [];
										var topten = [];

                    for (var i in data) {
                        date_1.push(data[i].date_1);
                        rank.push(data[i].rank);
												topten.push(10);
                    }

                    var chartdata = {
                        labels: date_1,
                        datasets: [
                            {
                                label: 'Overall position',
                                borderColor: 'lightblue',
																backgroundColor: '',
                                hoverBackgroundColor: '#111111',
                                hoverBorderColor: '#666666',
                                data: rank
                            },
														{
															label: 'Top ten',
															data: topten,
															borderDash: [10,5],
															backgroundColor: 'lightyellow',
														}
                        ]
                    };

										var chartOptions = {
	  scales: {
	    yAxes: [{
				ticks: {
	      	min: 1, reverse:true, max:50
				}
	    }],
	    xAxes: [{
				ticks: {
	      autoSkip:true
				}
	    }]
	  }
	};

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'line',
                        data: chartdata,
												options: chartOptions
                    });
                });
            }
        }
        </script>

	<?php
$tabla="";
 $nomostrar=['id', 'firstname', 'surname', 'sailor_id', 'isaf_id', 'nation', 'yacht_club', 'yacht_club_location',
 'public_email', 'campaign_website'];
 foreach ($row as $key => $value){
   if(!empty($value) AND !in_array($key, $nomostrar))
     {
       $campo = ucwords(str_replace("_"," ", $key));
         $tabla.=  "<tr>
	 	      <td class='two wide column'>$campo</td>
	 	      <td>$value</td>
					</tr>";
     }
 }
 ?>
	<h4 class="ui horizontal divider header">
	  <i class="bar chart icon"></i>
	  Other information
	</h4>
	<table class="ui definition table">
	  <tbody>
	   <?php echo $tabla; ?>
	  </tbody>
	</table>



</div>
</div>
    </body>
</html>
