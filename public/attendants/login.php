<?php
require_once('../../db/Database.php');
require_once('../../Models/Attendant.php');

$db = new Database();
$conn = $db->connect();

if(isset($_SESSION['uid'])) {
   session_destroy();
}

$errors = [
    'pwd' => ""
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
 
    $user = new Attendant($conn);

     if($user) {
        $checkLog = $user->logIn($email, $pwd);
        if($checkLog['isLogged']) {

            session_start();
    
            $_SESSION['uid'] = $checkLog['userObject']['att_id'];
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
<?php include_once("./../includes/att_header.php") ?>
    
        <div  class="form-content" >
            <form action="login.php" method="post" >
                <h2 style="text-align: center;">Attendant log in</h2>
                <p>Email</p>
                <input type="email" class="form-control" id="username" name="email" placeholder="Email" required>
                <p>Password</p>
                <input type="password" name="pwd" placeholder="Enter your Password" required><br>
                <div class="red-text"><?php echo $errors['pwd']; ?></div>
                <button type="submit" name="submit">Log In</button> <br> <br>
            </form>
     </div>
</div>
<!-- <?php
  include_once '../../footer.php';
?> -->