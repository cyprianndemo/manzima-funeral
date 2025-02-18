<style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&family=Space+Grotesk&display=swap');
        #customers {
            margin: 5px;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }

        .n_pick{
            padding: 5px 8px;
            background: red;
            color: #fff;
            border: none;
            border-radius: 3px;
        }
        .pick{
            padding: 5px 8px;
            border: none;
            border-radius: 3px;
        }
        nav {
            position: relative;
        }
        nav ul li a {
            font-size: 15px;
        }
        .log{
            margin-left:200px
        }
        .active
        {
            margin-left: 120px;
        }   
        #PrintButton{
            padding:10px 25px;
            margin:10px
        }
       @media only print {
          footer, header, .sidebar {
            display:none;
        }
    }
</style>
	
    </style>
<?php 
  require_once "../../db/Database.php";
  require_once "../../Models/Body.php";

  $db = new Database();
  $conn = $db->connect();

  $body = new Body($conn);
  $body_det = $body->getClaimedBodies();

  if($_SERVER["REQUEST_METHOD"] === "POST") {

    $b_id = $_POST['b_id'];
    $body->pickBody($b_id);
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
      <!--th>Identified</th-->
      <!-- <th>Reuested</th> -->
      <!-- <th>Picked</th> -->
      <!-- <th>Action</th> -->
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
              <a href="#">Claimed</a>
            <?php } else {?>
              <p>Not claimed</p>
            <?php } ?>
          </td>
          <!--td>
            <?php if($body['isIdentified']){ ?>
              <a href="#">Identified</a>
            <?php } else {?>
              <p>Not Identified</p>
            <?php } ?>
          </td -->
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
          <!-- <td> 
            <a href="delete.php?id=<?php echo $body['body_id'] ?>" class="dl">Delete</a> -->
          </td>
        </tr>
      <?php }?>
      
    </tr>   
  </table>
</div>

<a href="" class="btn btn-success pull-right" id ="customers" onclick="window.print()"> print </a>
<!-- <button id="customers" class= "pritbar" onclick="window.print()">Print</button> -->

<script type="text/javascript">
	    function PrintData()
      {
		     var divToPrint= document.getElementById("customers");
         newWin= window.open("");
         newWin.document.write(divToPrint.outerHTML);
         newWin.print();
         newWin.close();
      }
      $('button').on('click',function(){
        PrintData();
      })
         
    </script>