<?php
require 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM inscrit WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        //if (password_verify($password, $user['password'])) {
        if($password==$user['password']){
            session_start();
            $_SESSION['user_id'] = $user['id'];
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.html");
                exit();
            } 
                        
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found";
    }
}
?>
