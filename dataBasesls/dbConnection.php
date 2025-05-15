<?php

$username = "root";
$host = "localhost";
$password ="";
$con = mysqli_connect($host,$username,$password);
$db = mysqli_select_db($con, "HMReportSystem");