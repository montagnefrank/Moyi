<?php
session_start();
$id = $_GET['id'];
if($_SERVER["REQUEST_METHOD"] == "POST") {  
    if(isset($_POST['cajas'])){
	     $_SESSION['cajas'] = $_POST["cajas"];
	 	 if($id == 1){
	 	 	header('Location: crearorden.php?id=1');
	     }else{
		    //header('Location: asig_guia.php?id=2'); 
		 }
	}
	else{
		echo "<script> alert('Debe seleccionar al menos un Item'); 
			  location.href='crearorden.php';  </script>";

	}
}
?>