
<?php 
/* Open connection to "zing_db" MySQL database. */
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
 
/* Check the connection. */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
 
/* Fetch result set from t_test table */
$data=mysqli_query($mysqli,"SELECT * FROM phone_data");
?>

<script>
    var pickupData=[<?php 
        while($pickupInfo=mysqli_fetch_array($data))
            echo $pickupInfo['pickupCount'].','; /* We use the concatenation operator '.' to add comma delimiters after each data value. */
        ?>];
        console.log("pickupData: ");
        console.log(pickupData);
		pickupData = pickupData.reverse();
    <?php
    $data=mysqli_query($mysqli,"SELECT * FROM phone_data");
    ?>
    var minuteData=[<?php 
      while($minuteInfo=mysqli_fetch_array($data))
          echo $minuteInfo['minuteCount'].','; /* We use the concatenation operator '.' to add comma delimiters after each data value. */
      ?>];
        console.log("minuteData: ");
        console.log(minuteData);
		minuteData = minuteData.reverse();
    <?php
    $data=mysqli_query($mysqli,"SELECT * FROM phone_data");
    ?>
    var dateLabel=[<?php 
    while($dateInfo=mysqli_fetch_array($data))
        echo '"'.$dateInfo['reg_date'].'",'; /* The concatenation operator '.' is used here to create string values from our database names. */        
    ?>];
        console.log("dates: ");
        console.log(dateLabel);
		dateLabel = dateLabel.reverse();
</script>
 
<?php
/* Close the connection */
$mysqli->close(); 
?>

<!DOCTYPE html>
<html>
	<head>
		<script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
		<script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
		ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9","ee6b7db5b51705a13dc2339db3edaf6d"];</script></head>
  
	<body>
		<div id='myChart'></div>
    <script>
      window.onload=function(){
        zingchart.render({
            id:"myChart",
            width:"100%",
            height:400,
            data:{
            "type":"line",
            "title":{
                "text":"Data Pulled from MySQL Database"
            },
            "scale-x":{
                "labels":dateLabel
            },
            "series":[
              {
                    "values":minuteData
                },
              {
                    "values":pickupData
                }
        ]
        }
        });
        };

    </script>

	</body>
</html>
