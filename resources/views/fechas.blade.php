@section('content')
<script> 
  var mensajeBorrar=''; //Por ahora no se crea mensaje antes de borrar.

  $(document).ready(function() {

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
console.log($(this).serialize());
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
          var opcion=$("#cursoFiltro").val();
          var formularios=$("*[id^='form_id-']");
          formularios.each( function() {
            var currentId=$(this).attr('id').split('-')[1]; 
            var thisValue=$('#curso--'+currentId).val();
            //console.log($("#CC").attr('name'));
            if(currentId>0) {
              if(opcion==thisValue) {
                $('#full-'+currentId).show(1000);
              } else {
                $('#full-'+currentId).hide(1000);
              }
            }
          });
          return false;
    }); 

  function llamadaInicial() {
    fechas({!!$curso!!});
  }


  })

</script>

<div class="container">
    <h1 style='text-align: center;' >Gestión de Fechas.</h1>
<!--Filtro-->
    <div class="card bg-secondary sp-2" style='border-width: 5px'>
        <div class="card-body bg-light text-center" style='font-size: 2em;'>
          <form id="form_filter" name="form_filter"  > 
            <div class='form-row'>
              <div class="form-group form-check-inline col-sm-9 "  >
                <label for="cursoFiltro" >Curso: </label>
                <input type="number" id="cursoFiltro" onKeyDown="return false" class="col-sm-3 text-center"  name="cursoFiltro"  value="<?php echo($curso);?>" form='form_filter'>
              </div>
              <div class="form-check form-check-inline col-sm-2 ">
                <button type="submit" class="btn text-center"  style="border:4px solid black;" >
                  <i class="material-icons " title='Filtrar resultados'  style="font-size:2em; color:black;  align-content: center">filter_alt</i></button>         
              </div>
            </div>
          </form>
        </div>
    </div>

