<?php 
$page_title = 'Logout';
include ('includes/header.html');
?>
<?php
	
	if(!isset($_SESSION['username'])){
		ob_end_clean();
		header("Location: index.php");
		exit();
	}else{
		$_SESSION = array();
		session_destroy();
		setcookie(session_name(), '', time()-3600);
	}

	echo '<p>You have been successfully logged out.</p>';
?>
<?php 
include ('includes/footer.html');
?>