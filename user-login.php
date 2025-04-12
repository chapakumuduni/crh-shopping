<?php
session_start();
//Login
include("db_conn.php");

$message = '
        <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
            <div>
                No record found !
            </div>
        </div>';


if(isset($_POST["signInBtn"])) {
    $uname = $_POST["loginUsername"];
    $pass = $_POST["loginPassword"];


    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_name = :userName and password = :passWd");
    $stmt->bindParam(':userName', $uname);
    $stmt->bindParam(':passWd', $pass);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($results as $result) {
        echo $result['image'], '<br>';

        $usersName = $result['user_name'];
        $deliveryDistrict = $result['district'];
        $_SESSION["deliveryDistrict"] = $deliveryDistrict;
        $_SESSION["userName"] = $usersName;
        $verifyPassword = true; //password_verify($pass,$result['password']);
        if($uname == $usersName && $verifyPassword){ 
            header("Location:index.php");
        }else{
            $message = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    Invalied user name or password !
                </div>
            </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login & Registration Form</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">

  <!-- favicon -->
  <link rel="icon" href="images/icons/favicon.png" type="image/x-icon">
</head>
<body>

<?php
//Register

  if(isset($_POST["btnSignUp"])) {
    $fullname = $_POST["signUpFullName"];
    $userName = $_POST["SingUpUserName"];
    $password = $_POST["signUpPassword"];
    $phone = $_POST["SingUpPhone"];
    $email = $_POST["SingUpEmail"];
    $residential_address = $_POST["SingUpResidentialAddress"];
    // $district = $_POST["district"];
    //$password = password_hash($_POST["signUpPassword"],PASSWORD_DEFAULT);
    

    $checkQuery = "SELECT * FROM user WHERE user_name = '$userName'";
    $userresult = mysqli_query($conn,$checkQuery);
    if(!mysqli_num_rows($userresult) >=1) {

        $stmt = $pdo->prepare( "INSERT INTO user ( user_name, password, full_name, phone, email, residential_address) 
                    VALUES (:userName, :passWd, :fullname, :phone, :email, :residential_address)" );
        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':passWd', $password);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':residential_address', $residential_address);

        $stmt->execute();
?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>Swal.fire({icon: "success", title: "success",text: "successfuly Registerd !",});</script>

 <?php   } else{ ?>
           <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
           <script>Swal.fire({icon: "error", title: "Oops...",text: "UserName is already exist !",});</script>
 <?php   }
    
  }
?>



  <div class="cont">

    <div class="form sign-in">
        <form  method="post">
            <h2>Sign In</h2>
            <label>
                <span>User Name</span>
                <input type="text" name="loginUsernaem" required>
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="loginPassword" id="loginPassword" required>
            </label>
            <label>
            <input type="checkbox" id="showPassword"> Show Password
            </label>
            <button class="submit" type="submit" name="singInBtn">Sign In</button>
        </form>
        <div class="social-media">
            <p><?=$message ?></p>
        </div>
    </div>

    <div class="sub-cont">
        <div class="img">
            <div class="img-text m-up">
                <h1>New User</h1>
                <p>sign up and discover</p>
            </div>
            <div class="img-text m-in">
                <h1>Registered User</h1>
                <p>just sign in</p>
            </div>
            <div class="img-btn">
                <span class="m-up">Sign Up</span>
                <span class="m-in">Sign In</span>
            </div>
        </div>

        <div class="form sign-up">
            <form method="post">
                <h2>Sign Up</h2>
                <label>
                    <span>Full name</span>
                    <input type="text" name="signUpFullName" required>
                </label>
                <label>
                    <span>Phone</span>
                    <input type="text" name="SingUpPhone" required>
                </label>
                <label>
                    <span>Email</span>
                    <input type="text" name="SingUpEmail" required>
                </label>
                <label>
                    <span>Residential Address</span>
                    <input type="text" name="SingUpResidentialAddress" required>
                </label>

                <label>
                    <span>UserName</span>
                    <input type="text" name="SingUpUserName" required>
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="signUpPassword" required>
                </label>
                <button type="submit" class="submit" name="btnSignUp">Sign Up</button>
            </div>
            </form>
        </div>
  </div>
</div>
 <script>
  document.getElementById('showPassword').addEventListener('change', function() {
    var passwordField = document.getElementById('loginPassword');
    if (this.checked) {
        passwordField.type = 'text'; 
    } else {
        passwordField.type = 'password'; 
    }
});
 </script>
<script type="text/javascript" >
    document.querySelector('.img-btn').addEventListener('click', function()
        {
            document.querySelector('.cont').classList.toggle('s-signup')
        }
    );
</script>
</body>
</html>