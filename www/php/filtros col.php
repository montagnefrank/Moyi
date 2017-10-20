<?php
session_start();
$user     =  $_SESSION["login"];
include('conexion.php');
include ("conectarSQL.php");
include ("seguridad.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Orders consultations</title>
<script type="text/javascript" src="../js/script.js"></script>
  <script type="text/javascript" src="../js/calendar.js"></script>
  <script type="text/javascript" src="../js/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script language="javascript">
  function Compara(frmFec)
{
    var Anio = (frmFec.txtinicio.value).substr(0,4)
    var Mes = ((frmFec.txtinicio.value).substr(5,2))*1 
    var Dia = (frmFec.txtinicio.value).substr(8,2)
    var Anio1 = (frmFec.txtfin.value).substr(0,4)
    var Mes1 = ((frmFec.txtfin.value).substr(5,2))*1 
    var Dia1 = (frmFec.txtfin.value).substr(8,2)
    var Fecha_Inicio = new Date(Anio,Mes,Dia)
    var Fecha_Fin = new Date(Anio1,Mes1,Dia1)

  if(Anio == '' || Anio1 == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
    {
      alert("Las fechas no deben ser vacias; Ingrese correctamente")
	  return false;
     }

	 
    if(Fecha_Inicio > Fecha_Fin)
    {
      alert("La fecha de inicio es mayor; Ingrese correctamente")
	  return false;
     }
    else
    {
      return true;
     }
}
function Compara1(frmFec)
{
    var Anio = (frmFec.txtinicio1.value).substr(0,4)
    var Mes = ((frmFec.txtinicio1.value).substr(5,2))*1
    var Dia = (frmFec.txtinicio1.value).substr(8,2)
    var Anio1 = (frmFec.txtfin1.value).substr(0,4)
    var Mes1 = ((frmFec.txtfin1.value).substr(5,2))*1
    var Dia1 = (frmFec.txtfin1.value).substr(8,2)
    var Fecha_Inicio = new Date(Anio,Mes,Dia)
    var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
 
  if(Anio == '' || Anio == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
    {
      alert("Las fechas no deben ser vacias; Ingrese correctamente")
	  return false;
     }

	 
    if(Fecha_Inicio > Fecha_Fin)
    {
      alert("La fecha de inicio es mayor; Ingrese correctamente")
	  return false;
     }
    else
    {
      return true;
     }
}
function Compara2(frmFec)
{
    var Anio = (frmFec.txtinicio2.value).substr(0,4)
    var Mes = ((frmFec.txtinicio2.value).substr(5,2))*1
    var Dia = (frmFec.txtinicio2.value).substr(8,2)
    var Anio1 = (frmFec.txtfin2.value).substr(0,4)
    var Mes1 = ((frmFec.txtfin2.value).substr(5,2))*1
    var Dia1 = (frmFec.txtfin2.value).substr(8,2)
    var Fecha_Inicio = new Date(Anio,Mes,Dia)
    var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
 
  if(Anio == '' || Anio == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
    {
      alert("Las fechas no deben ser vacias; Ingrese correctamente")
	  return false;
     }

	 
    if(Fecha_Inicio > Fecha_Fin)
    {
      alert("La fecha de inicio es mayor; Ingrese correctamente")
	  return false;
     }
    else
    {
      return true;
     }
}
  
function Compara3(frmFec)
{
    var Anio = (frmFec.txtinicio0.value).substr(0,4)
    var Mes = ((frmFec.txtinicio0.value).substr(5,2))*1
    var Dia = (frmFec.txtinicio0.value).substr(8,2)
    var Anio1 = (frmFec.txtfin0.value).substr(0,4)
    var Mes1 = ((frmFec.txtfin0.value).substr(5,2))*1
    var Dia1 = (frmFec.txtfin0.value).substr(8,2)
    var Fecha_Inicio = new Date(Anio,Mes,Dia)
    var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
 
  if(Anio == '' || Anio == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
    {
      alert("Las fechas no deben ser vacias; Ingrese correctamente")
	  return false;
     }

	 
    if(Fecha_Inicio > Fecha_Fin)
    {
      alert("La fecha de inicio es mayor; Ingrese correctamente")
	  return false;
     }
    else
    {
      return true;
     }
} 
  </script>
</head>

<body  background="../images/fondo.jpg">
<form id="form1" name="form1" method="post" action="">
  <table width="1024" border="0" align="center">
  <tr>
    <td height="133" align="center" colspan="2"><img src="../images/logo.png" width="328" height="110" /></td>
    </tr>
      <tr>
  	<td width="436" colspan="2"><input type="image" src="../images/home.png" height="40" width="30" title="Inicio" formaction="../main.php?panel=mainpanel.php">
    </td>
  </tr>
      <tr>
        <td height="34" align="right" bgcolor="#3B5998" colspan="2"><ul id="MenuBar1" class="MenuBarHorizontal">
              <li><a class="MenuBarItemSubmenu" href="#"><strong>Órdenes por Fecha</strong></a>
                <ul>
                <li><a href="#" onclick="mostrar('new')">Nuevas</a></li>
                  <li><a href="#" onclick="mostrar('ordate')">Fecha de Orden</a>
                  </li>
                  <li><a href="#" onclick="mostrar('shipto')">Fecha de Vuelo</a></li>
                  <li><a href="#" onclick="mostrar('deliver')">Fecha de Entrega</a></li>
                </ul>
             </li>
              <li><a class="MenuBarItemSubmenu" href="#"><strong>Órdenes por Detalles</strong></a>
                <ul>
             <li><a href="#" onclick="mostrar('tracking')">Por Tracking</a></li>
             <li><a href="#" onclick="mostrar('ponumber')">Por Ponumber</a></li>
             <li><a href="#" onclick="mostrar('custnumber')">Por CustNumber</a></li>
             <li><a href="#" onclick="mostrar('item')">Por Producto</a></li>
             </ul>
             </li>
             <li><a class="MenuBarItemSubmenu" href="#"><strong>Clientes</strong></a>
                <ul>
                  <li><a href="#" onclick="mostrar('shipto1')">Nombre del Receptor</a></li>
                  <li><a href="#" onclick="mostrar('direccion')">Dirección del Receptor</a></li>
                  <li><a href="#" onclick="mostrar('soldto1')">Nombre del Comprador</a></li>
                  <li><a href="#" onclick="mostrar('cpdireccion_soldto')">Dirección del Comprador</a></li>
                </ul>
             </li>
            <!-- <li><a href="#" onclick="mostrar('farm')"><strong>Farms</strong></a></li>-->
              <li><a href="#"><strong><input name="pais" type="radio" value="US" checked="checked" />E.U</strong></a>
                   </li>
             <li><a href="#"><strong><input name="pais" type="radio" value="CA" />CA</strong></a>
             </li>
             <li><a href="#"><strong><input name="origen" type="radio" value="BOG-COLOMBIA" checked="checked"/>BOG</strong></a>
                   </li>
             <li><a href="#"><strong><input name="origen" type="radio" value="MED-COLOMBIA" />MED</strong></a>
             </li>                
          </ul></td>
      </tr>
      <tr id="new"  bgcolor="#CCCCCC" style='display:none;'>
      <!--<td> 
		  <p>&nbsp;</p>
		  <p><strong>Start date:</strong></p></td>
		   <td width="818"><p><strong>New Orders
		     </strong></p>
		     <p>
		       <input name="txtinicio0" type="text" id="txtinicio0" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio0");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio0",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

              </script>
		       <strong>End date:</strong>
		       <input name="txtfin0" type="text" id="txtfin0" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin0");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin0",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
		       <input type="submit" name="enviar0" id="enviar0" value="Search"  onclick="Compara3(this.form)" />
	         </p>
	      </strong></p>              
        </td>
      </tr>
     <tr id="ordate"  bgcolor="#CCCCCC" style='display:none;'>
        <td> 
		  <p>&nbsp;</p>
		  <p><strong>Start date:</strong></p></td>
		   <td width="818"><p><strong>Order Date</strong> </p>
		     <p>
		       <input name="txtinicio" type="text" id="txtinicio" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

              </script>
		       <strong>End date:</strong>
		       <input name="txtfin" type="text" id="txtfin" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
		       <input type="submit" name="enviar" id="enviar" value="Search"  onclick="Compara(this.form)" />
	         </p>
	      </strong></p>              
        </td>
      </tr>
       <tr id="shipto" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	     <p>&nbsp;</p>
	     <p><strong>Start date:</strong></p></td>
		   <td> 
          <p><strong>Ship Date            </strong></p>
          <p>
            <input name="txtinicio1" type="text" id="txtinicio1" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio1");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio1",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
            <strong>End date:</strong>
            <input name="txtfin1" type="text" id="txtfin1" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin1");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin1",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

        </script>
            <input type="submit" name="enviar1" id="enviar1" value="Search"  onclick="Compara1(this.form)" />
          </p>
        </strong>              
        </td>
      </tr>
      <tr id="deliver" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Start date:</strong></p></td>
		   <td> 
          <p><strong>Deliver Date            </strong></p>
          <p>
            <input name="txtinicio2" type="text" id="txtinicio2" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio2");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio2",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
            <strong>End date:</strong>
            <input name="txtfin2" type="text" id="txtfin2" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin2");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin2",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

        </script>
            <input type="submit" name="enviar2" id="enviar2" value="Search"  onclick="Compara2(this.form)" />
          </p>
        </strong>              
        </td>
      </tr>
      <tr id="tracking" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
		<strong>Tracking nbr:</strong></td>
		   <td> 
          <input name="tracking" type="text" id="idtracking"/>
        <input type="submit" name="enviar3" id="enviar3" value="Search"/>
        </strong>              
        </td>
      </tr>
      <tr id="ponumber" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
		<strong>Ponumber:</strong></td>
		   <td> 
          <input name="ponumber" type="text" id="ponumber"  />
        <input type="submit" name="enviar4" id="enviar4" value="Search"/>
        </strong>              
        </td>
      </tr>
      <tr id="custnumber" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
		<strong>Custnumber:</strong></td>
		   <td> 
          <input name="custnumber" type="text" id="custnumber"  />
        <input type="submit" name="enviar6" id="enviar6" value="Search"/>
        </strong>              
        </td>
      </tr>
      <tr id="item" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
		<strong>Item nbr:</strong></td>
	    <td> 
        <input name="item" type="text" id="item" />
        <input type="submit" name="enviar5" id="enviar5" value="Search"/>
        </strong>              
        </td>
      </tr>
      <tr id="farm" style='display:none;'  bgcolor="#CCCCCC">
       <td> 
		<p><strong>Farm:</strong></td>
	    <td> 
		  <input name="farm" type="text" id="farm" />
		  <input type="submit" name="enviar7" id="enviar7" value="Search"/>
	    </strong></p>
        </td>
      </tr>
       <tr id="shipto1" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p><strong>Ship to Name:</strong></td>
		   <td> 
		  <input name="shipto1" type="text" id="shipto1" />
		  <input type="submit" name="enviar8" id="enviar8" value="Search"/>
	    </strong></p></td>
      </tr>
      <tr id="direccion" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p><strong>Ship To Adress:</strong></td>
		   <td> 
		  <input name="direccion" type="text" id="direccion" />
		  <input type="submit" name="enviar9" id="enviar9" value="Search"/>
	    </strong></p></td>
      </tr>
       <tr id="soldto1" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p><strong>Bill to Name:</strong></td>
		   <td> 
		  <input name="soldto1" type="text" id="soldto1" />
		  <input type="submit" name="enviar10" id="enviar10" value="Search"/>
	    </strong></p></td>
      </tr>
      <tr id="cpdireccion_soldto" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p><strong>Bill to Adress:</strong></td>
		   <td> 
		  <input name="cpdireccion_soldto" type="text" id="cpdireccion_soldto" />
		  <input type="submit" name="enviar11" id="enviar11" value="Search"/>
	    </strong></p></td>
      </tr>
      -->
      <td width="190"> 
		  <p><strong>Fecha Inicio:</strong></p></td>
		   <td width="824"><p><strong>BUSCAR ÓRDENES NUEVAS</strong></p>
<p>
          <input name="txtinicio0" type="text" id="txtinicio0" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio0");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio0",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

               </script>
		       <strong>Fecha Fin:</strong>
		       <input name="txtfin0" type="text" id="txtfin0" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin0");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin0",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

               </script>
		       <input type="submit" name="enviar0" id="enviar0" value="Buscar"  onclick="Compara3(this.form)"class="btn-primary" title="Buscar" />
        </p>
        <p>&nbsp;</p></td>
      </tr>
      <tr id="ordate"  bgcolor="#CCCCCC" style='display:none;'>
        <td> 
		  <p>&nbsp;</p>
		  <p><strong>Fecha Inicio:</strong></p></td>
		   <td width="824"><p><strong>BUSCAR ÓRDENES POR FECHA DE ÓRDEN</strong></p>
<p>
          <input name="txtinicio" type="text" id="txtinicio" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

              </script>
		       <strong>Fecha Fin:</strong>
		       <input name="txtfin" type="text" id="txtfin" readonly="readonly" />
		       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
		       <input type="submit" name="enviar" id="enviar" value="Buscar"  onclick="Compara(this.form)" />
	         </p>
	      </strong></p>              
        </td>
      </tr>
       <tr id="shipto" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	     <p>&nbsp;</p>
	     <p><strong>Fecha Inicio:</strong></p></td>
		   <td> 
          <p><strong>BUSCAR ÓRDENES POR FECHA DE VUELO</strong></p>
<p>
          <input name="txtinicio1" type="text" id="txtinicio1" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio1");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio1",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
            <strong>Fecha Fin:</strong>
            <input name="txtfin1" type="text" id="txtfin1" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin1");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin1",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

        </script>
            <input type="submit" name="enviar1" id="enviar1" value="Buscar"  onclick="Compara1(this.form)" />
          </p>
        </strong>              
        </td>
      </tr>
      <tr id="deliver" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Fecha Inicio:</strong></p></td>
		   <td> 
          <p><strong>BUSCAR ÓRDENES POR FECHA DE ENTREGA</strong></p>
          <p>
            <input name="txtinicio2" type="text" id="txtinicio2" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio2");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio2",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
            <strong>Fecha Fin:</strong>
            <input name="txtfin2" type="text" id="txtfin2" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin2");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin2",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

        </script>
            <input type="submit" name="enviar2" id="enviar2" value="Buscar"  onclick="Compara2(this.form)" />
          </p>
        </strong>              
        </td>
      </tr>
      <tr id="tracking" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Tracking:</strong></p></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR  TRACKING</strong></p>
          <p>
          <input name="tracking" type="text" id="idtracking" />
         <!-- <input name="alltrack" id="alltrack" type="checkbox" value="" onchange="deshabilitar()"/>All Trackings-->
          <input type="submit" name="enviar3" id="enviar3" value="Buscar"/>
        </strong>              
          <p></td>
      </tr>
      <tr id="ponumber" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Ponumber:</strong></p></td>
		   <td>
           <p><strong>BUSCAR ÓRDENES POR PONUMBER</strong></p>
          <p> 
          <input name="ponumber" type="text" id="ponumber"  />
          <input type="submit" name="enviar4" id="enviar4" value="Buscar"/>
        </strong>              
          <p></td>
      </tr>
      <tr id="custnumber" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Custnumber:</strong></p></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR CUSTNUMBER</strong></p>
          <p>
          <input name="custnumber" type="text" id="custnumber"  />
        <input type="submit" name="enviar6" id="enviar6" value="Buscar"/>
        </strong>              
          <p></td>
      </tr>
      <tr id="item" style='display:none;'  bgcolor="#CCCCCC">
        <td> 
	    <p><strong>Producto:</strong></p></td>
	    <td> 
        <p><strong>BUSCAR ÓRDENES POR PRODUCTO</strong></p>
           <p>
             <input name="item" type="text" id="item" />
             <input type="submit" name="enviar5" id="enviar5" value="Buscar"/>
             </strong>              
        </p>
        <p>&nbsp;</p></td>
      </tr>
      <tr id="farm" style='display:none;'  bgcolor="#CCCCCC">
       <td> 
		<p><strong>Finca:</strong></td>
	    <td> 
        <p><strong>BUSCAR ÓRDENES POR FINCA</strong></p>
		  <p>
		    <input name="farm" type="text" id="farm" />
		    <input type="submit" name="enviar7" id="enviar7" value="Buscar"/>
		    </strong></p>
        </p>
	    <p>&nbsp;</p></td>
      </tr>
       <tr id="shipto1" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p><strong>Nombre del Receptor:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR NOMBRE DEL RECEPTOR</strong></p>
		   <p>
		     <input name="shipto1" type="text" id="shipto1" />
		     <input type="submit" name="enviar8" id="enviar8" value="Buscar"/>
		     </strong></p>
		   </p>
	     <p>&nbsp; </p></td>
      </tr>
      <tr id="direccion" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p><strong>Dirección  del Receptor:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL RECEPTOR</strong></p>
		   <p>
		     <input name="direccion" type="text" id="direccion" />
		     <input type="submit" name="enviar9" id="enviar9" value="Buscar"/>
		     </strong></p>
		   </p>
	    <p>&nbsp; </p></td>
      </tr>
       <tr id="soldto1" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p><strong>Nombre del Comprador:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR NOMBRE DEL COMPRADOR</strong></p>
		   <p>
		     <input name="soldto1" type="text" id="soldto1" />
		     <input type="submit" name="enviar10" id="enviar10" value="Buscar"/>
		     </strong></p>
		   </p>
	     <p>&nbsp; </p></td>
      </tr>
      <tr id="cpdireccion_soldto" style='display:none;'  bgcolor="#CCCCCC">
     <td> 
		<p>        
		<p><strong>Dirección del Comprador:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL COMPRADOR</strong></p>
		   <p>
		     <input name="cpdireccion_soldto" type="text" id="cpdireccion_soldto" />
		     <input type="submit" name="enviar11" id="enviar11" value="Buscar"/>
		     </p>
	    </strong></p></td>
     <tr>
     <td>
     </td>
     </tr> 
    <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  <?php 
  //Si se oprimio el boton buscar de new
   if(isset($_POST['enviar0']))
  {	  
  	 
	 $fecha_inicio0=$_POST["txtinicio0"];
	 $fecha_fin0   =$_POST["txtfin0"];
	 $pais        = $_POST["pais"];
	 $origen      = $_POST["origen"];
	 $_SESSION["pais"]=$pais;
	 $_SESSION["origen"]=$origen;
	 $_SESSION["inicio0"]=$fecha_inicio0;
	 $_SESSION["fin0"]=$fecha_fin0;	
	 $_SESSION["login"] = $user ;
	 $url="repor_excel col.php"; 
     echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
  //Si se oprimio el boton buscar de orderdate
   if(isset($_POST['enviar']))
  {	  
  	 
	 $fecha_inicio=$_POST["txtinicio"];
	 $fecha_fin   =$_POST["txtfin"];
	 $pais        =$_POST["pais"];
	 $origen      = $_POST["origen"];
	 $_SESSION["pais"]=$pais;
	 $_SESSION["origen"]=$origen;
	 $_SESSION["inicio"]=$fecha_inicio;
	 $_SESSION["fin"]=$fecha_fin;	
	 $url="repor_excel col.php";
     echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
  //Si se oprimio el boton buscar de shipdt
  if(isset($_POST['enviar1']))
  {	  
	  $fecha_inicio1=$_POST["txtinicio1"];
	  $fecha_fin1=$_POST["txtfin1"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["inicio1"]=$fecha_inicio1;
	  $_SESSION["fin1"]=$fecha_fin1;
	  $url="repor_excel col.php";  
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
  //Si se oprimio el boton buscar de deliver
   if(isset($_POST['enviar2']))
  {	  
	  $fecha_inicio1=$_POST["txtinicio2"];
	  $fecha_fin1=$_POST["txtfin2"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["inicio2"]=$fecha_inicio1;
	  $_SESSION["fin2"]=$fecha_fin1;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
   //Si se oprimio el boton buscar de tracking
    if(isset($_POST['enviar3']))
  {	  
	  $tracking         =$_POST["tracking"];
	  $alltrack         = $_POST["alltrack "];
	  $pais             =$_POST["pais"];
	  $origen            = $_POST["origen"];
	  $_SESSION["pais"]  =$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["tracking"]=$tracking;
	  $_SESSION["login"] = $user ;
	  $_SESSION["alltrack"] = $alltrack ;
	   $url="repor_excel col.php";
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
   //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar4']))
  {	  
	 $ponumber=$_POST["ponumber"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["ponumber"]=$ponumber;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
     //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar5']))
  {	  
	  $item=$_POST["item"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["item"]=$item;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar6']))
  {	  
	  $custnumber=$_POST["custnumber"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["custnumber"]=$custnumber;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar7']))
  {	  
	  $farm=$_POST["farm"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["farm"]=$farm;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de shipto1
    if(isset($_POST['enviar8']))
  {	  
	  $shipto1=$_POST["shipto1"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["shipto1"]=$shipto1;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de direccion
    if(isset($_POST['enviar9']))
  {	  
	  $direccion=$_POST["direccion"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["direccion"]=$direccion;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de soldto1
    if(isset($_POST['enviar10']))
  {	  
	  $soldto1=$_POST["soldto1"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["soldto1"]=$soldto1;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de direccion
    if(isset($_POST['enviar11']))
  {	  
	  $cpdireccion_soldto=$_POST["cpdireccion_soldto"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["pais"]=$pais;
	  $_SESSION["origen"]=$origen;
	  $_SESSION["cpdireccion_soldto"]=$cpdireccion_soldto;
	  $url="repor_excel col.php"; 
      echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
 
  ?>
</form>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</html>
