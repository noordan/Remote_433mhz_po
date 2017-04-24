<?php
  // Function to get the client ip address
  function get_client_ip_env() {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';
      return $ipaddress;
  }

  // Fetch data in csv file
  function fetch_csv(){
    $signals = array();
    $signal = array();
    if (($handle = fopen("codes.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $signal['name'] = $data[0];
          $signal['on_code'] = $data[1];
          $signal['off_code'] = $data[2];
          $signal['place'] = $data[3];
          $signal['on_time'] = $data[4];
          $signal['off_time'] = $data[5];
          $signal['status'] = $data[6];
          array_push($signals, $signal);
        }
        fclose($handle);
        return $signals;
    }
    else {
      echo "failed to parse the codes";
    }
  }

  // combined row for csv file
  function combined_string($signal){
    $row = "";
    $first = True;
    foreach ($signal as $key => $s) {
      if ($first){
        $row = $s;
        $first = False;
      } else {
        $row .= "," . $s;
      }
    }
    $row .= "\n";
    return $row;
  }

  // print out data to csv
  function update_csv(){
    $signals = fetch_csv();
    $csv = fopen("codes.csv", "w") or die("Unable to open file!");
    foreach ($signals as $key => $signal) {
      if ($signal['name'] == $_POST['name']) {
        $signal['name'] = $_POST['name'];
        $signal['on_code'] = $_POST['on_code'];
        $signal['off_code'] = $_POST['off_code'];
        $signal['place'] = $_POST['place'];
        $signal['on_time'] = $_POST['on_time'];
        $signal['off_time'] = $_POST['off_time'];
        $signal['status'] = $signal['status'];
      }
      $row = combined_string($signal);
      fwrite($csv, $row);
    }
    fclose($csv);
  }

  // Add a new socket
  function add_remote_outlet(){
    $add_socket = array();
    $add_socket[0] = $_POST['name'];
    $add_socket[1] = $_POST['on_code'];
    $add_socket[2] = $_POST['off_code'];
    $add_socket[3] = $_POST['place'];
    $add_socket[4] = $_POST['on_time'];
    $add_socket[5] = $_POST['off_time'];
    $add_socket[6] = "off";
    $row = combined_string($add_socket);
    $csv = fopen("codes.csv", "a") or die("Unable to open file!");
    fwrite($csv, $row);
    fclose($csv);
  }

  // Update status in csv file
  function status($name, $status){
    $signals = fetch_csv();
    $csv = fopen("codes.csv", "w") or die("Unable to open file!");
    foreach ($signals as $key => $signal) {
      if ($signal['name'] == $name) {
        $signal['status'] = $status;
      }
      $row = combined_string($signal);
      fwrite($csv, $row);
    }
    fclose($csv);

  }

  function scheduling($action){
    // List current cronjobs in $cron
    $cron = exec("crontab -l");
    // Define cronjob
    $cronjob = "*/5 * * * * python3 " . getcwd() . "/backend/send_code.py cron";

    if ($action == "check"){
      $scheduled = "False";
      // Return true
      if (preg_match('/\*\/5 \* \* \* \* .*backend\/send_code.py cron/', $cron)) {
        $scheduled = "True";
      }
      // Return true or false
      return $scheduled;
    // Check if the sockets have been scheduled

    } elseif ($action == "enable") {
      // Check if cron already exists
      if (preg_match('/\*\/5 \* \* \* \* .*backend\/send_code.py cron/', $cron)){
        # Do nothing, already exists in crontab
      } else {
        // list existing cronjobs and add socket scheduling
        exec("crontab -l > /tmp/cron");
        exec("echo \"" . $cronjob . "\" >> /tmp/cron");
        // append the new crontab scheduling in web-users crontab
        exec("/usr/bin/crontab /tmp/cron");
        exec("rm /tmp/cron");
      }

    } elseif ($action == "disable") {
        //remove crontab from www-data
        exec("/usr/bin/crontab -r", $cronjob);
    }
  }

  function update_config($configs){
    file_put_contents('config.php', '<?php return ' . var_export($configs, true) . ';?>');
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php?updated=true');
  }
  // Call edit or add socket function
  if (isset($_POST['edit'])){
    update_csv();
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/edit.php');
  } elseif (isset($_POST['add'])){
    add_remote_outlet();
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/edit.php');
  }


?>