<!--Formulario añadir-->
    <div class="row justify-content-center">
        <div class="col-sm-12 mt-2">
            <div class="card bg-secondary sp-2" style='border-width: 5px'>
                <button id='btn_add' class='adding' for='form_id-0'><div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i> </h3>  </div> </button>
                
                <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Fecha añadida.</h3>   </div>
                <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                <div name='add_body' id='add_body' class="card-body bg-light" style="display:none;"> 
                    <form id='form_id-0' name='form_id-0' method='POST' action="<?php echo($apiUrl); ?>fechas">  
                      @csrf
                      <div class='form-group'>
                        <div class="  form-row">
                          <label for="curso" class='col-sm-2'>Curso: </label>
                          <label for="fechaLimite" class='col-sm-3 offset-sm-1 '>Fecha límite:</label>
                          <label for="enviar" class='col-sm-2 justify-content-center'>Enviar Mensaje:</label>
                          <label for="diasParaAviso" class='col-sm-3 ' >Días para aviso:</label>
                        </div>

                        <div class=" form-row">
                          <input type="number" id="curso" name="curso" onKeyDown="return false"   class="col-sm-2  text-center form-control"   min="<?php echo($curso);?>"  value="<?php echo($curso);?>" form='form_id-0'>
                          <input type="date" class="form-control  col-sm-2 text-center offset-sm-1" name='fechaLimite' id="fechaLimite" aria-describedby="fechaLimite" form='form_id-0'  required>
                          <div class='form-inline col-sm-3 justify-content-center'>
                            <input class="form-check-input " type="radio" name="enviar" id="enviar1" value="1" form='form_id-0' checked>
                            <label class="form-check-label " for="enviar1">Si</label>
                            <input class="form-check-input ml-3" type="radio" name="enviar" id="enviar2" value="0" form='form_id-0'>
                            <label class="form-check-label " for="enviar2">No</label>
                          </div>
                          <input type="number" id="diasParaAviso" name="diasParaAviso" onKeyDown="return false" class="col-sm-1 text-center"   min="1>" max="60"  form='form_id-0' value="7" >

                        </div>
                      </div>
                      <div class="form-group form-row">
                        <label for="nombre" class='col-sm-2'>Nombre:</label>
                        <input type="input" class="form-control col-sm-10" name='nombre' id="nombre" aria-describedby="nombre" placeholder="Nombre de la fecha" form='form_id-0' maxlength="100" required>
                      </div>
                      <div class="form-group">
                        <label for="descripcion">Descripción de la fecha:</label>
                        <textarea rows='2' class="form-control" id="descripcion" name='descripcion' placeholder="Describa la fecha (este será el mensaje que recibirán l@s alumn@s si es enviable)" form='form_id-0' required></textarea>
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
    @if($fechas!=null)
    @foreach ($fechas as $it)
        <div class="row justify-content-center mt-1"  id="{{'full-'.$it['id']}}"    @if ($it["curso"]!=$curso)    style="display:none;"     @endif >
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-header bg-success text-center" id="{{'headerSaved'.$it['id']}}"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="{{'headerError'.$it['id']}}" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                    <div class="card-body bg-light">
                      <form id="{{'form_id-'.$it['id']}}" name="{{'form_id-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>fechas/<?php echo($it['id']); ?>"> 
                          @csrf

                        <div class='form-group'>
                          <div class="  form-row">
                            <label for="curso--<?php echo ($it['id']); ?>" class='col-sm-2'>Curso: </label>
                            <label for="fechaLimite--<?php echo ($it['id']); ?>" class='col-sm-3 offset-sm-1'>Fecha límite:</label>
                            <label for="enviar--<?php echo ($it['id']); ?>" class='col-sm-2 justify-content-center'>Enviar Mensaje:</label>
                            <label for="diasParaAviso--<?php echo ($it['id']); ?>" class='col-sm-3 ' >Días para aviso:</label>
                          </div>

                          <div class=" form-row">
                            <input type="number" id="curso--<?php echo ($it['id']); ?>" name="curso" onKeyDown="return false" class="col-sm-2 text-center"  min="<?php echo($curso-20);?>" max="<?php echo($curso+20);?>"  form="{{'form_id-'.$it['id']}}" value="{{$it['curso']}}" disabled>
                            <input type="date" class="form-control col-sm-2 offset-sm-1 text-center" name='fechaLimite' id="fechaLimite--<?php echo ($it['id']); ?>" aria-describedby="fechaLimite" form="{{'form_id-'.$it['id']}}"  value="{{$it['fechaLimite']}}" disabled required>
                            <div class='form-inline col-sm-3 justify-content-center'>
                              <input class="form-check-input " type="radio" name="enviar" id="enviar1--<?php echo ($it['id']); ?>" value="1" form="{{'form_id-'.$it['id']}}"  disabled <?php echo($it['enviar']? 'checked' : '') ?> >
                              <label class="form-check-label " for="enviar1--<?php echo ($it['id']); ?>">Si</label>
                              <input class="form-check-input ml-3" type="radio" name="enviar" id="enviar2--<?php echo ($it['id']); ?>" value="0" form="{{'form_id-'.$it['id']}}" disabled <?php echo(!$it['enviar']? 'checked' : '') ?> >
                              <label class="form-check-label " for="enviar2--<?php echo ($it['id']); ?>">No</label>
                            </div>
                            <input type="number" id="diasParaAviso--<?php echo ($it['id']); ?>" name="diasParaAviso" onKeyDown="return false" class="col-sm-1 text-center"   min="1>" max="60"  form="{{'form_id-'.$it['id']}}" value="{{$it['diasParaAviso']}}" disabled>
                          </div>
                        </div>
                        <div class="form-group ">
                          <label for="nombre--<?php echo ($it['id']); ?>" class='col-sm-2'>Nombre:</label>
                          <input type="input" class="form-control co-sm-10" name='nombre' id="nombre--<?php echo ($it['id']); ?>" aria-describedby="nombre" placeholder="Nombre de la fecha" form="{{'form_id-'.$it['id']}}" maxlength="100" value="{{$it['nombre']}}" disabled required>
                        </div>
                        <div class="form-group">
                          <label for="descripcion--<?php echo ($it['id']); ?>">Descripción de la fecha:</label>
                          <textarea rows='2' class="form-control" id="descripcion--<?php echo ($it['id']); ?>" name='descripcion' placeholder="Describa la fecha (este será el mensaje que recibirán l@s alumn@s si es enviable)" form="{{'form_id-'.$it['id']}}"  disabled required>{{$it['descripcion']}} </textarea>
                        </div>

                        @if ($curso<=$it['curso'])
                        <hr/>
                        <div class="form-group text-center">
                          <button type="button" id="btn_edit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" ><i class="material-icons mr-3" style="font-size:1em;color:white">edit</i>Editar.</button>
                          <button type="submit" id="btn_submit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" hidden><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>  
                          <button type="button" id="btn_clear-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" ><i class="material-icons mr-3" style="font-size:1em;color:white">clear</i>Eliminar.</button>

                        </div>
                        @endif


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
                        <h2 style='text-align: center;'>No hay Fechas guardadas. </br></h2>
                    </div>
                </div>
            </div>
        </div>
    @endif    
</div>
@section('content')

