<?php

include 'config.php';
session_start();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";
    $stm=$conn->prepare($sql);

    $stm->bind_param("sss", $username,$email, $password);

    if($stm->execute()){
        header("Location: login.php");
        exit();
    } else {
        echo "Error" . $stm->error;
    }

}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title> Register</title>
      <link rel="stylesheet" href="css/login.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  </head>
   <body>
      <div class="login-form">
         <div class="text">
            Register
         </div>
         <form action="" method="POST">
            <div class="field">
               <div class="fas fa-user"></div>
               <input type="text" name="username" placeholder="Username">
            </div>
            <div class="field">
               <div class="fas fa-envelope"></div>
               <input type="text" name="email" placeholder="Email">
            </div>
            <div class="field">
               <div class="fas fa-lock"></div>
               <input type="password" placeholder="Password">
            </div>
            <button>Register</button>
            <div class="link">
               You'r a member?
               <a href="login.php">Log in now</a>
            </div>
         </form>
      </div>
   </body>
</html>