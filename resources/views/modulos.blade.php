@section('content')
<script> 
  var mensajeBorrar='MUY IMPORTANTE. CAMBIOS IRREVERSIBLES\n\nLos módulos pueden haber sido asignados a algún ciclo y portanto haber sido asignadas a alguna matrícula de alumn@ o asignado a algún docente para su impartición.\n\nSi elimina el módulo se pueden perder estas conexiones. \n\nSi no está seguro, no lo elimine y edítelo o cree un registro nuevo.\n\n¿está usted segur@?';

  $(document).ready(function() {

    $(".truefalse").change(function() {
        if ($(this).is(':checked')) {
          $(this).attr('value', '1');
        } else {
          $(this).attr('value', '0');
        }
    });

    $("[id^='btn_edit-']").click(function() {
      var currentId=this.id.split('-')[1]; //Obtenemos el ID.
      $("[id$=--"+currentId+"]").prop('disabled',false); //Activamos los campos para edición.
      $('#btn_submit-'+currentId).prop('hidden',false);
      $('#btn_edit-'+currentId).prop('hidden',true);
    });

    $("#btn_add").click(function () { //muestra - oculta el formulario de añadir
        
        if ($(this).hasClass("adding")){
            $("#add_body").show('slow');
            $(this).html('<div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">remove_circle_outline</i> </h3>  </div> ');
           $(this).toggleClass("adding notadding");
        } else {
           $("#add_body").hide('slow');
           $(this).html('<div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i> </h3>  </div> ');
           $(this).toggleClass("notadding adding");   
        }
        return false;
    });

    $("[id^='btn_clear-']").click(function() {
      var currentId=$(this).attr('id').split('-')[1]; //Obtenemos el ID.
      var deleteUrl=$('#form_id-'+currentId).attr('action');
      var metodo='DELETE'
      if (typeof mensajeBorrar=='undefined' || mensajeBorrar=='' || confirm(mensajeBorrar)) {
        $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
        $.ajax({
            type: metodo,
            url: deleteUrl,
            crossDomain: true,
            success: function (result,txt,code) { 
              $('#headerSaved'+currentId).show("slow");
              $('#full-'+currentId).attr('id','deleted-'+currentId);
              $('#deleted-'+currentId).delay(2000).hide('slow');
            },
            error: function (result,txt,code) { 
              $('#headerError'+currentId).show("slow").delay(4000).hide("slow");
            },
        });
      }
    });

    $("[id^='form_id-']").bind('submit', function (e) {
          var currentId=$(this).attr('name').split('-')[1]; //Obtenemos el ID.
          var thisUrl=$(this).attr('action');
          var metodo=$(this).attr('method');
          e.preventDefault();
          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
          $.ajax({
              type: metodo,
              url: thisUrl,
              data: $(this).serialize(),
              crossDomain: true,
              success: function (result,txt,code) { 
                $('#headerSaved'+currentId).show("slow").delay(2000).hide("slow");
                if (currentId>0) {
                  $("[id$=--"+currentId+"]").prop('disabled',true); //Activamos los campos para edición.
                  $('#btn_submit-'+currentId).prop('hidden',true);
                  $('#btn_edit-'+currentId).prop('hidden',false);
                  $('#form_filter').trigger('submit'); //Actualizamos el filtro.
                }  else {
                  $("#btn_add").trigger('click');
                  new Promise(r => setTimeout(r, 1000));
                  llamadaInicial();
                }
              },
              error: function (result,txt,code) { 
                console.log(txt);
                $('#headerError'+currentId).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. '+ code+'</br>'+result.responseText.split(':')[1].replace('}','')+'</h3>');
                $('#headerError'+currentId).show("slow").delay(4000).hide("slow");
              },
          });
          return false;
    }); 

    $("#form_filter").bind('submit', function (e) { //los filtros son propios de cada pantalla. hay que hacer una funcion par cada una
          e.preventDefault();
          return false;
    }); 

    function llamadaInicial() {
      modulos();
    }


  })

</script>

<div class="container">
    <h1 style='text-align: center;' >Gestión de Módulos.</h1>

