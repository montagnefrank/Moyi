<div class="modal fade bs-example-modal-lg" id="ClienteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Nuevo Cliente</h4>
      </div>
      <div class="modal-body">
          <form id="form_nuevoCliente">
             <div class="row">     
                <div class="col-md-6">
                      <label for="empresa" class="control-label">Nombre:</label>
                      <input type="text" class="form-control" id="empresa" name="empresa" value=""  tabindex="1" size="30"/>

                </div>
                 <div class="col-md-6">
                      <label for="empresa2" class="control-label">Apellido:</label>
                      <input type="text" class="form-control" id="empresa2" name="empresa2" value=""  tabindex="1" size="30"/>

                </div>
            </div>
            <div class="row">     
                <div class="col-md-6">
                      <label for="direccion" class="control-label">Dirección:</label>
                      <input type="text" class="form-control" id="direccion" name="direccion" value=""  tabindex="1" size="30"/>

                </div>
                 <div class="col-md-6">
                      <label for="direccion2" class="control-label">Direccion2:</label>
                      <input type="text" class="form-control" id="direccion2" name="direccion2" value=""  tabindex="1" size="30"/>

                </div>
            </div>  
            <div class="row">     
                <div class="col-md-6">
                      <label for="ciudad" class="control-label">Ciudad:</label>
                      <input type="text" class="form-control" id="ciudad" name="ciudad" value=""  tabindex="1" size="30"/>

                </div>
                 <div class="col-md-6">
                      <label for="estado" class="control-label">Estado:</label>
                      <input type="text" class="form-control" id="estado" name="estado" value=""  tabindex="1" size="30"/>

                </div>
            </div>  	            
            <div class="row">     
                <div class="col-md-6">
                      <label for="zip" class="control-label">Zip:</label>
                      <input type="text" class="form-control" id="zip" name="zip" value=""  tabindex="1" size="30"/>

                </div>
                 <div class="col-md-6">
                      <label for="pais" class="control-label">Pais:</label>
                      <select type="text" class="form-control" name="pais" id="pais" >
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
                      <label for="telefono" class="control-label">Teléfono:</label>
                      <input type="text" class="form-control" id="telefono" name="telefono" value=""  tabindex="1" size="30"/>

                </div>
                 <div class="col-md-6">
                      <label for="vendedor" class="control-label">Vendedor Asignado:</label>
                      <select type="text" class="form-control" name="vendedor" id="vendedor" >
                    <?php 
                      //Consulto la bd para obtener solo los id de item existentes
                      $sql   = "SELECT id_usuario,cpnombre FROM tblusuario";
                      $query = mysqli_query($link,$sql);
                        //Recorrer los iteme para mostrar
                      echo '<option value=""></option>'; 
                      while($row1 = mysqli_fetch_array($query)){
                            echo '<option value="'.$row1["id_usuario"].'">'.$row1["cpnombre"].'</option>'; 
                          }
                    ?>    
                   </select>

                </div> 
            </div>
            <div class="row">     
                <div class="col-md-12">
                      <label for="mail" class="control-label">E-Mail:</label>
                      <input type="email" class="form-control" id="mail" name="mail" value="" />

                </div>
            </div>
         
          
      </div>
     <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" id="insertar_cliente" class="btn btn-primary">Insertar</button>
      </div>
    </div>
</form>
  </div>
</div>