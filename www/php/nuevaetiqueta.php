<?php 
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$rol      =  $_SESSION["rol"];

//Recogiendo el id del usuario
$sqluser = "SELECT id_usuario FROM tblusuario WHERE cpuser = '".$user."'";
$queryuser = mysql_query($sqluser,$link);
$rowuser = mysql_fetch_array($queryuser);
$usuario = $rowuser['id_usuario'];

$sql = "select tblfinca.codigo,tblfinca.nombre from tblfinca inner join tblcarro_pedido on tblfinca.codigo=tblcarro_pedido.codfinca WHERE
tblcarro_pedido.id_usuario='".$usuario."'";
$query = mysql_query($sql,$link);
$row9  = mysql_fetch_array($query);


?>

<script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('<p></p>');

//        $("#finca").autocomplete({
//          source: "buscar_finca.php",
//          minLength: 2
//        });
        
        //validando el formulario de nuevo item
        $("#datos_item").validate({
                errorClass: "my-error-class",
                validClass: "my-valid-class",
          
                // Specify the validation rules
                rules: {
                    finca: "required",
                    item1: "required",
                    cantidad1: "required",
                    precioUnitario: "required"
                },
              
                // Specify the validation error messages
                messages: {
                    finca:"Por favor seleccione la finca",
                    item1: "Por favor inserte el Item",
                    cantidad1: "Por favor inserte la cantidad",
                    precioUnitario: "Por favor inserte el precio",
                    
                },
              
                submitHandler: function(form) {
                  
                  var formElement = document.getElementById("datos_item");  
                  var formData = new FormData(formElement);
                  $.ajax({
                    data:  formData,
                    url:   'nuevoitem_pedido.php',
                    type:  'post',
                    processData: false,
                    contentType: false ,
                    dataType: 'json',
                    success:  function (response) {
                        if(response[4]=='editar')
                        {
                          //modifico la fila la tabla de los items
                           $('#lista_items tbody tr#'+response[0]).find('td:eq("0")').html(response[1]);
                           $('#lista_items tbody tr#'+response[0]).find('td:eq("1")').html(response[2]);
                           $('#lista_items tbody tr#'+response[0]).find('td:eq("2")').html(response[3]);
                           $(':input','#datos_item').not(':button, :submit, :reset, :hidden, [readonly]').val('').removeAttr('selected');
                           $('#btn_editaritem').css('display','none');
                           
                        }
                        else
                        {
                        //lleno la tabla de los items
                        $('#lista_items tbody').append('<tr id="'+response[0]+'"><td>'+response[1]+
                               '</td><td>'+response[2]+
                               '</td><td>'+response[3]+
                                '<td><button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-default" data-toggle="tooltip" data-placement="left" title = "Modificar Item" >'+
                                             '<span class="glyphicon glyphicon-edit" aria-hidden="true" ></span>'+
                                         '</button></td>'+
                                '<td><button type="button" name="btn_eliminar" id="btn_eliminar" class="btn btn-default" data-toggle="tooltip" data-placement="left" title = "Eliminar Item" >'+
                                             '<span class="glyphicon glyphicon-trash" aria-hidden="true" ></span>'+
                                         '</button>'+                 
                               '</td></tr>');
                         $(':input','#datos_item').not(':button, :submit, :reset, :hidden, [readonly]').val('').removeAttr('selected');
                         $('#btn_editaritem').css('display','none');
                       }
                       $('#busqueda').attr('readonly','readonly');
                    }
                  });
                }

            });
        
        //boton de modificar pedido en la tabla
        $("#lista_items").on('click','#btn_modificar',function(){
          var id=$(this).parents('tr').attr('id');
          var item=$('#lista_items tr#'+id).find('td:eq("0")').text();
          var cant=$('#lista_items tr#'+id).find('td:eq("1")').text();
          var precio=$('#lista_items tr#'+id).find('td:eq("2")').text();
          $("#idcompra_pedido").val(id);
          $("#item1").val(item);
          $("#cantidad1").val(cant);
          $("#precioUnitario").val(precio);
          $("#btn_editaritem").css('display','inline-block');
        });
        
        $("#lista_items").on('click','#btn_eliminar',function(){ 
          var id=$(this).parents('tr').attr('id');  
          $.ajax({
            data:'accion=eliminar&id='+id,
            url:'nuevoitem_pedido.php',
            type:'post',
            dataType: 'json',
            success:  function (response) {
              $('#lista_items tbody tr#'+id).remove();
              $(':input','#datos_item').not(':button, :submit, :reset, :hidden, [readonly]').val('').removeAttr('selected');
              $('#btn_editaritem').css('display','none');
              var nFilas = $("#lista_items tbody tr").length;
              if(nFilas==0)
              {
                 $("#busqueda").removeAttr('readonly');
              }
            }
           });
        
        });
        
        //validando el formulario de registrar datos generales
        $("#datos_generales").validate({
                errorClass: "my-error-class",
                validClass: "my-valid-class",
          
                // Specify the validation rules
                rules: {
                    fecha: "required",
                    ftentativa: "required",
                    destino: "required",
                    agencia: "required"
                },
              
                // Specify the validation error messages
                messages: {
                    fecha:"Por favor seleccione la fecha",
                    ftentativa: "Por favor seleccione la fecha",
                    destino: "Por favor seleccione el destino",
                    agencia: "Por favor seleccione la agencia"
                    
                },
              
                submitHandler: function(form) {
                  
                  var formElement = document.getElementById("datos_generales");  
                  var formData = new FormData(formElement);
                  formData.append('codigo_finca',$('#myModal3').find("#codigo_finca").val());
                  formData.append('finca',$('#myModal3').find("#finca").val());
                  formData.append('accion','registrar');
                  $.ajax({
                    data:  formData,
                    url:   'nuevoitem_pedido.php',
                    type:  'post',
                    processData: false,
                    contentType: false ,
                    dataType: 'json',
                    success:  function (response) {
                        if(response=='2')
                        {
                          $('#error').css('display','inline-block');  
                          $('#error').html("<strong>No hay items en el carro de pedido.</strong>");
                        }
                        if(response=='3')
                        {
                          location.reload();
                        }
                    }
                    });
                  
                 
                }
            });
            
            //agregar nuevo pedido
            $('#btn_nuevo').on('click',function(){
              $('#myModal3').modal('show');
            });
            
             //boton de cancelar pedido
            $('.cancel').on('click',function(){
              $.ajax({
                    data:'accion=cancelar&codigo_finca='+$('#myModal3').find("#codigo_finca").val(),
                    url: 'nuevoitem_pedido.php',
                    type:'post',
                    dataType: 'json',
                    success:  function (response) {
                       //location.reload();
                    },
                    complete:  function (response) {
                       location.reload();
                    },
                    });
            });
    });
    
    function buscar() {
        var textoBusqueda = $("input#busqueda").val();

        if (textoBusqueda != "") {
            $.post("buscarFinca.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
                $("#resultadoBusqueda").html(mensaje);
             });
             //para dar click en una finca
            $("#resultadoBusqueda").on('click','#tabla_fincas tbody tr',function(){
              $("#myModal3").find('#codigo_finca').val($(this).attr('id')); //pongo el codigo de la finca
              $("#myModal3").find('#finca').val($('td:eq("1") strong',this).html()); //pongo el nombre de la finca
              $("#myModal3").find("#resultadoBusqueda").html('<p></p>'); //limpiamos el div de la tabla de resultados
            });
        } else { 
            $("#resultadoBusqueda").html('<p></p>');
        };
    };
       
    </script>

