<?php 
$page_title = 'Change Pricing';
include('includes/header.html');
require('includes/connect.php');
?>

<?php 
if(!isset($_SESSION['admin'])){
	header ('Location: index.php');
}
?>

<?php 

$errors = $updated = false;
$ridErr = $priceErr = $serverErr = "";

require ('includes/testinput.inc.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){


	if(!empty($_POST['rid'])){
		$rid = mysqli_real_escape_string($dbc, test_input($_POST['rid']));
	}else{
		$errors = true;
		$ridErr = ' Please select a room.';
	}

	$pattern = '/^[0-9]{2,3}\.[0-9]{2}$/';

	if(preg_match($pattern, $_POST['price'])){
		if($_POST['price'] >= 10.00 && $_POST['price'] <= 999.99){
			$price = mysqli_real_escape_string($dbc, test_input($_POST['price']));
		}else{
			$errors = true;
			$priceErr = ' Please enter a price in the range of 10.00 to 999.99 inclusive.';
		}
	}else{
		$errors = true;
		$priceErr = ' Please enter a price in the valid format.';
	}


	if(!$errors){
		$query  = "UPDATE rooms SET price=$price ";
		$query .= "WHERE rid='$rid'";

		$result = mysqli_query($dbc, $query)
					or die('Problem with query ' . mysqli_error($dbc));

		if(mysqli_affected_rows($dbc) == 1){
			$updated = true;
		}else{
			$serverErr = '<p><span>Unfortunately an unexpected error has occured, we apologise for the inconvenience.</span></p>';
		}

	}

	
}
?>


<h2>Change Room Price</h2>
<br/>
<form name="rform" id="rform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<fieldset>
	<legend>Room Information</legend>

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

   	<p><label for="price">Enter New Price:*</label><br/>
   	<input type="text" name="price" /><span><?php echo $priceErr; ?></span></p>

</fieldset>
<br/>
<input type="submit" name="submit"/>
<input type="reset" name="reset"/>
<br/><br/>
<?php if($errors) {echo '<p><span>Please correct any invalid input.</span></p>';}?>
<?php if($serverErr) {echo $serverErr; }?>
<?php if($updated) {echo '<p>The price of the room has been successfully updated.</p>';}?>

</form>


<?php 
include('includes/footer.html');
mysqli_close($dbc);
?>