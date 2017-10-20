<?php 
?>
<script type="text/javascript">
    
    	$(document).ready(function(){
    	    $("#btn_si").click(function(event) { 
                var formElement = document.getElementById("form1");  
                var formData = new FormData(formElement);
                formData.append('accion','rechazar_cajas');
                
                //armando el array con los codigos a rechazar
                var cajas = [];
                var i=0;
                $("tr#ftabla input[type=checkbox]:checked").each(function(){
                   cajas[i]=$(this).val();
                   i++;
                });
                formData.append('cajas',cajas);
                                
                $.ajax({
                    data: formData,
                    url:  'nuevoitem_pedido.php',
                    type: 'post',
                    processData: false,
                    contentType: false ,
                    dataType: 'json',
                    success:  function (response) {
                       
                    },
                    complete:  function (response) {
                        $('#myModal').modal('hide');
                        location.reload();
                    }
                });
            });
      });
      
  </script>



<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" style="width: 900px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Rechazar pedido</h4>
      </div>
      <div class="modal-body">
            <form id="form1" name="form1" method="post">
            <table width="500" border="0" align="center" class="alert">
                <tr>
                  <td align="center"colspan="2"><strong>Comentarios</strong></td>
                </tr>
                <tr style="display:none">
                   <td align="center"colspan="2"><input name="cajas" type="text"  value="<?php echo $cajas?>"/></td>
                   <td width="186"colspan="2" align="center"><input name="id" type="text" value="<?php echo $codi?>"/></td>
                </tr>
                <tr>
                <td align="center" colspan="2"><textarea name="comentario" class="form-control" cols="80" rows="5" autofocus="autofocus" style="resize:none;"></textarea></td>
                </tr>
                <tr>
                  <td height="30" colspan="2" align="center"><strong>Razones</strong></td>
                </tr>
                <tr>
                <td align="center" colspan="2"><table width="410" border="0">
              <tr>
                <td width="202"><input name="cajas1[]" type="checkbox"  value="BO" />Botritys</td>
                <td width="171"><input name="cajas1[]" type="checkbox"  value="FG" />Flor Guardada</td>
              </tr>
              <tr>
                <td width="202"><input name="cajas1[]" type="checkbox" value="DC" />DAE Caducado</td>
                <td width="171"><input name="cajas1[]" type="checkbox" value="PE" />Problema de Empaque</td>
              </tr>
              <tr>
                <td width="202"><p>
                  <input name="cajas1[]" type="checkbox" value="RA" />
                    Rechazado por Agrocalidad</p></td>
                <td width="171"><p>
                  <input name="cajas1[]" type="checkbox" value="NC" />
                    No es el color Ordenado</p></td>
              </tr>
            </table>
            </td>
            </tr>
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Esta seguro de rechazar la(s) solicitud(es) marcada(s).</span></strong></td>
    </tr>
    <tr>
      <td width="265" align="right"><input name="btn_si" type="button" class="btn btn-danger" id="btn_si" value="Rechazar" /></td>
      <td width="186" align="left"><input name="btn_no" type="button" class="btn alert-info" id="btn_no" value="Cancelar" /></td>
      <input name="idorden" type="hidden" id="idorden" value="<?php echo $_GET["id"]?>" />
    </tr>
  </table>
            </form>
      </div>
    </div>
    </div>
 </div>
 
<!--//  if(isset($_POST["si"])){
//	$comentario = $_POST['comentario'];
//        $cajas = $_POST['cajas'];
//        $cajas1 = $_POST['cajas1'];
//        $id = $_POST['id'];
//		
//        //Obtener las razones de credito seleccionada
//        if (is_array($_POST['cajas1'])) {
//               $selected = '';
//               $num_razones = count($_POST['cajas1']);
//               $current = 0;
//               foreach ($_POST['cajas1'] as $key => $value) {
//                       if ($current != $num_razones-1)
//                               $selected .= $value.', ';
//                       else
//                               $selected .= $value;
//                       $current++;
//               }
//       }
//       else {
//               echo("<script> alert ('Inserte al menos una razón');</script>");
//               return;
//       }
//        //Armar el array en php de las razones
//        $selected = json_encode($selected);
//        //Armar el array en php
//        $cajas = explode(",",$cajas);
//		
//        //hacer ciclo para recoger cada codigo marcado para asignarle la guia
//        for($i=0; $i < count($cajas);$i++){	
//            $sql = "SELECT estado,nropedido FROM tbletiquetasxfinca WHERE codigo='".$cajas[$i]."'";
//           
//            $query = mysql_query($sql,$link);
//            $row   = mysql_fetch_array($query);
//           
//            if($row[0]=='0' || $row[0]=='4'){  
//
//                    //actualizar el cooldrrom el campo de rechazadas para que en el main cuando llame a las cajas que estan en cuarto frio y que no sean rechazadas saberlo
//                    $sql="Update tblcoldroom set rechazada='2' WHERE codigo='".$cajas[$i]."'";
//                    $modificado1= mysql_query($sql,$link) or die("Error");
//
//                    //actualiza la tabla etiquetasxfinca
//                    $sql="Update tbletiquetasxfinca set entregado='0', estado='2', comentario='".$comentario."', razones1= '".$selected."' WHERE codigo='".$cajas[$i]."'";
//                    $modificado= mysql_query($sql,$link) or die("Error");						    
//            }
//        }
//        echo("<script> alert ('Etiquetas rechazadas correctamente');
//        window.close();
//        window.opener.document.location='etiqxentregar.php?id=".$id."';				   
//        </script>");
//    }			
//
//    if(isset($_POST["no"])){  
//        echo("<script>window.close();
//            window.opener.document.location='etiqxentregar.php?id=".$_POST['id']."';
//            </script>");
//    }  
//  
//  ?>-->
