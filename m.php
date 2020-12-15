<?php
include ("database_local.php");
include ("funciones.php");

$ret=[];
$data=null;
$value=null;
$listanm=null;
$sortabl=null;
$listapa=null;
$campeonato=null;
$campeParcial=null;
$equipos=null;
$competidores=10;
//$mododebug=1;
$i=0;

// se recibe por url un orden x de medal, es la variacion hecha por el user que qiuere compartir
$variacion = (isset($_REQUEST['v'])?$_REQUEST['v']:"");
$variaciones = explode(',', $variacion);

//echo $variaciones[0];

$hashtext = $_REQUEST['h'];
$lid = intval($_REQUEST['lid']);

if($lid>0){ //seteado desde formulario nueva medal
    $q = "SELECT * FROM medal WHERE id=$lid  ";
    $query = $db->query($q);
    $row = $query->fetch_assoc();
    $champ_name = $row['champ_name'];

    //var_dump($row);
    
    $te = json_decode($row['teams']);
    usort($te, function($a, $b){
        return intval($a->points) > intval($b->points) ? 1 : -1;
    });
    //$te = array_sort($te_sinorden, 'points');

    //var_dump($te);
    foreach($te as $arr){
        
        //var_dump($arr);
        //die();
        $value[$i]['premedal']= $arr->points;
        $value[$i]['identificador']=str_replace(" ","",$arr->team);
        $puntostemp = $arr->medal;
        if($variacion!=''){$puntostemp = intval($variaciones[$i]);}
        $value[$i]['medal']= $puntostemp;
        
        $puntajetemp = intval($arr->points)+$puntostemp;
       
        $campeonato.="campeonato[\"".$value[$i]['identificador']."\"]=".$value[$i]['premedal'].";\n";
        //$campeParcial.="campeParcial[\"".$value[$i]['identificador']."\"]=".$value[$i][$c-1].";\n";
        $campeParcial.="campeParcial[\"".$value[$i]['identificador']."\"]=".$puntajetemp.";\n";
        $equipos.="equipos['".$value[$i]['identificador']."']='".str_replace(" ","",$arr->team)."';\n";
        
        $listapa.="<li id=\"".$value[$i]['identificador']."\"></li>\n";
        $i++;
    }

}else{

//get search term
$id = $_REQUEST['id'];
$m = datosRegatta($id, $db);
$competidores = $m['medal'];
/*
array(2) { ["total"]=> int(18) ["medal"]=> int(10) } 
*/

$q = "SELECT * FROM result_regatta WHERE event_id=$id LIMIT ".$m['medal'];
$query = $db->query($q);
$q_event = $db->query("SELECT * FROM events INNER JOIN regattas ON events.regatta_id=regattas.id WHERE events.event_id=".$id." ");
$r_event = $q_event->fetch_assoc();



while ($row = $query->fetch_array()) {
    //echo("<pre>");
    //var_dump($row);
    if(intval($row['SailNumber'])>0){$row['SailNumber']=$row['Country'].$row['SailNumber'];}
    $indexmedal ="R".strval($m["total"]);
   
    //$value[$i]['premedal']= ($value[$i][$c-1]-$value[$i][$c-3]);
    $value[$i]['premedal']= $row["NetPoints"]-$row[$indexmedal];
    $value[$i]['identificador']=str_replace(" ","",$row['SailNumber']);
   
    $value[$i]['medal']= $row[$indexmedal];
   

    $campeonato.="campeonato[\"".$value[$i]['identificador']."\"]=".$value[$i]['premedal'].";\n";
    //$campeParcial.="campeParcial[\"".$value[$i]['identificador']."\"]=".$value[$i][$c-1].";\n";
    $campeParcial.="campeParcial[\"".$value[$i]['identificador']."\"]=".$row['NetPoints'].";\n";
    $equipos.="equipos['".$value[$i]['identificador']."']='".str_replace(" ","",$row['SailNumber'])."';\n";
    
    $listapa.="<li id=\"".$value[$i]['identificador']."\">".$value[$i]['identificador']."-".$row['NetPoints']."</li>\n";

    $i++;
}

}
### lee resultados desde csv
/*
if(isset($_REQUEST["y"])){$y=$_REQUEST["y"];}else{$y=2016;}
$archivo= "results/".$y."/".$_REQUEST['r']."/".$_REQUEST['v'].".csv" ;
/// independizar inicio de fila
/// encontrar la fila donde comienzan los resultados
/// clave el numero 1 del primer puesto


if (($handle = fopen($archivo, "r")) !== FALSE) {
    
	$value=Array();
	
	
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        
        for ($c=0; $c < $num; $c++) {
            //echo $c." -- ".$data[$c] . "<br />\n";
             $value[$row][$c] = $data[$c];
        }
		
		$row++;
	}
	$titulo = $value[0][0];
	$clase = $value[1][0];
	
	$value = array_slice($value, 2, 11);
    fclose($handle);
	
    $competidores = $row - 3;
    //limite a la cantidad de competidores
	if ($competidores > 10) 
	  $competidores = 10;
	
	
	for ($i=1; $i<($competidores+1); $i++){
		$value[$i]['premedal']= ($value[$i][$c-1]-$value[$i][$c-3]);
		$value[$i]['identificador']=str_replace(" ","",$value[$i][2]);
		
		$campeonato.="campeonato[\"".$value[$i]['identificador']."\"]=".$value[$i]['premedal'].";\n";
		$campeParcial.="campeParcial[\"".$value[$i]['identificador']."\"]=".$value[$i][$c-1].";\n";
		$equipos.="equipos['".$value[$i]['identificador']."']='".$value[$i][2]."';\n";
		
		$listapa.="<li id=\"".$value[$i]['identificador']."\"></li>\n";
			
	}
	
		
}
*/
// array orden regata medal solamente
//$c-3 lo reemplace por cero>
$arraymedal = array_sort($value, 'medal');
$arraymedal = array_slice($arraymedal, 0, $competidores);

