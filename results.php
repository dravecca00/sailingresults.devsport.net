<?php
	$ret=[];
	$data=null;
	$value=null;
	$i=0;
	//get search term
    $id = $event_id; //$_REQUEST['id'];

    //$query = $db->query("SELECT * FROM result_regatta WHERE event_id=".$id." ORDER BY rank ASC");
    $query = $db->query("SELECT * FROM `result_regatta` WHERE `event_id`=$id ORDER BY `rank` ASC");

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



    //return json data


 if (count($ret) > 0): ?>

<table class="ui single line unstackable table">
  <thead>
    <tr>
      <th>Rank</th><?php if (isset($ret[1]["Country"])){ echo("<th>MNA</th>");} ?><th>Sail Number</th><th>Skipper</th><?php
      if (isset($ret[1]["CrewName"])){ echo("<th>Crew</th>");}
      for ($j=1; $j<=$totalRegatas; $j++){
      echo("<th>R".$j."</th>");
      }
      ?><th>Net Points</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($ret as $row): ?>
    <tr>
      <?php   
      if (isset($row["Skipper_id"])){
				$skp="<a href='sailor/".$row["Skipper_id"]."-".slugify($row['SkipperName']." ".$row['SkipperSurname'])."'>".$row["SkipperName"]." ".$row["SkipperSurname"]."<a>";}
				else {
					$skp= $row["SkipperName"]." ".$row["SkipperSurname"] ;
        }
        
        if (isset($row["Crew_id"])){
          $crew="<a href='sailor/".$row["Crew_id"]."-".slugify($row['CrewName']." ".$row['CrewSurname'])."'>".$row["CrewName"]." ".$row["CrewSurname"]."<a>";}
          else {
            $crew= $row["CrewName"]." ".$row["CrewSurname"] ;
          }

          if (isset($ret[1]["CrewName"])){
            $crewslot="<td>".$crew."</td>";}
            else {
              $crewslot='';
            } 
          if (isset($row["Country"])){ $mna = "<td>".$row['Country']."</td>";}  
			print("<td>".$row["rank"]."</td>$mna<td>".$row["SailNumber"]."</td><td>".$skp."</td>".$crewslot);
     // if (isset($row["CrewName"])){print("<td>".$row["CrewName"]." ".$row["CrewSurname"]."</td>");}else{if (isset($ret[0]["Crew"])){ echo("<td></td>");}}
      for ($k=1; $k<=$totalRegatas; $k++){
        if (array_key_exists( "R".$k."", $row)){
          $point=intval($row["R".$k.""]);
          }else {$point="";}
      echo("<td>".$point."</td>");
      }
      print("<td>".$row["NetPoints"]."</td>");
      ?>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
