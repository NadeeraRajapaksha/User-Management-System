<?php session_start(); ?>
<?php require_once('inc/connection.php');?>
<?php require_once('inc/functions.php'); ?>
<?php 
    // checking if the uder logging
    if(!isset($_SESSION['user_id'])){
         header('Location: index.php');
    }

    $user_list = '';
    //getting the list of users

    $query = "SELECT * FROM user WHERE is_deleted=0 ORDER BY first_name";
    $users = mysqli_query($connection, $query);

     verify_query($users);
     while($user = mysqli_fetch_assoc($users)){
        $user_list .= "<tr>";
        $user_list .= "<td>{$user['first_name']}</td>";
        $user_list .= "<td>{$user['last_name']}</td>";
        $user_list .= "<td>{$user['email']}</td>";
        //$user_list .= "<td><a href=\"modify-user.php?user_id={$user['id']}\" >Edit </a></td>";
        //$user_list .= "<td><a href=\"delete-user.php?user_id={$user['id']}\" onclick=\"return confirm('Are you sure delete?');\">Delete </a></td>";
 
        //$user_list .= "<td><button class=\"edit-btn\"  onclick=\"document.location='modify-user.php?user_id={$user['id']}'\">Edit</button></td>";
        //$user_list .= "<td><button class=\"delete-btn\" onclick=\"return confirm('Are you sure delete?');\"><a href=\"delete-user.php?user_id={$user['id']}\" >Delete</a></button></td>";
		  $user_list .= "<td class=\"text-center\"><a class='btn btn-info btn-xs' onclick=\"return confirm('Are you want Update?');\" href=\"modify-user.php?user_id={$user['id']}\"><span class=\"glyphicon glyphicon-edit\"></span> Edit</a> 
		                                           <a href=\"delete-user.php?user_id={$user['id']}\" class=\"btn btn-danger btn-xs\" onclick=\"return confirm('Are you sure delete?');\"><span class=\"glyphicon glyphicon-remove\"></span> Delete</a></td>";
        $user_list .= "</tr>";
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
<Title>Uesrs</Title>
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

    <div class="row col-md-6 col-md-offset-2 custyle">
    <table class="masterlist">
    <thead>
    <a href="add-user.php" class="btn btn-primary btn-xs pull-right"><b>+</b> Add new user</a>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
            
<?php echo $user_list; ?>
    </table>
    </div>
</div>
</body>

</html>
