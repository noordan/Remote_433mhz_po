<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Remote ligth control</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="./custom.css">
  </head>
  <body>
    <div class="container text-center">
      <ol class="breadcrumb" style="margin-top:1%;">
        <li><a href="./index.php">Home</a></li>
        <li><a href="./lights.php">Lights</a></li>
        <li class="active">Edit</li>
      </ol>
      <?php
        // fetch csv file
        $signals = array();
        if (($handle = fopen("codes.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                array_push($signals, $data);
                for ($c=0; $c < count($data); $c++) {
                }
            }
            fclose($handle);
        }
        else {
          echo "failed to parse the codes";
        }
        # Print out the csv file
        foreach ($signals as $key => $signal) {
          if ($signal[0] != "name") {
            echo '<div class="panel panel-default">
                    <div class="panel-body">';
            echo '<b>Name:</b> ' . $signal[0]. ' ';
            echo '<b>Place:</b> ' . $signal[3]. ' ';
            echo '<b>On time:</b> ' . $signal[4]. ' ';
            echo '<b>Off time:</b> ' . $signal[5]. ' ';
            echo '<input class="btn btn-default" type="button" value="Edit">';
            echo '</div>
            </div>';
          }
        }
      ?>
      <?php
        // echo "<pre>";
        // print_r($signals);
        // echo "</pre>";
      ?>
    </div>
  </body>
</html>
