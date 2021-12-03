@section('content')
<script> 
  var mensajeBorrar='MUY IMPORTANTE. CAMBIOS IRREVERSIBLES\n\nLas rúbricas pueden haber sido usados ya para evaluar algún proyecto por algún proyecto.\n\nSi elimina la rúbrica se pueden perder estas conexiones. \n\nSi no está seguro, no lo elimine y edítelo o cree un registro nuevo.\n\n¿está usted segur@?';

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
                console.log(txt);
                console.log(result);
                console.log(code);
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
                console.log(result);
                console.log(code);
                $('#headerError'+currentId).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. '+ code+'</br>'+result.responseText.split(':')[1].replace('}','')+'</h3>');
                $('#headerError'+currentId).show("slow").delay(4000).hide("slow");
              },
          });
          return false;
    }); 

    $("#form_filter").bind('submit', function (e) { //los filtros son propios de cada pantalla. hay que hacer una funcion par cada una
          e.preventDefault();
          var opcion1=$("#cursoFiltro").val();
          var opcion2=$("#cicloSel").val();
          var opcion3=$("#grupo_rubrica").val();
          var formularios=$("*[id^='form_id-']");
          
          formularios.each( function() {
            var currentId=$(this).attr('id').split('-')[1]; 
            var thisValue1=$('#curso--'+currentId).val();
            var thisValue2=$('#ciclo_id--'+currentId).val();
            var thisValue3=$('#grupo_rubrica_id--'+currentId).val();
            if(currentId>0) {
              if(opcion1==thisValue1 && (opcion2==thisValue2 || opcion2==0) && (opcion3==thisValue3 || opcion3==0)) {
                $('#full-'+currentId).show(1000);
              } else {
                $('#full-'+currentId).hide(1000);
              }
            }
          });
          return false;
    }); 
    $('#newGroup').click(function () {
        var result = window.prompt('Introduzca un nuevo grupo de rúbricas. \n\n\nSi no introduce nada se entiende que no quiere añadir un grupo de rubricas. ','');
        if (result!=null && result!='') {
          result = result.substring(0, 40);
          var thisUrl=$(this).attr('action');          
          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
          $.ajax({
              type: 'POST',
              url: thisUrl,
              data: {'grupo':result},
              crossDomain: true,
              success: function (result,txt,code) { 
                alert('Grupo añadido. La página recargará para poder usar el nuevo grupo.');
                new Promise(r => setTimeout(r, 1000));
                llamadaInicial();
              },
              error: function (result,txt,code) { 
                alert('Error al intentar agregar el grupo.');
              },
        });
      }
      return false;
    });

    function llamadaInicial() {
      rubricas({!!$curso!!});
    }


  })

</script>

<div class="container">
    <h1 style='text-align: center;' >Gestión de Rúbricas.</h1>
<!--Filtro-->

    <div class="card bg-secondary sp-2" id='CC' name="<?php echo($curso);?>" style='border-width: 5px'>
        <div class="card-body bg-light text-center" >
          <form id="form_filter" name="form_filter"> 
            <div class="form-row ">
              <div  class="form-group form-check-inline col-sm-9 mt-1 "  >
                <label for="cursoFiltro" class='col-sm-2' >Curso: </label>
                <input type="number" id="cursoFiltro" class='col-sm-1 pr-1' onKeyDown="return false"  name="cursoFiltro" value="<?php echo($curso);?>" form='form_filter'>
                <label for="cicloSel" class='col-sm-2' >Ciclo:</label>
                <select  id="cicloSel" name='cicloSel' form='form_filter' class='col-sm-3 mr-2'>
                    <option value='0' selected>Todos</option>
                    @if ($ciclos!=null)
                    @foreach ($ciclos as  $ci)
                      <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                    @endforeach
                    @endif
                </select>
                <label for="grupo_rubrica" class='class='col-sm-2 ' >Grupo:</label>
                <select  id="grupo_rubrica" name='grupo_rubrica' form='form_filter' class='col-sm-2 pr-1 '>
                    <option value='0' selected>Todos</option>
                    @if ($grupos!=null)
                    @foreach ($grupos as  $gr)
                      <option value="{{$gr['id']}}" >{{$gr['grupo']}}</option>
                    @endforeach
                    @endif
                </select>
              </div>
              <div class="form-check form-check-inline col-sm-2">
                <button type="submit" class="btn text-center " style="border:4px solid black;">
                  <i class="material-icons " style="font-size:35px;color:black; align-content: center">filter_alt</i></button>         
              </div>
            </div>
          </form>
        </div>
    </div>

