<?php

$connection = mysqli_connect("localhost:3306","root","","register");

if(!$connection){ 
    die("Error". mysqli_connect_error());

}

$query = "select * from users";

$stmt = mysqli_query($connection,$query);

while($row = mysqli_fetch_array($stmt,MYSQLI_ASSOC)){
    
    echo $row['LastName']. '  ' .$row['FirstName'].'<br>';
}

?>