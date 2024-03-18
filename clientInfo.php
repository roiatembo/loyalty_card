<?php 

$servername = "localhost";
$username = "dithetoc_roia";
$password = "rolanga4";
$dbname = "dithetoc_loyalty";

if (isset($_POST)) {
    $cardNumber = $_POST["cardNumber"];

    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT * FROM clients WHERE card_number = '$cardNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $fullName = $row["full_name"];
            $email = $row["email"];
            $phoneNumber = $row["phone_number"];
            $points = $row["reward_count"];
            $reward_earned = $row["is_reward_earned"];
         
            $conn->close();

            echo '{"full_name": "'.$fullName.'", "email": "'.$email.'", "phone_number": "'.$phoneNumber.'", "points": "'.$points.'", "reward_earned": "'.$reward_earned.'", "card_number": "'.$cardNumber.'"}';
        }
    }
}


?>