@section('content')
<script> 
  var mensajeBorrar='MUY IMPORTANTE. CAMBIOS IRREVERSIBLES\n\nLas combinaciones de ciclo y módulo pueden haber sido usadas en alguna matrícula de alumn@s o haber sido asignada a algún docente para su impartición.\n\nSi elimina esta combinación de ciclo y módulo se pueden perder estas conexiones. \n\nSi no está seguro, no lo elimine y edítelo o cree un registro nuevo.\n\n¿está usted segur@?';
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
          var opcion=$("#cicloSel").val();
          var formularios=$("*[id^='form_id-']");
          formularios.each( function() {
            var currentId=$(this).attr('id').split('-')[1]; 
            var thisValue=$('#ciclo_id--'+currentId).val();
            if(currentId>0) {
              if(opcion==$('#ciclo_id--'+currentId).val()) {
                $('#full-'+currentId).show(1000);
              } else {
                $('#full-'+currentId).hide(1000);
              }
            }
          });
          return false;
    }); 

  function llamadaInicial() {
    ciclos_modulos();
  }


  });

</script>

<div class="container">
    <h1 style='text-align: center;' >Gestión de Módulos de cada Ciclo.</h1>
<!--Filtro-->
    <div class="card bg-secondary sp-2" style='border-width: 5px'>
        <div class="card-body bg-light text-center" >
          <form id="form_filter" name="form_filter" > 
            <div class="form-row ">
              <div  class="justify-content-center form-row col-sm-9 "  >
                <label for="cicloSel" class='col-sm-3' >Ciclo:</label>
                <select  id="cicloSel" name='cicloSel' form='form_filter' class='col-sm-8 form-control'>
                    @foreach ($ciclos as  $ci)
                      <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group justify-content-center col-sm-3">
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
                <button id='btn_add'  class='adding' form='form_id-0'><div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i>   </div> </button>
                
                <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Ciclo-Módulo añadido.</h3>   </div>
                <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                <div name='add_body' id='add_body' class="card-body bg-light" style="display:none;">
                    <form id='form_id-0' name='form_id-0' method='POST' action="<?php echo($apiUrl); ?>ciclo_modulos">  
                      @csrf
                      <div class="form-row " >
                        <label for="ciclo_id"  class="col-sm-1">Ciclo:</label>
                        <select  id="ciclo_id" name='ciclo_id' form='form_id-0' class='col-sm-4'>
                            @if($ciclos!=null)
                            @foreach ($ciclos as  $ci)
                              <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                            @endforeach
                            @endif
                        </select>
                        <label for="modulo_id" class="col-sm-1 offset-sm-1">Módulo:</label>
                        <select  id="modulo_id" name='modulo_id' form='form_id-0' class='col-sm-5'>
                            @if ($modulos!=null)
                            @foreach ($modulos as  $mo)
                              <option value="{{$mo['id']}}" >{{$mo['codigoModulo'].' - '.$mo['nombreModulo']}}</option>
                            @endforeach
                            @endif
                        </select>
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
    @if ($ciclos!=null)
    @foreach ($ciclo_modulos as $it)
        <div class="row justify-content-center mt-2"  id="{{'full-'.$it['id']}}"     @if ($it['ciclo_id']!=$ciclos[0]['id'])    style="display:none;"     @endif >
            <div class="col-sm-12 ">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-header bg-success text-center" id="{{'headerSaved'.$it['id']}}"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="{{'headerError'.$it['id']}}" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                    <div class="card-body bg-light">
                      <form id="{{'form_id-'.$it['id']}}" name="{{'form_id-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>ciclo_modulos/<?php echo($it['id']); ?>"> 
                        @csrf
                        <div class="form-row " >
                          <label for="ciclo_id--<?php echo ($it['id']); ?>"  class="col-sm-6" >Ciclo:</label>
                          <label for="modulo_id--<?php echo ($it['id']); ?>"  class="col-sm-6 " >Módulo:</label>
                        </div>
                        <div class="form-row " >
                          <select  id="ciclo_id--<?php echo ($it['id']); ?>" name='ciclo_id' form="{{'form_id-'.$it['id']}}" class='col-sm-6' disabled="">
                              @if ($ciclos!=null)
                              @foreach ($ciclos as  $ci)
                                <option value="{{$ci['id']}}" <?php echo($it['ciclo_id']==$ci['id']? 'selected' : '') ?>>{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                              @endforeach
                              @endif
                          </select>
                          <select  id="modulo_id--<?php echo ($it['id']); ?>" name='modulo_id' form="{{'form_id-'.$it['id']}}" class="col-sm-6 " disabled="" >
                              @if ($modulos!=null)
                              @foreach ($modulos as  $mo)
                                <option value="{{$mo['id']}}" <?php echo($it['modulo_id']==$mo['id']? 'selected' : '') ?>>{{$mo['codigoModulo'].' - '.$mo['nombreModulo']}}</option>
                              @endforeach
                              @endif
                          </select>
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
                        <h2 style='text-align: center;'>No hay combinaciones de ciclo y módulo guardadas. </br></h2>
                    </div>
                </div>
            </div>
        </div>
    @endif    


</div>
@section('content')

