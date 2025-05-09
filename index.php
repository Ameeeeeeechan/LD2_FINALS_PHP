<?php

session_start();

$first_name = "";
$last_name = "";
$email = "";
$phone = "";
$address = "";

$fname_err = "";
$Lname_err = "";
$email_err = "";
$pass_err = "";
$Cpass_err = "";


$error = false;

IF($_SERVER['REQUEST_METHOD'] == 'POST'){
    $first_name = $_POST['fname'];
    $last_name = $_POST['Lname'];
    $email = $_POST['em'];
    $password = $_POST['pass'];
    $confirmed_pass = $_POST['Cpass'];




    if(empty($first_name)){
        $fname_err = "First Name is required.";
        $error = true;
    }
    if(empty($last_name)){
        $Lname_err = "Last Name is required.";
        $error = true;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Email format invalid.";
        $error = true;
    }
    

    include "tools/db.php";
    $dbConnection = getDBConnection();

    $statement = $dbConnection->prepare("SELECT id FROM users WHERE email = ?");
    $statement->bind_param("s", $email);

    $statement->execute();


    $statement->store_result();
    if ($statement->num_rows > 0){
        $email_err = "Email already used.";
        $error = true;
    }

    $statement->close();

    if(strlen($password) < 6){
        $pass_err = "Password must be greater than 7 characters.";
        $error = true;
    }
    if($confirmed_pass != $password){
        $Cpass_err = "Passwords do not match.";
        $error = true;
    }


    if(!$error){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');

        $statement = $dbConnection->prepare(
            "INSERT INTO users (first_name, last_name, email, password, createdAt) ".
            "VALUES (?,?,?,?,?)"
        );

    $statement->bind_param('sssss', $first_name,$last_name,$email,$password,$created_at);

    $statement->execute();

    $insert_id = $statement->insert_id;
    $statement->close();



    $_SESSION["id"] = $insert_id;
    $_SESSION["first_name"] = $first_name;
    $_SESSION["last_name"] = $last_name;
    $_SESSION["email"] = $email;
    $_SESSION["created_at"] = $created_at;

    header("Location: login.php");
    exit();
    }
}
?>
<html lang="en" >


<head>
  
    <title>Login</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
   
    <style media="screen">
      *,
*:before,
*:after{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body{
    background-color: #080710;
}
.background{
    width: 430px;
    height: 520px;
    position: absolute;
    transform: translate(-50%,-50%);
    left: 50%;
    top: 50%;
}
.background .shape{
    height: 200px;
    width: 200px;
    position: absolute;
    border-radius: 50%;
}
.shape:first-child{
    background: linear-gradient(
        #1845ad,
        #23a2f6
    );
    left: -80px;
    top: -80px;
}
.shape:last-child{
    background: linear-gradient(
        to right,
        #ff512f,
        #f09819
    );
    right: -30px;
    bottom: -80px;
}
form{
    height: 520px;
    width: 400px;
    background-color: rgba(255,255,255,0.13);
    position: absolute;
    transform: translate(-50%,-50%);
    top: 50%;
    left: 50%;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 40px rgba(8,7,16,0.6);
    padding: 50px 35px;
}
form *{
    font-family: 'Poppins',sans-serif;
    color: #ffffff;
    letter-spacing: 0.5px;
    outline: none;
    border: none;
}
form h3{
    font-size: 32px;
    font-weight: 500;
    line-height: 42px;
    text-align: center;
}

label{
    display: block;
    margin-top: 30px;
    font-size: 16px;
    font-weight: 500;
}
input{
    display: block;
    height: 50px;
    width: 100%;
    background-color: rgba(255,255,255,0.07);
    border-radius: 3px;
    padding: 0 10px;
    margin-top: 8px;
    font-size: 14px;
    font-weight: 300;
}
::placeholder{
    color: hsl(325, 68%, 52%);
}
button{
    margin-top: 50px;
    width: 100%;
    background-color: #f02a9d;
    color: hsl(200, 20%, 94%);
    padding: 15px 0;
    font-size: 18px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
}
.social{
  margin-top: 30px;
  display: flex;
}
.social div{
  background: rgb(255, 0, 140);
  width: 150px;
  border-radius: 3px;
  padding: 5px 10px 10px 5px;
  background-color: #f1e8f045;
  color: #eaf0fb;
  text-align: center;
}
.social div:hover{
  background-color: rgba(255,255,255,0.47);
}
.social .fb{
  margin-left: 25px;
}
.social i{
  margin-right: 4px;
}
    </style>
</head>
<body>
        <h2>Log In</h2>
        <form method= "post" id="login-form">
            <div class="input-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="<?= $first_name; ?>" required placeholder="Enter your First Name">
                <label for="Lname">Last Name</label>
                <input type="text" id="Lname" name="Lname" value="<?= $last_name; ?>" required placeholder="Enter your Last Name">
                <label for="em">Email</label>
                <input type="email" id="em" name="em" value="<?= $email; ?>" required placeholder="Enter your Last Name">
            </div>

            <div class="input-group">
                <label for="pass">Password</label>
                <input type="password" id="pass" name="pass" required placeholder="Enter your password">
                <label for="Cpass">Confirm Password</label>
                <input type="password" id="Cpass" name="Cpass" required placeholder="Enter your password">
            </div>

            <p class="forgot-password">
                <a href="#">Forgot Password?</a>
            </p>
          <button type="submit" id="createAccountButton" class="create-account-button">Register</button>
                    
            <a href="./login.php">
            <button type="button" id="createAccountButton" class="create-account-button">Login</button>
            </a> 
            <p id="error-message" class="error-message"></p>
        </form>
</body>
</html>
