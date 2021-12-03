@section('content')
<script>
  $(document).ready(function() {

    $("[id^='btn_edit-']").click(function() {
      var currentId=this.id.split('-')[1]; //Obtenemos el ID.
      $("[id$=--"+currentId+"]").prop('disabled',false); //Activamos los campos para edición.
      $('#btn_submit-'+currentId).prop('hidden',false);
      $('#btn_edit-'+currentId).prop('hidden',true);
    });

    $("[id^='btn_leer-']").click(function() {
      var currentId=$(this).attr('id').split('-')[1]; //Obtenemos el ID.
      if ($("#leido-"+currentId).attr('value')=='0') {
        $("#leido-"+currentId).attr('value','1');
        $(this).html('<i class="material-icons mr-3" style="font-size:1em;color:white">task_alt</i>Marcar como no leido.');
      } else {
        $("#leido-"+currentId).attr('value','0');
        $(this).html('<i class="material-icons mr-3" style="font-size:1em;color:white">cancel</i>Marcar como leido.');
      }
      $('#btn_submit-'+currentId).trigger('submit');
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
          var opcion=$("input[name='filtroleido']:checked").val();
          var formularios=$("*[id^='form_id-']");
          formularios.each( function() {
            var currentId=$(this).attr('id').split('-')[1]; 
            if(currentId>0) {
              if (opcion==0) {
                if ($('#leido--'+currentId).attr('value')=='0') { //registro actual no leido
                  $('#full-'+currentId).show(1000);
                } else { //registro actual leido
                  $('#full-'+currentId).hide(1000);
                }
              } else if (opcion==1) {
                if ($('#leido--'+currentId).attr('value')=='1') { //registro actual no leido
                  $('#full-'+currentId).show(1000);
                } else { //registro actual leido
                  $('#full-'+currentId).hide(1000);
                }
              } else  {
                $('#full-'+currentId).show(1000);
              }
            }
          });
          return false;
    }); 
  })


</script>

