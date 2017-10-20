<?php
			session_start();
			include ("conectarSQL.php");
			include ("seguridad.php"); 
			$user = trim($_POST['usuario']);
			//echo "Login".$user;
			$passwd = $_POST['contrasenna'];
			//echo "Paswd".$passwd ;	
			$bd = "dbasebloms";				
			
			$_SESSION["login"] = $user;
			$_SESSION["passwd"] = $passwd;
			$_SESSION["bd"] = $bd;
			
			/*conexion con la BD*/
			// chequeas si el usuario esta en la bd
			$conexion = conectar("localhost",'root','W1nnts3rv3r','dbasebloms')or die('No pudo conectarse : '. mysql_error());
			$sql_chk = mysql_query("SELECT idrol_user FROM tblusuario WHERE cpuser='$user' AND cppassword = '$passwd'",$conexion) or die(mysql_error());
			// entonces dices
			if(mysql_num_rows($sql_chk)==0){ // no esta disponible
			// aqui el codigo de ingreso del usuario y datos
			   echo "Could not connect, user or paswod incorrect <br>";
			   echo '<a href="../index.html">Back</a>';
			} else {
			$row = mysql_fetch_row($sql_chk);
			$_SESSION["rol"] = $row[0];	
			header("Location: ../main.php?panel=mainpanel.php");
			}
			
			
?>

