<?php
// para saber si hay medal
// cuantos en la medal, 5 o 10
// cantidad de regatas del evento
// 

function datosRegatta($event_id, $db){
$query = $db->query("SELECT * FROM result_regatta WHERE event_id='".$event_id."' ORDER BY `rank` ASC ");
$ret = [];
$m = [];
$i = 0;
while ($row = $query->fetch_array()) {
    foreach ($row as $key => $value) {
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
    $m['total']=$totalRegatas;
    if($ret[4]["R".$totalRegatas.""]!= null && $ret[5]["R".$totalRegatas.""]== null){$m['medal']= 5;}else{
    if($ret[9]["R".$totalRegatas.""]!= null && $ret[10]["R".$totalRegatas.""]== null){$m['medal']=10;}
    if($ret[9]["R".$totalRegatas.""]!= null && $ret[10]["R".$totalRegatas.""]!= null){$m['medal']=null;}
    }
return($m);
}

/// para convertir a url 
function slugify($text){

    $pat = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ë', 'ö' );
    $ree = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'e', 'o');
  
    $text = str_replace($pat, $ree, $text);
  
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  
    // transliterate
    $text = iconv('utf-8', 'us-ascii//IGNORE', $text);
  
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
  
    // trim
    $text = trim($text, '-');
  
    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
  
    // lowercase
    $text = strtolower($text);
  
    if (empty($text)) {
      return 'n-a';
    }
  
    return $text;
  }

?>