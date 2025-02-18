<?php 

   session_start();

   require_once "../../db/Database.php";
   require_once "../../Models/Body.php";

   $db = new Database();
   $con = $db->connect();

   $uid = $_SESSION["uid"];
   $bid = $_GET["id"];

   $body = new Body($con);
   $bd_arr = $body->claimBody($uid, $bid);

   header('Location: ./identify.php');
?>
