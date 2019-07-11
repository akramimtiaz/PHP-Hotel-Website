<?php 
$page_title = 'Customer Registration';
include ('includes/header.html');
?>

<?php 
if(isset($_SESSION['username'])){
	header ('Location: index.php');
}
?>

<?php 

$errors = $registered = false;
$unameErr = $gnameErr= $snameErr = $pwErr = $addressErr = $stateErr = $pcodeErr =  $mobileErr = $emailErr = $serverErr = "";

require('includes/testinput.inc.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	require ('includes/connect.php');

	#validate USER NAME

	$pattern = '/^[A-Za-z\d]{1}[A-Za-z\d\-\_\']{1,19}$/';

	if(preg_match($pattern, $_POST['uname'])){
		$query  = "SELECT * FROM customers ";
		$query .= "WHERE username = '{$_POST['uname']}'";
		$result = mysqli_query($dbc, $query)
			or die ('Problem with query' . mysqli_error($dbc));

		$num = mysqli_num_rows($result); 

		if($num > 0){
			$unameErr = ' The username specified already exists.';
			$errors = true;
		}else{
			$uname = mysqli_real_escape_string($dbc, test_input($_POST['uname']));
			$unameErr = '<span id="available"> Username available.</span>';
		}
	}else{
		$unameErr = ' Please enter a valid username.';
		$errors = true;
	}


	#validate GIVEN NAME

	$pattern = '/^[A-Za-z]{1}[A-Za-z\'\-]{0,}[\s]{0,1}[A-za-z\'\-]{1,}$/';

	if((preg_match($pattern, $_POST['gname'])) && (strlen($_POST['gname']) <= 20)){
		$gname = mysqli_real_escape_string($dbc, test_input($_POST['gname']));
	}else{
		$gnameErr = ' Please enter a valid given name.';
		$errors = true;
	}

	#validate FAMILY NAME

	if((preg_match($pattern, $_POST['sname'])) && (strlen($_POST['sname']) <= 20)){
		$sname = mysqli_real_escape_string($dbc, test_input($_POST['sname']));
	}else{
		$snameErr = ' Please enter a valid family name.';
		$errors = true;
	}

	#validate PASSWORD

	$pattern = '/^.\S{5,20}$/'; #index starts at 0 therefore 5 is equivalent to 6.

	if(preg_match($pattern, $_POST['pw1'])){
		if($_POST['pw1'] == $_POST['pw2']){
			$pw = mysqli_real_escape_string($dbc, test_input($_POST['pw1']));
		}else{
			$pwErr = ' Your passwords did not match.';
			$errors = true;
		}
	}else{
		$pwErr = ' Please enter a valid password.';
		$errors = true;
	}

	#validate ADDRESS

	$pattern = '/^[A-Za-z\d\']{1}[A-Za-z\d\s\'\/\,\.\-]{1,39}$/';

	if(preg_match($pattern, $_POST['address'])){
		$address = mysqli_real_escape_string($dbc, test_input($_POST['address']));
	}else{
		$addressErr = ' Please enter a valid address.';
		$errors = true;

	}

	#validate STATE

	if(!empty($_POST['state'])){
		$state = mysqli_real_escape_string($dbc, test_input($_POST['state']));
	}else{
		$stateErr = ' Please select a state.';
		$errors = true;
	}

	#validate POSTCODE

	$pattern = '/^[\d]{4}$/';

	if(preg_match($pattern, $_POST['postcode'])){
		$postcode = mysqli_real_escape_string($dbc, test_input($_POST['postcode']));
	}else{
		$pcodeErr = ' Please enter a valid postcode.';
		$errors = true;
	}

	#validate MOBILE

	$pattern = '/^04[\d]{8}$/';

	if(preg_match($pattern, $_POST['mobile'])){
		$mobile = mysqli_real_escape_string($dbc, test_input($_POST['mobile']));
	}else{
		$mobileErr = ' Please enter a valid mobile number.';
		$errors = true;
	}

	#validate EMAIL

	$pattern = '/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/';
	if((preg_match($pattern, $_POST['email'])) && (strlen($_POST['email']) <= 40)){
		$email = mysqli_real_escape_string($dbc, test_input($_POST['email']));
	}else{
		$emailErr = ' Please enter a valid email.';
		$errors = true;
	}

	if(!$errors){

		$query  = "INSERT INTO customers (username, password, gname, sname, address, state, postcode, mobile, email) ";
		$query .= "VALUES ('$uname', '$pw', '$gname', '$sname', '$address', '$state', $postcode, $mobile, '$email')";

		$result = mysqli_query($dbc, $query)
			or die('Problem with query' . mysqli_error($dbc));

		if(mysqli_affected_rows($dbc) == 1){
			$registered = true;
			$unameErr = '';
			$_POST = array();
			header("Location: customerlogin.php");
		}else{
			$serverErr = '<h3>Error</h3><p>Unfortunately an unexpected error occured, sorry for the inconvenience.</p>';
		}
	}
	mysqli_close($dbc);
}

