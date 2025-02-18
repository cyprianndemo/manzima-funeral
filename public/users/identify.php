<?php 
  require_once "../../db/Database.php";
  require_once "../../Models/Body.php";

  $db = new Database();
  $con = $db->connect();

  $body = new Body($con);
  $body_det = $body->getUnclaimedBodies();

?>

<?php include_once("../includes/user_header.php") ?>


<?php 
  if(!isset($_SESSION['uid'])) {
    header('Location: login.php');
  }
?>


<div class="container my-2">
  <ul class="list-unstyled">
    <?php foreach($body_det as $bd) {?>
      <li class="media">
        <div class="media-body">
          <h5 class="mt-0 mb-1">Body-M-<?php echo md5($bd["body_id"]) ?></h5>
          <p style="font-size: 17px;"><b>Description:</b> <?php echo $bd["desc_text"] ?> | <b>Found at:</b> <?php echo $bd["location_desc"] ?> | <b>Gender:</b> <?php echo $bd["gender_desc"] ?></p>
          <a class="btn-primary" href="claim.php?id=<?php echo $bd["body_id"] ?>" role="button">Claim body</a>
        </div>
      </li>
      <hr>
    <?php }?>
  </ul>
</div>
