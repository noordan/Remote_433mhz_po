<div class="panel-body">
  <p class="h4">Raspberry pi settings</p>
  <label for="basic-url">IP address and port settings</label>
  <form method="post">
    <div class="form-group">
      <?php
        if ($_SERVER['REQUEST_URI'] != '/settings.php'){
          header('location: https://' . $_SERVER['HTTP_HOST'] . '/settings.php');
        }
        // Get configuration file
        $configs = include('config.php');
        echo '<div class="input-group">';
          echo '<span class="input-group-addon" id="basic-addon3">IP - Address</span>';
          echo '<input type="text" value=' . json_encode($configs['socket_info']['ip']) . ' class="form-control" name="ip" aria-describedby="basic-addon3">';
          echo '<span class="input-group-addon" style="margin-left:0.5%; id="basic-addon2">Port</span>';
          echo '<input type="text" value=' . json_encode($configs['socket_info']['port']) . ' class="form-control" name="port" aria-describedby="basic-addon3">';
        echo '</div>';
        echo '<button class="btn btn-custom btn-lg btn-block" style="border-color:#B2B2B2;margin-top:20px;" type="submit" name="update_ip">Update Raspberry Pi settings</button>';
      ?>
    </div>
  </form>
</div>
