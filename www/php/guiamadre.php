  <script>
  jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf("_") < 0 && value != ""; 
  }, "No space please and don't leave it empty");

  // When the browser is ready...
  $(function() {
      // Setup form validation on the #register-form element
      $("#form_guiam").validate({
      errorClass: "my-error-class",
      validClass: "my-valid-class",
      // Specify the validation rules
       rules: {
	        guiam: {
	          required: true,	          
	          noSpace: true
	        },
                vuelo: {
	          required: true,	          
	          noSpace: true
	        },
                entrega: {
	          required: true,	          
	          noSpace: true
	        }
	  },
	  
	  // Specify the validation error messages
	  messages: {
	        guiam: {
	          required: "Por favor inserte un código válido",
	          noSpace: "No puede dejar espacios en blanco"
	        },
                vuelo: {
	          required: "Por favor inserte una fecha válido",
	          noSpace: "No puede dejar espacios en blanco"
	        },
                entrega: {
	          required: "Por favor inserte una fecha válido",
	          noSpace: "No puede dejar espacios en blanco"
	        }
	  },
      submitHandler: function(form) {
         //obtener las checkbox seleccionados
        var cajas=[];
        var i=0;
        $("tbody input[type=checkbox]:checked").each(function(){
            cajas[i++]=$(this).val();
        });

        $.ajax({
            data:  "accion=guiamadre&cajas="+cajas+"&guiam="+$('#guiam').val()+"&entrega="+$('#entrega').val()+"&vuelo="+$('#vuelo').val()+"&servicio="+$('#servicio').val(),
            url:   'modelasig_guia.php',
            type:  'post',
            dataType: 'json',
            success:  function (response) {
               location.reload();
            }
        }); 
      }
    });
     
    $("#guiam").mask("***-********");
    
   
    $("#datetimepicker_entrega").datetimepicker({
        format: "YYYY-MM-DD",
        showTodayButton:true
    });
    $("#datetimepicker_vuelo").datetimepicker({
        format: "YYYY-MM-DD",
        showTodayButton:true
    });
                                      
  });
  </script>

  <div class="modal fade" id="myModalGM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignando Guia Madre</h4>
      </div>
        <form id="form_guiam" name="form_guiam" method="post" >
        <div class="modal-body">
            <div class="col-md-12">
            	<strong>Fecha de Entrega:</strong>
                <div class='input-group date' id='datetimepicker_entrega'>
                    <input type='text' class="form-control" name="entrega" id="entrega" value="" placeholder="Fecha vuelo"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>   
                      
            <div class="col-md-12">
            	<strong>AWB-GUIA:</strong>
                <input type="text" id="guiam" name="guiam" value="" class="form-control" />
            </div>
            <div class="col-md-12">
            	<strong>Servicio:</strong>
                <select type="text" id="servicio" name="servicio" class="form-control">
                    <option value="48">48 Hrs</option>
                    <option value="ER" selected="selected">Entrega Regular</option>
                </select>
            </div>
               
            <div class="col-md-12">
            	<strong>Fecha de vuelo:</strong>
                <div class='input-group date' id='datetimepicker_vuelo'>
                    <input type='text' class="form-control" name="vuelo" id="vuelo" value="" placeholder="Fecha vuelo"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div> 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="asignar" >Asignar</button>
        </div>
      </form> 
    </div>
  </div>
  </div>