//array ordenado por los resultados antes de la medal
$arraycampeonato = array_sort($arraymedal, 'premedal'); 
$arraycampeonato = array_values($arraycampeonato);
/// hasta aca
//header('Content-Type: text/html; charset=UTF-8');
// modo debug...
if (isset ($mododebug)){
echo ("<pre> value -");
print_r($value) ;	
echo ("<br><br> arraycampeonato -");
print_r($arraycampeonato) ;
echo ("<br><br> arraymedal -");
print_r($arraymedal) ;
die();
}

for ($i=0; $i<$competidores; $i++){
    
$listanm.="<li id=\"".$arraycampeonato[$i]['identificador']."\">".$arraycampeonato[$i]['identificador']." - ".$arraycampeonato[$i]['premedal']."</li>\n";
}
for ($i=0; $i<$competidores; $i++){
$sortabl.="<li id=\"".$arraymedal[$i]['identificador']."\">".$arraymedal[$i]['identificador']."</li>\n";
}
//print $listanm;
//echo ("</pre>");
//die();

//$sortabl.="<li id=\"".$arraymedal[$i]['identificador']."\">".$arraymedal[$i][2]."</li>\n";


function array_sort($array, $on, $order=SORT_ASC){

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

if(isset($champ_name)){$titulo = $champ_name;$event=$champ_name;}else{
$titulo = $r_event["event_name"]." | ".$r_event["gender"]." - ".$r_event["name"];$event=$r_event["event_name"];}

if($lid>0 && isset($hashtext)){
    $s = "<a href='#' onClick='guardarMedal();'>and save</a>";
}else{
    $s =  " and then <a href='#' onClick='guardarVariacion();'><button class='ui blue basic button'>share variation</button></a>";
}
?>
<!DOCTYPE html>  
<html>
    <head>
      <!-- Standard Meta -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

      <!-- Site Properties -->
        <title><?php echo "Medal race - ".$titulo;?></title>
        <meta name="description" content='<?php echo "Medal race ".$titulo;?>' />

  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="https://code.jquery.com/mobile/1.4.0-alpha.2/jquery.mobile-1.4.0-alpha.2.min.js"></script>
  <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script src="./js/jquery.ui.touch-punch.min.js"></script>
 
<link href='https://fonts.googleapis.com/css?family=Lilita+One' rel='stylesheet' type='text/css'>
<link href="./css/pagina.css" rel="stylesheet" type="text/css" media="all">
<link rel="stylesheet" type="text/css" href="semantic/semantic.css">

</head>  
<body>
<div class="ui text container">
     <?php if ($_REQUEST['step']!=3){ ?>
            <h2 class="ui dividing header">Medal Race <?php echo $event; ?></h2>
            <p class="first">Drag center column (medal) <?php echo $s; ?></p>
     <?php }else{ ?>
        <div class="ui mini ordered steps">
            <div class="completed step">
            <div class="content">
                <div class="title">Email & Event name</div>
                <div class="description">Please provide your email.</div>
            </div>
            </div>
            <div class="completed step">
            <div class="content">
                <div class="title">Teams & points</div>
                <div class="description">Enter team names.</div>
            </div>
            </div>
            <div class="active step">
            <div class="content">
                <div class="title">Reorder Medal race</div>
                <div class="description">Drag center column and reorder medal race. Then <a href='' onClick='guardarMedal();'> save</a></div>
            </div>
            </div>
      </div>
      <button class="positive ui button" onClick='guardarMedal();'>Save medal race</button>

        <?php } ?>
  </div>

  <div class="ui container">
    <div class="ui one column grid">
    <div class="column">
            <div id="wrapper" >
                <ul id="listaNotModif">  
                    <?php echo $listanm;?>
                </ul>
            </div>
            <div id="segment1" class="hline"></div>
            <div id="segment2" class="vline"></div>
            <div id="segment3" class="hline"></div>
            <div id="wrapper">
                    <ul id="sortable" >  
                        <?php echo $sortabl;?>
                    </ul>
            </div>
            <div id="segment4" class="hline"></div>
            <div id="segment5" class="vline"></div>
            <div id="segment6" class="hline"></div>
            <div id="wrapper">
                    <ul id="listaParcial">  
                        <?php echo $listapa;?>
                    </ul>
            </div>
    </div>
</div>
</div>
<script>

//ESTE ARREGLO SE TENDRIA Q CARGAR DESDE LA BD
var campeonato = new Array();
	<?php echo $campeonato; ?>

//ESTE ARREGLO SE TENDRIA Q CARGAR DESDE LA BD
  var fecha = new Array();
	fecha[0]=2;
    fecha[1]=4.1;
	fecha[2]=6.2;
	fecha[3]=8.3;
	fecha[4]=10.4;
	fecha[5]=12.5;
	fecha[6]=14.6;
	fecha[7]=16.7;
	fecha[8]=18.8;
	fecha[9]=20.9;

var campeParcial = new Array();
	<?php echo $campeParcial; ?>

//viene de la Base de datos
var equipos = new Array();
	<?php echo $equipos; ?>

  ordenarResultados();

        $("#listaParcial li, #sortable li, #listaNotModif li").hover(
  function () {
				 var id = this.id;
			//	console.log(id);
			//console.log($('#listaParcial #'+id).position.top);
			//console.log($('#listaParcial #'+id).text);
                var compensado = 15;
                console.log(compensado);
				 var leftY = $('#listaNotModif #'+id).position().top+compensado;
				 var rightY = $('#sortable #'+id).position().top+compensado;
				 var leftY2 =rightY;
				 var rightY2 = $('#listaParcial #'+id).position().top+compensado;
				 var H = Math.abs(rightY-leftY);
			 var H2 = Math.abs(rightY2-leftY2);
				 if (H == 0) H = 1;
			if (H2 == 0) H2 = 1;
				 $('#segment1').css('top',leftY+'px');
				 $('#segment3').css('top',rightY+'px');
				 $('#segment2').css('top',Math.min(leftY,rightY)+'px');
				 $('#segment2').css('height',H+'px');
				 $('#segment4').css('top',leftY2+'px');
				 $('#segment6').css('top',rightY2+'px');
				 $('#segment5').css('top',Math.min(leftY2,rightY2)+'px');
				 $('#segment5').css('height',H2+'px');
$('#listaParcial #'+id + ',#sortable #'+id+',#listaNotModif #'+id+ ',#segment1,#segment2,#segment3,#segment4,#segment5,#segment6').addClass("highlight");
				//$('#listaParcial #'+id + ',#sortable #'+id+',#listaNotModif #'+id+ ',#segment1,#segment2,#segment3,#segment4,#segment5,#segment6').css({"background-color":"red"});

},
function(){  
        var id = this.id;
				//$('#listaParcial #'+id + ',#sortable #'+id+',#listaNotModif #'+id+ ',#segment1,#segment2,#segment3,#segment4,#segment5,#segment6').css({"background-color":"blue"});
   $('#listaParcial #'+id + ',#sortable #'+id+',#listaNotModif #'+id+ ',#segment1,#segment2,#segment3,#segment4,#segment5,#segment6').removeClass("highlight");
});


         $(function() {        $('#sortable').sortable({
                    start : function(event, ui) {
                        var start_pos = ui.item.index();
                        ui.item.data('start_pos', start_pos);
                       
                    },
                    change : function(event, ui) {
                        var start_pos = ui.item.data('start_pos');
                        var index = ui.placeholder.index();
						
					//console.log(start_pos);
					//console.log(index);
			      //console.log($('#sortable li:nth-child(' + (index +1) + ')').attr('id'));
                       
                    if (start_pos < index) {
                            var valorActual = campeonato[$('#sortable li:nth-child(' + index + ')').attr('id')]; 
			                var puntaje = fecha[index-2];
 			                campeParcial[$('#sortable li:nth-child(' + index + ')').attr('id')]=valorActual+puntaje;
                            //$('#listaParcial li:nth-child(' + index + ')').html(valorActual+puntaje);
                        } else {
                            var valorActual = campeonato[$('#sortable li:eq(' + (index + 1) + ')').attr('id')];
				            var puntaje = fecha[index+1];
			                campeParcial[$('#sortable li:eq(' + (index + 1) + ')').attr('id')]=valorActual+puntaje;
                            //$('#listaParcial li:eq(' + (index + 1) + ')').html(valorActual+puntaje);
                        }
                         $('#listaParcial li,#sortable li,#listaNotModif li,#segment1 ,#segment2,#segment3,#segment4,#segment5,#segment6').removeClass("highlight");
                    },
                    update : function(event, ui) {
			                var index = ui.item.index();
    			            var valorActual = campeonato[$('#sortable li:nth-child(' + (index + 1) + ')').attr('id')];
                            var puntaje = fecha[index ];
			                campeParcial[$('#sortable li:nth-child(' + (index + 1) + ')').attr('id')]=Math.abs(valorActual+puntaje);
			                //$('#listaParcial li:nth-child(' + (index + 1) + ')').html(valorActual+puntaje);
			                ordenarResultados();		
                            $('#listaParcial li,#sortable li,#listaNotModif li,#segment1 ,#segment2,#segment3,#segment4,#segment5,#segment6').removeClass("highlight");
                    },
					axis : 'y'
                });
            });

function ordenarResultados(){//ordenamos los nodos CAMPEONATO PARCIAL
							$(document).ready(function(){
								    $("ul#listaParcial li").sort(sort_descending).appendTo('ul#listaParcial');
								    function sort_descending(a, b) {  
                                        var sumab = campeParcial[$(b).attr('id')];
                                        var sumaa = campeParcial[$(a).attr('id')];							  
                                        console.log( campeParcial[$(b).attr('id')]);
                                        $(b).html(equipos[$(b).attr('id')]+ ' -- '+'<b>'+ Math.floor(sumab) +'</b>' );
                                        $(a).html(equipos[$(a).attr('id')]+ ' -- '+'<b>'+ Math.floor(sumaa) +'</b>');
                                        return (campeParcial[$(b).attr('id')]) < (campeParcial[$(a).attr('id')]) ? 1 : -1;    
								        }
								});		
				
                        }
                        
<?php if(isset($_REQUEST['lid'])){ ?>

function guardarMedal(){// arma arreglo de columna medal guarda en json
    var te = <?php echo $row['teams'] ;?> ;
    //console.log(te);
    $("ul#sortable li").each(function() {
        console.log($(this).index(), $(this).attr('id'));
            for (var i=0; i < te.length; i++) {
                var teamtemp = te[i].team.replace(/ /g,"");
                if (teamtemp === $(this).attr('id')) {
                    var elindex = $(this).index();
                    te[i].medal = Math.abs(2*(elindex+1));
                    te[i].medal = te[i].medal.toString();
                }
            }
    });
    console.log(te);
    var teams = JSON.stringify(te);
    console.log(teams);

    $.ajax({
        type:"POST",
        cache:false,
        url:"./medal-race-update.php",
        data: {j: teams, lid: '<?php echo $lid ;?>' } ,    // multiple data sent using ajax
        success: function () {
          alert('Saved!');
        },
           
      });
}


function sortFunction(a, b){
                if(Number(a[0]) === Number(b[0])){
                    return 0;
                }
                else{
                    return(Number(a[0]) < Number(b[0])) ? -1:1;
                }
            }

function guardarVariacion(){// otorga un link para compartir el estado actual de la combinatoria (variacion)
    var te = <?php echo $row['teams'] ;?> ;
    var final = [];
    var fin = [];
    $("ul#sortable li").each(function() {
        console.log($(this).index(), $(this).attr('id'));
            for (var i=0; i < te.length; i++) {
                var teamtemp = te[i].team.replace(/ /g,"");
                if (teamtemp === $(this).attr('id')) {
                    var elindex = $(this).index();
                    te[i].medal = Math.abs(2*(elindex+1));
                    te[i].medal = te[i].medal.toString();
                    final.push([te[i].points, te[i].medal]);
                }
            }
            final.sort(sortFunction);
            
    });
    console.log(final);
    for(var i=0; i < final.length; i++){
        fin.push(final[i][1]);
    }
    console.log(fin);
    var linke = window.location.href + "&v="+fin.join(',');
    navigator.clipboard.writeText(linke);
    prompt("Link copied to clipboard!... paste and share!" , linke);
}
<?php }?>
$(document).ready(function(){
    ordenarResultados() 
    });
$(document).bind('pageinit', function() {
    /*$( "#sortable" ).sortable({
       items: "li:not(.ui-li-divider)"
    });*/
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    $( "#sortable" ).bind( "sortstop", function(event, ui) {
      $('#sortable').listview('refresh');
    });
  });

</script>

	
</body>
</html>
