<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
error_reporting(E_ALL ^ E_NOTICE);  
 
sec_session_start();



if (isset($_GET['email'])) {
  $email = $_GET['email'];
}
else {
  echo '<script>console.log("email not set, sad!")</script>';
  $email = " ";
}

$data=mysqli_query($mysqli,"SELECT * FROM phone_data WHERE email = '$email'");
$date = time();
$mysqldate = date ("Y-m-d", $date);

// $answersTodayQuery = "SELECT answer FROM member_surveys WHERE date = '$date'";
// $answersTodayResult = mysqli_query($mysqli, $answersTodayQuery);

$usernameQuery = "SELECT username FROM members WHERE email = '$email' LIMIT 1";
$usernameResult = mysqli_query($mysqli, $usernameQuery);
$username = mysqli_fetch_assoc($usernameResult)['username'];

$questionQueryOne = "SELECT question FROM survey_questions WHERE id = 1";
$questionOneResult = mysqli_query($mysqli, $questionQueryOne);
$questionOne = mysqli_fetch_assoc($questionOneResult)['question'];
$answerOneQuery = "SELECT answer FROM member_surveys WHERE reg_date = '$mysqldate' AND question_number = 1 AND username = '$username'";
$answerOneResult = mysqli_query($mysqli, $answerOneQuery);
// $answerOne = mysqli_fetch_assoc($answerOneResult)['answer'];
$answerOne = getAnswer($answerOneResult);

$questionQueryTwo = "SELECT question FROM survey_questions WHERE id = 2";
$questionTwoResult = mysqli_query($mysqli, $questionQueryTwo);
$questionTwo = mysqli_fetch_assoc($questionTwoResult)['question'];
$answerTwoQuery = "SELECT answer FROM member_surveys WHERE reg_date = '$mysqldate' AND question_number = 2 AND username = '$username'";
$answerTwoResult = mysqli_query($mysqli, $answerTwoQuery);
$answerTwo = getAnswer($answerTwoResult);

$questionQueryThree = "SELECT question FROM survey_questions WHERE id = 3";
$questionThreeResult = mysqli_query($mysqli, $questionQueryThree);
$questionThree = mysqli_fetch_assoc($questionThreeResult)['question'];
$answerThreeQuery = "SELECT answer FROM member_surveys WHERE reg_date = '$mysqldate' AND question_number = 3 AND username = '$username'";
$answerThreeResult = mysqli_query($mysqli, $answerThreeQuery);
$answerThree = getAnswer($answerThreeResult);
$number_id = 1;

// get average stats for all and average for current user
$userMinuteQuery = "SELECT AVG(minuteCount) FROM phone_data WHERE email = '$email'";
$userMinuteAverage = getAverage($userMinuteQuery, $mysqli, "minuteCount");

$userPickupQuery = "SELECT AVG(pickupCount) FROM phone_data WHERE email = '$email'";
$userPickupAverage = getAverage($userPickupQuery, $mysqli, "pickupCount");

$minuteQuery = "SELECT AVG(minuteCount) FROM phone_data";
$minuteAverage = getAverage($minuteQuery, $mysqli, "minuteCount");

$pickupQuery = "SELECT AVG(pickupCount) FROM phone_data";
$pickupAverage = getAverage($pickupQuery, $mysqli, "pickupCount");

while ($row = mysqli_fetch_array($data)) {
	$minuteRows[]=(int)($row['minuteCount']);
	$pickupRows[]=(int)($row['pickupCount']);
}
$stand_dev_minute = round(Stand_Deviation($minuteRows), 2);
$stand_dev_pickup = round(Stand_Deviation($pickupRows), 2);


?>


<script>
    var pickupData=[<?php 
        while($pickupInfo=mysqli_fetch_array($data))
            echo $pickupInfo['pickupCount'].','; /* We use the concatenation operator '.' to add comma delimiters after each data value. */
        ?>];
    <?php
    $data=mysqli_query($mysqli,"SELECT * FROM phone_data WHERE email = '$email'");
    ?>
    var minuteData=[<?php 
      while($minuteInfo=mysqli_fetch_array($data))
          echo $minuteInfo['minuteCount'].','; /* We use the concatenation operator '.' to add comma delimiters after each data value. */
      ?>];
    <?php
		$date_query = "SELECT * FROM phone_data WHERE email = '$email'";
    $data=mysqli_query($mysqli, $date_query);
    ?>
    var dateLabel=[<?php 
    while($dateInfo=mysqli_fetch_array($data))
        echo '"'.$dateInfo['reg_date'].'", '; /* The concatenation operator '.' is used here to create string values from our database names. */        
    ?>];
	
