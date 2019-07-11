<?php 
$page_title = 'Book Rooms';
include ('includes/header.html');
require ('includes/connect.php');
?>

<?php 
if(!isset($_SESSION['username']) || isset($_SESSION['admin'])){
	header ('Location: index.php');
}
?>

<?php 

$dateErr = $serverErr = $ridErr = "";
$errors = $valid = false;

require ('includes/testinput.inc.php');
require ('includes/testdate.inc.php');
require ('includes/calperiod.inc.php');


if($_SERVER['REQUEST_METHOD'] == 'POST'){


	if(!empty($_POST['rid'])){
		$rid = mysqli_real_escape_string($dbc, test_input($_POST['rid']));
	}else{
		$ridErr = ' Please select a room.';
		$errors = true;
	}

	$pattern = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';

	if(preg_match($pattern, $_POST['checkin']) && preg_match($pattern, $_POST['checkout'])){

		$cin_year  = substr($_POST['checkin'], 0, 4);
		$cin_month = substr($_POST['checkin'], 5, 2);
		$cin_day   = substr($_POST['checkin'], 8, 2);

		$cout_year  = substr($_POST['checkout'], 0, 4);
		$cout_month = substr($_POST['checkout'], 5, 2);
		$cout_day   = substr($_POST['checkout'], 8, 2);

		if(checkdate($cin_month, $cin_day, $cin_year) && checkdate($cout_month, $cout_day, $cout_year)){
			
			$curr_year = idate("Y");
			if(idate("m") < 10){$curr_month = '0' . idate("m");}else{$curr_month = idate("m");}	
			if(idate("d") < 10){$curr_day = '0' . idate("d");}else{$curr_day = idate("d");}
			$currDate = $curr_year . '-' . $curr_month . '-' . $curr_day;

			if(test_date($currDate, $_POST['checkin']) &&  test_date($currDate, $_POST['checkout']) &&  test_date($_POST['checkin'], $_POST['checkout'])){
				$checkin = mysqli_real_escape_string($dbc, test_input($_POST['checkin']));
				$checkout = mysqli_real_escape_string($dbc, test_input($_POST['checkout']));
			}else{
				$errors = true;
				$dateErr = ' Please input valid checkin and checkout dates.';
			}
		}else{
			$errors = true;
			$dateErr = ' Please ensure both dates are valid.';
		}
	}else{
		$errors = true;
		$dateErr = ' Please ensure both dates are specified in the required format.';
	}

	if(!$errors){
		$cin_year  = substr($_POST['checkin'], 2, 2);
		$cout_year = substr($_POST['checkin'], 2, 2);

		$checkin = $cin_year . ':' . $cin_month . ':' . $cin_day;
		$checkout = $cout_year . ':' . $cout_month . ':' . $cout_day;

		$query  = "SELECT r.rid, b.checkin, b.checkout ";
		$query .= "FROM rooms AS r INNER JOIN bookings AS b USING (rid) ";
		$query .= "WHERE (r.rid = '$rid') AND ( '$checkin' < b.checkout ) AND ( '$checkout' > b.checkin )";

		$result = mysqli_query($dbc, $query)
					or die('Problem with query ' . mysqli_error($dbc));

		$num = mysqli_num_rows($result);

		if($num == 0){

			$query = "SELECT price FROM rooms WHERE rid = '$rid'";
			$result = mysqli_query($dbc, $query)
						or die('Problem with query ' . mysqli_error($dbc));

			while($row = mysqli_fetch_array($result)){
			$cost = $row['price'];
			}
			$cost *= calculate_period($_POST['checkin'], $_POST['checkout']);

			$query = "INSERT INTO bookings (rid, username, checkin, checkout, cost) VALUES ('$rid', '{$_SESSION['username']}', '$checkin', '$checkout', '$cost')";	
			$result = mysqli_query($dbc, $query)
						or die('Problem with query ' . mysqli_error($dbc));

			$num = mysqli_affected_rows($dbc);

			if($num == 1){
				$valid = true;
			}else{
				$serverErr = '<p>Unfortunately an unexpected server error occured, we apologise for the inconvenience.</p>';
			}
		}else{
			$serverErr = '<p>The specified room for the specified period is already booked, please try again.</p>';
		}
	}
}
?>

<form name="rform" id="rform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
	<fieldset>
		<legend>Search Details</legend>
		<p><label for="rid">Room ID:*</label><br/>
   		   <select name="rid">
   		   	<?php 
   		   	$q = "SELECT rid FROM rooms";
   		   	$r = mysqli_query($dbc, $q)
   		   			or die('Problem with query ' . mysqli_error($dbc));

   		   		echo '<option value=""></option>';
   		   	while($row = mysqli_fetch_array($r)){
   		   		echo '<option value="' . $row['rid'] . '">' . $row['rid'] .  '</option>'; //
   		   	}
   		   	?>
   			</select><span><?php echo $ridErr; ?></span>
   		</p>

		<p><label for="checkin">Date of Checkin:*</label><br/>
		<input type="text" name="checkin" size="40" value="<?php if(isset($_POST['checkin'])){ echo $_POST['checkin']; } ?>" placeholder="YYYY-MM-DD"/><span><?php echo $dateErr; ?></span></p>

		<p><label for="checkout">Date of Checkout:*</label><br/>
		<input type="text" name="checkout" size="40" value="<?php if(isset($_POST['checkout'])){ echo $_POST['checkout']; }?>" placeholder="YYYY-MM-DD"/></p>

	</fieldset>
	 <br/>
	<input type="submit" name="submit"/>
	<br/><br/>
	<?php if($errors) {echo '<p><span>Please correct any invalid input.</span></p>';}?>
	<?php if($serverErr) {echo $serverErr; }?>
	<?php if($valid) {echo '<p>Your room booking has been successfully completed!</p>'; }?>
	
</form>



<?php 
include ('includes/footer.html');
mysqli_close($dbc);
?>