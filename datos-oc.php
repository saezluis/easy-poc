<?php
  session_start();

  if(!isset($_SESSION['username'])){
    header("location:login.php");
  }
  
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title> </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximun-scale=1">
    <link rel="stylesheet" href="tema/css/estilos.css">
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="tema/js/scripts.js"></script>
    <link rel="stylesheet" href="tema/js/source/jquery.fancybox.css?v=2.1.5">
    <script src="tema/js/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	
	<style>
	
	.redText { 
			background-color:red; 
		}
		
	</style>
	
	<script type = "text/javascript">
	
		function validarFechas(){
			if( document.myForm.from.value == "" ){
				alert( "Por favor elija una fecha inicial" );
				return false;
			}
			
			if( document.myForm.to.value == "" ){
				alert( "Por favor elija una fecha final" );
				return false;
			}
			
			var x = new Date(Date.parse(document.myForm.from.value));
			var y = new Date(Date.parse(document.myForm.to.value));
			
			//var timeX = x.getTime();
			//var timeY = y.getTime();
			
			//alert(x);
			//alert(y);
			
			if (x > y) {
				alert( "La fecha inicial no puede ser mayor que la final" );
				return false;
			}
		}
	
	</script>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">	
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>	      
	<script src="js/calendario.js"></script>
	
	<script type = "text/javascript" >
	
		$(document).ready(function() {
			$( "#from" ).datepicker({
				//defaultDate: "+1D",
				changeMonth: true,
				numberOfMonths: 1,
				dateFormat:"yy-mm-dd",
				//minDate: "+1D",
				showOn: "button",
				buttonImage: "http://keith-wood.name/img/calendar.gif",
				buttonImageOnly: true ,
				changeYear: true
				//onSelect: function(dateText, inst){
				//var d = $.datepicker.parseDate(inst.settings.dateFormat, dateText);
				//d.setDate(d.getDate()+1);
				//$("#to").val($.datepicker.formatDate(inst.settings.dateFormat, d));
				
		});
  
		$( "#to" ).datepicker({
					showOn: "button",
					dateFormat:"yy-mm-dd",
					buttonImage: "http://keith-wood.name/img/calendar.gif",
					buttonImageOnly: true,
					changeMonth: true,
					changeYear: true
				});
		  
		  $('#from').attr('readonly', true);
		  $('#to').attr('readonly', true);
		});
	
	</script>
	
	
	
  </head>
  <body>
  	
	
	<?php
		
		include "config.php";
	
		$conexion=mysqli_connect($host,$username,$password,$db_name) or die("Problemas con la conexión");
		$acentos = $conexion->query("SET NAMES 'utf8'");
		
		$registros=mysqli_query($conexion,"select * from ordenes where visto_bueno = \"no\"") or
		die("Problemas en el select:".mysqli_error($conexion));
						

		//-------------- INICIO Paginador ------------------
		
		//Limito la busqueda a 10 registros por pagina
		$TAMANO_PAGINA = 10; 
		
		//examino la página a mostrar y el inicio del registro a mostrar 
		@$pagina = $_GET["pagina"]; 
		if (!$pagina) { 
			$inicio = 0; 
			$pagina=1; 
		} 
		else { 
			$inicio = ($pagina - 1) * $TAMANO_PAGINA; 
		}
		
		$num_total_registros = mysqli_num_rows($registros); 
		//calculo el total de páginas 
		$total_paginas = ceil($num_total_registros / $TAMANO_PAGINA); 
		
		$ssql = "select * from ordenes where visto_bueno = \"no\" limit " . $inicio . "," . $TAMANO_PAGINA; 
		$rs = mysqli_query($conexion,$ssql); 
		
		//-------------- FIN Paginador ------------------

		
	?>
	
    <header class="grupo">
      <div class="caja base-50 no-padding">
        <h1> <a href="#" class="logo"> <img src="tema/img/logo.jpg" alt="POC"></a></h1>
      </div>
      <div class="caja base-50 no-padding">
		<!--
      	<a class="logout" href="logout.php" >Logout</a>
        -->
		<nav>
          <ul>
			<!--
            <li> <a href="perfil-boss-vb-si.php">Historial de órdenes de compra con VºBº</a></li>
             	<li> <a href="#" class="active">Perfil</a></li>  -->
          </ul>
        </nav>
		<!--	<div class="counter">15</div>	-->
      </div>
      <div class="caja base-100 no-padding">
        <h2>Bienvenido al nuevo sistema de emisión de órdenes de compra.</h2>
      </div>
    </header>
    <div id="data--input" class="grupo">
      <h3>Descargar datos de OC</h3>
    </div>
	
	<div id="campana" class="grupo">
		<div class="caja-100">
		<?php
		
		$username = $_SESSION['username'];
		
		$registrosMembers=mysqli_query($conexion,"select * from members WHERE username = '$username' ") or
		die("Problemas en el select:".mysqli_error($conexion));
		
		if($regMem=mysqli_fetch_array($registrosMembers)){
			$username = $regMem['username'];
			$nombre = $regMem['nombre'];
			$apellido =  $regMem['apellido'];			
		}
		
		echo "<form onsubmit=\"return validarFechas();\" name=\"myForm\" method=\"post\" action=\"phpexcel/Examples/download-oc-xls.php\">";
		
		echo "Seleccione rango de fechas: ";
		echo "<br>";
		echo "<br>";
		echo "<label>Desde: </label>";
        echo "<input type=\"text\" name=\"from\" id=\"from\" >";
		echo "<br>";
		echo "<br>";
		echo "<label>Hasta: </label>";
        echo "<input type=\"text\" name=\"to\"  id=\"to\" >";
		echo "<br>";
		echo "<br>";
		/*
		echo "<a href=\"phpexcel/Examples/download-oc-xls.php\">Descargar Excel</a>";
		echo "<br>";
		echo "<br>";
		*/
		/*
		for ($x = 1; $x <= 3; $x++) {
			foreach (range('A', 'O') as $char) {
				echo $char."$x" . "\n";
			}
			echo "<br>";
		} 
		*/
		echo "<input type=\"submit\" value=\"Descargar Excel\">";
		echo "</form>";
	/*
	Datos que necesito sacar
	
	Fecha de OC
	Nº de OC POC
		RUT proveedor
		Razón social
		
	Nº presupuesto
	Nº factura
	
		Campaña
	
		Centro de Costo
	
		Control de Presupuesto
		
		Registro
		
	Monto neto
	Descripción
	
		Usuario responsable
		
	Nº OC SAP
	Nº OC recepción
	*/
	
		?>	
		</div>  
	</div>
	
	<br>
	<br>
	 <a href="seleccion-sap.php"><input type="button" value="Volver"></a>
	
    <div id="footer" class="total">
      <div class="grupo">
        <div id="logo-footer" class="caja-50"><img src="tema/img/logo-footer.png" alt=""></div>
        <div id="copy" class="caja-50">
          <p>© 2016 Easy S.A.</p>
        </div>
      </div>
    </div>
	
	
	
  </body>
</html>