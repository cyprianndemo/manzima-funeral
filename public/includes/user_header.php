<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel ="stylesheet" href="../../static/css/style-u.css?v=<?php echo time(); ?>"> 
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&family=Montserrat:wght@200;400&display=swap" rel="stylesheet">
    <title>Manzima Funeral Home</title>

    <style>
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
        .btn-primary
        {
             border: 2px solid;
             padding:8px 15px;
             border-radius: 20px;
            background-color: #black;
        }       
    </style>

</head>
<body >
      
    <nav>  
      <div>
        <img src="../../images/logo.png" class="image-pointer" width="" height="70px">
      </div>
      <ul>
            <li class="active"><a href="index.php">Home</a></li>
            <?php if(isset($_SESSION['uid'])) {?>
                <li class="log"><a href="./services.php">Our Services</a></li>
                <li class="log"><a href="identify.php">Identify body</a></li>
                <li class="log"><a href="logout.php">Log Out</a></li>
                <li class="log" style="color: #fff;">Hi <a href="#"><?php echo $_SESSION['fname'] ?></a></li>
                
            <?php } else {?>
                <li class="log"><a href="login.php"> Login </a></li>
                <li class="log"><a href="register.php">Sign up</a></li>
            <?php } ?>
        </ul>
    </nav>