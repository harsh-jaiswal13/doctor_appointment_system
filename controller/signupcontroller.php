<?php
header('content-type application/json');
include_once('../modal/DoctorModel.php');
include_once('../modal/PatientModel.php');


// @author Harsh Jaiswal  
// @Purpose: This will make the form validation then save the details in db
// @Date:24 Feb 2023
class SignupController 
{ 
 
  public function SignupDoctor($fields){
      $json=[];
      $fname =trim($fields['fname']) ;
      $lname =trim($fields['lname']);
      $email =trim($fields['email']);
      $speciality=trim($fields['speciality']);
      $pass_word =$fields['password'];
      $phone=trim($fields['contact']);

      $errors = "";   
      if(!preg_match('/^[0-9]{10}$/', $phone)){
        $errors.="enter valid phone no.<br>";
      }
      if (empty($fname)) {
        $errors.='Name is required<br>';
      }
      elseif (strlen($fname) < 2) {
        $errors.='Name must be at least 2 characters long<br>';
      }
      elseif(!preg_match("/^[a-zA-Z]*$/",$fname)){
        $errors.='fname must contain only letters <br>';
      }         
      if (empty($lname)) {
        $errors.='lastName is required<br>';
      }
      elseif (strlen($lname) < 2){
        $errors.= 'last name must be at least 2 characters long<br>';
      }
      elseif(!preg_match("/^[a-zA-Z]*$/",$lname)){
        $errors.='lname must contain only letters <br>';
      }         
      
      if(empty($speciality)) {
        $errors.='speciality is required<br>';
      }
      elseif (strlen($speciality) < 2){
        $errors.= 'speciality name must be at least 2 characters long<br>';
      }
      elseif(!preg_match("/^[a-zA-Z]*$/",$speciality)){
        $errors.='speciality must contain only letters <br>';
      } 

      
      if (empty($email)) {
        $errors.='Email is required<br>';
      } 
      elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors.='Email is not valid<br>';
      }

      if (empty($pass_word)) {
        $errors.='Password is required<br>';
      } 
      elseif (strlen($pass_word) < 4) {
        $errors.='Password must be at least 8 characters long<br>';
      }

      
      $json = ['success' => TRUE, "message" => 'Validation done'];
      
      if(!empty($errors)){ 
        $json = ['success' => FALSE, "message" => $errors];
      }
      if ($json['message'] === 'Validation done'){
        $fields=array('fname'=>$fname,
                      'lname'=>$lname,
                      'email'=>$email,
                      'pass_word'=>$pass_word,
                      'speciality'=>$speciality,
                      'phone'=>$phone);
        $dbmodel = new DoctorModel();
        $json=$dbmodel->RegisterDoctor($fields);
      }
      return $json;  
  }


  public function login(){
    $json=[];
    $errors="";
    $email=$_POST['email'];
    // echo 
    $password=$_POST['password'];
    $usertype=$_POST['usertype'];
 

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
                    'password'=>$password,
                    'usertype'=>$usertype
                   );
          if($fields['usertype']==1){
            //doctor login
            $dbmodel = new DoctorModel();  
            $json=$dbmodel->validateLogin($fields); 
            if($json['success']===TRUE)
            { 


                // session_start();
                $_SESSION['status']='loggedin';
                $_SESSION['email']=$json['email'];
                $_SESSION['uid']=$json['uid'];
                $_SESSION['usertype']=$json['usertype'];
                $_SESSION['speciality']=$json['speciality'];

            }


          }
          if($fields['usertype']==0){
              //patient login
              $dbmodel = new PatientModel();  
              $json=$dbmodel->validateLogin($fields);
              if($json['success']===TRUE)
              {
                  $_SESSION['status']='loggedin';
                  $_SESSION['email']=$json['email'];
                  $_SESSION['uid']=$json['uid'];
                  $_SESSION['usertype']=$json['usertype'];
              }
            }
    }
    return $json;
  }


  public function SignupPatient($fields){
      $json=[];

      $fname =trim($fields['fname']) ;
      $lname =trim($fields['lname']);
      $email =trim($fields['email']);
      $age=trim($fields['age']);
      $pass_word =$fields['password'];
      $phone=trim($fields['contact']);

      $errors = "";   
      if(!preg_match('/^[0-9]{10}$/', $phone)){
        $errors.="enter valid phone no.<br>";
      }
      if (empty($fname)) {
        $errors.='Name is required<br>';
      }
      elseif (strlen($fname) < 2) {
        $errors.='Name must be at least 2 characters long<br>';
      }
      elseif(!preg_match("/^[a-zA-Z]*$/",$fname)){
        $errors.='fname must contain only letters <br>';
      }         
      if (empty($lname)) {
        $errors.='lastName is required<br>';
      }
      elseif (strlen($lname) < 2){
        $errors.= 'last name must be at least 2 characters long<br>';
      }
      elseif(!preg_match("/^[a-zA-Z]*$/",$lname)){
        $errors.='lname must contain only letters <br>';
      }         

      
      if (empty($email)) {
        $errors.='Email is required<br>';
      } 
      elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors.='Email is not valid<br>';
      }

      if (empty($pass_word)) {
        $errors.='Password is required<br>';
      } 
      elseif (strlen($pass_word) < 4) {
        $errors.='Password must be at least 8 characters long<br>';
      }

      $json = ['success' => TRUE, "message" => 'Validation done'];
      
      
      if(!empty($errors)){ 
        $json = ['success' => FALSE, "message" => $errors];
      }
      if ($json['message'] === 'Validation done'){
        $fields=array('fname'=>$fname,
                      'lname'=>$lname,
                      'email'=>$email,
                      'pass_word'=>$pass_word,
                      'age'=>$age,
                      'phone'=>$phone);

        $dbmodel = new PatientModel();
        $json=$dbmodel->RegisterPatient($fields);
      }
      return $json;  

  }

  
}




?>
      
    
      
    
      




