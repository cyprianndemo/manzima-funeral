<?php
require_once('../../db/Database.php');
require_once('../../Models/User.php');

$db = new Database();
$conn = $db->connect();

if(isset($_SESSION['uid'])) {
   session_destroy();
}

$errors = array(
   'pwd' => ''
);

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
 
    $user = new User($conn);

    if($user) {
        $checkLog = $user->logIn($email, $pwd);
        if($checkLog['isLogged']) {

            session_start();
    
            $_SESSION['uid'] = $checkLog['userObject']['user_id'];
            $_SESSION['fname'] = $checkLog['userObject']['f_name'];
            $_SESSION['email'] = $checkLog['userObject']['email'];
    
            header('Location: index.php');
        } else {
            $errors["pwd"] = "Wrong password. Try again";
        }
    } else {
        $errors["pwd"] = "User account does not exist. <span><a href='register.php'>Register</a></span> to log in";
    }
}

?>
<?php include_once("./../includes/user_header.php") ?>
    <div>
    <div class="logIn">
    <a href="../../index.php">Home</a>
    <a> Login</a>
    </div>
        <div  class="form-content" >
            <form action="login.php" method="post" >
                <p style="margin-bottom:40px; color: maroon;">Already have an account? Login.</p>
                <p>Email</p>
                <input type="email" class="form-control" id="username" name="email" placeholder="Email" required>
                <p>Password</p>
                <input type="password" name="pwd" placeholder="Enter your Password" required><br>
                <small><p><?php echo $errors['pwd']; ?></p></small>
                <button type="submit" name="submit">Sign In</button> <br> <br>
               <p>Dont have an account? <a href="register.php">Sign Up</a></p> 
            </form>
     </div>
</div>
<?php include_once("./../includes/footer.php") ?>