<?php 

DEFINE('DB_HOST', 'localhost');
DEFINE('DB_USER', 'twa106');
DEFINE('DB_PW', 'twa106p8');
DEFINE('DB_NAME', 'westernhotel106');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PW, DB_NAME);

if(!$dbc){
	die('Could not connect: ' . mysqli_connect_error());
}else{
	mysqli_set_charset($dbc, 'utf8');
}

/*<!--End of Connect-->*/