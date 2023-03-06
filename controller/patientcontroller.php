<?php
// @author: Harsh Jaiswal  
// @Purpose:This functionalities are for the patient and will return json format as result
// @Date:03 Mar 2023 
include_once("../modal/DoctorModel.php");
include_once('../modal/PatientModel.php');
include_once('../modal/SlotsModel.php');

header('content-type application/json');

class PatientController
{

// @author: Harsh Jaiswal  
// @Purpose:This function will show all the categories of doctor
// @Date:03 Mar 2023 
    public function showDocCategories(){
        $myobj=new DoctorModel();
        $json=$myobj->showDocCategories();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This function will show all the categories of doctor
// @Date:03 Mar 2023 
    public function showDoctors(){
        $myobj=new DoctorModel();
        $json=$myobj->showDoctors();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This will check the availabilty of doctor on any particular slot and date
// @Date:03 Mar 2023     
    public function checkAvailabilty(){
        $myobj=new SlotsModel();
        $json=$myobj->checkAvailabilty();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This will book the appointment 
// @Date:03 Mar 2023 
    public function bookAppointment(){
        $myobj=new SlotsModel();
        $json=$myobj->bookAppointment();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This will show the appoinments history of the patient
// @Date:03 Mar 2023 
    public function showLogs(){
        $myobj=new SlotsModel();
        $json=$myobj->showLogs();
        return $json;

    }


    public function processRequest(){
        $myobj=new SlotsModel();
        $json=$myobj->processRequest();
        return $json;
    }



}
?>