<?php
require_once('../../db/Database.php');
require_once('../../Models/Attendant.php');

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
 
    if($email == "admin@manzima.com" && $pwd == "Admin123") {
        session_start();
        $_SESSION["aid"] = md5(rand());
        header("Location: index.php");
    }
    else {
        $errors["pwd"] = "Wrong details. Try again";
    }
}

?>
<?php include_once("./../includes/adm_header.php") ?>
    
        <div  class="form-content" >
            <form action="login.php" method="post" >
                <h2 style="text-align: center;">Admin log in</h2>
                <p>Email</p>
                <input type="email" class="form-control" id="username" name="email" placeholder="Email" required>
                <p>Password</p>
                <input type="password" name="pwd" placeholder="Enter your Password" required><br>
                <small><p><?php echo $errors['pwd']; ?></p></small>
                <button type="submit" name="submit">Log In</button> <br> <br>
            </form>
     </div>
</div>
