@section('content')
<script> 
  var mensajeBorrar=''; //Por ahora no se crea mensaje antes de borrar.

  $(document).ready(function() {

    $(".truefalse").change(function() {
        if ($(this).is(':checked')) {
          $(this).val(1);
        } else {
          $(this).val(0);
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
              crossDomain: true,
              processData: false,
              contentType: false,  
              cache: false,     
              data: new FormData(this),
              success: function (result,txt,code) { 
                $('#headerSaved'+currentId).show("slow").delay(2000).hide("slow");
                console.log(result); console.log(txt); console.log(code); 
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
                console.log(result); console.log(txt); console.log(code); 
                $('#headerError'+currentId).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. '+ code+'</br>'+result.responseText.split(':')[1].replace('}','')+'</h3>');
                $('#headerError'+currentId).show("slow").delay(4000).hide("slow");
              },
          });
          return false;
    }); 

    $("#form_filter").bind('submit', function (e) { //los filtros son propios de cada pantalla. hay que hacer una funcion par cada una
          e.preventDefault();
          var opcion1=$("#cicloSel").val();
          var opcion2=$("input[name='filtrotipodoc']:checked").val();
          var formularios=$("*[id^='form_id-']");
          formularios.each( function() {
            var currentId=$(this).attr('id').split('-')[1]; 
            var thisValue1=$('#ciclo_id--'+currentId).val();
            var thisValue2=$("input[name='publico--"+currentId+"']:checked").val(); 
            if(currentId>0) {
              if(opcion1==thisValue1 && (opcion2==thisValue2 || opcion2==-1)) {
                $('#full-'+currentId).show(1000);
              } else {
                $('#full-'+currentId).hide(1000);
              }
            }
          });
          return false;
    }); 

  function llamadaInicial() {
    documentos();
  }

  $(".urichange").change(function() {
      if ( $("input[name='isFile']:checked").val()==0) { //Fichero marcado
        console.log('uri');
        $('#lblfichero').hide();
        $('#fichero').hide();
        $('#fichero').val('');
        $('#lbluri').show();
        $('#uri').show();
      } else { //URL marcada
        console.log('fichero');
        $('#lbluri').hide();
        $('#uri').hide();
        $('#uri').val('');
        $('#lblfichero').show();
        $('#fichero').show();
      }
  });

})
</script>
<div class="container">
    <h1 style='text-align: center;' >Gestión de Documentos.</h1>
