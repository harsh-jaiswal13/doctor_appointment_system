<?php
class PatientModel{
    protected $conn;

    public function __construct() {
        include_once('../modal/connection.php');
        $myobj=new DbConnection();
        $this->conn=$myobj->MakeDbConnection();
    }

    
    public function RegisterPatient($fields){
        $json=[];

        $stmt = $this->conn->prepare("INSERT INTO patient (fname,lname,email,pass_word,Age,contact) VALUES (?,?,?,?,?,?)");
        
        $stmt->bind_param("ssssss",$fields['fname'],$fields['lname'], $fields['email'],$fields['pass_word'],$fields['age'],$fields['phone']);
        
        if($stmt->execute()){
          $json = ['success' => TRUE, "message" => "REGISTERED SUCCESSFULLY"];
        }
        else{
          $json = ['success' => FALSE ,"message" => "CREDETIALS ALREADY EXIST"];
        } 
        $this->conn->close();
        return $json;
    }

    public function validateLogin($fields){

        $sql =$this->conn->prepare("SELECT * FROM patient WHERE email = ? AND pass_word=?");
        
        // Bind the parameters
        $sql->bind_param("ss",$fields['email'],$fields['password']);
        
        // Execute the statement
        $sql->execute();
        
        $result=$sql->get_result();
       
        if(mysqli_num_rows($result)>0){
          $row = $result->fetch_assoc();
            $json = ["success" => TRUE ,
                     "message" => "Login success : welcome ".$row['fname'],
                     'uid'=>$row['p_id'],
                     'status'=>"loggedin",
                     'email'=>$row['email'],
                     'usertype'=>0];  
                     
        }        
        else{
          $json = ["success" => "FALSE" ,"message" => "Invalid login credentials RETRY! "];  
        }
      
 
      $this->conn->close();
      return $json;

    }
    
      
    



      
}
?>