<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}



$name="";
$phone="";
$picture=null;
if(isset($_POST) && isset($_FILES)){
    //checks
    print_r($_POST);
    print_r($_POST["name"]);
    print_r($_POST["phone"]);
    print_r($_POST["pictures"]);
    print_r($_FILES);
    print_r($_FILES["file"]["name"]); //string
    print_r($_FILES["file"]["tmp_name"]); //actual file location

    //add credentials
    $hostname="";
    $username="";
    $password="";
    $dbname="";

    $mysqli = new mysqli($hostname, $username, $password, $dbname);

    //set
    $name=$_POST["name"];
    $phone=$_POST["phone"];
    $picture=$_POST["picture"];
    $img=file_get_contents($_FILES["file"]["tmp_name"]); //because is reading file of temp location in server
    $saveImg=mysqli_real_escape_string($mysqli, $img); //must escape or break

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }else{
    $db_selected = mysqli_select_db($mysqli, $dbname) or die(mysqli_error($mysqli));
    if (!$db_selected) {
        die(mysqli_error($mysqli));
    }else{
        $sql = "INSERT INTO tbl_users (name, phone, picture) VALUES ('$name', $phone, '$saveImg')"; //saveimg must be in quotes
        if (!mysqli_query($mysqli, $sql))
        {
            die('Error:'.mysqli_error($mysqli));
        }
        echo "SUCESSS!";
        }
    }
mysqli_close($mysqli);
}
