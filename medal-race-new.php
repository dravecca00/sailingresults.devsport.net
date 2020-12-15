<?php // include ("database_local.php"); 

?>
<!DOCTYPE html>
    <html>
    <head>
      <!-- Standard Meta -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

      <!-- Site Properties -->
      <title>New Medal Race</title>
      <link rel="stylesheet" type="text/css" href="./semantic/semantic.css">

      <script src="./lib/jquery.min.js"></script>
      <script src="./semantic/semantic.js"></script>

    </head>
    <body>

<div class="ui container">
   <div>
  	<div>

      <h1 class="ui center aligned header">Medal race simulator</h1>

      <div class="ui ordered steps">
        <div class="active step">
          <div class="content">
            <div class="title">Email & Event name</div>
            <div class="description">Please provide your email and event name.</div>
          </div>
        </div>
        <div class="step">
          <div class="content">
            <div class="title">Teams & points</div>
            <div class="description">Enter team names and points before Medal race</div>
          </div>
        </div>
        <div class="step">
          <div class="content">
            <div class="title">Reorder Medal race</div>
            <div class="description">Drag center column and reorder medal race.</div>
          </div>
        </div>
      </div>


     <form action="./medal-race-new-1.php" class="ui form" method="POST">
         
            <div class="required field">
            <label>Championship</label>
            <input type="text" placeholder="Name of the championship" name="champ">
            </div>
            <div class="required field">
                <label>Your email</label>
                <input type="text" placeholder="email" name="email">
            </div>
        

            <button class="ui button" type="submit">Submit</button>
        </form>

    </div>

  </div>
</div>


    </body>
</html>
