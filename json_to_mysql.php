<?php

include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

$email = "shannonrubin9@gmail.com";

$newestDateResult=mysqli_query($mysqli, "SELECT reg_date FROM phone_data WHERE email = '$email' ORDER BY id DESC LIMIT 1");
$newestDate = mysqli_fetch_assoc($newestDateResult)['reg_date'];				
$startDate = strtotime($newestDate);

$jsonFile="moment/SR_5_22.json";
$jsondata = file_get_contents($jsonFile);

$reverseData = json_decode($jsondata, true);
$array_data = array_reverse($reverseData['days'], true);


foreach ($array_data as $row) {  
    $currDateString = $row['date'];
    $currDate = strtotime($currDateString);

    if ($currDate > $startDate) {
        $sql = "INSERT INTO phone_data (email, reg_date, minuteCount, pickupCount)
        VALUES ('$email', '" . $row["date"] . "', " . $row["minuteCount"] . ", " . $row["pickupCount"] . ")";
        $status = mysqli_query($mysqli, $sql);
        if ($status == false) {
            echo 'query error';
        } else {
            echo 'successful row insertion';
        }
      
    }
    else {
      echo 'already in database';
    }
}

?>

