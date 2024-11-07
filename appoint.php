<?php 
    require "connection_cust.php";
    session_start();

    if(empty($_SESSION['user_id'])){
        echo "<script>window.location.href='login.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/animations.css">  
    <link rel="stylesheet" href="assets/css/main.css">  
    <link rel="stylesheet" href="assets/css/signup.css">
     <link rel="stylesheet" href="assets/css/main.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <title>Make Appointment</title>
    
</head>
<body>
     <center>
    <div class="container">
        <form action="payment.php" method="post">
            <?php if(isset($_POST['btnNext'])) {
                $hospital = $_POST['hospital'];
            }
            ?>
        
        <input type="hidden" name="user_id" value="<?php echo ($_SESSION['user_id']) ?>">
        <table border="0">
            
            <tr>
                <td colspan="2">
                    <p class="header-text">Book now</p>
                    <p class="sub-text"></p>
                </td>
            </tr>
            
            
            <tr>
                <td class="label-td" colspan="2">
                    <label for="appointment_time" class="form-label">Appointment Time: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="time" name="appoint_StartTime" id="appoint_StartTime" class="input-text" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="appointment_date" class="form-label">Appointment Date: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="date" name="inputdate" id="inputdate" class="input-text" required>
                    <script type="text/javascript">
                    $(function(){
                        var dtToday = new Date();
                        var month = dtToday.getMonth() + 1;
                        var day = dtToday.getDate() + 1;
                        var year = dtToday.getFullYear();
                        if(month < 10)
                            month = '0' + month.toString();
                        if(day < 10)
                        day = '0' + day.toString();
                        var maxDate = year + '-' + month + '-' + day;
                        $('#inputdate').attr('min', maxDate);
                    });
                    </script>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="Submit" class="login-btn btn-primary btn" name="btnSubmit">
                </td>
            </tr>
            </table>
        </form>
    </div>
</center>
</body>
</html>