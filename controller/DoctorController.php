<?php
include_once('../modal/DoctorModel.php');
include_once('../modal/PatientModel.php');
include_once('../modal/SlotsModel.php');

header('content-type application/json');

class DoctorController
{   
// @author: Harsh Jaiswal  
// @Purpose:This function will show the logs to doctor will get the json from slots model
// @Date:03 Mar 2023 
    public function showLogsToDoctor(){
        $myobj=new SlotsModel();
        $json=$myobj->showLogsToDoctor();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This function will get the disabled slots by doctor using the doctor id and date
// @Date:03 Mar 2023     
    public function enableShow(){
        $myobj=new SlotsModel();
        $json=$myobj->enableShow();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This function will enable the  disabled slots by doctor using theslot num and  date
// @Date:03 Mar 2023     
    public function enableSlot(){
        $myobj=new SlotsModel();
        $json=$myobj->enableSlot();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This function will get the slots which are free means theres is no appointment on it.
// @Date:03 Mar 2023         
    public function disableShow(){
        $myobj=new SlotsModel();
        $json=$myobj->disableShow();
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This function will disable the slot by its slot no.,doc id and date of appointment
// @Date:03 Mar 2023 
    public function disableSlot(){
        $myobj=new SlotsModel();
        $json=$myobj->disableSlot();
        return $json;
    }
    public function processRequest(){
        $myobj=new SlotsModel();
        $json=$myobj->processRequest();
        return $json;

    }
    



    

}   
?>