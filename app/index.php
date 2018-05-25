<!--start db script-->
<?php

//add credentials
$hostname="";
$username="";
$password="";
$dbname="";
$usertable="";
$result=array();

$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}else{
    $db_selected = mysqli_select_db($mysqli, $dbname) or die(mysqli_error($mysqli));
    if (!$db_selected) {
        echo "fail1";
        die(mysqli_error($mysqli));
    }else{
        $result = mysqli_query($mysqli, "SELECT * FROM tbl_users ORDER BY `id` DESC");
        if($result==false){
            echo "fail2";
            die(mysqli_error($mysqli));
        }
    }
}
mysqli_close($mysqli);
?>
<!--end db script-->


<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ionic2 App</title>
    <meta name="description" content="Ionic2 App">
    <meta name="author" content="codingpandas.org">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="jumbotron">
            <h1>Uploaded results</h1>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Picture</th>
                </tr>
                </thead>
                <tbody>
                <!--display rows-->
                <?php
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".($row['name'])."</td>";
                    echo "<td>".($row['phone'])."</td>";
                    //base64_encode works
                    echo '<td><img src="data:image/jpeg;base64,'.base64_encode($row["picture"]).'" alt="HTML5 Icon" style="width:128px;height:128px"></td>';
                    echo "</tr>";
                    //echo "<br><br>";
                }
                ?>
                <!--end display rows-->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>