
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        td, th{
          padding : 10px;
        }
        table{
          border :" border-dark";
        }
    </style>
    <script>
        $(document).ready(function(){
            $('#MakeAppointment').hide();  


             //Show Categgories of doctors
             $("#ShowDocCategories").click(function() {
                $.ajax({
                    url: '../controller/core.php',
                    type:"POST",
                    data:{task:"ShowDocCategories"},
                    success: function(response) {
                        $('#MakeAppointment').hide();  
                        // document.getElementById("appointment-form").reset();
                        // $('#appointment-form').hide();  
                        // $('#MakeAppointment').hide();  
                        $()
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
                $('#MakeAppointment').hide();  

                $.ajax({
                    url: '../controller/core.php',
                    type:"POST",
                    data:{task:"ShowDoctors",
                          category:category,   
                         },
                    success: function(response) {
                        document.getElementById("Result").innerHTML = '<h1>'+category+' specialist</h1>';
                        result=JSON.parse(response);
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

            $(document).on("click", ".BookAppointment", function(){
                var doc_id= $(this).closest('tr').find('#doctor_id').text();
                $('#MakeAppointment').show();
                // $('#form').reset();
                $(document).on("click", ".CheckAvailabilty", function(){
                    event.preventDefault();
                    // document.getElementById("form").reset();

                    var ds={    data:$('#form').serialize(),
                                usertype:"0",
                                doc_id:doc_id,
                                task:"CheckAvailability" 
                        };
                    $.ajax({
                        url: '../controller/core.php',
                        type: 'post',
                        data: ds,
                        datatype : "json",
                        success: function(response){
                            result=JSON.parse(response);
                            console.log($('#form').serialize());
                            if(result.success==1){
                                
                            }
                        }
                    });
                });
            });
                




               

            



            

        });



    </script>
</head>
<body>

    <button id="ShowDocCategories">Show Doc Categories</button>
    <button id="ShowLogs">Show Logs</button>
    <button id="Logout" style="float:right;">Logout</button>
    <div id="Result">   </div>
    <div id="BookResult">  </div>
    
    <div id="MakeAppointment">
        <form action="" id="form">

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
    <button class="CheckAvailabilty" type="submit">Check Availaibilty</button>
    <div id="slots"></div>
    
    </form>
    </div>
        

    
</body>
</html>