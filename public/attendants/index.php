<?php 
  require_once "../../db/Database.php";
  require_once "../../Models/Body.php";

  $db = new Database();
  $conn = $db->connect();

  $body = new Body($conn);
  $body_det = $body->getBodies();

  if($_SERVER["REQUEST_METHOD"] === "POST") {

    $b_id = $_POST['b_id'];
    $body->pickBody($b_id);
  }

?>

<?php include_once("../includes/att_header.php") ?>

<?php 
  if(!isset($_SESSION['uid'])) {
    header('Location: login.php');
  }
?>

<div class="cst" style="width: 97vw;">
  <table id="customers" style="font-family: 'Roboto', sans-serif;">
    <tr style="font-family: 'Space Grotesk', sans-serif; font-size: 13px;">
      <th>Body id</th>
      <th>Place found</th>
      <th>Death Cause</th>
      <th>Hospital</th>
      <th>Gender</th>
      <th>Age range</th>
      <th>Description</th>
      <th>Admited on</th>
      <th>Claimed</th>
      <!-- <th>Identified</th> -->
      <!-- <th>Reuested</th> -->
      <!-- <th>Picked</th> -->
      <th>Action</th>
    </tr>
    <tr>
      <?php foreach($body_det as $body) {?>
        <tr style="font-size: 12px;">
          <td><?php echo $body['body_id'] ?></td>
          <td><?php echo $body['location_desc'] ?></td>
          <td><?php echo $body['cause_desc'] ?></td>
          <td><?php echo $body['hospital'] ?></td>
          <td><?php echo $body['gender_desc'] ?></td>
          <td><?php echo $body['age_range'] . " " . $body['range_desc'] ?></td>
          <td><?php echo $body['desc_text'] ?></td>
          <td><?php echo $body['created_on'] ?></td>
          <td>
            <?php if($body['isClaimed']){ ?>
              <p>Claimed</p>
            <?php } else {?>
              <p>Not claimed</p>
            <?php } ?>
          </td>
          <!-- <td>
            <?php if($body['isIdentified']){ ?>
              <a href="#">Identified</a>
            <?php } else {?>
              <p>Not Identified</p>
            <?php } ?>
          </td> -->
          <!-- <td>
            <?php if($body['isRequested']){ ?>
              <a href="#">Requested</a>
            <?php } else {?>
              <p>Not Requested</p>
            <?php } ?>
          </td> -->
          <!-- <td>
            <form action="index.php" method="post">
              <input type="hidden" name="b_id" value="<?php echo $body['body_id'] ?>">
              <?php if($body['isPicked']) { ?>
                <button disabled="disabled" class="pick">Picked</button>
              <?php } else {?>
                <button type="submit" class="n_pick">Not Picked</button>
              <?php } ?>
            </form>
            
          </td> -->
          <td>
            <a href="./edit.php?id=<?php echo $body['body_id'] ?>" class="dl">Edit</a>
          </td>
        </tr>
      <?php }?>
      
    </tr>   
  </table>
</div>
