<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="../../static/css/style.css?v=<?php echo time(); ?>"> 
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&family=Montserrat:wght@200;400&display=swap" rel="stylesheet">
    <title>Manzima Funeral Home</title>

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
    </style>

</head>
<body >
      
    <nav style="font-family: 'Space Grotesk', sans-serif;">  
      <div>
        <img src="../../images/logo.png" width="" height="70px">
      </div>
      <ul>
             <li><a href="../../index.php">Home</a></li>
            <li class="active"><a href="index.php">All bodies</a></li>
            <?php if(isset($_SESSION['uid'])) {?>
                <li><a href="add.php?att_id=<?php echo $_SESSION['uid'] ?>">Admit Body</a> </li>
                <li><a href="claimed.php">Claimed Bodies</a> </li>
            <?php } ?>
            <?php if(isset($_SESSION['uid'])) {?>
                <li class="log" style="color: #fff;">Hi <a href="#"><?php echo $_SESSION['fname'] ?></a></li>
                <li><a href="logout.php">Log out</a></li>
            <?php } else {?>
                <li class="log"><a href="login.php"> Login </a></li>
            <?php } ?>
        </ul>
    </nav>