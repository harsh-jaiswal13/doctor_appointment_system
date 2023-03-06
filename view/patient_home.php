<?php
session_start();
if ($_SESSION['usertype']!=0){
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
    <style>
        td, th{
          padding : 10px;
        }
        table{
          border :" border-dark";
        }
      </style>
    <title>Patient</title>
    <script>
        $(document).ready(function() {
            $('#appointment-form').hide();  
            $('#MakeAppointment').hide();  

            
            //Show Categories of doctors
            $("#ShowDocCategories").click(function() {
                $.ajax({ 
                    url: '../controller/core.php',
                    type:"POST",
                    data:{task:"ShowDocCategories"},
                    success: function(response) {
                        $('#MakeAppointment').hide();  
                        document.getElementById("appointment-form").reset();
                        $('#appointment-form').hide();  


                        document.getElementById("Result").innerHTML = "";
                        result=JSON.parse(response);   

                        $.each(result.message, function(key, value){
                            $("#Result").append('<tr>'+
                                '<td id="categories">'+value.categories+'</td>'+
                                '<td >'+value.num+'</td>'+
                                '<td>\
                                <button class="ShowDoctors">Show doctors</button>\
                                </td>\
                                </tr>');
                        });
                    }
                });
            });    

            //Show doctors of particular category
            $(document).on("click", ".ShowDoctors", function(){
                var category = $(this).closest('tr').find('#categories').text();
                    dropdown=document.getElementById("my-dropdown");
                    if(dropdown){
                        dropdown.remove();
                    }
                    b1=document.getElementById("cb");
                    if(b1){

                            b1.disabled = false;
                        
                    }


                $.ajax({
                    url: '../controller/core.php',
                    type:"POST",
                    data:{task:"ShowDoctors",
                          category:category,   
                         },
                    success: function(response) {
                        document.getElementById("Result").innerHTML = '<h1>'+category+' specialist</h1>';
                        result=JSON.parse(response);
                        // console.log(result);

                        $.each(result.message, function(key, value){
                            $("#Result").append('<tr>'+
                                '<td id="doctor_id">'+value.d_id+'</td>'+
                                '<td >'+value.fname+' '+value.lname +'</td>'+
                                '<td >'+value.email+'</td>'+
                                '<td>\
                                <button class="BookAppointment">Book</button>\
                                </td>\
                                </tr>');
                        });
                    }
                });
            });

            //Book Appointment
            $(document).on("click", ".BookAppointment", function(){
                var doc_id= $(this).closest('tr').find('#doctor_id').text();

                $('#appointment-form').show();  
                $('#appointment-form').submit(function(){
                event.preventDefault();

                var ds={
                  data:$('#appointment-form').serialize(),
                usertype:"0",
                doc_id:doc_id,
                task:"CheckAvailability" };
                
                $.ajax({
                    url: '../controller/core.php',
                    type: 'post',
                    data: ds,
                    datatype : "json",
                    success: function(response) {
                        result=JSON.parse(response);
                        document.getElementById("cb").disabled = true;
                      
                        
                        //Check if already have appointment
                        if(result.success!=1){
                            alert(result.message);
                            location.reload();
                        }
                        else{
                            var slots = [];
                            for (var i = 0; i < result.slots.length; i++) {
                                    slots.push(result.slots[i].slots);  
                                }
                            available_slots=[];
                            available_time_slots=[];
                            for (var i = 1; i < 17; i++) {
                                if(slots.indexOf(i) == -1){
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
                            element = document.getElementById('my-dropdown');
                                if (element) {
                                element.remove();
                                }

                            $("#appointment-form").append(
                                '<select id="my-dropdown">'+
                                '<option value="" disabled selected>Available Slots</option>'
                                );
                                $.each(available_time_slots, function(key, value){
                                $("#my-dropdown").append(                 
                                    '<option value="'+value.id+'">'+value.time+'</option>'
                                );
                            });
                            $("#appointment-form").append(
                                '</select>'
                            );
                            $('#MakeAppointment').show(); 
                            $(document).on("click", "#MakeAppointment", function(e){
                                e.preventDefault();
                                
                                var slot=document.getElementById("my-dropdown").value;
                                if(slot==""){
                                    alert("ALERT! please select the time slot");
                                }
                                else{
                                    ds={
                                        slot:slot,
                                        doc_id:doc_id,
                                        task:"BookAppointment",
                                        date:$('#appointment-form').serialize()
                                    };
                                    $.ajax({
                                        url: '../controller/core.php',
                                        type: 'post',
                                        data:ds,
                                        datatype : "json",
                                        success: function(response){
                                            result=JSON.parse(response);
                                            if(result.success!=1){
                                                alert(result.message);
                                                location.reload();
                                            }

                                            if(result.success==1){
                                                alert(result.message);
                                                location.reload();

                                                $('#appointment-form').hide();  
                                                $('#MakeAppointment').hide();  
                                                document.getElementById("appointment-form").reset();
                                            }
                                        }
                                    });    
                                }
                                
                            });
                        }
                    }
                });
            });
        });
        
        $("#Logout").click(function() {
            $.ajax({
                url: '../routes/redirect_logout.php',
                type:"POST",
                success: function(data) {
                    window.location = data;
                }
            });
        });
        //Show Logs
        $("#ShowLogs").click(function() {
             $.ajax({
            url: '../controller/core.php',
            type:"POST",
            data:{ task:"ShowLogs"},
            success: function(response){
                result=JSON.parse(response)
                console.log(result);
                $('#MakeAppointment').hide();  
                document.getElementById("appointment-form").reset();
                $('#appointment-form').hide();  

                document.getElementById("Result").innerHTML = "";
                $("#Result").append('<tr>'+
                        '<td id="categories">DOCTOR</td>'+
                        '<td >Appointment date</td>'+
                        '<td >Date of booking</td>'+
                        '<td >STATUS</td>'+
                        '<td>\
                        </td>\
                        </tr>');
                $.each(result, function(key, value){

                    $("#Result").append('<tr>'+
                        '<td id="categories">'+value.doctor_name+'</td>'+
                        '<td >'+value.appointment_date+'</td>'+
                        '<td >'+value.date_of_booking+'</td>'+
                        '<td >'+value.STATUS+'</td>'+

                        '<td>\
                        </td>\
                        </tr>');
                       
                    })

            }
    });
});
       
    });

    </script>
</head>
<body>
    
    <button id="ShowDocCategories">Show Doc Categories</button>
    <button id="ShowLogs">Show Appointment Status</button>
    <button id="Logout" style="float:right;">Logout</button>
    <div id="Result">

    </div>
<div id="booking">
    <form id="appointment-form">
        <label for="date">Date:</label>
        <select id="date" name="date" required>
            <option value="">Select a date</option>
                <script>
                    const today = new Date();
                    const nextMonday = new Date(today.getFullYear(), today.getMonth(), today.getDate() + (7 - today.getDay()) % 7 + 1); // Get next Monday
                    for (let i = 0; i < 7; i++) {
                        const date = new Date(nextMonday.getTime() + i * 24 * 60 * 60 * 1000);
                        const dateString = date.toISOString().slice(0, 10);
                        const option = document.createElement('option');
                        option.value = dateString;
                        option.textContent = dateString;
                        document.getElementById('date').appendChild(option);
                    }
                </script>
        </select>
        <button type="submit" id="cb">Check Availaibilty</button>
    </form>
    <form id="make-booking">
    <button type="submit" id="MakeAppointment">make appointment</button>

    </form>
</div>
        


        

</body>
</html>