<!--Ventana modal para crear nuevo ppedido-->
<div class="modal fade bs-example-modal-lg" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" style="width: 900px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Nuevo Pedido</h4>
      </div>
      <div class="modal-body">
          <div class="alert alert-danger" role="alert" id="error" style="display: none;"></div>
        <div class="row" style="margin-right: 10px;">
            <form class="form-inline" style="float: right;">
                <div class="form-group">
                  <label for="busqueda">Buscar Finca:</label>
                  <?php if($row9['nombre']==''){?>
                    <input type="text" class="form-control" name="busqueda" id="busqueda" onKeyUp="buscar();"/>
                  <?php }else{?>
                    <input type="text" class="form-control" name="busqueda" id="busqueda" readonly=""/>
                    <?php }?>
                </div>
            </form>
        </div>
    
        <div id="resultadoBusqueda"></div>
        
                <div class="row">
                  <form name="datos_item" id="datos_item" method="post" enctype="multipart/form-data">  
                  <div class="col-md-6">
                    <div class="form-group" style="width: 80%">
                      <label for="finca">Finca:</label>
                      <input class="form-control" type="text" id="finca" name="finca" value="<?php echo $row9['nombre'];?>" readonly />
                      <input type="hidden" id="codigo_finca" name="codigo_finca" value="<?php echo $row9['codigo'];?>"/>
                    </div>
                    <div class="form-group" style="width: 80%">
                            <label for="item1">Producto:</label>
                            <select id="item1" name="item1" placeholder="Producto" class="form-control">
                            <?php
                                $sql1 = "SELECT id_item,prod_descripcion FROM tblproductos order by id_item";
                                $query1 = mysql_query($sql1,$link) or die ("Error leyendo los productos");
                                while($row2 = mysql_fetch_array($query1)){
                                    echo '<option value="'.$row2['id_item'].'">'.$row2['id_item'].'-'.$row2['prod_descripcion'].'</option>';
                                }
                            ?>
                            </select>  
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="cantidad1">Cantidad:</label>
                            <input type="text" id="cantidad1" name="cantidad1" placeholder="Cantidad" size="10" class="form-control required"/>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="precioUnitario">Precio:</label>
                            <input type="text" id="precioUnitario" name="precioUnitario" placeholder="Precio Unitario" class="form-control required"/>
                        </div>
                        <input type="submit" class="btn btn-primary" id="btn_nuevoitem" name="btn_nuevoitem" value="Añadir item"/>
                        <input type="submit" class="btn btn-primary" id="btn_editaritem" name="btn_editaritem" value="Editar item" style="display: none"/>
                        <input type="hidden" id="idcompra_pedido" name="idcompra_pedido"/>
                  </div>
                  </form>
                  <form name="datos_generales" id="datos_generales" method="post" enctype="multipart/form-data">     
                  <div class="col-md-6">
                        <div class="form-group">
                          <label for="fecha">Salida de Finca:</label>
                          <div class='input-group date' id='datetimepicker1' style="width: 80%">
                                <input type='text'  class="form-control" name="fecha" id="fecha" value="<?php if(isset($_SESSION['fecha'])) echo $_SESSION['fecha'];?>" placeholder="Fecha salida finca"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                          <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker1').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        showTodayButton:true
                                    });
                                });
                            </script>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="ftentativa">Fecha Tentativa de Vuelo:</label>
                            <div class='input-group date' id='datetimepicker2'>
                                  <input type='text' class="form-control" name="ftentativa" id="ftentativa" value="<?php if(isset($_SESSION['ftentativa'])) echo $_SESSION['ftentativa']; ?>" placeholder="Fecha tentativa vuelo"/>
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                              <script type="text/javascript">
                                  $(function () {
                                      $('#datetimepicker2').datetimepicker({
                                          format: 'YYYY-MM-DD',
                                          showTodayButton:true
                                      });
                                  });
                              </script>
                        </div>
                        <div class="form-group" style="width: 80%">
                          <label for="finca">Destino:</label>
                          <select id="destino" name="destino" class="form-control">
                            <?php
                               $sqldestino   = "SELECT * FROM tblpaises_destino";
                               $querydestino = mysql_query($sqldestino,$link) or die ("Error leyendo los paises");

                               $sqldest = "SELECT * FROM tblpaises_destino WHERE codpais = '".$_SESSION['destino']."'";
                               $querydest = mysql_query($sqldest,$link);

                               while($rowdestino = mysql_fetch_array($querydestino)){
                                    if($rowdestino['codpais']==$_SESSION['destino']){
                                        //echo '<option value=""</option>';
                                    }
                                    else{
                                        echo '<option value="'.$rowdestino['codpais'].'">'.$rowdestino['pais_dest'].'</option>';
                                    }
                               }

                               while($rowdest = mysql_fetch_array($querydest)){
                                    echo '<option selected value="'.$rowdest['codpais'].'">'.$rowdest['pais_dest'].'</option>'; 
                               }

                            ?>
                            </select>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="agencia">Agencia de Carga:</label>
                            <select id="agencia" name="agencia" class="form-control">
                            <?php
                                     $sqlagencia   = "SELECT * FROM tblagencia";
                                         $queryagencia = mysql_query($sqlagencia,$link) or die ("Error leyendo las agencias");
                                         //echo '<option value="'.$_SESSION['agencia'].'">'.$row2['nombre_agencia'].'</option>';

                             $sqlag = "SELECT * FROM tblagencia WHERE nombre_agencia = '".$_SESSION['agencia']."'";
                             $queryag = mysql_query($sqlag,$link);

                             while($rowagencia = mysql_fetch_array($queryagencia)){
                                  if($rowagencia['nombre_agencia']==$_SESSION['agencia']){
                                      //echo '<option value=""</option>';
                                  }
                                  else{
                                         echo '<option value="'.$rowagencia['nombre_agencia'].'">'.$rowagencia['nombre_agencia'].'</option>';
                                  }
                                         }

                             while($rowag = mysql_fetch_array($queryag)){
                                  echo '<option selected value="'.$rowag['nombre_agencia'].'">'.$rowag['nombre_agencia'].'</option>'; 
                             }
                                      ?>
                            </select>
                        </div>
                        
                    </div>
                    
                 
                </div>
     
                <div style="margin-top: 5px;">
                <div class="row" style="text-align: center">
                    <h3><strong>Listado de Items a comprar</strong></h3>
                </div>   
                <table width="100%" id="lista_items" border="0" align="center" style="text-align: center" class="table table-striped" > 
                    <thead>
                      <th><strong>Producto</strong></th>
                      <th><strong>Cantidad</strong></th>
                      <th><strong>Precio Unitario</strong></th>
                      <th><strong>Editar</strong></th>
                      <th><strong>Eliminar</strong></th>
                    </thead>
                    <tbody>
                    <?php
                       $sql = "SELECT * FROM tblcarro_pedido INNER JOIN tblproductos ON tblcarro_pedido.id_item=tblproductos.id_item WHERE tblcarro_pedido.codfinca = '".$row9['codigo']."' AND id_usuario= '".$usuario."'";
                       $val = mysql_query($sql,$link);
                          if(!$val){
                            echo "<tr><td>".mysql_error()."</td></tr>";
                           }else{
                             $cant = 0;
                             while($row = mysql_fetch_array($val)){

                              $cant ++;
                              echo "<tr id='".$row['idcompra_pedido']."'>";
                              echo "<td>".$row['id_item']."</td>";
                              echo "<td>".$row['cantidad']."</td>";
                              echo "<td>".$row['preciounitario']."</td>";
                              echo '<td><button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-default" data-toggle="tooltip" data-placement="left" title = "Modificar Item" >
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true" ></span>
                                        </button></td>';
                              echo '<td><button type="button" name="btn_eliminar" id="btn_eliminar" class="btn btn-default" data-toggle="tooltip" data-placement="left" title = "Eliminar Item" >
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true" ></span>
                                        </button>';
                              echo "</tr>";
                           }

                           }
                      ?> 
                        </tbody>
        
                        <tfoot>
                          <td></td>
                          <td></td>
                          <td align="right"></td>
                          <td width="83"> <input name="Registrar" id="Registrar"  type="submit" value="Registrar" class="btn btn-primary"/></td>
                          <td width="153"><input name="Cancelar" type="button" value="Cancelar" class="btn btn-danger cancel" /></td>
                        </tfoot>
                </table>
            </div>
           </form>
            </div>
        </div>
    </div>
</div>