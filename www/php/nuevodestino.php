
<div class="modal fade bs-example-modal-lg" id="DestinoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Nuevo Destino</h4>
      </div>
      <div class="modal-body">
          <form id="form_nuevoDestino">
             
            <div class="row">     
             <div class="col-md-8">
                   <label for="nombredestino" class="control-label">Nombre del Destino:</label>
                   <input type="text" class="form-control" id="nombredestino" name="nombredestino" value=""  tabindex="1" size="30"/>
                    
             </div>
           </div>
           <div class="row">     
             <div class="col-md-6">
                   <label for="shipto" class="control-label">Nombre del destinatario:</label>
                  <input type="text" class="form-control" id="shipto" name="shipto" value=""  tabindex="1" size="30"/>
             </div>
           
             <div class="col-md-6">
                   <label for="shipto2" class="control-label">Apellido del destinatario:</label>
                   <input type="text" class="form-control" id="shipto2" name="shipto2" value="" tabindex="2" size="30"/>
             </div>
           </div>   
            <div class="row">     
             <div class="col-md-6">
                   <label for="direccion" class="control-label">Dirección:</label>
                   <input type="text" class="form-control" id="direccion" name="direccion" value="" tabindex="3" size="30" />
             </div>
             
             <div class="col-md-6">
                   <label for="direccion2" class="control-label">Dirección2:</label>
                   <input type="text" class="form-control" id="direccion2" name="direccion2" value="" tabindex="3" size="30" />
             </div>
           </div>
           <div class="row">     
             <div class="col-md-4">
                   <label for="ciudad" class="control-label">Ciudad:</label>
                   <input type="text" class="form-control" id="ciudad" name="ciudad" value="" tabindex="4"/>
             </div>
              <div class="col-md-4">
                   <label for="estado" class="control-label">Estado:</label>
                   <input type="text" class="form-control" id="estado" name="estado" value="" />
             </div>
             <div class="col-md-4">
                   <label for="pais" class="control-label">Pais:</label>
                    <select type="text" class="form-control" name="pais" id="pais" tabindex="20">
                    <?php 
                      //Consulto la bd para obtener solo los id de item existentes
                      $sql   = "SELECT * FROM tblpaises_destino";
                      $query = mysqli_query($link,$sql);
                        //Recorrer los iteme para mostrar
                      echo '<option value=""></option>'; 
                      while($row1 = mysqli_fetch_array($query)){
                            echo '<option value="'.$row1["codpais"].'">'.$row1["pais_dest"].'</option>'; 
                          }
                    ?>    
                </select>
             </div>
           </div>
          
           <div class="row">     
             <div class="col-md-6">
                   <label for="zip" class="control-label">Cod. Postal:</label>
                   <input type="text" class="form-control" id="zip" name="zip" value="" tabindex="6"/>
             </div>
             <div class="col-md-6">
                   <label for="estado" class="control-label">Teléfono:</label>
                   <input type="text" class="form-control" id="telefono" name="telefono" value="" tabindex="7"/>
             </div>
           </div>
           
           <div class="row">     
             <div class="col-md-12">
                   <label for="mail" class="control-label">Email:</label>
                   <input type="text" class="form-control" id="mail" name="mail" value=""tabindex="10" size="30" />
             </div>
           </div>
          
      </div>
     <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" id="insertar_destino" class="btn btn-primary">Insertar</button>
      </div>
    </div>
</form>
  </div>
</div>