<!--Filtro-->
    <div class="card bg-secondary sp-2" style='border-width: 5px'>
        <div class="card-body bg-light " >
          <form id="form_filter" name="form_filter" > 
            <div class="form-row ">
              <div class='col-sm-6 form-row'>
                <label for="cicloSel" class='col-sm-2' >Ciclo:</label>
                <select  id="cicloSel" name='cicloSel' form='form_filter' class='col-sm-10 form-control'>
                    <option value='0'>Generales</option>
                    @if ($ciclos!=null)
                    @foreach ($ciclos as  $ci)
                      <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                    @endforeach
                    @endif
                </select>
              </div>
              <div class='col-sm-4 form-inline justify-content-center '>
                <input class="form-check-input ml-2" type="radio" name="filtrotipodoc" id="filtrotipodoc1" value="1">
                <label class="form-check-label" for="filtrotipodoc1">Públicos</label>
                <input class="form-check-input ml-2" type="radio" name="filtrotipodoc" id="filtrotipodoc2" value="0">
                <label class="form-check-label" for="filtrotipodoc2">privados</label>
                <input class="form-check-input ml-2" type="radio" name="filtrotipodoc" id="filtrotipodoc3" value="-1" checked>
                <label class="form-check-label" for="filtrotipodoc3">Todos</label>
              </div>
              <div class="form-check form-check-inline col-sm-1">
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
                <button id='btn_add' class='adding' for='form_id-0'><div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i> </h3>  </div> </button>
                
                <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Documento añadido.</h3>   </div>
                <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                <div name='add_body' id='add_body' class="card-body bg-light" style="display:none;"> 
                    <form id='form_id-0' name='form_id-0' enctype='multipart/form-data' method='POST' action="<?php echo($apiUrl); ?>documentos/"> 
                      @csrf
                      <div class="form-row ">
                        <label for="nombre" class='col-sm-2'>Nombre:</label>
                        <input type="input" class="form-control col-sm-5" name='nombre' id="nombre" aria-describedby="nombre" placeholder="Nombre del documento" form='form_id-0' maxlength="100" required>
                        <div class='form-inline col-sm-5'>
                          <input class="form-check-input ml-3" type="radio" name="publico" id="publico1" value="1" form='form_id-0' checked>
                          <label class="form-check-label " for="publico1">Acceso público</label>
                          <input class="form-check-input ml-3" type="radio" name="publico" id="publico2" value="0" form='form_id-0'>
                          <label class="form-check-label " for="publico2">Solo usuarios registrados</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="descripcion">Descripción del documento:</label>
                        <textarea rows='3' class="form-control" id="descripcion" name='descripcion' placeholder="Describa el contenido del documento" form='form_id-0' ></textarea>
                      </div>
                      <div class="form-row ">
                        <diV class='col-sm-5 form-row justify-content-center'>
                          <label for="tipo" class='col-sm-5'>Tipo de documento:</label>
                          <select  id="tipo" name='tipo' form='form_id-0' class='col-sm-7'>
                            <option value='Recursos útiles'>Recursos útiles</option>
                            <option value='Información general'>Información general</option>
                            <option value='Legislación'>Legislación</option>
                            <option value='Programas'>Programas</option>
                            <option value='Otros'>Otros</option>                                                
                          </select>
                        </diV>
                        <diV class='col-sm-7 form-row justify-content-center'>                        
                          <label for="ciclo_id" class='col-sm-2 offset'>Ciclo:</label>
                          <select  id="ciclo_id" name='ciclo_id' form='form_id-0' class='col-sm-9'>
                            <option value='0'>Generales</option>
                            @if($ciclos!=null)
                            @foreach ($ciclos as  $ci)
                              <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                            @endforeach
                            @endif
                          </select>
                        </diV>
                      </div>
                      <hr/>
                      <div class="form-group justify-content-center ">
                        <div class="form-check form-check-inline ">
                          <input class="form-check-input urichange" type="radio" name="isFile" id="isFile1" value="1" form='form_id-0' checked>
                          <label class="form-check-label urichange" for="isFile1">Fichero</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input urichange" type="radio" name="isFile" id="isFile2" value="0" form='form_id-0'>
                          <label class="form-check-label urichange" for="isFile2">Link</label>
                        </div>
                      </div>
                      <div class="form-row ">
                        <label for="fichero" id='lblfichero' class='col-sm-2'>Fichero:</label>
                        <input type='file' class="form-control col-sm-10" id="fichero" name='fichero' form='form_id-0' >
                        <label for="uri" id='lbluri' style="display:none;" class='col-sm-2'>Link:</label>
                        <input type="url" class="form-control col-sm-10" name='uri' id="uri" aria-describedby="url" placeholder="Pegue o escriba aqui el link completo." form='form_id-0' maxlength="200" style="display:none;">
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

    @if($docs!=null)
    @foreach ($docs as $it)
        <div class="row justify-content-center mt-2"  id="{{'full-'.$it['id']}}"  @if ($it['ciclo_id']!=null) style="display:none;" @endif >
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-header bg-success text-center" id="{{'headerSaved'.$it['id']}}"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="{{'headerError'.$it['id']}}" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                    <div class="card-body bg-light">
                      <form id="{{'form_id-'.$it['id']}}" name="{{'form_id-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>documentos/<?php echo($it['id']); ?>"> 
                          @csrf
                        <div class="form-row ">
                          <label for="nombre--<?php echo ($it['id']); ?>"  class='col-sm-2'>Nombre:</label>
                          <input type="input" class="form-control col-sm-5" name='nombre' id="nombre--<?php echo ($it['id']); ?>" aria-describedby="nombre" placeholder="Nombre del documento" form="{{'form_id-'.$it['id']}}" maxlength="100" value="<?php echo ($it['nombre']); ?>" disabled required>
                          <div class='form-inline col-sm-5'>
                            <input class="form-check-input ml-3" type="radio" name="publico--<?php echo ($it['id']); ?>" id="publico1--<?php echo ($it['id']); ?>" value="1" form="{{'form_id-'.$it['id']}}" disabled <?php echo($it['publico']? 'checked' : '') ?>>
                            <label class="form-check-label " for="publico1--<?php echo ($it['id']); ?>">Acceso público</label>
                            <input class="form-check-input ml-3" type="radio" name="publico--<?php echo ($it['id']); ?>" id="publico2--<?php echo ($it['id']); ?>" value="0" form="{{'form_id-'.$it['id']}}" disabled <?php echo(!$it['publico']? 'checked' : '') ?>>
                            <label class="form-check-label " for="publico2--<?php echo ($it['id']); ?>">Solo usuarios registrados</label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="descripcion--<?php echo ($it['id']); ?>">Descripción del documento:</label>
                          <textarea rows='2' class="form-control" id="descripcion--<?php echo ($it['id']); ?>" name='descripcion' placeholder="Describa el contenido del documento" form="{{'form_id-'.$it['id']}}" disabled >{{$it['descripcion']}}</textarea>
                        </div>
                        <div class="form-row ">
                          <div class='col-sm-5 form-row justify-content-center'>
                            <label for="tipo--<?php echo ($it['id']); ?>" class='col-sm-5'>Tipo de documento:</label>
                            <select  id="tipo--<?php echo ($it['id']); ?>" name='tipo' form="{{'form_id-'.$it['id']}}" class='col-sm-7' disabled>
                               @if ($it['tipo']=='Recursos útiles')                               <option value='Recursos útiles' selected >Recursos útiles</option>
                               @else                              <option value='Recursos útiles' >Recursos útiles</option>                             @endif>
                               @if ($it['tipo']=='Información general')                               <option value='Información general' selected>Información general</option>
                               @else                              <option value='Información general' >Información general</option>                             @endif>
                               @if ($it['tipo']=='Programas')                               <option value='Programas' selected >Programas</option>
                               @else                              <option value='Programas' >Programas</option>                             @endif>
                               @if ($it['tipo']=='Legislación')                               <option value='Legislación'  selected >Legislación</option>
                               @else                              <option value='Legislación'   >Legislación</option>                             @endif>
                               @if ($it['tipo']=='Otros')                               <option value='Otros' selected >Otros</option>
                               @else                              <option value='Otros'  >Otros</option>                             @endif>
                            </select>
                          </div>
                          <div class='col-sm-7 form-row justify-content-center'>
                            <label for="ciclo_id--<?php echo ($it['id']); ?>" class='col-sm-2 offset-sm-1' >Ciclo:</label>
                            <select  id="ciclo_id--<?php echo ($it['id']); ?>" name='ciclo_id' form="{{'form_id-'.$it['id']}}" class='col-sm-9' disabled>
                              <option value=0 <?php echo($it['ciclo_id']==null? 'selected' : '') ?>>Generales</option>
                              @if($ciclos!=null)
                              @foreach ($ciclos as  $ci)
                                <option value="{{$ci['id']}}" <?php echo($it['ciclo_id']==$ci['id']? 'selected' : '') ?>>{{$ci['codigoCiclo'].' -- '.$ci['nombreCiclo']}}</option>
                              @endforeach
                              @endif
                            </select>
                          </div>
                        </div>
                        <hr/>
                        <div class="form-group ">
                          @if  ($it['uri']=='')
                              <button class='btn btn-secondary col-sm-12' style="font-size:2em;"disabled><i class="material-icons col-sm-1" style="color:red">dangerous</i> No hay link al recurso.</button>
                          @else
                              @if ($it['isFile']==1)
                                  <a href="{{url($publicUrl.$it['uri'])}}" class="btn btn-secondary col-sm-12" role="button" style="font-size:2em;"><i class="material-icons mr-3 " style="color:black" >description</i> Pulse para descargar el documento.</a>
                              @else
                                  <a href="{{url($it['uri'])}}"  target="_blank" class="btn btn-secondary col-sm-12" role="button" style="font-size:2em;"><i class="material-icons col-sm-1" style="color:black">travel_explore</i>Link al recurso: {{$it['uri']}}</a>
                              @endif
                          @endif
                        </div>

                        <hr/>

                        <div class="form-group text-center">
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
                        <h2 style='text-align: center;'>No hay Documentos guardados. </br></h2>
                    </div>
                </div>
            </div>
        </div>
    @endif    




@section('content')

