<?php
session_start();
if($_SESSION['status']==NULL){
    include_once('../controller/signupcontroller.php');
    if($_POST['usertype']=="doctor")
    {   
        parse_str($_POST['data'], $fields);
        $myobj=new SignupController();
        $json=$myobj->signupDoctor($fields);
    }
    else{
        parse_str($_POST['data'], $fields);
        $myobj=new SignupController();
        $json=$myobj->signupPatient($fields);
    }
    echo json_encode($json);
    

}
elseif($_SESSION['status']=='login'){

    include_once('../controller/signincontroller.php');
    $myobj=new SigninController();

        $json=$myobj->login();
        echo json_encode($json);
    
}        
elseif($_SESSION['status']=='loggedin'){

        if($_SESSION['usertype']=="0"){
            include_once('../controller/patientcontroller.php');
            $myobj=new PatientController();
            
            switch($_POST['task']){
                case 'ShowDocCategories':
                    $json=$myobj->showDocCategories();
                    echo json_encode($json);
                    break;

                case 'ShowDoctors':
                        $json=$myobj->ShowDoctors();
                        echo json_encode($json);
                        break;
                case 'CheckAvailability':
                        $json=$myobj->checkAvailabilty();
                        echo json_encode($json);
                        break;   
                case 'BookAppointment':
                        $json=$myobj->bookAppointment();
                        echo json_encode($json);
                        break;
                case 'ShowLogs':
                    $json=$myobj->showLogs();
                    echo json_encode($json);
                    break;
            }



        } 
        if($_SESSION['usertype']=="1"){
            include_once('../controller/DoctorController.php');
            $myobj=new DoctorController();
            
            switch($_POST['task']){
                case 'ShowLogsToDoctor':
                    $json=$myobj->showLogsToDoctor();
                    echo json_encode($json);
                    break;

                case 'Showslot':
                    $json=$myobj->showSlot();
                    echo json_encode($json);
                    break;
                case 'EnableShow':
                    $json=$myobj->enableShow();
                    echo json_encode($json);
                    break;
                case 'enableSlot':
                    
                    $json=$myobj->enableSlot();
                    echo json_encode($json);
                    break;    
                case 'disableShow':
                        $json=$myobj->disableShow();
                        echo json_encode($json);
                        break;    
                case 'disableSlot':
                    $json=$myobj->disableSlot();
                    echo json_encode($json);
                    break;    
                case 'processRequest':
                    $json=$myobj->processRequest();
                    echo json_encode($json);
            }
        }
         
}
else{
    echo "hellwsedeo";
}











?>