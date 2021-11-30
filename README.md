# Needed file

This website need a mysql database (see [7stolen scripts](https://github.com/bestel74/7stolen_scripts)) and 3 more files in the 'mysql' folder:

'mysql_connect_ro.php':

    <?php
      //connect
      $conn = new mysqli("localhost", "database_username", "database_password", "database_name");
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }	
    ?>


'mysql_connect_rw.php':

    <?php
      //connect
      $conn = new mysqli("localhost", "database_username", "database_password", "database_name");
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }	
    ?>

'secret_captcha.php':

    <?php
      //connect
      $secret = "insert secret token here";	
    ?>
