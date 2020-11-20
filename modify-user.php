<?php session_start(); ?>
<?php require_once('inc/connection.php');?>
<?php require_once('inc/functions.php'); ?>


<?php

// checking if the uder logging
if(!isset($_SESSION['user_id'])){
    header('Location: index.php');
}
     
     $errors = array();
     $user_id = '';
     $first_name = '';
     $last_name = '';
     $email = '';
     $password = '';

     if(isset($_GET['user_id'])){
         //getting the user information
         $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
         $query = "SELECT * FROM user WHERE id = {$user_id} LIMIT 1";

         $result_set = mysqli_query($connection, $query);

         if($result_set){
             if(mysqli_num_rows($result_set) == 1){
                 // user found
                 $result = mysqli_fetch_assoc($result_set);
                 $first_name = $result['first_name'];
                 $last_name = $result['last_name'];
                 $email = $result['email'];

             }else{
                 // user not found
                 header('Location: users.php?err=query_not_found');
             }
         }else{
             // query unsuccessfull
             header('Location: users.php?err=query_failed');
         }
     }

     if(isset($_POST['submit'])){
        $user_id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
         

        $req_fields = array('user_id','first_name','last_name','email');
        $errors = array_merge($errors, check_req_fields($req_fields)) ;
       
    // checking max length
        $max_len_fields = array('first_name' => 50,'last_name' => 100 ,'email' => 100);

        foreach($max_len_fields as $field => $max_len){
            if(strlen(trim($_POST[$field])) > $max_len){
                $errors[] = $field .' must be less than '.$max_len.'characters';
            
               }
            }

            // checking email address
            
            if(!is_email($_POST['email'])){
                $errors[] = 'Email address is Invalid!';
            }

            // checking if email address already exists
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $query = "SELECT * FROM user WHERE email = '{$email}' AND id !={$user_id} LIMIT 1";

            $result_set = mysqli_query($connection, $query);
            
            if($result_set){
                if(mysqli_num_rows($result_set)== 1){
                    $errors[] = 'Email address already exists.';
                }
            }

            // Insert new user data To database
            if(empty($errors)){
                //no errors found.... adding new records
                $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
                $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);

               $query = "UPDATE user SET ";
               $query .= "first_name = '{$first_name}',";
               $query .= "last_name = '{$last_name}',";
               $query .= "email = '{$email}'";
               $query .= "WHERE id = {$user_id} LIMIT 1";
               
                $result = mysqli_query($connection, $query);

                if($result){
                    //query successfull.... redirecting to users page
                    echo "<script type='text/javascript'>alert('Update User Details Successfully!')</script>";
                }else{
                    $errors[] = 'Failed to modify the new records.';
                }

                
     }


    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
<Title>Modify User</Title>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="css/main.css">

<!------ Include the above in your HEAD tag ---------->

</head>

<body>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <p><b>User Management System<b></p>
    </div>
    <div class="loggedin">Welcome <b><?php echo $_SESSION['first_name']; ?></b> <a href="logout.php" class="btn btn-info btn-lg">
          <span class="glyphicon glyphicon-log-out"></span> Log out
        </a></div>
  </div>
</nav>



<div class="container">
<div class="row justify-content-center">
                    <div class="col-md-8">
                        
                            <div class="card-header" id="navbarname">Modify User<span class="back"><a href= "users.php"> < Back to User List</a></span></div>
                            
<?php

    if(!empty($errors)){
        echo '<div class="errmsg">';
        echo '<b>There were errors on your form.</b><br>';
        foreach($errors as $error){
            $error = ucfirst(str_replace("_"," ",$error));
            echo ' -'.$error.'<br>';
        }
        echo '</div>';
    }

?>



                       <form class="form-horizontal" method="post" action="modify-user.php">
					   <input type="hidden" name='user_id' value="<?php echo $user_id; ?>">

                                    <div class="form-group">
                                        <label for="name" class="cols-sm-2 control-label">Your First Name</label>
                                        <div class="cols-sm-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-user fa" ></i></span>
                                                <input type="text" class="form-control" name="first_name" id="name" <?php echo 'value="'.$first_name.'"';  ?> placeholder="Enter your Name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="cols-sm-2 control-label">Your Last Name</label>
                                        <div class="cols-sm-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                                <input type="text" class="form-control" name="last_name" id="last_name" <?php echo 'value="'.$last_name.'"';  ?> placeholder="Enter your Last Name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="cols-sm-2 control-label">Your Email</label>
                                        <div class="cols-sm-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                                                <input type="text" class="form-control" name="email" id="email" <?php echo 'value="'.$email.'"';  ?> placeholder="Enter your Email" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="cols-sm-2 control-label">Password</label>
                                        <div class="cols-sm-10">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                                <span class="changepassword">*************** | &nbsp;</span>  <a style="color:red" href="change-password.php?user_id=<?php echo $user_id; ?>"> <p class="changepasswordlink">Change Password</p></a>
                                            </div>
                                        </div>
                                    </div>
                                   
                                        <div class="form-group">
						<input type="submit" value="Save" name="submit" class="btn btn-warning btn-lg btn-block login-button" id="addbtn">
					</div>


                                </form>
                            

                      
                    </div>
                </div>
</div>
   
</body>
</html>

<?php mysqli_close($connection)?>
