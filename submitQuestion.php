<?php

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
 

$username = $_POST["username"];
$email = $_POST["email"];
$question_number = intval($_POST["number_id"]);
$answer = $_POST["answer"];
$reg_date = $_POST["reg_date"];

$finished = false;

$total_query = mysqli_query($mysqli, "SELECT question_number FROM survey_questions ORDER BY question_number DESC LIMIT 1;");
$question_total_string = mysqli_fetch_assoc($total_query)['question_number'];
$question_total = intval($question_total_string);


$answer_sql = "INSERT INTO member_surveys (username, question_number, reg_date, answer) VALUES ('$username', " . $question_number . ", 
  '" . $reg_date . "', '" . $answer . "' )";
$status = mysqli_query($mysqli, $answer_sql);
if ($status == false) {
        echo '<script>console.log("false")</script>';
    } else {
        echo '<script>console.log("true")</script>';
    }
?>

<script>
  console.log("question_total:");
  console.log(<?php echo $question_total?>);
  console.log("question_number");
  console.log(<?php echo $question_number?>);
//   document.getElementById(JSON.stringify(currQuestion)).style.display = "none";
//   console.log("currQuestion: " + currQuestion);
//   if (currQuestion < questionTotal) {
// //     currQuestion += 1;
// //     document.getElementById(JSON.stringify(currQuestion)).style.display = "inline";
// //     console.log("currQuestion: " + currQuestion);
//     document.getElementById("number_id").value = document.getElementById("number_id").value + 1;
//   }
  var currentNumber = document.getElementById("number_id");
  console.log(currentNumber);
  currentNumber.value = 1;
  if (currentNumber.value > <?php echo $question_total ?>) {
    // set surveyQuestion to hidden and thank you message to inline
  }
</script>

<?php

$url = "Location: ../protected_page.php?email=".urlencode($email);
header($url);
  

?>
