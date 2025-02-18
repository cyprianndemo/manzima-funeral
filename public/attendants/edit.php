<?php
require_once "../../db/Database.php";
require_once "../../Models/Attendant.php";
require_once "../../Models/Body.php";


// get metadata
function getLocation($conn) {
  $stmt = $conn->prepare("SELECT * FROM location");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCauses($conn) {
  $stmt = $conn->prepare("SELECT * FROM causes");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGender($conn) {
  $stmt = $conn->prepare("SELECT * FROM gender");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getRange($conn) {
  $stmt = $conn->prepare("SELECT * FROM age_range");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// ============================================


$db = new Database();
$conn = $db->connect();

$bd = new  Body($conn);
$bd_arr = $bd->getSingleBody($_GET["id"]);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

  $place = $_POST['lcn'];
  $cause = $_POST['cause'];
  $hospital = $_POST['hsp'];
  $gender = $_POST['gnd'];
  $age_range = $_POST['range'];
  $desc = $_POST['desc'];

  $user_arr = [
    'place' => $place,
    'cause' => $cause,
    'hospital' => $hospital,
    'gender' => $gender,
    'age_range' => $age_range,
    'desc' => $desc
  ];

  $body = new Body($conn);
  $body->updateBody($user_arr, $_GET["id"]);

  header('Location: ./index.php');
  

}

?>

<?php include_once("./../includes/att_header.php"); ?>

      <div class="logIn">
        <!-- <a href="../index.php">Home</a>
        <a> Sign Up</a> -->
        </div>
        <div  class="form-content" >
          <form name="myForm" action="" method="post">
            <p>Hospital</p>
            <input type="text" name="hsp" Value="<?php echo $bd_arr["hospital"] ?>">
            <p>Gender: </p><br>
            <select class="form-control" name="gnd">
              <?php $lcns = getGender($conn); ?>
              <?php foreach($lcns as $type) { ?>
                <option value=<?php echo $type["gender_id"] ?> ><?php echo $type["gender_desc"] ?></option>
              <?php } ?>
            </select>
            <p>Place found</p><br>
            <select class="form-control" name="lcn">
              <?php $lcns = getLocation($conn); ?>
              <?php foreach($lcns as $type) { ?>
                <option value=<?php echo $type["location_id"] ?> ><?php echo $type["location_desc"] ?></option>
              <?php } ?>
            </select>
            <p>Age: </p><br>
            <select class="form-control" name="range">
              <?php $lcns = getRange($conn); ?>
              <?php foreach($lcns as $type) { ?>
                <option value=<?php echo $type["range_id"] ?> ><?php echo $type["age_range"] . " - " . $type["range_desc"] ?></option>
              <?php } ?>
            </select>
            <p>Cause: </p><br>
            <select class="form-control" name="cause">
              <?php $lcns = getCauses($conn); ?>
              <?php foreach($lcns as $type) { ?>
                <option value=<?php echo $type["cause_id"] ?> ><?php echo $type["cause_desc"] ?></option>
              <?php } ?>
            </select>
            <p>Description:</p>
            <textarea name="desc" cols="10" rows="6" style="width: 100%;"><?php echo $bd_arr["desc_text"] ?></textarea>
            <button type="submit" name="submit">Update</button> <br><br>
            </div>
            
          </form>
    </div>
    