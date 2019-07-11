<?php 
$page_title = 'Show Rooms';
include ('includes/header.html');
require ('includes/connect.php');
?>

<?php 
if(!isset($_SESSION['admin'])){
	header ('Location: index.php');
}
?>

<?php 

require ('includes/testinput.inc.php');

if(!isset($_POST['table'])){
	echo '<h2>Error</h2>';
	echo '<p>No table was specified.</p><br/>';
}else{
	$table = mysqli_real_escape_string($dbc, test_input($_POST['table']));


	echo "<h2>$table table:</h2>";
	$query = "SHOW COLUMNS FROM $table";
	
	$result = mysqli_query($dbc, $query)
		or die ('Problem with query' . mysqli_error($dbc));

	$i = 0;
	echo '<table>';
	echo '<tr>';
	while($row = mysqli_fetch_array($result)){
		echo '<th>';
		echo $row[0];
		echo '</th>';
		$i++;
	}	
	echo '</tr>';
	
	$query = "SELECT * FROM $table";
	
	$result = mysqli_query($dbc, $query)
		or die ('Problem with query' . mysqli_error($dbc));

	
	while($row = mysqli_fetch_array($result)){
	echo '<tr>';
		for($j = 0; $j < $i; $j++){
		  	echo '<td>';
			echo $row[$j];
			echo '</td>';	
		}
	echo '<tr>';
	}
	echo '</table>';
	echo '<br/><br/>';

}
?>


<?php 
include ('includes/footer.html');
mysqli_close($dbc);
?>