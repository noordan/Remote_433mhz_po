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
          $signal['status'] = $data[6]
          array_push($signals, $signal);
        }
        fclose($handle);
        return $signals;
    }
    else {
      echo "failed to parse the codes";
    }
  }

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

  function update_csv(){
    $signals = fetch_csv();
    $csv = fopen("codes.csv", "w") or die("Unable to open file!");
    $csv_header = "name,on,off,place,on_time,off_time";
    #fwrite($csv, $csv_header);
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

  function add_remote_outlet(){
    $add_socket = array();
    $add_socket[0] = $_POST['name'];
    $add_socket[1] = $_POST['on_code'];
    $add_socket[2] = $_POST['off_code'];
    $add_socket[3] = $_POST['place'];
    $add_socket[4] = $_POST['on_time'];
    $add_socket[5] = $_POST['off_time'];
    $add_socket[6] = "off"
    $row = combined_string($add_socket);
    $csv = fopen("codes.csv", "a") or die("Unable to open file!");
    fwrite($csv, $row);
    fclose($csv);
  }

  if (isset($_POST['edit'])){
    update_csv();
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/edit.php');
  } elseif (isset($_POST['add'])){
    add_remote_outlet();
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/edit.php');
  }

  function check_status(){

  }
?>
