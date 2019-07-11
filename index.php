<?php 
include ('includes/header.html');
?>
<h2>Western Sydney Hotel</h2>
<img src="includes/index_image.jpg" alt="an image potraying the standard of accomodation." width="100%" height="auto">
<h3><em>Welcome <?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?></em></h3>
<p>Built upon a history of experience, phenomenal design, ground breaking innovation and through listening to our customers, Western Sydney Hotel provides exceptional service to ensure all our guests have memorable, personalised experience. With exceptional facilities, Western Sydney Hotel is perfectly located right in the heart of the city, with magnificent views and convenient access to Sydney's favourite attractions, including the Sydney Opera House.
At Western Sydney Hotel you can search and book from a range of hotel accommodation rooms.</p>
<h4>Website Features:</h4>
<p>The Western Sydney Hotel website provides the following features:</p>
<ul>
	<li>Register as a customer.</li>
	<li>Login as a customer.</li>
	<li>Login as an adminstrator.</li>
</ul>

<p>As a <strong>registered customer</strong> of this website you will then be able to:</p>
<ul>
	<li>Search for rooms.</li>
	<li>Book rooms.</li>
</ul>
<?php 
if(isset($_SESSION['admin'])){
 	echo '<p>As a <strong>registered adminstator</strong> you will be able to:</p>';
 	echo '<ul>';
		echo '<li>Change the pricing of rooms.</li>';
		echo '<li>Browse the Western Sydney Hotel database tables.</li>';
	echo '</ul>';
}
?>
<br/>
<?php 
include ('includes/footer.html');
?>