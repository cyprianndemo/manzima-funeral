<?php
require_once "../../db/Database.php";
require_once "../../Models/Body.php";

$db = new Database();
$conn = $db->connect();

function getLocation($conn) {
  $stmt = $conn->prepare("SELECT * FROM location");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getCause($conn) {
  $stmt = $conn->prepare("SELECT * FROM causes");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getGnd($conn) {
  $stmt = $conn->prepare("SELECT * FROM gender");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getRange($conn) {
  $stmt = $conn->prepare("SELECT * FROM age_range");
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if($_SERVER["REQUEST_METHOD"] === "POST") {
  $body_arr = array(
    "place" => $_POST['lcn'],
    "cause" => $_POST['cse'],
    "hospital" => $_POST['hsp'],
    "gender" => $_POST['gnd'],
    "age_range" => $_POST['age'],
    "desc" => $_POST['desc']
  );

  $body = new Body($conn);
  $body->addBody($body_arr, $_GET['att_id']);
  header('Location: index.php');
}
?>
<?php include_once("../includes/att_header.php") ?>
<div  class="form-content" >
          <form name="myForm" action="" method="post">
            <div class="form-group col-md-6">
              <p>Place found</p><br>
              <select class="form-control" name="lcn" style="width:100%;">
                  <?php $lcns = getLocation($conn); ?>
                  <?php foreach($lcns as $type) { ?>
                    <option value=<?php echo $type["location_id"] ?> ><?php echo $type["location_desc"] ?></option>
                  <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <p>Hospital:</p><br>
              <input type="text" name="hsp" id="" class="form-control" required >
            </div>
            <div class="form-group col-md-6">
              <p>Gender</p><br>
              <select class="form-control" name="gnd" style="width:100%;">
                  <?php $lcns = getGnd($conn); ?>
                  <?php foreach($lcns as $type) { ?>
                    <option value=<?php echo $type["gender_id"] ?> ><?php echo $type["gender_desc"] ?></option>
                  <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <p>Cause of death</p><br>
              <select class="form-control" name="cse" style="width:100%;">
                  <?php $lcns = getCause($conn); ?>
                  <?php foreach($lcns as $type) { ?>
                    <option value=<?php echo $type["cause_id"] ?> ><?php echo $type["cause_desc"] ?></option>
                  <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <p>Age:</p><br>
              <select class="form-control" name="age" style="width:100%;">
                  <?php $lcns = getRange($conn); ?>
                  <?php foreach($lcns as $type) { ?>
                    <option value=<?php echo $type["range_id"] ?> ><?php echo $type['age_range'] ." ".$type["range_desc"] ?></option>
                  <?php } ?>
              </select>
            </div>
            <p>Description:</p> 
            <textarea name="desc" id="" cols="30" rows="5" style="width:100%"></textarea>
            <button type="submit" name="submit">Admit body</button> <br> <br>
          </form>
    </div>