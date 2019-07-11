<?php 
$page_title = 'Browse Database';
include ('includes/header.html');
require ('includes/connect.php');
?>

<?php 
if(!isset($_SESSION['admin'])){
	header ('Location: index.php');
}
?>

<h2>Browse WSH Database</h2><br/>
<form name="rform" id="rform" action="showtable.php" method="post">
	<fieldset>
		<legend>Database Selection</legend><br/>
		<?php 
			$query = "SHOW TABLES";
			$result = mysqli_query($dbc, $query)
						or die ('Problem with query' . mysqli_error($dbc));
						
			echo '<label for="table">Select Table:*</label><br/><br/>';		
			while($row = mysqli_fetch_array($result, MYSQL_NUM)){
			echo '<input type="radio" name="table" value="' . $row[0] . '">' . $row[0] . '<br/>';
			}
			echo '<br/>';
		?>
	</fieldset>
	<br/><br/>
	<input type="submit" name="submit"/>
	<br/><br/>
</form>


<?php 
include ('includes/footer.html');
mysqli_close($dbc);
?>