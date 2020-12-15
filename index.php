<?php include ("database_local.php"); 
include ("funciones.php"); 
$q = "SELECT * FROM `medal` ORDER BY `datetime` DESC LIMIT 4";
$query = $db->query($q);

$links = '';

while($row = $query->fetch_array()){
  $links .= "<div class='item'><a href='./m.php?lid=".$row['id']."' target='_blank'>".$row['champ_name']."</a></div>";
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
      <title>Sailing Results</title>
      <link rel="stylesheet" type="text/css" href="./semantic/semantic.css">

      <script src="./lib/jquery.min.js"></script>
      <script src="./semantic/semantic.js"></script>

      <script type="text/javascript">
        function do_search()
        {
        var search_term=$("#search_term").val();
        if ($("#search_term").val().length>2){
        $.ajax
        ({
          type:'post',
          url:'j.php?type=l',
          data:{
          q: search_term
          },
          success:function(response) 
          {
          document.getElementById("result_div").innerHTML=response;
          }
        });
        }
        return false;
        }
        </script>

    </head>
    <body>
  
  <div class="ui main text container">
    <h1 class="ui header">Sailing results</h1>
    <p>Find relations between races data. Discover sailors background. Search sailor name, mna, regatta, any...</p>
 
  <form method="post"action="j.php" onsubmit="return do_search();" >
  <div class="ui fluid search">
    <div class="ui icon input">
      <input class="prompt" id="search_term" type="text" placeholder="Search ..." onkeyup="do_search();">
      <i class="search icon"></i>
    </div>
  </div>
  </form>

<div id="result_div"></div>
<div class="ui divider"></div>
<h1 class="header">Medal races points calculator</h1>
<p>Bring back medal races chances and study possibilities. Study the legendary <a href='./m.php?lid=5' target='_blank'>Rio 2016 Nacra medal race</a>... or you can make a new one:</p>
<p><a href='./medal-race-new.php' target='_blank'><button class="ui green basic button ui-btn ui-shadow ui-corner-all">Create a new medal race points calculator.</button></a></p>
    <h3 class="header">Medal races created</h3>
              <ul class="ui list">
                <?php echo $links;?>
              </ul>
  
     <div class="ui divider"></div>
     <h3 class="header">Regattas</h3>
              <ul class="ui list">
                <?php include("latests.php");?>
              </ul>
  
     <div class="ui divider"></div>
    <div class='ui text container'>
    <p>comments? <a href='https://t.me/ravecca'><i class='paper plane icon'></i></a> | <a href='mailto:info@devsport.net'><i class='mail icon'></i></a></p>
    </div>
    </body>
</html>
