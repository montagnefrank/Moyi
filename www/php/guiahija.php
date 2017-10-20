  <script>
  jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf("_") < 0 && value != ""; 
  }, "No space please and don't leave it empty");

  // When the browser is ready...
  $(function() {
      // Setup form validation on the #register-form element
      $("#form_guiah").validate({
      errorClass: "my-error-class",
      validClass: "my-valid-class",
      // Specify the validation rules
      rules: {
            guiah: {
              required: true
            }
      },
      messages: {
            guiah: {
              required: "Por favor inserte un código válido"
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
            data:  "accion=guiahija&cajas="+cajas+"&guiah="+$('#guiah').val(),
            url:   'modelasig_guia.php',
            type:  'post',
            dataType: 'json',
            success:  function (response) {
               location.reload();
            }
        }); 
      }
    });
     
    $("#guiah").mask("***-****?*****");
  });
  </script>

  <div class="modal fade" id="myModalGH" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignando Guia Hija</h4>
      </div>
        <form id="form_guiah" name="form_guiah" method="post" >
        <div class="modal-body">
                <div class="col-md-12">
                    <strong>HAWB-GUIA:</strong>
                    <input type="text" id="guiah" name="guiah" class="form-control" />
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