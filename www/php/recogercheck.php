<?php
session_start();
$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
     $_SESSION['cajas'] = $_POST["cajas"];
	 if($id == 1){
	 	header('Location: asig_guia.php?id=1');
	 }else{
		header('Location: asig_guia.php?id=2'); 
		 }
}
?>