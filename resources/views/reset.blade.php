@section('content')
<script>
  $(document).ready(function() {
    $("#form_id-0").bind('submit', function (e) { //los filtros son propios de cada pantalla. hay que hacer una funcion par cada una
          e.preventDefault();
          var currentId=0;
          var metodo=$(this).attr('method');
          var thisUrl=$(this).attr('action');
          thisUrl+='/'+$('#cursoDestino').val();
          if ($('#alumnos').is(':checked')) {            thisUrl+='/1';         } else {            thisUrl+='/0';          }
          if ($('#docentes').is(':checked')) {            thisUrl+='/1';         } else {            thisUrl+='/0';          }
          if ($('#fechas').is(':checked')) {            thisUrl+='/1';         } else {            thisUrl+='/0';          }
          if ($('#rubricas').is(':checked')) {            thisUrl+='/1';         } else {            thisUrl+='/0';          }
          if ($('#cambiarCurso').is(':checked')) {            thisUrl+='/1';         } else {            thisUrl+='/0';          }

          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
          $.ajax({
              type: metodo,
              url: thisUrl,
              crossDomain: true,
              success: function (result,txt,code) { 
                $('#headerSaved'+currentId).show("slow").delay(2000).hide("slow");
                $('#lblCursoDestino').html("Curso a comenzar (actual "+$('#cursoDestino').val()+"):");
              },
              error: function (result,txt,code) { 
                $('#headerError'+currentId).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. '+ code+'</br>'+result.responseText.split(':')[1].replace('}','')+'</h3>');
                $('#headerError'+currentId).show("slow").delay(2000).hide("slow");
              },
          });
          return false;
    }); 
  })
</script>

<div class="container ">
    <h1 style='text-align: center;' >Reset de curso</h1>
      <div class="row justify-content-center">
          <div class=" mt-2 col-sm-9">
              <div class="card bg-danger sp-2" style='border-width: 5px'>
                  <div class="card-header bg-danger text-center"> <h3 style='color:yellow;'>Marque las acciones que quiere realizar.</h3>   </div>
                  <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error.</h3>   </div>

                  <div class="card-body bg-light " style='font-size: 2em;'>
                      <form id='form_id-0' name='form_id-0' method='GET' action="<?php echo($apiUrl); ?>reset/<?php echo($curso); ?>"> 
                        @csrf
                        <div class="form-group" hidden>
                          <input type="input" class="form-control" name='cursoActual' id="cursoActual" aria-describedby="cursoActual" form='form_id-0' maxlength="100" value="<?php echo($curso);?>">
                        </div>
                        <div class="form-row"  >
                          <label for="cursoDestino col-sm-8"  id='lblCursoDestino' >Curso a comenzar (actual {{$curso}}):</label>
                          <input type="number" id="cursoDestino" onKeyDown="return false" class="col-sm-3 ml-3 text-center"  name="cursoDestino" min="<?php echo($curso-20);?>" max="<?php echo($curso+20);?>" value="<?php echo($curso+1);?>" form='form_id-0'>
                        </div>

                        <div class="form-check  ml-2" >
                          <input class="form-check-input" type="checkbox" value="" id="alumnos" name="alumnos" form='form_id-0' checked>
                          <label class="form-check-label" for="alumnos">Baja alumn@s.</label>
                        </div>
                        <div class="form-check ml-2 " >
                          <input class="form-check-input" type="checkbox" value="" id="docentes" name="docentes" form='form_id-0' checked>
                          <label class="form-check-label" for="docentes">Baja docentes.</label>
                        </div>
                        <div class="form-check  ml-2" >
                          <input class="form-check-input" type="checkbox" value="" id="fechas" name="fechas"  form='form_id-0' checked>
                          <label class="form-check-label" for="fechas">Copiar fechas de curso a curso.</label>
                        </div>

                        <div class="form-check  ml-2" >
                          <input class="form-check-input" type="checkbox" value="" id="rubricas" name="rubricas" form='form_id-0' checked>
                          <label class="form-check-label" for="rubricas">Copiar rúbricas de curso a curso.</label>
                        </div>

                        <div class="form-check  ml-2" >
                          <input class="form-check-input" type="checkbox" value="" id="cambiarCurso" name="cambiarCurso" form='form_id-0' checked>
                          <label class="form-check-label" for="cambiarCurso">Cambiar curso actual.</label>
                        </div>
                        <hr/>
                        <div class="form-group text-center">
                          <button type="submit" class="btn btn-danger text-center" style='color:yellow;' form='form_id-0'><i class="material-icons mr-3" style="font-size:1em;color:yellow">send</i>Cambiar de curso.</button>         
                        </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
</div>
@section('content')

