<?php
  if (!preg_match('/\/settings.php.*/', $_SERVER['REQUEST_URI'])) {
    header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
  }
  // Get configuration file
  $configs = include('config.php');
  // Raspberry Pi settings
  echo '<div class="panel-body">
    <p class="h4">Raspberry pi settings</p>';

    // Socket settings
    echo '<label for="basic-url">Socket settings for Raspberry Pi</label>
      <form method="post">
        <div class="form-group">';

        // Ip address setting
        echo '<div class="input-group">';
          echo '<span class="input-group-addon" id="basic-addon3">IP - Address</span>';
          echo '<input type="text" value=' . json_encode($configs['socket_info']['ip']) . ' class="form-control" name="ip" aria-describedby="basic-addon3">';
        echo '</div>
              <br />
              <div class="input-group">';
        // Port setting
          echo '<span class="input-group-addon" id="basic-addon2">Port</span>';
          echo '<input type="text" value=' . json_encode($configs['socket_info']['port']) . ' class="form-control" name="port" aria-describedby="basic-addon3">';
        echo '</div>';
        echo '<button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;margin-top:20px;" type="submit" name="update_ip">Update Raspberry Pi settings</button>';

        // updated socket settings
        if (isset($_POST['update_ip'])) {
          // Change raspberry i settings in configuration file and save it again
          // Auto refresh is not implemented
          $configs['socket_info']['ip'] = $_POST['ip'];
          $configs['socket_info']['port'] = $_POST['port'];
          update_config($configs);
        }
      ?>
    </div>
  </form>
</div>
