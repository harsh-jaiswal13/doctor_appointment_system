<?php
class SlotsModel{
    protected $conn;

    public function __construct() {
        include_once('../modal/connection.php');
        $myobj=new DbConnection();
        $this->conn=$myobj->makeDbConnection();
    }
// @author: Harsh Jaiswal  
// @Purpose:This will check the availabilty of doctor on any particular slot and date
// @Date:03 Mar 2023   
    public function checkAvailabilty(){


        $fields=substr($_POST['data'],5);
        $doc_id=$_POST['doc_id'];
        $patient_id=$_SESSION['uid'];
        
        $nextMonday = date('Y-m-d', strtotime('next Monday'));
        $nextSaturday = date('Y-m-d', strtotime($nextMonday. ' + ' . 5 . ' days'));


        $sql=$this->conn->prepare("SELECT `date_of_appointment`, `date_of_booking`, `slotnum` 
                                   FROM slots 
                                   WHERE doc_id = ? AND patient_id = ? 
                                   AND date_of_appointment is    NULL
                                   AND date_of_appointment BETWEEN ? AND ?");

        $sql->bind_param("iiss",$doc_id,$patient_id,$nextMonday,$nextSaturday);
        $sql->execute();  
        $result = $sql->get_result();
        if(mysqli_num_rows($result)>0){
            // print_r($result);
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $slot=$rows[0]['slotnum'];
            $a=9 +($slot-1)*.5;
            $base=floor($a);

            $slot="".$base;
            if(($base-$a)>0){
                $slot.=":30";
            }
            else{
                $slot.=":00";
            }

            
            $json = ['success' => NULL ,
                      "message" =>"Already made request on " .$slot." ".$rows[0]['date_of_appointment']  
                    ];
            return $json;

        }
        else{
            $sql=$this->conn->prepare("SELECT slotnum as slots from slots where doc_id= ? and `date_of_appointment`= ? order by slotnum;");

            $sql->bind_param("is",$doc_id,$fields);
            $sql->execute();  
            $result = $sql->get_result();
    
            if(mysqli_num_rows($result)>0){
              $rows = $result->fetch_all(MYSQLI_ASSOC);
              mysqli_close($this->conn);
              $json = ['success' => TRUE ,"slots" =>$rows];
            }
            else{
                $json = ['success' => TRUE ,"slots" =>"NULL"];
            }
        }
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This will book the appointment 
// @Date:03 Mar 2023 
    public function bookAppointment(){
        $slotnum=$_POST['slot'];
        $doc_id=$_POST['doc_id'];
        $patient_id=$_SESSION['uid'];
        $DateOfAppointment=substr($_POST['date'],5);
        $DateOfBooking = date('Y-m-d');


        //check Merge 
        $sql=$this->conn->prepare('SELECT doctor.fname ,doctor.lname,slots.slotnum from slots inner join doctor on doctor.d_id=slots.doc_id
        where patient_id=? and slotnum=? and date_of_appointment=?');
        $sql->bind_param("iis",$patient_id,$slotnum,$DateOfAppointment);
        $sql->execute();  
        $result = $sql->get_result();
        if(mysqli_num_rows($result)>0){
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $name=$rows[0]['fname']." ".$rows[0]['lname'];
            $slot=$rows[0]['slotnum'];
            $a=9 +($slot-1)*.5;
            $base=floor($a);
            $slot="".$base;
            if(($base-$a)>0){
                $slot.=":30";
            }
            else{
                $slot.=":00";
            }
            $json = ['success' => NULL,
                      "message" =>"Already have appointment on this shedule please select another slot.Appointment details: ".$slot." ".$DateOfAppointment." with DR.".$name  
                    ];
            return $json;
        }
       



        

        $sql=$this->conn->prepare('INSERT INTO `slots` (`doc_id`, `patient_id`, `slotnum`, `date_of_appointment`, `date_of_booking`) VALUES (?, ?, ?, ?, ?)');
        
        $sql->bind_param("iiiss",$doc_id,$patient_id,$slotnum,$DateOfAppointment,$DateOfBooking);

        if($sql->execute()){
            $json = ['success' => TRUE, "message" => "Appointment Booked"];
        }
        else{
            $json = ['success' => FALSE ,"message" => "Database error"];
        } 

        return $json;
     
    }
// @author: Harsh Jaiswal  
// @Purpose:This will show the appoinments history of the patient
// @Date:03 Mar 2023 
    public function showLogs(){

        $sql=$this->conn->prepare("SELECT concat(doctor.fname,doctor.lname) as doctor_name ,slots.date_of_appointment as appointment_date ,slots.date_of_booking as date_of_booking ,
        CASE 
            WHEN status is NULL  THEN 'PENDIING'
            WHEN status=TRUE THEN 'Approved'
            ELSE 'Declined'
            END AS STATUS
        from slots inner join doctor on slots.doc_id=doctor.d_id where slots.patient_id= ? order by slots.date_of_appointment;
        ");

        $sql->bind_param("i",$_SESSION['uid']);
        $sql->execute();  
        $result = $sql->get_result();
        if(mysqli_num_rows($result)>0){
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return $rows;
        }        
    }

// @author: Harsh Jaiswal  
// @Purpose:This function will show the logs to doctor will get the json from slots model
// @Date:03 Mar 2023    
    public function showLogsToDoctor(){
        // echo $_SESSION['uid']." ".$_POST['date'];
        $sql=$this->conn->prepare("SELECT `patient_id` as pid,date_of_appointment,
                         CONCAT(patient.fname,' ',patient.lname) as patient_name ,
                         slots.slotnum as slot
                         from slots inner join patient on patient.p_id=slots.patient_id where doc_id= ? and slots.status is NULL");
        $sql->bind_param("i",$_SESSION['uid']);
        $sql->execute();  
        $result = $sql->get_result();
        if(mysqli_num_rows($result)>0){
            $rows = $result->fetch_all(MYSQLI_ASSOC);
          $json = ['success' => TRUE, "message" => $rows];

        }
        else{
            $json = ['success' => FALSE, "message" => "No appointsments on this day"];
        }
        return $json;
            
    }

// @author: Harsh Jaiswal  
// @Purpose:This function will get the disabled slots by doctor using the doctor id and date
// @Date:03 Mar 2023  
    public function enableShow(){
        
        $sql=$this->conn->prepare("SELECT slotnum as slot from slots where patient_id is NULL and date_of_booking is NULL and doc_id= ? and date_of_appointment= ? order by slotnum");

        $sql->bind_param("is",$_SESSION['uid'],$_POST['date']);
        $sql->execute();  
        $result = $sql->get_result();
        if(mysqli_num_rows($result)>0){
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $json=['success' => TRUE, "slot" => $rows];
            
        }
        else{
            $json = ['success' => FALSE, "message" => "No disabled slots "];
        }
        return $json;
    }

// @author: Harsh Jaiswal  
// @Purpose:This function will enable the  disabled slots by doctor using theslot num and  date
// @Date:03 Mar 2023  
    public function enableSlot(){

        $slot=$_POST['slot'];
        $sql=$this->conn->prepare("DELETE from slots where slotnum= ? and date_of_appointment= ? and doc_id= ?");

        $sql->bind_param("isi",$slot,$_POST['date'],$_SESSION['uid']);
        
        if($sql->execute()){
            $json = ['success' => TRUE, "message" => "Slot enabled"];
        }
        else{
            $json = ['success' => FALSE, "message" =>"database error"];
        }

    
        return $json;

    }
// @author: Harsh Jaiswal  
// @Purpose:This function will get the slots which are not free means theres is appointment on it.
// @Date:03 Mar 2023     
    public function disableShow(){
        
        $sql=$this->conn->prepare("SELECT * FROM `SLOTS` WHERE doc_id =? and (status is NULL or status=1) and `date_of_appointment`=?");

        $sql->bind_param("is",$_SESSION['uid'],$_POST['date']);
        $sql->execute();  
        $result = $sql->get_result();
        if(mysqli_num_rows($result)<16){
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $json=['success' => TRUE, "slot" => $rows];
            
        }
        else{
            $json = ['success' => FALSE, "message" => "All slots are booked"];
        }
        return $json;
    }
// @author: Harsh Jaiswal  
// @Purpose:This function will disable the slot by its slot no.,doc id and date of appointment
// @Date:03 Mar 2023 
    public function disableSlot(){
        $status=1;
        $sql=$this->conn->prepare("INSERT INTO `SLOTS` (`doc_id`, `patient_id`, `slotnum`, `date_of_appointment`, `date_of_booking`,`status`) VALUES (?, NULL, ? , ?, NULL,?)");

        $sql->bind_param("iisi",$_SESSION['uid'],$_POST['slot'],$_POST['date'],$status);
        $sql->execute(); 


        if($sql->execute()){
            $json = ['success' => TRUE, "message" => "Slot disabled"];
        }
        else{
            $json = ['success' => FALSE, "message" =>"database error"];
        }
    
        return $json;



    }

// @author: Harsh Jaiswal  
// @Purpose:This function will approve/decline the request using patientid, slot no.,doc id and date of appointment
// @Date:03 Mar 2023     
    public function processRequest(){

        if($_POST['Status']==1){
            $status=TRUE;
        }
        else{
            $status=FALSE;
        }

        $sql=$this->conn->prepare("Update slots set status= ? where patient_id=? and slotnum = ? and doc_id= ? and date_of_appointment =? ");

        $sql->bind_param("siiis",$status,$_POST['patient_id'],$_POST['slot'],$_SESSION['uid'],$_POST['date']);
        
        if($sql->execute()){
            $json=['success'=>TRUE,'message'=>'Appoinment approved'];
        }
        else{
            $json=['success'=>FALSE,'message'=>'DB ERROR'];
        } 

        return $json;
    }

}

       



        
        


     



        



    
?>