<!--Formulario añadir-->
    <div class="row justify-content-center">
        <div class="col-sm-12 mt-2">
            <div class="card bg-secondary sp-2" style='border-width: 5px'>
                <button id='btn_add' class='adding' form='form_id-0'><div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i>   </div> </button>
                
                <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Rúbrica añadida.</h3>   </div>
                <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                <div name='add_body' id='add_body' class="card-body bg-light" style="display:none;">
                    <form id='form_id-0' name='form_id-0' method='POST' action="<?php echo($apiUrl); ?>rubricas">  
                      @csrf
                      <div class="form-group form-row"  >
                        <label for="curso" class='col-sm-1'>Curso: </label>
                        <input type="number" id="curso" name="curso" onKeyDown="return false"   class="col-sm-1 form-control text-center"   min="<?php echo($curso);?>"  value="<?php echo($curso);?>" form='form_id-0'>
                        <label for="ciclo_id" class='col-sm-1 offset-sm-1' >Ciclo:</label>
                        <select  id="ciclo_id" name='ciclo_id' form='form_id-0' class='form-control col-sm-8'>
                            @if ($ciclos!=null)
                            @foreach ($ciclos as  $ci)
                              <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                            @endforeach
                            @endif
                        </select>
                      </div>
                      <div class="form-group form-row">
                        <label for="grupo_rubrica_id" class='col-sm-2'>Grupo de Rúbricas:</label>
                        <select  id="grupo_rubrica_id" name='grupo_rubrica_id' form='form_id-0' class='col-sm-2'>
                            @if ($grupos!=null)
                            @foreach ($grupos as  $gr)
                              <option value="{{$gr['id']}}" >{{$gr['grupo']}}</option>
                            @endforeach
                            @endif
                        </select>
                        <button type="button" id='newGroup' class="btn btn-dark text-center col-sm-3" form='form_id-0' action="<?php echo($apiUrl); ?>grupo_rubricas" title='Pulse para agregar nuevos grupos de rúbricas.'><i class="material-icons" style="font-size:1em;color:white">add_circle</i> Agregar Grupo</button>                        
                        <label for="porcentaje" class='col-sm-2 offset-sm-1 ' >Valor de la Rúbrica (%):</label>
                        <input type="number" id="porcentaje" name="porcentaje"  title='introduzca un numero entre 0 y 100'  class="col-sm-1 text-center"   min="0" max="100" value="5.5" step="0.5" form='form_id-0'>
                        % 
                      </div>
                      <div class="form-group ">
                        <label for="rubrica" clas='col-form-label'>Nombre:</label>
                        <input type="input" class="form-control " name='rubrica' id="rubrica" aria-describedby="rubrica" placeholder="rubrica" form='form_id-0' maxlength="200" required>
                      </div>
                      <div class="form-group">
                        <label for="descExcelente">Descripción para Excelente:</label>
                        <textarea rows='1' class="form-control" id="descExcelente" name='descExcelente' placeholder="Describa el criterio para evaluar la rúbrica como Excelente" form='form_id-0' required></textarea>
                      </div>
                      <div class="form-group">
                        <label for="descBien">Descripción para Bien:</label>
                        <textarea rows='1' class="form-control" id="descBien" name='descBien' placeholder="Describa el criterio para evaluar la rúbrica como Bien" form='form_id-0' required></textarea>
                      </div>
                      <div class="form-group">
                        <label for="descRegular">Descripción para Regular:</label>
                        <textarea rows='1' class="form-control" id="descRegular" name='descRegular' placeholder="Describa el criterio para evaluar la rúbrica como Regular" form='form_id-0' required></textarea>
                      </div>
                      <div class="form-group">
                        <label for="descInsuficiente">Descripción para Insuficiente:</label>
                        <textarea rows='1' class="form-control" id="descInsuficiente" name='descInsuficiente' placeholder="Describa el criterio para evaluar la rúbrica como Insuficiente" form='form_id-0' required></textarea>
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
    @if ($rubricas!=null)
    @foreach ($rubricas as $it)
        <div class="row justify-content-center mt-2"  id="{{'full-'.$it['id']}}"     @if ($it["curso"]!=$curso )    style="display:none;"     @endif >
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-header bg-success text-center" id="{{'headerSaved'.$it['id']}}"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="{{'headerError'.$it['id']}}" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                    <div class="card-body bg-light">
                      <form id="{{'form_id-'.$it['id']}}" name="{{'form_id-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>rubricas/<?php echo($it['id']); ?>"> 
                          @csrf

                      <div class="form-group form-row"  >
                        <label for="curso--<?php echo ($it['id']); ?>" class='col-sm-1'>Curso: </label>
                        <input type="number" id="curso--<?php echo ($it['id']); ?>" name="curso" onKeyDown="return false"   class="col-sm-1 form-control text-center"   min="<?php echo($curso-20);?>" max="<?php echo($curso+20);?>" value="{{$it['curso']}}" form="{{'form_id-'.$it['id']}}" disabled>
                        <label for="ciclo_id--<?php echo ($it['id']); ?>" class='col-sm-1 offset-sm-1' >Ciclo:</label>
                        <select  id="ciclo_id--<?php echo ($it['id']); ?>" name='ciclo_id' form="{{'form_id-'.$it['id']}}" class='form-control col-sm-8' disabled>
                            @if ($ciclos!=null)
                            @foreach ($ciclos as  $ci)
                              @if ($ci['id']==$it['ciclo_id'])
                              <option value="{{$ci['id']}}" selected >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                              @else
                              <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                              @endif
                            @endforeach
                            @endif
                        </select>
                      </div>
                      <div class="form-group form-row">
                        <label for="grupo_rubrica_id--<?php echo ($it['id']); ?>" class='col-sm-2' >Grupo de Rúbricas:</label>
                        <select  id="grupo_rubrica_id--<?php echo ($it['id']); ?>" name='grupo_rubrica_id' form="{{'form_id-'.$it['id']}}" class='col-sm-2 form-control' disabled>
                            @if ($grupos!=null)
                            @foreach ($grupos as  $gr)
                              @if ($gr['id']==$it['grupo_rubrica_id'])
                                <option value="{{$gr['id']}}" selected>{{$gr['grupo']}}</option>
                              @else
                                <option value="{{$gr['id']}}" >{{$gr['grupo']}}</option>
                              @endif
                            @endforeach
                            @endif
                        </select>
                        <label for="porcentaje--<?php echo ($it['id']); ?>" class='col-sm-2 offset-sm-2 ' >Valor de la Rúbrica (%):</label>
                        <input type="number" id="porcentaje--<?php echo ($it['id']); ?>" name="porcentaje"    class="col-sm-1 form-control text-center"  title='introduzca un numero entre 0 y 100' min="0" max="100" value="{{$it['porcentaje']}}" step="0.5" form="{{'form_id-'.$it['id']}}" disabled>
                        %
                      </div>
                      <div class="form-group" >
                        <label for="rubrica--<?php echo ($it['id']); ?>" clas='col-form-label'>Nombre:</label>
                        <input type="input" class="form-control " name='rubrica' id="rubrica--<?php echo ($it['id']); ?>" aria-describedby="rubrica" placeholder="rubrica" form="{{'form_id-'.$it['id']}}" maxlength="200" value="{{$it['rubrica']}}" required disabled>
                      </div>
                      <div class="form-group">
                        <label for="descExcelente--<?php echo ($it['id']); ?>">Descripción para Excelente:</label>
                        <textarea rows='1' class="form-control" id="descExcelente--<?php echo ($it['id']); ?>" name='descExcelente' placeholder="Describa el criterio para evaluar la rúbrica como Excelente" form="{{'form_id-'.$it['id']}}" required disabled>{{$it['descExcelente']}}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="descBien--<?php echo ($it['id']); ?>">Descripción para Bien:</label>
                        <textarea rows='1' class="form-control" id="descBien--<?php echo ($it['id']); ?>" name='descBien' placeholder="Describa el criterio para evaluar la rúbrica como Bien" form="{{'form_id-'.$it['id']}}" required disabled>{{$it['descBien']}}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="descRegular--<?php echo ($it['id']); ?>">Descripción para Regular:</label>
                        <textarea rows='1' class="form-control" id="descRegular--<?php echo ($it['id']); ?>" name='descRegular' placeholder="Describa el criterio para evaluar la rúbrica como Regular" form="{{'form_id-'.$it['id']}}" required disabled>{{$it['descRegular']}}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="descInsuficiente--<?php echo ($it['id']); ?>">Descripción para Insuficiente:</label>
                        <textarea rows='1' class="form-control" id="descInsuficiente--<?php echo ($it['id']); ?>" name='descInsuficiente' placeholder="Describa el criterio para evaluar la rúbrica como Insuficiente" form="{{'form_id-'.$it['id']}}" required disabled>{{$it['descInsuficiente']}}</textarea>
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
                        <h2 style='text-align: center;'>No hay Rúbricas guardadas. </br></h2>
                    </div>
                </div>
            </div>
        </div>
    @endif    
</div>
@section('content')

