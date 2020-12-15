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
        <div class="completed step">
          <div class="content">
            <div class="title">Email & Event name</div>
            <div class="description">Please provide your email and event name.</div>
          </div>
        </div>
        <div class="active step">
          <div class="content">
            <div class="title">Teams & points</div>
            <div class="description">Enter team names and points before Medal race</div>
          </div>
        </div>
        <div class="step">
          <div class="content">
            <div class="title">Reorder Medal race</div>
            <div class="description">Drag center column and reorder medal race. </div>
          </div>
        </div>
      </div>


      <form action="./medal-race-new-process.php" class="ui form" method="POST">
      <h2>Teams & Points</h2>

            <?php for($i=0; $i<10; $i++) { ?>

                    <div class="two fields">
                        <div class="inline field">
                        <label>Team</label>
                        <input type="text" name="T<?php echo $i;?>" placeholder="ARG 1">
                        </div>
                        <div class="inline field">
                        <label>Points</label>
                        <input type="text" name="P<?php echo $i;?>" placeholder="partial points">
                        </div>
                        
                    </div>
            <?php } ?>
            <input type="hidden" name="email" value="<?php echo $_POST['email'];?>">
            <input type="hidden" name="champ" value="<?php echo $_POST['champ'];?>">

            <button class="ui button" type="submit">Submit</button>
        </form>

    </div>

  </div>
</div>


    </body>
</html>
