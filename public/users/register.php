<?php
require_once "../../db/Database.php";
require_once "../../Models/User.php";
require_once "../../Models/LocationHelper.php";


$db = new Database();
$conn = $db->connect();

function getLocation($conn) {
  $stmt = $conn->prepare("SELECT * FROM location");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$fname = "";
$lname = "";
$uname = "";
$lcn = "";
$phone = "";
$location = "";
$pwd = "";
$cpwd = "";

$errors = [
  'fname' => "",
  'lname' => "",
  'email' => "",
  'phone' => "",
  'pwd' => "",
  'pwd1' => ""
];

if($_SERVER['REQUEST_METHOD'] === 'POST') {

  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $nat_id = $_POST['nat_id'];
  $lcn = $_POST['lcn'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $pwd = $_POST['pwd'];
  $cpwd = $_POST['cpwd'];


  // handle input validation
  if(!preg_match("/[a-z\s-]/i", $fname)) {
    $errors['fname'] ="name must be letters only";
  }
  if(!preg_match("/[a-z\s-]/i", $lname)) {
    $errors['lname'] ="name must be letters only";
  }
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Invalid email address";
  }
  if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/', $pwd)) {
      $errors['pwd'] = "Password must contain 8 or characters, capital letters and special characters";
  }
  if($cpwd !== $pwd) {
      $errors['pwd1'] = "Passwords do not match";
  }
  if(!preg_match("/(^07|01)[0-9]{8}$/mi", $phone)) {
      $errors['phone'] = "Invalid phone";
  }
  

  // post to database
  if(!array_filter($errors)) {

    $user_arr = [
      'fname' => $fname,
      'lname' => $lname,
      'lcn' => $lcn,
      'nat_id' => $nat_id,
      'email' => $email,
      'phone' => $phone,
      'pwd' => $pwd

    ];

      $user = new User($conn);
      $user->register($user_arr);

      header('Location: login.php');
  }

}

?>

<?php include_once("./../includes/user_header.php"); ?>

      <style>
        .red-text{
          color:red;
          font-weight:600;
        }
      </style>

      <div class="logIn">
        <a href="../../index.php">Home</a>
        <a> Sign Up</a>
        </div>
        <div  class="form-content" >
          <form name="myForm" action="register.php" method="post">
            <p>First Name</p>
            <input type="text" name="fname" placeholder="Enter your First Name" required>
            <div class="red-text"><?php echo $errors['fname']; ?></div>
            <p>Last Name</p>
            <input type="text" name="lname" placeholder="Enter your last Name" required>
            <div class="red-text"><?php echo $errors['lname']; ?></div>
            <p>Email</p>
            <input type="email" name="email" placeholder="Enter your email" required>
            <div class="red-text"><?php echo $errors['email']; ?></div>
            <p>ID number</p>
            <input type="text" name="nat_id" placeholder="Enter your national ID Number" required>
            <div class="form-group col-md-6">
              <p>Location</p><br>
              <select class="form-control" name="lcn" style="width:100%;">
                  <?php $lcns = getLocation($conn); ?>
                  <?php foreach($lcns as $type) { ?>
                    <option value=<?php echo $type["location_id"] ?> ><?php echo $type["location_desc"] ?></option>
                  <?php } ?>
              </select>
            </div>
            <p>Contact</p> 
            <input type="text" name="phone" placeholder="Enter your Phone Number" required>
            <div class="red-text"><?php echo $errors['phone']; ?></div>
            <p>Password</p>
            <input type="password" name="pwd" placeholder="Enter your Password" required><br>
            <div class="red-text"><?php echo $errors['pwd']; ?></div>
            <p>Confirm Password</p>
            <input type="password" name="cpwd" placeholder="Enter your Password" required><br>
            <div class="red-text"><?php echo $errors['pwd1']; ?></div>
            <button type="submit" name="submit">Sign Up</button> <br> <br>
            <p> Already Have an Account <a href="login.php">Login</a></p> 
          </form>
    </div>
    <?php include_once("./../includes/footer.php") ?> 