<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/functions.php'); ?>

<?php
	if(isset($_POST['submit'])){

		$errors = array();

		if(!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1){
			$errors[] = 'Username is Missing Or Invalid';
		}

		if(!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1){
			$errors[] = 'Password is Missing Or Invalid';
		}

		if(empty($errors)){

			$email = mysqli_real_escape_string($connection, $_POST['email']);
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

			$query = "SELECT * FROM user WHERE email = '{$email}' AND password = '{$password}' LIMIT 1";

			$result_set = mysqli_query($connection,$query);

			if($result_set){
				if(mysqli_num_rows($result_set) == 1){

					$user = mysqli_fetch_assoc($result_set);
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['first_name'] = $user['first_name'];


					//updating last logging
					$query ="UPDATE user SET last_login = NOW()";
					$query .= "WHERE id = {$_SESSION['user_id']} LIMIT 1";

					$result_set = mysqli_query($connection, $query);

					if(!$result_set){
						die("Database query failed.");
					}


					header('Location: users.php');

				}else{
					$errors[] = 'Invalid Username / Password';
				}
			}else{
				$errors[] = 'Database quert failed';
			}
		}
	}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Log In | User Management System</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header"><br>
				<h3>Sign In</h3>

			<?php 
				if(isset($errors) && !empty($errors)){
					echo '<p class="error">Inavlid Username / Password</p>';
				}

				?>

           <?php
				
				if(isset($_GET['logout'])){
					echo '<p class="info">You have Successfully logged out from the System</p>'; 
				}

				?>
				
			</div>
			<div class="card-body">
				<form action="index.php" method="post">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" class="form-control" name="email" placeholder="username">
						
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" class="form-control" name="password" placeholder="password">
					</div>
					


					<div class="form-group">
						<input type="submit" value="Login"name="submit" class="btn btn-warning btn-lg btn-block login-button">
					</div>
				</form>
			</div>
			
		</div>
	</div>
</div>
</body>
</html>