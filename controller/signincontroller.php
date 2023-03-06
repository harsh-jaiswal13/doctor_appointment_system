<?php
header('content-type application/json');
include_once('../modal/DoctorModel.php');
include_once('../modal/PatientModel.php');


// @author Harsh Jaiswal  
// @Purpose: This will make the form validation then save the details in db
// @Date:24 Feb 2023
class SigninController 
{ 
    public function login(){

        $json=[];
        $errors="";
        $email=$_POST['email'];
        $password=$_POST['password'];
        if(empty($email)){
          $errors.='email is required<br>';
        }
        if (empty($password)) {
          $errors.='Password is required<br>';
        }   
        if(!empty($errors)){
            $json = ['success' => FALSE, "message" => $errors];
        }
        else{
          $fields=array('email'=>$email,
                        'password'=>$password
                       );
            //patient login
            $dbmodel = new DoctorModel(); 
            $json=$dbmodel->validateLogin($fields);
            


            if($json['success']===TRUE)
            {
                $_SESSION['status']='loggedin';
                $_SESSION['email']=$json['email'];
                $_SESSION['uid']=$json['uid'];
                $_SESSION['usertype']=$json['usertype'];
            }

    


        
        return $json;

                       




        }
    }



}





?>


