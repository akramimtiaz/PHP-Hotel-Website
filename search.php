<?php 
$page_title = 'Search Rooms';
include ('includes/header.html');
require ('includes/connect.php');
?>

<?php 
if(!isset($_SESSION['username']) || isset($_SESSION['admin'])){
	header ('Location: index.php');
}
?>

<?php 

$bedroomErr = $dateErr = "";
$errors = $valid = false;

require ('includes/testinput.inc.php');
require ('includes/testdate.inc.php');


if($_SERVER['REQUEST_METHOD'] == 'POST'){

	

	if(isset($_POST['bedroom'])){
		$bedroom = mysqli_real_escape_string($dbc, test_input($_POST['bedroom']));
	}else{
		$errors = true;
		$bedroomErr = ' Please select the number of bedrooms.';
	}

	if(isset($_POST['orientation'])){
		$orientation = mysqli_real_escape_string($dbc, test_input($_POST['orientation']));
	}else{
		$orientation = 'ANY';
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
		$valid = true;
	}
	
}

?>
<h2>Search Room</h2>
<br/>
<form name="rform" id="rform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
	<fieldset>
		<legend>Search Details</legend>

		<label for="bedroom">Number of Bedrooms:*</label><span><?php echo $bedroomErr; ?></span><br/>
		<input type="radio" name="bedroom" value="1" <?php if(isset($_POST['bedroom']) && $_POST['bedroom'] == '1'){ echo 'checked';} ?>/>1<br/>
		<input type="radio" name="bedroom" value="2" <?php if(isset($_POST['bedroom']) && $_POST['bedroom'] == '2'){ echo 'checked';} ?>/>2<br/>
		<input type="radio" name="bedroom" value="3" <?php if(isset($_POST['bedroom']) && $_POST['bedroom'] == '3'){ echo 'checked';} ?>/>3<br/>

		<label for="orientation">Orientation of Bedroom:</label><br/>
		<input type="radio" name="orientation" value="N" <?php if(isset($_POST['orientation']) && $_POST['orientation'] == 'N'){ echo 'checked';} ?>/>N<br/>
		<input type="radio" name="orientation" value="S" <?php if(isset($_POST['orientation']) && $_POST['orientation'] == 'S'){ echo 'checked';} ?>/>S<br/>
		<input type="radio" name="orientation" value="W" <?php if(isset($_POST['orientation']) && $_POST['orientation'] == 'W'){ echo 'checked';} ?>/>W<br/>
		<input type="radio" name="orientation" value="E" <?php if(isset($_POST['orientation']) && $_POST['orientation'] == 'E'){ echo 'checked';} ?>/>E<br/>

		<p><label for="checkin">Date of Checkin:*</label><br/>
		<input type="text" name="checkin" size="40" value="<?php if(isset($_POST['checkin'])){ echo $_POST['checkin']; } ?>" placeholder="YYYY-MM-DD"/><span><?php echo $dateErr; ?></span></p>

		<p><label for="checkout">Date of Checkout:*</label><br/>
		<input type="text" name="checkout" size="40" value="<?php if(isset($_POST['checkout'])){ echo $_POST['checkout']; }?>" placeholder="YYYY-MM-DD"/></p>
	</fieldset>
	 <br/>
	<input type="submit" name="submit"/>
	<br/><br/>
	<?php if($errors) {echo '<p><span>Please correct any invalid input.</span></p>';}?>
	
</form>

<?php 

		if($valid){
			$cin_year  = substr($_POST['checkin'], 2, 2);
			$cout_year = substr($_POST['checkin'], 2, 2);
		
			$checkin = $cin_year . ':' . $cin_month . ':' . $cin_day;
			$checkout = $cout_year . ':' . $cout_month . ':' . $cout_day;
		
			$subquery = "SELECT r.rid FROM  rooms AS r INNER JOIN bookings AS b USING (rid) WHERE ( '$checkin' < b.checkout ) AND ( '$checkout' > b.checkin )";
		
			if($orientation == 'ANY'){	
				$query = "SELECT r.rid, r.beds, r.orientation, r.price FROM rooms AS r WHERE r.rid NOT IN ($subquery) AND r.beds = '$bedroom'";
			}else{
				$query = "SELECT r.rid, r.beds, r.orientation, r.price FROM rooms AS r WHERE r.rid NOT IN ($subquery) AND r.beds = '$bedroom' AND r.orientation = '$orientation'";
			}
		
			$result = mysqli_query($dbc, $query)
						or die('Problem with query ' . mysqli_error($dbc));
		
			$num = mysqli_num_rows($result);
		
			if($num>0){
				echo '<h3>Search Result:</h3>';
				echo '<table>';
				echo '<tr>';
				echo '<th>Room ID</th>';
		 		echo '<th>Bedrooms</th>';
		 		echo '<th>Orientation</th>';
		 		echo '<th>Price</th>';
				echo '</tr>';
		
				while($row = mysqli_fetch_array($result)){
						echo "<tr>";
		 				echo "<td>{$row['rid']}</td>";
		 				echo "<td>{$row['beds']}</td>";
						echo "<td>{$row['orientation']}</td>";
		 				echo "<td>{$row['price']}</td>";
		 				echo "</tr>";	
				}
		
				echo '</table><br/><br/>';
		
				}else{
					echo '<p><span>No rooms available in the period specified with the specified bedrooms and orientation.</span></p>';
				}
		}
?>

<?php 
include ('includes/footer.html');
mysqli_close($dbc);
?>