// 	function hideQuestion() {
// 			setTimeout(function(){ window.location.reload(); }, 3000);
// 			return true;
// // 	}
// 	function hideQuestion() {
// 		console.log("hiding");
//    var promise = new Promise(function(resolve, reject) {
//      window.setTimeout(function() {
//        resolve('done!');
//      }, 2000);
//    });
//    return promise;
// }
	
	function hideFunction(divID) {
    var x = document.getElementById(divID);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

	
</script>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
        <title>Secure Login: Protected Page</title>
        <link rel="stylesheet" href="styles/main.css" />
				<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/tables-min.css">
        <script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
		    <script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
		    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9","ee6b7db5b51705a13dc2339db3edaf6d"];</script></head>
				<meta name="keywords" content="" />
				<meta name="description" content="" />
</head>

<body>

<div class="wrapper">

	<header class="header">
		<b>Welcome, <?php echo $username ?>!</b>
	</header><!-- .header-->
	
	<div class="containerGraph">
			<main class="content">
					<div id='myChart'></div>
      <script>
				function dateFormat(dateArray) {
					for (var i=0; i<dateArray.length; i++) {
						var date = new Date(dateArray[i]);

						var year = date.getFullYear();
						var month = date.getMonth()+1;
						var day = date.getDate();

						if (day < 10) {
							day = '0' + day;
						}
						if (month < 10) {
							month = '0' + month;
						}

						var formattedDate = month + '-' + day + '-' + year;
						dateArray[i] = formattedDate;
					}
					return dateArray;
					
				}
				
        window.onload=function(){
          zingchart.render({
              id:"myChart",
              width:"100%",
              height:400,
              data:{
              "type":"line",
							"labels":[
								{
									"text": "Standard Deviation: \n Minutes: <?php echo $stand_dev_minute ?> \n Pickups: <?php echo $stand_dev_pickup ?>",
									"font-size":"10",
									"x":"1%",
									"y":"2%",
									"border-radius":"5px",
									"fontStyle": 'bold'
								}
							],
              "legend":{
    
              },
              "title":{
                  "text":"My Phone Data"
              },
              "scale-x":{
                  "labels":dateFormat(dateLabel)
              },
              "series":[
                {
                      "values":minuteData,
                      "text": "Minutes on Phone"
                  },
                {
                      "values":pickupData,
                      "text": "Pickup Count"
                  }
          		]
          }
          });
          };

      </script>
		</div><!-- .container-->

	<div class="parentDiv">
		<div class="containerSurvey">
			<table>
				<tr>
					<td>
				<div id="surveyQuestionOne" class="surveyQuestion">
<!-- 					<form method="POST" onsubmit="return hideQuestion()"> -->
					<form method="POST">
					<b><?php echo $questionOne;?></b> <br>
					<input type="hidden" name="questionName" id="questionName" value="surveyQuestionOne">
					<input type="hidden" name="username" id="username_id" value="<?php echo $username; ?>">
					<input type="hidden" name="email" id="email_id" value="<?php echo $email; ?>">
					<input type="hidden" name="reg_date" id="date_id" value="<?php echo $date; ?>">
					<input type="hidden" name="number_id" id="number_id" value=1>
					<textarea name="answer" rows="5" cols="40"></textarea><br>
					<button type="submit" value="submit">Submit</button>
				</form>
				</div>
						</td>
					<td>
				<div class="questionHistory" id="questionHistory">
					<button onclick="hideFunction('questionHistory')">Click Me</button>

					<div id="myDIV">
						This is my DIV element.
					</div>

				</div>
						</td>
			</tr>

			<tr>
				<td>
				<div id="surveyQuestionTwo" class="surveyQuestion">
<!-- 					<form method="POST" onsubmit="return hideQuestion()"> -->
					<form method="POST">
					<b><?php echo $questionTwo;?></b> <br>
					<input type="hidden" name="username" id="username_id" value="<?php echo $username; ?>">
					<input type="hidden" name="email" id="email_id" value="<?php echo $email; ?>">
					<input type="hidden" name="reg_date" id="date_id" value="<?php echo $date; ?>">
					<input type="hidden" name="number_id" id="number_id" value=2>
					<textarea name="answer" rows="5" cols="40"></textarea><br>
					<button type="submit" value="submit">Submit</button>
				</form>
				</div>
				</td>
				<td>
				<div class="questionHistory">
					<b>hi</b>
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div id="surveyQuestionThree" class="surveyQuestion">
<!-- 					<form method="POST" onsubmit="return hideQuestion()"> -->
					<form method="POST">
					<b><?php echo $questionThree;?></b> <br>
					<input type="hidden" name="username" id="username_id" value="<?php echo $username; ?>">
					<input type="hidden" name="email" id="email_id" value="<?php echo $email; ?>">
					<input type="hidden" name="reg_date" id="date_id" value="<?php echo $date; ?>">
					<input type="hidden" name="number_id" id="number_id" value=3>
					<textarea name="answer" rows="5" cols="40"></textarea><br>
					<button type="submit" value="submit">Submit</button>
				</form>
				</div>
					</td>
				<td>
				<div class="questionHistory">
					<b>hi</b>
				</div>
				</td>
			</tr>
			</table>

			
			<div id="thankYouMessage"><br>
				<b>Thank you for answering today's questions!</b>
			</div>

		</div>
		
		
		<div class="containerStats">
			<table class="pure-table pure-table-bordered">
			<thead>
					<tr>
							<th>Average:</th>
							<th>Pickups</th>
							<th>Minutes</th>
							<th>Minutes/Pickup</th>
					</tr>
			</thead>

			<tbody>
					<tr>
							<td><b>My Stats</b></td>
							<td><?php echo (int)$userPickupAverage;?></td>
							<td><?php echo (int)$userMinuteAverage;?></td>
							<td><?php echo round(((int)$userMinuteAverage / (int)$userPickupAverage), 2);?></td>
					</tr>

					<tr>
							<td><b>Community</b></td>
							<td><?php echo (int)$pickupAverage;?></td>
							<td><?php echo (int)$minuteAverage;?></td>
							<td>2012</td>
					</tr>

					<tr>
							<td><b>My Goal</b></td>
							<td>Hyundai</td>
							<td>Elantra</td>
							<td>2010</td>
					</tr>
			</tbody>

	</div><!-- .middle-->

</div><!-- .wrapper -->

</body>
</html>
<script>
// 	var questionCount = 0;
// 	var answerOne = '<?php echo $answerOne?>';
// 		if (answerOne.length != 0) {
// 			console.log(answerOne);
// 				document.getElementById('surveyQuestionOne').style.display = 'none';
// 				console.log('one and none');
// 				questionCount ++;
// 		}
// 		else {
// 				document.getElementById('surveyQuestionOne').style.display = 'inline';
// 		}
	
// 	var answerTwo = '<?php echo $answerTwo?>';
// 		if (answerTwo.length != 0) {
// 				document.getElementById('surveyQuestionTwo').style.display = 'none';
// 				questionCount ++;
// 		}
// 		else {
// 				document.getElementById('surveyQuestionTwo').style.display = 'inline';
// 		}
	
// 	var answerThree = '<?php echo $answerThree?>';
// 		if (answerThree.length != 0) {
// 				document.getElementById('surveyQuestionThree').style.display = 'none';
// 				questionCount ++;
// 		}
// 		else {
// 				document.getElementById('surveyQuestionThree').style.display = 'inline';
// 		}
// 	console.log(questionCount);
// 	if (questionCount == 3) {
// 			document.getElementById('thankYouMessage').style.display = 'inline';
// 			console.log("set visible");
// 	}
// 	else {
// 			document.getElementById('thankYouMessage').style.display = 'none';
// 			console.log('should stay false');
// 	}
</script>

<?php
$status = false;
// figure out thankYouVisible javascirpt/php communication


if(isset($_POST)) {
  echo '<script>console.log("set")</script>';
    $username = $_POST['username'];
    $email = $_POST["email"];
    $question_number = intval($_POST["number_id"]);
    $answer = $_POST["answer"];
    $reg_date = $_POST["reg_date"];

    $total_query = mysqli_query($mysqli, "SELECT question_number FROM survey_questions ORDER BY question_number DESC LIMIT 1;");
    $question_total_string = mysqli_fetch_assoc($total_query)['question_number'];
    $question_total = intval($question_total_string);

    $answer_sql = "INSERT INTO member_surveys (username, question_number, reg_date, answer) VALUES ('$username', " . $question_number . ", 
      '" . ($mysqltime = date ("Y-m-d", $reg_date)) . "', '" . $answer . "' )";
    $status = mysqli_query($mysqli, $answer_sql);
    if ($status == false) {
            echo '<script>console.log("false")</script>';
        } else {
            echo '<script>console.log("true")</script>';
        }

//     if ($question_number < $question_total) {
//         $question_number += 1;
//         echo '<script>console.log("increased number_id")</script>';
//     }
// 		else {
// 				//TODO: deal with
// 		}
	
}


?>

<?php
/* Close the connection */
$mysqli->close(); 
?>