?>

<h2>Customer Registration</h2>
<br/>
<form name="rform" id="rform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<fieldset>
		<legend>Personal Information</legend>
			<p><label for="uname">User Name:*</label><br/><input type="text" id="uname" name="uname" size="40" onblur="valUsername(this);" value="<?php if(isset($_POST['uname'])){ echo $_POST['uname']; } ?>"/>
			<span><?php echo $unameErr ?></span></p> 
			<p><label for="gname">Given Name:*</label><br/><input type="text" id="gname" name="gname" size="40" onblur="valName(this);" value="<?php if(isset($_POST['gname'])){ echo $_POST['gname']; } ?>"/>
			<span><?php echo $gnameErr ?></span></p> 
			<p><label for="sname">Family Name:*</label><br/><input type="text" id="sname" name="sname" size="40" onblur="valName(this);" value="<?php if(isset($_POST['sname'])){ echo $_POST['sname']; } ?>"/>
			<span><?php echo $snameErr ?></span></p>  
			<p><label for="pw1">Password:*</label><br/><input type="password" id="pw1" name="pw1" size="40" onblur="valPassword(this);"/>
			<span><?php echo $pwErr ?></span></p> 
			<p><label for="pw2">Re-enter Password:*</label><br/><input type="password" id="pw2" name="pw2" size="40" onblur="valMatching(this);"/></p>  
	</fieldset>
	<br/>
	<fieldset>
		<legend>Contact Information</legend>
			<p><label for="address">Address:</label><br/><input type="text" id="address" name="address" size="40" onblur="valAddress(this);" value="<?php if(isset($_POST['address'])){ echo $_POST['address']; } ?>"/>
			<span><?php echo $addressErr ?></span></p> 
			<p><label for="state">State:</label><br/>
			<select id="state" name="state">
				<option value=""></option>
       		    <option value="ACT" <?php if(isset($_POST['state']) && $_POST['state'] == "ACT"){ echo ' selected'; } ?>>ACT</option>
        		<option value="NSW" <?php if(isset($_POST['state']) && $_POST['state'] == "NSW"){ echo ' selected'; } ?>>NSW</option>
        		<option value="NT"  <?php if(isset($_POST['state']) && $_POST['state'] == "NT"){ echo ' selected'; } ?>>NT</option>
        		<option value="QLD" <?php if(isset($_POST['state']) && $_POST['state'] == "QLD"){ echo ' selected'; } ?>>QLD</option>
        		<option value="SA"  <?php if(isset($_POST['state']) && $_POST['state'] == "SA"){ echo ' selected'; } ?>>SA</option>
        		<option value="TAS" <?php if(isset($_POST['state']) && $_POST['state'] == "TAS"){ echo ' selected'; } ?>>TAS</option>
        		<option value="VIC" <?php if(isset($_POST['state']) && $_POST['state'] == "VIC"){ echo ' selected'; } ?>>VIC</option>
         		<option value="WA" <?php if(isset($_POST['state']) && $_POST['state'] == "WA"){ echo ' selected'; } ?>>WA</option>
			</select><span><?php echo $stateErr ?></span></p>
			<p><label for="postcode">Postcode:</label><br/><input type="text" id="postcode" name="postcode" size="40" onblur="valPostcode(this);" value="<?php if(isset($_POST['postcode'])){ echo $_POST['postcode']; } ?>"/>
			<span><?php echo $pcodeErr ?></span></p> 
			<p><label for="mobile">Mobile Number:</label><br/><input type="text" id="mobile" name="mobile" size="40" onblur="valNumber(this);" value="<?php if(isset($_POST['mobile'])){ echo $_POST['mobile']; } ?>"/>
			<span><?php echo $mobileErr ?></span></p> 
			<p><label for="email">Email Address:</label><br/><input type="text" id="email" name="email" size="40" onblur="valEmail(this);" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>"/>
			<span><?php echo $emailErr ?></span></p> 
	</fieldset>
	<br/><br/>
	<input type="submit" name="submit"/>
	<input type="button" name="reset" value="Reset" onclick="resetForm(document.getElementById('rform'));"/>
	<?php 
	if($errors){
	echo '<p><span>Please correct any invalid input.</span></p>';
	}

	echo $serverErr;

	if($registered){
		echo  '<h3>Registration Complete</h3><p>You have been successfully registered!</p>';
	}
	?>
</form>
<br/>
<?php 
include ('includes/footer.html');
?>




