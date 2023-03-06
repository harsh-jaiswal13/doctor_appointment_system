<?php
session_start();
if ($_SESSION['usertype']!=1){
    header("Location:../view/login.php");
}  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Doctor</title>
    <style>
        td, th{
          padding : 10px;
        }
        table{
          border :" border-dark";
        }
      </style>
    <script>
        $(document).ready(function() {
            $('#editresult').hide();  

            $("#Logout").click(function() {
                $.ajax({
                    url: '../routes/redirect_logout.php',
                    type:"POST",
                    success: function(data) {
                        window.location = data;
                    }
                });
            });


            $("#ShowLogs").click(function() {
                $('#editresult').hide(); 
                document.getElementById("result").innerHTML ="";
                $.ajax({
                    url: '../controller/core.php',
                    type:"POST",
                    data:{task:'ShowLogsToDoctor'},
                    success: function(response){
                        result=JSON.parse(response);
                        console.log(response);
                        if(result.success!=1){
                            alert("No requests");
                        }   
                        else{
                            document.getElementById("result").innerHTML ="";
                            result=JSON.parse(response);
                            $("#result").append('<tr>'+
                            '<td >ID</td>'+
                            '<td >Patient name</td>'+
                            '<td >Slot number</td>'+
                            '<td >date_of_appointment</td>'+
                            '<td >STATUS</td>'+
                            '<td>\
                            </tr>');
                            $.each(result.message, function(key, value){
                                $("#result").append('<tr>'+
                                '<td id="p_id">'+value.pid+'</td>'+
                                '<td >'+value.patient_name+'</td>'+
                                '<td id="s_id">'+value.slot+'</td>'+
                                '<td id="d_id">'+value.date_of_appointment+'</td>'+
                                '<td>\
                                <button class="ApproveBtn">APPROVE</button>\
                                <button class="DeclineBtn">DECLINE</button>\
                                </td>\
                                </tr>');
                            });
                        } 
                    }
                });
            });
            
            $(document).on("click", ".ApproveBtn", function(){
                var p_id = $(this).closest('tr').find('#p_id').text();
                var s_id = $(this).closest('tr').find('#s_id').text();
                var d_id = $(this).closest('tr').find('#d_id').text();
       
                var data = {
                    task:"processRequest",
                    patient_id: p_id,
                    slot:s_id,
                    date:d_id,
                    Status: "1"
                    };
                $.ajax({
                    url: "../controller/core.php",
                    type: "POST",
                    data:data,
                    success: function(response) {
                        console.log(response);
                        document.getElementById("ShowLogs").click();
                    }
                });    
            });
            $(document).on("click", ".DeclineBtn", function(){
                var p_id = $(this).closest('tr').find('#p_id').text();
                var s_id = $(this).closest('tr').find('#s_id').text();
                var d_id = $(this).closest('tr').find('#d_id').text();
       
                var data = {
                    task:"processRequest",
                    patient_id: p_id,
                    slot:s_id,
                    date:d_id,
                    Status: "0"
                    };
                $.ajax({
                    url: "../controller/core.php",
                    type: "POST",
                    data:data,
                    success: function(response) {
                        console.log(response);
                        document.getElementById("ShowLogs").click();
                    }
                });    
            });


            $("#EditLogs").click(function(){
                document.getElementById("result").innerHTML ="";
                document.getElementById("editresult").innerHTML ="";
                //Show enable and disable buttons
                $("#editresult").append(
                                '<button id="Enable">Enable Slot</button>'+
                                '<button id="Disable">Disable Slot</button>'
                );

                $("#Enable").click(function(){
                    document.getElementById("editresult").innerHTML ="";
                    $("#editresult").append('<h1>Enable the slots </h1>'+
                                            '<p>These are the slots that you have blocked</p>'
                                           );
                    $("#editresult").append('<form id="harsh" method="POST">'+
                                            '<label for="date">Choose a date:</label>'+
                                            '<input type="date" name="date" required>'+
                                            '<button id="EnableShow" type="submit">Submit</button>'+
                                            '</form>'
                    );
                    today = new Date().toISOString().split('T')[0];
                    $("#EnableShow").click(function(e){
                        event.preventDefault();
                        date=$('#harsh').serialize().substring(5);
                        var today = new Date();
                        var selectedDate = new Date(date);
                        if(date==""){
                            alert("please enter date");
                        }
                        else if (selectedDate.getTime() <= today.getTime()) {
                                alert("select date after today");  
                        }
                        else{
                            $.ajax({
                                    url: '../controller/core.php',
                                    type:"POST",
                                    data:{task:'EnableShow',date:date},
                                    success: function(response){
                                        result=JSON.parse(response);
                                        console.log(result);
                                        if(result.success!=1){
                                            alert(result.message);
                                        }
                                        else{
                                            var slots = [];
                                            slotArray = result.slot.map(obj => obj.slot);
                                            console.log(slotArray);
                                            available_slots=[];
                                            available_time_slots=[];
                                            
                                            for (var i = 1; i < 17; i++) {
                                                if(slotArray.includes(i)){
                                                    available_slots.push(i);  
                                                    var a=9+(i-1)*.5;
                                                    var base= Math.floor(a);
                                                    var time=base;
                                                    if ((a-base)>0){time+=":30";}
                                                    else{time+=":00";}
                                                    let jsonObject={}; 
                                                    jsonObject["id"] = i;
                                                    jsonObject["time"]=time;
                                                    available_time_slots.push(jsonObject);
                                                }
                                            }
                                            console.log(available_time_slots);
                                           
                                            $("#editresult").append(
                                            '<select id="my-dropdown">'+
                                            '<option value="" disabled selected>Available Slots</option>'
                                            );
                                            $.each(available_time_slots, function(key, value){
                                                $("#my-dropdown").append(                 
                                                    '<option value="'+value.id+'">'+value.time+'</option>'
                                                );
                                            });
                                            $("#editresult").append(
                                                '</select>'+
                                                '<button id="EnableSlot">EanablSlot</button>'
                                                );
                                            $("#EnableSlot").click(function(e){
                                                event.preventDefault();
                                                slotnum=document.getElementById("my-dropdown").value;
                                                const dateObject = new Date(selectedDate);
                                                const formattedDate = dateObject.toISOString().slice(0, 10);
                                                console.log(formattedDate);
                                                $.ajax({
                                                    url: '../controller/core.php',
                                                    type:"POST",
                                                    data:{task:'enableSlot',
                                                          slot:slotnum,
                                                          date:formattedDate
                                                         },
                                                    success: function(response){
                                                        alert("Slot Enabled");
                                                        location.reload();
                                                    }
                                                });
                                            });
                                        }
                                    }
                            });
                          
                        }
                    });
                });

                $("#Disable").click(function(){
                    document.getElementById("editresult").innerHTML ="";
                    $("#editresult").append('<h1>Disable the slots </h1>'+
                                            '<p>These are the slots which are open to get disabled</p>'
                                           );
                    $("#editresult").append('<form id="harsh" method="POST">'+
                                            '<label for="date">Choose a date:</label>'+
                                            '<input type="date" name="date" required>'+
                                            '<button id="disableShow" type="submit">Submit</button>'+
                                            '</form>'
                    );

                    $("#disableShow").click(function(e){
                        event.preventDefault();
                        date=$('#harsh').serialize().substring(5);
                            var today = new Date();
                            var selectedDate = new Date(date);
                        
                        if (selectedDate.getTime() <= today.getTime()) {
                                    alert("select date after today");  
                            }
                        else{
                            $.ajax({
                                    url: '../controller/core.php',
                                    type:"POST",
                                    data:{task:'disableShow',date:date},
                                    success: function(response){
                                        result=JSON.parse(response);
                                        var slot = result.slot;
                                        let slotNumArray = []; 
                                        for (let i = 0; i < slot.length; i++) {
                                        slotNumArray.push(slot[i].slotnum);
                                        }
                                        available_slots=[];
                                        available_time_slots=[];
                                        for (var i = 1; i < 17; i++) {
                                            if(slotNumArray.indexOf(i) == -1){
                                                available_slots.push(i);  
                                                var a=9+(i-1)*.5;
                                                var base= Math.floor(a);
                                                var time=base;
                                                if ((a-base)>0){
                                                    time+=":30";
                                                }
                                                else{
                                                    time+=":00";
                                                }
                                                let jsonObject={}; 
                                                jsonObject["id"] = i;
                                                jsonObject["time"]=time;
                                                available_time_slots.push(jsonObject);
                                            }
                                        }
                                        $("#editresult").append(
                                            '<select id="my-dropdown">'+
                                            '<option value="" disabled selected>Available Slots</option>'
                                            );
                                            $.each(available_time_slots, function(key, value){
                                            $("#my-dropdown").append(                 
                                                '<option value="'+value.id+'">'+value.time+'</option>'
                                            );
                                        });
                                        $("#editresult").append(
                                            '</select>'+
                                            '<button id="disableSlot">DisableSlot</button>'
                                            );
                                            $("#disableSlot").click(function(e){
                                                event.preventDefault();
                                                const dateObject = new Date(selectedDate);
                                                const formattedDate = dateObject.toISOString().slice(0, 10);
                                                slotnum=document.getElementById("my-dropdown").value;
                                                $.ajax({
                                                    url: '../controller/core.php',
                                                    type:"POST",
                                                    data:{task:'disableSlot',slot:slotnum,date:formattedDate},
                                                    success: function(response){
                                                        alert("slot Disabled");
                                                        location.reload();

                                                    }
                                                });
                                            });
                                        }
                                    });
                        }
                    });
                });

                $('#editresult').show();  
                today = new Date().toISOString().split('T')[0];
 
                $("#Showslot").click(function(e) {
                            //enable Slots
                            date=$('#harsh').serialize().substring(5);



                            var today = new Date();
                            var selectedDate = new Date(date);
                            if(date==""){h
                                alert("please enter date");
                            }
                            else if (selectedDate.getTime() <= today.getTime()) {
                                  alert("select date after today");  
                            }
                            else{
                                $.ajax({
                                    url: '../controller/core.php',
                                    type:"POST",
                                    data:{task:'Showslot',date:date},
                                    success: function(response){
                                        result=JSON.parse(response);
                                        console.log(result);
                                        console.log(result);
                                        if(result.success!=1){
                                            alert(result.message);
                                        }
                                        else{
                                            var slots = [];
                                                for (var i = 0; i < result.slots.length; i++) {
                                                    slots.push(result.slots[i].slots);  
                                                }
                                            available_slots=[];
                                            available_time_slots=[];
                                         
                                            $("#editresult").append(
                                                '<select id="my-dropdown">'+
                                                '<option value="" disabled selected>Available Slots</option>'
                                                );
                                                $.each(available_time_slots, function(key, value){
                                                $("#my-dropdown").append(                 
                                                    '<option value="'+value.id+'">'+value.time+'</option>'
                                                );
                                            });
                                            $("#editresult").append(
                                                '</select>'
                                            );




                                        }

                                    }
                                });
                            }
                        });

            });
        });
            
        function enableButton() {

            const dateInput = document.getElementById("date");
            const button = document.getElementById("Show");
            if (dateInput.value){
                button.removeAttribute("disabled");
            }else{
                button.setAttribute("disabled", true);
            }
        }

        function getDate(){
            const dateInput = document.getElementById("date");
            const dateValue = dateInput.value;
            return dateValue;
        }
        

            
        
        

    </script>

</head>
<body>

    
    
    <button id="EditLogs">Edit slots</button>
    <button id="ShowLogs">Appointment Requests</button>
    <button id="Logout" style="float:right;">Logout</button>
    <div id="result"></div>

    <div id="editresult">
        </div>

 



    


</body>
</html>