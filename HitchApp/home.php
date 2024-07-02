<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/homestyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <title>Home</title>
</head>

<body>
    <!-- -------------Navigation Bar------------ -->
    <div class="navContainerWrap">
        <div class="nav-left">
            <p class="logo">
                <a href="home.php">HitchApp</a>
            </p>

        </div>
        <div class="nav-right">

            <?php 
            
            $id = $_SESSION['id'];
            $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];
                $res_id = $result['Id'];
            }
            
            echo "<a href='edit.php?Id=$res_id'>Change Profile</a>";
            ?>
            <!-- <p class="edit-profile">Edit Profile</p> -->
            
            <p class="logout-btn">
                <a href="php/logout.php"> Log Out </a>
            </p>
        </div>

    </div>
    <!-- ------------------Main Container-------------- -->
    <div class="main-container">
        <div class="main-left">
            <h1>Hitch hike safely with <br> HitchApp</h1>
            <div class="main-btns">
                <button class="ride-btn"><a href="">Find Ride</a></button>
                <button class="drive-btn"><a href="">Publish Ride</a></button>
            </div>
        </div>
        <div class="main-right">
            <img src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_767,h_960/v1695226426/assets/b9/71b0b4-b082-4d67-9615-3ea8a60e6e2a/original/header-dual.png"
                alt="HitchApp img">
        </div>
    </div>




</body>

</html>