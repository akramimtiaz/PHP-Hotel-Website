<?php 
$page_title = 'Admin Login';
include ('includes/header.html');
?>

<?php 
if(isset($_SESSION['username'])){
	header ('Location: index.php');
}
?>

<?php

require ('includes/testinput.inc.php');

$unameErr = $pwErr = "";
$errors = false;
$loginFail = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	require ('includes/connect.php');

	$pattern = '/^[A-Za-z\d]{1}[A-Za-z\d\-\_\']{1,19}$/';

	if(preg_match($pattern, $_POST['username']) && !empty($_POST['username'])){
		$username = mysqli_real_escape_string($dbc, test_input($_POST['username']));
	}else{
		$errors = true;
		$unameErr = ' Please input a valid username.';
	}

	$pattern = '/^.\S{5,20}$/';

	if(preg_match($pattern, $_POST['password']) && !empty($_POST['password'])){
		$password = mysqli_real_escape_string($dbc, test_input($_POST['password']));
	}else{
		$errors = true;
		$pwErr = ' Please input a valid password.';
	}

	if(!$errors){

		$query  = "SELECT username FROM administrators ";
		$query .= "WHERE (username = '$username') AND (password = '$password')";

		$result = mysqli_query($dbc, $query)
				  	or die ('Problem with query' . mysqli_error($dbc));
		$num = mysqli_num_rows($result);

		if($num == 1){
			$row = mysqli_fetch_array($result);
			$_SESSION['username'] = $row['username'];
			$_SESSION['admin'] = true;
			header("Location: browse.php"); //has to be changed to browse tables page
		}else{
			$loginFail = true;
		}	
	}

	mysqli_close($dbc);
}


?>

<h2>Administrator Login</h2>
<br/>
<form name="rform" id="rform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<fieldset>
		<legend>Account Information</legend>
		<p><label for="username">Username:</label><br/><input type="text" id="username" name="username" size="30" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>"/>
		<span><?php echo $unameErr; ?></span></p>
		<p><label for="password">Password:</label><br/><input type="password" id="password" name="password" size="30" />
		<span><?php echo $pwErr; ?></span></p>
	</fieldset>
	<br/><br/>
	<input type="submit" name="submit"/>
	<input type="reset" name="reset"/>
</form>
<?php 
if($errors){
	echo '<p><span>Please correct any invalid input.</span></p>';
}

if($loginFail){
	echo '<p><span>The username and password provided did not match that in the database.</span></p>';
}

?>



<?php 
include ('includes/footer.html');
?>