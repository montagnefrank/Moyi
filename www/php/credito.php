<style>
.modal-header{
    color: #ffffff;
    background-color: #428bca;
    border-color: #428bca;
}
</style>

<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Adicionar Nuevo Registro de Quejas</h4>
      </div>
        
        <form name="form_quejas" id="form_quejas" method="post">
        <div class="modal-body">
               
                <div class="row">
                <div class="col-md-4">
                  <form id="form1" name="form1" method="post" style="margin-top: 20px;">
                    <div class="form-group">
                        <label for="precio">Precio unitario</label>
                        <input name="precio" id="precio" style="width: 200px;" type="text" class="form-control" readonly="readonly"/>
                    </div>
                   
                    <div class="form-group">
                       <label for="credito">Crédito a devolver</label>
                       <input style="width: 200px;" name="credito" id="credito" type="text" class="form-control" value=""/>
                    </div>
                     
                    <div class="form-group">
                        <label for="credito">Seleccione las causas</label>
                        <select id="origen" id="origen" style="width:250px;height: 250px;overflow: auto;" multiple="multiple" ></select>    
                               
                    </div>
                    <div class="form-group" id="subir_correo">
                        
                    </div>  
                      
                </form>
                </div>
                <div class="col-md-6">
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-3">
                            <button type="button" id="agregar_queja" class="btn btn-primary" aria-label="Left Align">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Agregar Queja
                            </button>
                        </div>
                    </div>
                    <div id="contenedor_quejas" style="margin-top: 10px;height: 650px;overflow: auto">
                        <input type="hidden" id="cant_quejas" value="">
                    </div>
                </div>
                
                </div>
                <input type="hidden" id="id_orden_detalle" value="" />      
        </div>
   
</form>
  <div class="modal-footer">
      <button type="button" id="cancelar" class="btn btn-default" data-dismiss="modal">Cancelar Registro</button>
      <button type="button" id="registrar" class="btn btn-primary">Registrar Quejas</button>
  </div>
</div>
</div>
</div>


<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Confirmaci&oacute;n de eliminar queja</h4>
      </div>
        <div class="modal-body">
            <label>Esta seguro que desea eliminar esta queja?</label>
            <input type="hidden" id="id_queja" value=""/>
        </div>
        <div class="modal-footer">
            <button type="button" id="No" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="button" id="Si" class="btn btn-primary">Si</button>
        </div>
    </div>
  </div>
</div>      


<script>
    $(document).ready(function() 
    {
      var i=0;
      //para agregar quejas 
      $('#agregar_queja').on('click',function(){ 
           $('#contenedor_quejas').append('<div class="panel panel-default" id=p'+i+' style="width: 80%;text-align: left;">'+
                '<div class="panel-body">'+
                '<div class="col-md-9">'+
                '<div class="form-group">'+
                    '<label for="fecha">Fecha:</label>'+
                    '<input name="fecha[]" type="text" class="form-control" id="fecha_'+i+'" value="<?php echo $row['fecha'];?>" style="width:200px;">'+
                    
                '</div>'+
                                
                '<script type="text/javascript">'+
                    '$(function () {'+
                        '$("#fecha_'+i+'").datetimepicker({'+
                            'format: "YYYY-MM-DD",'+
                            'showTodayButton:true'+
                        '});'+
                   ' });<\/script>'+
                '<div class="form-group">'+
                    '<label for="queja" >Queja:</label>'+
                    '<textarea name="queja[]" style="width: 280px;" type="text" class="form-control" id="queja_'+i+'" rows="4" ></textarea>'+
                '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                    '<button type="button" id="eliminar_dest" class="btn btn-primary" aria-label="Left Align"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>'+
                '</div>'+
            '</div>'+
            '</div>'
        );
        i++;
       
       });
       
      //para los botones de eliminar quejas
      $('#contenedor_quejas').on('click','button#eliminar_dest',function(event){
        id=$(this).parents('div.panel-default').attr('id');
        $('#myModal1').find('#id_queja').val(id);
        $('#myModal1').modal('show');
     });
     
      //para el boton de si eliminar queja
      $('#myModal1').on('click','button#Si',function(event){
        id=$('#myModal1').find('#id_queja').val(); 
        $('div#'+id).remove();
        $.ajax({
          data:"accion=eliminar_quejas&id="+id,
          url:'modeloquejas.php',
          type:'post',
        success:function(response){
          
        },
        complete:function(){
          $('#myModal1').modal('hide');
        }
      });
     });
       
      //para el boton de registrar quejas
      $('#myModal').find("#registrar").on('click',function(){
            var formData = new FormData(document.getElementById('form_quejas'));
            formData.append('accion','registrar');
            formData.append('id',$('#id_orden_detalle').val());

            //obteniendo valores del select multiple
            var listaValoresSelector = $("#origen :selected").map(function () {
                return this.value;
            }).get();
            formData.append('listacausas',listaValoresSelector);

           //llamada ajax para obtener credito y precio unitario
           $.ajax({
                data:  formData,
                url:   'modeloquejas.php',
                type:  'post',
            processData: false,  // tell jQuery not to process the data
            contentType: false ,
            dataType: 'json',
            success:  function (response) {

            }
        });

        //resetar la ventana modal
        $('#myModal').find("#contenedor_quejas").html('');
        $("#origen").find('option:selected').removeAttr('selected');

        $('#myModal').modal('hide');
       }); 
      
      
    });
  
 
</script>