<!--Formulario añadir-->
    <div class="row justify-content-center">
        <div class="col-sm-12 mt-2">
            <div class="card bg-secondary sp-2" style='border-width: 5px'>
                <button id='btn_add'  class='adding' form='form_id-0'><div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i>   </div> </button>
                
                <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Módulo añadido.</h3>   </div>
                <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                <div name='add_body' id='add_body' class="card-body bg-light" style="display:none;">
                    <form id='form_id-0' name='form_id-0' method='POST' action="<?php echo($apiUrl); ?>modulos">  
                      @csrf
                      <div class="form-row"  >
                        <label for="codigoModulo" class='col-sm-1'>Codigo:</label>
                        <input type="input" class="form-control col-sm-2" name='codigoModulo' id="codigoModulo" aria-describedby="codigoModulo" placeholder="Código del Módulo" form='form_id-0' maxlength="10" required>
                        <label for="nombreModulo" class='col-sm-1 offset-sm-1'>Nombre:</label>
                        <input type="input" class="form-control col-sm-7" name='nombreModulo' id="nombreModulo" aria-describedby="nombre" placeholder="Nombre del Módulo" form='form_id-0' maxlength="200" required>
                      </div>
                      <div class='form-row mt-3'>
                        <div class="form-group form-inline col-sm-6"  >
                          <div class="form-check form-check-inline ml-5">
                            <input class="form-check-input" type="radio" form='form_id-0' name="tiene_UC" id="tiene_UC1" value="1" checked>
                            <label class="form-check-label mr-1" for="tiene_UC1">Tiene UC</label>
                          </div>
                          <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input mr-2 ml-2" form='form_id-0' type="radio" name="tiene_UC" id="tiene_UC2" value="0">
                            <label class="form-check-label mr-5" for="tiene_UC2">No Tiene UC (Unidad de Competencia) </label>
                          </div>
                        </div>
                        <div class="form-group form-inline col-sm-6"  >
                          <div class="form-check form-check-inline ml-5">
                            <input class="form-check-input mr-2 ml-2" form='form_id-0' type="radio" name="curso" id="curso1" value="1" checked>
                            <label class="form-check-label mr-2" for="curso1">1º</label>
                          </div>
                          <div class="form-check form-check-inline ml-2">
                            <input class="form-check-input mr-2 ml-2" form='form_id-0' type="radio" name="curso" id="curso2" value="2" >
                            <label class="form-check-label mr-2" for="curso2">2º</label>
                          </div>
                        </div>
                      </div>
                      <hr/>
                      <div class="form-group text-center">
                        <button type="submit" class="btn btn-dark text-center" form='form_id-0'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>         
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  @if($modulos!=null)
    @foreach ($modulos as $it)
        <div class="row justify-content-center mt-2"  id="{{'full-'.$it['id']}}"  >
            <div class="col-sm-12 ">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-header bg-success text-center" id="{{'headerSaved'.$it['id']}}"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="{{'headerError'.$it['id']}}" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                    <div class="card-body bg-light">
                      <form id="{{'form_id-'.$it['id']}}" name="{{'form_id-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>modulos/<?php echo($it['id']); ?>"> 
                        @csrf
                        <div class="form-row"  >
                          <label for="codigoModulo--<?php echo ($it['id']); ?>" class='col-sm-1'>Codigo:</label>
                          <input type="input" class="form-control col-sm-2" name='codigoModulo' id="codigoModulo--<?php echo ($it['id']); ?>" aria-describedby="codigoModulo" placeholder="Código del Módulo" form="{{'form_id-'.$it['id']}}" maxlength="10"  value="{{$it['codigoModulo']}}" required disabled>
                          <label for="nombreModulo--<?php echo ($it['id']); ?>" class='col-sm-1 offset-sm-1'>Nombre:</label>
                          <input type="input" class="form-control col-sm-7" name='nombreModulo' id="nombreModulo--<?php echo ($it['id']); ?>" aria-describedby="nombre" placeholder="Nombre del Módulo" form="{{'form_id-'.$it['id']}}" maxlength="200" value="{{$it['nombreModulo']}}" required disabled>
                        </div>
                        <div class='form-row mt-3'>
                          <div class="form-group form-inline col-sm-6"  >
                            <div class="form-check form-check-inline ml-5">
                              <input class="form-check-input"  form="{{'form_id-'.$it['id']}}" type="radio" name="tiene_UC" id="tiene_UC1--<?php echo ($it['id']); ?>" value="1" 
                              <?php echo($it['tiene_UC']=="1"? 'checked' : '') ?> disabled>
                              <label class="form-check-label mr-1" for="tiene_UC1--<?php echo ($it['id']); ?>">Tiene UC</label>
                            </div>
                            <div class="form-check form-check-inline ml-2">
                              <input class="form-check-input mr-2 ml-2" form="{{'form_id-'.$it['id']}}" type="radio" name="tiene_UC" id="tiene_UC2--<?php echo ($it['id']); ?>" value="0" <?php echo($it['tiene_UC']=="0"? 'checked' : '') ?> disabled>
                              <label class="form-check-label mr-5" for="tiene_UC2--<?php echo ($it['id']); ?>">No Tiene UC (Unidad de Competencia) </label>
                            </div>
                          </div>
                          <div class="form-group form-inline col-sm-6"  >
                            <div class="form-check form-check-inline ml-5">
                              <input class="form-check-input mr-2 ml-2" form="{{'form_id-'.$it['id']}}" type="radio" name="curso" id="curso1--<?php echo ($it['id']); ?>" value="1" <?php echo($it['curso']=="1"? 'checked' : '') ?> disabled>
                              <label class="form-check-label mr-2" for="curso1--<?php echo ($it['id']); ?>">1º</label>
                            </div>
                            <div class="form-check form-check-inline ml-2">
                              <input class="form-check-input mr-2 ml-2" form="{{'form_id-'.$it['id']}}" type="radio" name="curso" id="curso2--<?php echo ($it['id']); ?>" value="2" <?php echo($it['curso']=="2"? 'checked' : '') ?> disabled>
                              <label class="form-check-label mr-2" for="curso2--<?php echo ($it['id']); ?>">2º</label>
                            </div>
                          </div>
                        </div>
                        <hr/>
                        <div class="form-group text-center">
                          <button type="button" id="btn_edit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" ><i class="material-icons mr-3" style="font-size:1em;color:white">edit</i>Editar.</button>
                          <button type="submit" id="btn_submit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" hidden><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>   
                          <button type="button" id="btn_clear-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" ><i class="material-icons mr-3" style="font-size:1em;color:white">clear</i>Eliminar.</button>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @else
        <div class="row justify-content-center">
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-body bg-light">
                        <h2 style='text-align: center;'>No hay Módulos guardados. </br></h2>
                    </div>
                </div>
            </div>
        </div>
    @endif    
</div>
@section('content')


