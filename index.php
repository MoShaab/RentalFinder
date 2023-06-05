<?php 

  session_start(); 


  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: landlord-login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: rent-search.php");

  }
?>
<?php
include ('server.php');
if(isset($_POST["submit"])){
  $house_name = $_POST["house_name"];
  $landlord_email = $_POST["landlord_email"];
  $city = $_POST["city"];
  $monthly_rent = $_POST["monthly_rent"];
  $street = $_POST["street"];
  $description = $_POST["description"]; 
  $phone = $_POST["phone"]; 

 
  if($_FILES["image"]["error"] == 4){
    echo
    "<script> alert('Image Does Not Exist'); </script>"
    ;
  }
  else{
    $imageName = $_FILES["image"]["name"];
    $fileSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = explode('.', $imageName);
    $imageExtension = strtolower(end($imageExtension));
    if ( !in_array($imageExtension, $validImageExtension) ){
      echo
      "
      <script>
        alert('Invalid Image Extension');
      </script>
      ";
    }
    else if($fileSize > 100000000){
      echo
      "
      <script>
        alert('Image Size Is Too Large');
      </script>
      ";
    }
    else{
      $newImageName = uniqid();
      $newImageName .= '.' . $imageExtension;

      move_uploaded_file($tmpName, 'img/' . $newImageName);
      $query = $db->prepare("INSERT INTO house (house_name, landlord_email, city, street, monthly_rent, description, phone, image, date)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$query->bind_param("ssssdsss", $house_name, $landlord_email, $city, $street, $monthly_rent, $description, $phone, $newImageName);

      
      echo
      "
      <script>
        alert('Successfully Added');
        document.location.href = 'data.php';
      </script>
      ";
      return $query->execute();
      
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload Image File</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  </head>
  <body>
  <?php  if (isset($_SESSION['username'])) : ?>
    	<div  class = "welcome">
    <p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong></p>
    </div>
      <?php endif ?>

    <h1 class = "text-center my-3">Choose a file to Upload</h1>
    <div class = "Container" >
    <form class="" action="index.php" method="post" autocomplete="off" enctype="multipart/form-data">
      <div class = "mb-3">
      <label for="house_name" class = "form-label">Name : </label>
      <input type="text"  class = "form-control" name="house_name" id = "name" placeholder = "Name of the Rental" required value=""> 
      </div>
      <div class = "mb-3">
      <label for="image">Image : </label>
      <input type="file" name="image" id = "image" accept=".jpg, .jpeg, .png" required value="">
      </div>
      <div class = "mb-3">
      <label for="landlord_email" class="form-label">Email: </label>
      <input type="email" class="form-control" name="landlord_email" id="landlord_email" aria-describedby="emailHelp" required value="">
      <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      
      <div class = "mb-3">
      <label for="monthly_rent" class="form-label">Monthly Rent : </label>
      <input type="number" class="form-control" name="monthly_rent" id="monthly_rent"  required value="">
      </div>

      <div class = "mb-3">
      <label for="city" class="form-label">City : </label>
      <input type="text" class="form-control" name="city" id="city"  required value="">
      </div>

      <div class = "mb-3">
      <label for="street" class="form-label">Street : </label>
      <input type="text" class="form-control" name="street" id="street"  required value="">
      </div>
      
      <div class = "mb-3">
      <label for="description" class ="form-label">Description : </label>
      <textarea name="description" class = "form-control" id="description" cols="60" rows="8"  required value></textarea><br>
      </div>
      <div class = "mb-3">
      <label for="phone" class="form-label">Phone : </label>
      <input type="number" class="form-control" name="phone" id="phone"  required value="">
      </div>
      

      <button type = "submit" class="btn btn-primary" name = "submit">Submit</button>
    </form>
    </div>
    
    <br>
    <a href="data.php">Data</a>

    <div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
   
    	<p> <a href="index.php?logout='1'" style="color: green;">Logout</a> </p>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
  </body>
</html>
