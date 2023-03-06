<?php

class DbConnection{
    public $error;
    public $conn;
    public function makeDbConnection() {
      include_once('../config/config.php');
      
      $conn = mysqli_connect($servername, $username, $password, $database);
      if (!$conn){
        $this->error.=mysqli_connect_error();
        echo $this->error;
      }
      else{
        // echo $servername." ".$username." ".$password." ".$database."hello ";
        return $conn;
      }
    }
  }


      

?>