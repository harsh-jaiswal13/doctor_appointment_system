<?php
class DoctorModel{
    protected $conn;

    public function __construct() {
        include_once('../modal/connection.php');
        $myobj=new DbConnection();
        $this->conn=$myobj->makeDbConnection();
    }

// @author: Harsh Jaiswal  
// @Purpose:This will insert the details of doctor in database 
// @Date:03 Mar 2023     
    public function registerDoctor($fields){
        $json=[];
        $stmt = $this->conn->prepare("INSERT INTO doctor (fname,lname,email,pass_word,phone,speciality) VALUES (?,?,?,?,?,?)");
        
        $stmt->bind_param("ssssss",$fields['fname'],$fields['lname'], $fields['email'],$fields['pass_word'],$fields['phone'],$fields['speciality']);
        
        if($stmt->execute()){
          $json = ['success' => TRUE, "message" => "REGISTERED SUCCESSFULLY"];
        }
        else{
          $json = ['success' => FALSE ,"message" => "CREDETIALS ALREADY EXIST"];
        } 
        $this->conn->close();
        return $json;
    }

// @author: Harsh Jaiswal  
// @Purpose:This will verify the login detailsfrom data base
// @Date:03 Mar 2023 
    public function validateLogin($fields){

        $sql =$this->conn->prepare("SELECT '1' as usertype, d_id as uid
        FROM doctor
        WHERE email = ? AND pass_word = ?
        UNION
        SELECT '0' as usertype, p_id as uid
        FROM patient
        WHERE email = ? AND pass_word = ?");
        
        // Bind the parameters
        $sql->bind_param("ssss",$fields['email'],$fields['password'],$fields['email'],$fields['password']);
        // Execute the statement
        $sql->execute();
        $result=$sql->get_result();
        if(mysqli_num_rows($result)>0){
          $row = $result->fetch_assoc();
            $json = ["success" => TRUE ,
                     "message" => "Login success",
                     'status'=>"loggedin",
                     'email'=>$fields['email'],
                     'uid'=>$row['uid'],
                     'usertype'=>$row['usertype'],];  
        }        
        else{
          $json = ["success" => "FALSE" ,"message" => "Invalid login credentials RETRY! "];  
        }
      
 
      $this->conn->close();
      return $json;

    }
// @author: Harsh Jaiswal  
// @Purpose:This function will show all the categories of doctor
// @Date:03 Mar 2023 
    public function showDocCategories(){
      $json=[];
      $sql =$this->conn->prepare("SELECT speciality as categories, COUNT(*) as num FROM doctor GROUP BY speciality");
      $sql->execute();
      $result = $sql->get_result();
    
      
      if(mysqli_num_rows($result)>0)
      {  $rows = $result->fetch_all(MYSQLI_ASSOC);
         mysqli_close($this->conn);
         $json = ['success' => TRUE ,"message" =>$rows];
      }    
      else{
        $json = ['success' => TRUE ,"message" =>"No doctors found"];
      }
      
      return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This function will show all the categories of doctor
// @Date:03 Mar 2023 
    public function showDoctors(){


      $json=[];
      $sql =$this->conn->prepare("SELECT * from doctor where speciality= ?");
      $sql->bind_param("s",$_POST['category']);
      $sql->execute();
      $result = $sql->get_result();
    
      
      if(mysqli_num_rows($result)>0)
      {  $rows = $result->fetch_all(MYSQLI_ASSOC);
         mysqli_close($this->conn);
         $json = ['success' => TRUE ,"message" =>$rows];
      }    
      else{
        $json = ['success' => TRUE ,"message" =>"No doctors found"];
      }
      
      return $json;



    }
}
?>