<div class="container">
    <h1 style='text-align: center;' >Propuestas de Proyectos  </h1>
    @if ($mode=='add') <!--Mostramos form para rellenar y guardar propuesta -->
        <div class="row justify-content-center">
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'> 
                    <div class="card-header bg-secondary text-center"> <h3 style='color:white;'>Rellene los campos siguientes para enviar su sugerencia de proyecto.</h3>   </div>
                    <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Sugerencia enviada, Muchas gracias por su colaboración.</h3>   </div>
                    <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                    <div class="card-body bg-light">
                        <form id='form_id-0' name='form_id-0' method='POST' action="<?php echo($apiUrl); ?>proyectos_propuestos">  
                            <!--no se envia el codigociclo porque el user puede cambiarlo. En edit lo que se enviará es el id de la propuesta-->
                          @csrf
                          <div class="form-row pb-1">
                            <label for="nombre" class='col-sm-2'>Nombre:</label>
                            <input type="input" class="col-sm-10 form-control" name='nombre' id="nombre" aria-describedby="nombre" placeholder="Introduzca su nombre (opcional)" form='form_id-0' maxlength="100">
                          </div>
                          <div class="form-row pb-1">
                            <label for="email" class='col-sm-2'>Dirección email:</label>
                            <input type="email" class="form-control col-sm-10" id="email" name='email' aria-describedby="email" placeholder="Introduzca correo electrónico" maxlength="50" form='form_id-0' required > 
                            <!--<small id="email" class="form-text text-muted">Introduzca una dirección válida de correo por si necesitaramos ponernos en contacto con usted.</small>-->
                          </div>
                          <div class="form-row pb-1">
                            <label for="ciclo_id" class='col-sm-2'>Ciclo:</label>
                            <select class="form-control col-sm-10" id="ciclo_id" name='ciclo_id' form='form_id-0'>
                                @if ($ciclos!=null)
                                @foreach ($ciclos as $ci)
                                    @if ($ci['id']==$ciclo_id)
                                      <option value="{{$ci['id']}}" selected>{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                                    @else
                                      <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                                    @endif
                                @endforeach
                                @endif
                            </select>
                          </div>
                          <div class="form-group pb-1">
                            <label for="descripcion">Descripción de la propuesta:</label>
                            <textarea rows='5' class="form-control" id="propuesta" name='propuesta' placeholder="Describa el proyecto" form='form_id-0' required></textarea>
                          </div>
                          <div class="form-check" hidden>   <input type="hidden" value="0" id="leido" name='leido' form='form_id-0' >    </div>
                          <hr/>
                          <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark text-center" form='form_id-0'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>         
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else 
      <div class="card bg-secondary sp-2" style='border-width: 5px'>
          <div class="card-body bg-light text-center">
            <form id="form_filter" name="form_filter" > 
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filtroleido" id="filtroleido1" value="1">
                <label class="form-check-label" for="filtroleido1">Leidos</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filtroleido" id="filtroleido2" value="0">
                <label class="form-check-label" for="filtroleido2">No Leidos</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filtroleido" id="filtroleido3" value="-1" checked>
                <label class="form-check-label" for="filtroleido3">Todos</label>
              </div>
              <div class="form-check form-check-inline ">
                <button type="submit" class="btn text-center"  style="border:4px solid black;" >
                  <i class="material-icons " title='Filtrar resultados' style="font-size:2em; color:black;  align-content: center">filter_alt</i></button>         
              </div>
            </form>
          </div>
      </div>
      @if ($propuestas!=null)
      @foreach ($propuestas as $it)
        <div class="row justify-content-center"  id="{{'full-'.$it['id']}}">
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-header bg-success text-center" id="{{'headerSaved'.$it['id']}}"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="{{'headerError'.$it['id']}}" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>
                    <div class="card-body bg-light">
                      <form id="{{'form_id-'.$it['id']}}" name="{{'form_id-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>proyectos_propuestos/<?php echo($it['id']); ?>"> 
                          @csrf
                          <div class="form-group row">
                            <label for="nombre--<?php echo ($it['id']); ?>"class='col-sm-2'>Nombre:</label>
                            <input type="input" class="col-sm-10" name='nombre' id="nombre--<?php echo ($it['id']); ?>" aria-describedby="nombre" form="{{'form_id-'.$it['id']}}" maxlength="100"  value="{{$it['nombre']}}" disabled > 
                          </div>
                          <div class="form-group row">
                            <label for="email--<?php echo ($it['id']); ?>" class='col-sm-2'>Correo electrónico:</label>
                            <input type="email" class="col-sm-10" id="email--<?php echo ($it['id']); ?>" name='email' aria-describedby="email"  maxlength="50" form="{{'form_id-'.$it['id']}}" required value="{{$it['email']}}" disabled > 
                          </div>
                          <div class="form-group row">
                            <label for="ciclo_id--<?php echo ($it['id']); ?>" class='col-sm-2'>Ciclo:</label>
                            <select class=" col-sm-10" id="ciclo_id--<?php echo ($it['id']); ?>" name='ciclo_id' form="{{'form_id-'.$it['id']}}" disabled>
                                @if($ciclos!=null)
                                @foreach ($ciclos as $ci)
                                    @if ($ci['id']==$it['ciclo_id'])
                                      <option value="{{$ci['id']}}" selected>{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                                    @else
                                      <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                                    @endif
                                @endforeach
                                @endif
                            </select>
                          </div>
                          <div class="form-group">
                            <label for="descripcion--<?php echo ($it['id']); ?>">Descripción de la propuesta:</label>
                            <textarea rows='2' class="form-control form-control-sm" id="propuesta--<?php echo ($it['id']); ?>" name='propuesta'  form="{{'form_id-'.$it['id']}}" required disabled>{{$it['propuesta']}} </textarea>
                          </div>
                          <div class="form-check" hidden>
                            @if ($it['leido']==0)
                             <input  type="hidden" value='0' id="leido-<?php echo ($it['id']); ?>" name='leido' form="{{'form_id-'.$it['id']}}" >
                            @else
                              <input  type="hidden" value='1' id="leido-<?php echo ($it['id']); ?>" name='leido' form="{{'form_id-'.$it['id']}}" checked  >
                            @endif
                          </div>
                          <hr/>
                          <div class="form-group text-center">
                            <button type="submit" id="btn_submit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" hidden><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>    
                            <button type="button" id="btn_edit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" ><i class="material-icons mr-3" style="font-size:1em;color:white">edit</i>Editar.</button>         
                            @if ($it['leido']==0)     
                              <button type="button" class="btn btn-dark mr-3 ml-3" form="{{'form_id-'.$it['id']}}" id="btn_leer-<?php echo ($it['id']); ?>" ><i class="material-icons mr-3" style="font-size:1em;color:white">cancel</i>Marcar como leido.</button>     
                            @else
                              <button type="button" class="btn btn-dark mr-3 ml-3" form="{{'form_id-'.$it['id']}}" id="btn_leer-<?php echo ($it['id']); ?>" ><i class="material-icons mr-3" style="font-size:1em;color:white">task_alt</i>Marcar como no leido.</button>     
                            @endif
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
                          <h2 style='text-align: center;'>No hay Propuestas guardadas. </br></h2>
                      </div>
                  </div>
              </div>
          </div>
      @endif    

    @endif
</div>
@section('content')

