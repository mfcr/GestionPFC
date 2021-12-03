@section('content')
<script> 
  var mensajeBorrar=''; //Por ahora no se crea mensaje antes de borrar.

  $(document).ready(function() {

    $("[id^='btn_see_rub--']").click(function() {
      var currentId=this.id.split('--')[1]; //Obtenemos el ID.
       if ($(this).hasClass("notshowing")){
        $('#descripciones--'+currentId).show('slow');
        $(this).html('<i class="material-icons " style="font-size:1em;color:white">visibility_off</i>');
        $(this).toggleClass("notshowing showing");

      } else {
        $('#descripciones--'+currentId).hide('slow');
        $(this).html('<i class="material-icons " style="font-size:1em;color:white">visibility</i>');
        $(this).toggleClass("showing notshowing");
      }
    });

    $("[id^='btn_seecard-']").click(function() {
      var currentCard=this.id.split('-')[1]; //Obtenemos el ID.
       if ($(this).hasClass("notshowing")){
        $('#add_body-'+currentCard).show('slow');
        $(this).html('<i class="material-icons " style="font-size:1em;color:white">visibility_off</i>');
        $(this).toggleClass("notshowing showing");

      } else {
        $('#add_body-'+currentCard).hide('slow');
        $(this).html('<i class="material-icons " style="font-size:1em;color:white">visibility</i>');
        $(this).toggleClass("showing notshowing");
      }
    });

    $("[id^='form_id--']").bind('submit', function (e) {
          //var currentId=$(this).attr('name').split('-')[3]; //Obtenemos el ID.
          var prefix=$(this).attr('name').split('-')[2]; //Obtenemos el prefix del form.
          var thisUrl=$(this).attr('action');
          var metodo=$(this).attr('method');
          var data=$(this).find('input,select,textarea').filter(function(){  return (this.value && this.value!='null' && this.value!=''); }).serialize(); 
          console.log(data+', '+prefix);
          console.log(prefix);
          console.log($(this).attr('name'));
          e.preventDefault();
          $(document.body).css('cursor','wait');
          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021' ,  }});
          $.ajax({
              type: metodo,
              url: thisUrl,
              data: data,
              crossDomain: true,
              success: function (result,txt,code) { 
                $('#headerSaved-'+prefix).show("slow").delay(2000).hide("slow");
                $(document.body).css('cursor','default');
                if (prefix!='doc') {llamadaInicial();}
              },
              error: function (result,txt,code) { 
                console.log(txt);
                $('#headerError-'+prefix).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. '+ code+'</br>'+result.responseText.split(':')[1].replace('}','')+'</h3>');
                $('#headerError-'+prefix).show("slow").delay(4000).hide("slow");
                $(document.body).css('cursor','default');
              },
          });
          
          return false;
    }); 

    $("[id^='form_id-doc-0']").bind('submit', function (e) {
      console.log('Aqui2');
          //var currentId=$(this).attr('name').split('-')[3]; //Obtenemos el ID.
          var prefix='doc'; //Obtenemos el prefix del form.
          var thisUrl=$(this).attr('action');
          var metodo=$(this).attr('method');
          e.preventDefault();
          $(document.body).css('cursor','wait');
          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021' ,  }});
          $.ajax({
              type: metodo,
              url: thisUrl,
              data: new FormData(this),
              crossDomain: true,
              processData: false,
              contentType: false,
              success: function (result,txt,code) { 
                $('#headerSaved-'+prefix).show("slow").delay(2000).hide("slow");
                $(document.body).css('cursor','default');
                llamadaInicial();
              },
              error: function (result,txt,code) { 
                console.log(txt);
                $('#headerError-'+prefix).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. '+ code+'</br>'+result.responseText.split(':')[1].replace('}','')+'</h3>');
                $('#headerError-'+prefix).show("slow").delay(4000).hide("slow");
                $(document.body).css('cursor','default');
              },
          });
          
          return false;
    }); 

    $("[id^='btn_clear--doc-']").click(function() {
      var currentId=$(this).attr('id').split('-')[3]; //Obtenemos el ID.
      var form='form_id--doc-'+currentId;
      var deleteUrl=$('#'+form).attr('action');
      console.log(deleteUrl);
      var metodo='DELETE'
      if (typeof mensajeBorrar=='undefined' || mensajeBorrar=='' || confirm(mensajeBorrar)) {
        $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
        $.ajax({
            type: metodo,
            url: deleteUrl,
            crossDomain: true,
            success: function (result,txt,code) { 
              $('#headerSaved-doc').show("slow").delay(2000).hide('slow');
              $('#full-doc-'+currentId).delay(1000).hide('slow');
            },
            error: function (result,txt,code) { 
              $('#headerError-doc').show("slow").delay(4000).hide("slow");
            },
        });
      }
    });

    $("[id^='btn_change--doc-']").click(function() {
      var currentId=$(this).attr('id').split('-')[3]; //Obtenemos el ID.
      var titulo=$(this).attr('title');
      if ($("#publico--doc-"+currentId).attr('value')=='0') {
        $("#publico--doc-"+currentId).attr('value','1');
        $(this).html('<i class="material-icons " style="font-size:1em;color:white">public</i> ');
        $(this).attr('title',titulo.replace('privado','público'));
      } else {
        $("#publico--doc-"+currentId).attr('value','0');
        $(this).html('<i class="material-icons " style="font-size:1em;color:white">lock</i>');
        $(this).attr('title',titulo.replace('público','privado'));
      }
      $('#btn_submit--doc-'+currentId).trigger('submit');
    });


    $("[name^='forms_id-']").bind('click', function (e) {
          var prefix=$(this).attr('name').split('-')[1];
          var forms = $("[id^='form_id-"+prefix+"-']");
          var errores=0;
          var mensaje="";
          $(document.body).css('cursor','wait');
          forms.each(function(index,value) {
            var currentId=$(this).attr('name').split('-')[2]; //Obtenemos el ID.
            var thisUrl=$(this).attr('action');
            var metodo=$(this).attr('method');
            var data=$(this).find('input,select,textarea').filter(function(){  return (this.value && this.value!='null' && this.value!=''); }).serialize(); 
            //console.log(data+', '+prefix+'-'+currentId);
            //console.log($(this).attr('name'));
            e.preventDefault();
            $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
            $.ajax({
                type: metodo,
                url: thisUrl,
                data: data,
                crossDomain: true,
                success: function (result,txt,code) { 
                  if (index == forms.length -1) {
                    if (errores==0) {
                      $('#headerSaved-'+prefix).show("slow").delay(2000).hide("slow");
                      $(document.body).css('cursor','default');
                      llamadaInicial();
                    } else {
                      $('#headerError-'+prefix).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. </br>'+mensaje+'</h3>');
                      $('#headerError-'+prefix).show("slow").delay(4000).hide("slow");
                      $(document.body).css('cursor','default');
                    }
                  }
                },
                error: function (result,txt,code) { 
                  errores++;
                  mensaje+=", modulo: "+currentId;
                  console.log("error en "+currentId);
                  if (index == forms.length -1) {
                    $('#headerError-'+prefix).html( '<h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error. </br>'+mensaje+'</h3>');
                    $('#headerError-'+prefix).show("slow").delay(4000).hide("slow");
                    $(document.body).css('cursor','default');
                  }
                },
            });
          
          });
          return false;
    }); 

  function llamadaInicial() {
    verProyectos({!!$id!!},@json($mode),{!!$code!!});
  }


  $("[id^='sendMail']").click(function() {
    var cabecera=prompt("Introduzca un asunto:");
    var textoMensaje=prompt("Introduzca el mensaje a enviar:");
    if (textoMensaje!=null && textoMensaje!="" && cabecera!=null && cabecera!="" ) {
      var data={'cabecera':cabecera,'mensaje':textoMensaje};
      $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   }});
      $.ajax({
          type: 'POST',
          url: $(this).attr('name'),
          data: data,
          crossDomain: true,
          success: function (result,txt,code) { 
            alert("Mensaje enviado correctamente");
          },
          error: function (result,txt,code) { 
            alert("Error enviando mensaje. ");
          },
      });
    } else {
      alert("No ha introducido ningún mensaje o asunto, el mensaje no se enviará.");
    }
  });


  $(".urichange").change(function() {
      if ( $("input[name='isFile']:checked").val()==0) { //Fichero marcado
        console.log('uri');
        $('#lblfichero').hide();
        $('#fichero').hide();
        $('#fichero').val('');
        $('#lbluri').show();
        $('#UriDocumento').show();
      } else { //URL marcada
        console.log('fichero');
        $('#lbluri').hide();
        $('#UriDocumento').hide();
        $('#UriDocumento').val('');
        $('#lblfichero').show();
        $('#fichero').show();
      }
  });


});



</script>

<div class="container">
    <h1 style='text-align: center;' >PROYECTO. (Estado: {{$proyecto[0]['estados']['estado']}}) - Curso: {{$proyecto[0]['curso']}}<br/>{{$proyecto[0]['ciclos']['codigoCiclo']}} - {{$proyecto[0]['ciclos']['nombreCiclo']}}</h1>

<!--Formulario Datos Alumno-->

    @php
      //Si alumno puede editar sus datos y si no están rellenos no se le permite seguir.
      $rellenarDatosPersonales=false;
      $editableAlumno='disabled';
      if ($mode=='al') {
        $editableAlumno='';
        if ($proyecto[0]['alumnos']['nombre']==null || $proyecto[0]['alumnos']['apellido1']==null || $proyecto[0]['alumnos']['dni']==null) {
          $rellenarDatosPersonales=true;
        }
      } 
      if ($mode=='public') {$hidePublic='hidden'; } else {$hidePublic='';} 
      $it=$proyecto[0];
    @endphp

    <div class="row justify-content-center">
        <div class="col-sm-12 mt-2">
            <div class="card bg-secondary sp-2" style='border-width: 5px'>
                <div class="card-header bg-secondary form-row" style='color:white;'>
                  <h3 class='col-sm-11 text-center'>Datos alumn@</h3> 
                  <button type="button" id="btn_seecard-al" class="btn btn-dark col-sm-1 notshowing"  title="Ver los detalles de la matrícula."><i class="material-icons" style="font-size:1em;color:white"><?php echo($rellenarDatosPersonales?'visibility_off':'visibility')?></i></button>  
                </div>

                <div class="card-header bg-success text-center" id="headerSaved-al" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos modificados.</h3>   </div>
                <div class="card-header bg-danger text-center" id="headerError-al" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>
                <div name='add_body' id='add_body-al' class="card-body bg-light" <?php echo($rellenarDatosPersonales?'':'style="display:none;"')?> > 
                    <form id="form_id--al" name="{{'form_id--al-'.$it['alumnos']['id']}}" method='POST' action="<?php echo($apiUrl); ?>alumnos/<?php echo($it['alumnos']['id']); ?>"> 
                      @csrf
                      <div class="form-row">
                        <div class="form-group col-sm-4">
                          <label for="nombre--<?php echo ($it['alumnos']['id']); ?>" >Nombre:</label>
                          <input type="input" class="form-control " name='nombre' id="nombre--<?php echo ($it['alumnos']['id']); ?>" aria-describedby="nombre" placeholder="Nombre" form="form_id--al" maxlength="30" value="<?php echo($it['alumnos']['nombre'])?>" required {{$editableAlumno}}>  
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="apellido1--<?php echo ($it['alumnos']['id']); ?>" >Primer apellido:</label>
                          <input type="input" class="form-control " name='apellido1' id="apellido1--<?php echo ($it['alumnos']['id']); ?>" aria-describedby="apellido1" placeholder="primer apellido" form="form_id--al" maxlength="30" value="<?php echo($it['alumnos']['apellido1'])?>" required {{$editableAlumno}}> 
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="apellido2--<?php echo ($it['alumnos']['id']); ?>" >Segundo apellido:</label>
                          <input type="input" class="form-control " name='apellido2' id="apellido2--<?php echo ($it['alumnos']['id']); ?>" aria-describedby="apellido2" placeholder="segundo apellido" form="form_id--al" maxlength="30" value="<?php echo($it['alumnos']['apellido2'])?>" {{$editableAlumno}}> 
                        </div>
                    </div>
                      <div class="form-row" <?php echo($hidePublic);?>>
                        <div class="form-group col-sm-4">
                          <label for="email--<?php echo ($it['alumnos']['id']); ?>" >Email:</label>
                          <input type="email" class="form-control " name='email' id="email--<?php echo ($it['alumnos']['id']); ?>" aria-describedby="email" placeholder="dirección de correo electrónico" form="form_id--al" maxlength="50" disabled value="<?php echo($it['alumnos']['email'])?>" >   
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="dni--<?php echo ($it['alumnos']['id']); ?>" >DNI:</label>
                          <input type="input" class="form-control " name='dni' id="dni--<?php echo ($it['alumnos']['id']); ?>" aria-describedby="dni" placeholder="NIF (8 números y letra sin guiones ni espacios)" form="form_id--al" maxlength="9" required value="<?php echo($it['alumnos']['dni'])?>" required {{$editableAlumno}}>   
                        </div>
                        <div class="form-group col-sm-4">
                          <label for="telefono--<?php echo ($it['alumnos']['id']); ?>" >Teléfono:</label>
                          <input type="input" class="form-control " name='telefono' id="telefono--<?php echo ($it['alumnos']['id']); ?>" aria-describedby="telefono" placeholder="Numero de telefono" form="form_id--al" maxlength="15" value="<?php echo($it['alumnos']['telefono'])?>" {{$editableAlumno}}>   
                        </div>
                    </div>
                    <hr/>
                    @if ($rellenarDatosPersonales)
                      <h3 class='text-center' >Para poder continuar editando su proyecto debe rellenar primero sus datos personales</h3><hr/>
                    @endif
                    @if ($editableAlumno=='')
                    <div class="form-group text-center">
                      <button type="submit" class="btn btn-dark text-center" form="form_id--al"><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Guardar los datos.</button>         
                    </div>
                    @endif

                    </form>
                </div>


            </div>
        </div>
    </div>
    @if ($mode!='al' || !$rellenarDatosPersonales)
  <!--Formulario Datos Matricula-->

      @php
        $valorado=true; 
        $estados_matricula=['Superado', 'Convalidado','Exento', 'Solicitado convalidación', 'Matriculado', 'No Matriculado'];
        $valores_estados=[1,1,1,0,0,0];
        $proyectoPresentable=true;
        $editableStatus=false;
        $saveButton=false;
        if($mode=='al' && $it['estado']<4) {$editableStatus=true;} //Solo ppueden modificar el estado de las asignaturas los alumnos siempre que el proyecto n está ya aprobado/suspenso/abandonado.
      @endphp

      <div class="row justify-content-center" <?php echo($hidePublic);?>>
          <div class="col-sm-12 mt-2">
              <div class="card bg-secondary sp-2" style='border-width: 5px'>
                  <div class="card-header bg-secondary form-row" style='color:white;'>
                    <h3 class='col-sm-11 text-center'>Datos Matrícula</h3> 
                    <button type="button" id="btn_seecard-mat" class="btn btn-dark col-sm-1 notshowing"  title="Ver los detalles de la matrícula."><i class="material-icons" style="font-size:1em;color:white">visibility</i></button>  
                  </div>
                  <div class="card-header bg-success text-center" id="headerSaved-mat" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos modificados.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError-mat" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                  <div name='add_body' id='add_body-mat' class="card-body bg-light" style="display:none;" > 
                        <div class="form-row">
                          <div class="form-group col-sm-4">
                            <label >Módulo:</label>
                          </div>
                          <div class="form-group col-sm-2">
                            <label >Estado:</label>
                          </div>
                          <div class="form-group col-sm-1">
                            <label title='Si tiene Unidad Competencial el proyecto debe ser preevaluado por el docente del módulo'>Tiene UC:</label>
                          </div>
                          <div class="form-group col-sm-2">
                            <label >Valor.anteproyecto:</label>
                          </div>
                          <div class="form-group col-sm-3">
                            <label >Comentario del docente:</label>
                          </div>
                      </div>
                      @if ($it['modulos_matriculados']!=null)
                      @foreach ($it['modulos_matriculados'] as $mat)
                      @php  $editablePreeval=false;  @endphp
                      <form id="{{'form_id-mat-'.$mat['id']}}" name="{{'form_id-mat-'.$mat['id']}}" method='POST' action="<?php echo($apiUrl); ?>modulos_matriculados/<?php echo($mat['id']); ?>"> 
                        @csrf
                        <div class='form-row'>
                            <input type="hidden" class="form-control " name='ciclo_modulo_id' value="<?php echo($mat['ciclo_modulo_id'])?>" form="{{'form_id-mat-'.$mat['id']}}" >  
                            <input type="hidden" class="form-control " name='id' value="<?php echo($mat['id'])?>" form="{{'form_id-mat-'.$mat['id']}}" >
                            <input type="hidden" class="form-control " name='proyecto_id' value="<?php echo($mat['proyecto_id'])?>" form="{{'form_id-mat-'.$mat['id']}}" >
                            <div class="form-group col-sm-4">
                              <input type="input" class="form-control " name='nombreModulo' id="nombreModulo--<?php echo ($mat['id']); ?>" aria-describedby="nombre" placeholder="Nombre" form="{{'form_id-mat-'.$mat['id']}}" maxlength="30" value="<?php echo($mat['modulos']['nombreModulo'])?>" title="<?php echo($mat['modulos']['nombreModulo'])?>" disabled> 
                            </div>

                            <div class="form-group col-sm-2">
                              <select id="estado--<?php echo ($mat['id']); ?>" name='estado' form="{{'form_id-mat-'.$mat['id']}}" class='form-control' <?php echo(!$editableStatus?'disabled':'' ) ?>>
                                @foreach($estados_matricula as $est)
                                  @if ($est==$mat['estado'])
                                    <option value="{{$est}}" selected>{{$est}}</option>
                                    @if ($est=='Solicitado convalidación' || $est=='Matriculado' || $est== 'No Matriculado') @php $proyectoPresentable=false; @endphp @endif
                                  @else
                                    @if ($est==null) @php $proyectoPresentable=false; @endphp @endif
                                    <option value="{{$est}}" >{{$est}}</option>
                                  @endif
                                @endforeach
                              </select>
                            </div>
                            @if ($mode=='doc' && $it['estado']<2)
                              @foreach ($mat['ciclo_modulos']['docente_imparte_modulos'] as $mm)
                                @if ($mm['docente_id']==$code && $mm['curso']==$it['curso']) @php  $editablePreeval=true; $saveButton=true;  @endphp @endif
                              @endforeach
                            @endif
                            <div class="form-group col-sm-1 text-center">
                              <input class="form-check-input " type="checkbox" checked disabled>
                            </div>
                            <div class="form-group col-sm-2">
                              <select id="preevaluado--<?php echo ($mat['id']); ?>" name='preevaluado' form="{{'form_id-mat-'.$mat['id']}}" class='form-control' <?php echo(!$editablePreeval?'disabled':'' ) ?> > 
                                <option value='null' <?php echo($mat['preevaluado']==null?'selected':'')?>>Pdte.Evaluación</option>
                                <option value='1' <?php echo(($mat['preevaluado']!=null && $mat['preevaluado']==true)?'selected':'')?>>Apto</option>
                                <option value='0' <?php echo(($mat['preevaluado']!=null && $mat['preevaluado']==false)?'selected':'')?>>No Apto. </option>
                              </select>
                            </div>
                            <div class="form-group col-sm-3">
                              <input type="input" class="form-control " name='comentario' id="comentario--<?php echo ($mat['id']); ?>" aria-describedby="comentario" placeholder="comentario" form="{{'form_id-mat-'.$mat['id']}}" maxlength="100" title="<?php echo($mat['comentario'])?>" value="<?php echo($mat['comentario'])?>" <?php echo(!$editablePreeval?'disabled':'' ) ?>>  
                            </div>
                        </div>
                      </form>
                      @endforeach
                      @endif
                      <!--@if (!$proyectoPresentable)
                        <hr/>
                        <h3 class='text-center'> No puede presentar el proyecto dado que tiene asignaturas no superadas.</h3>
                      @endif-->
                      @if ($saveButton || $editableStatus)
                        <hr/>
                        <div class="form-group text-center">
                          <button type="button" class="btn btn-dark text-center" name='forms_id-mat-' id='form_id_mat-'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Guardar cambios.</button>         
                        </div>
                      @endif
                  </div>
              </div>
          </div>
      </div>

  <!--Anteproyecto-->

      @php

        $editableAnte=false;
        if ($mode=='al' && $it['estado']<2) {$editableAnte=true;} //ver otras posible condiciones fechas o estado proyecto.
      @endphp

      <div class="row justify-content-center">
          <div class="col-sm-12 mt-2">
              <div class="card bg-secondary sp-2" style='border-width: 5px'>
                  <div class="card-header bg-secondary form-row" style='color:white;'>
                    <h3 class='col-sm-11 text-center'>Anteproyecto</h3> 
                    <button type="button" id="btn_seecard-ap" class="btn btn-dark col-sm-1 notshowing"  title="Ver los detalles de la matrícula."><i class="material-icons" style="font-size:1em;color:white">visibility</i></button>  
                  </div>
                  <div class="card-header bg-success text-center" id="headerSaved-ap" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos modificados.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError-ap" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                  <div name='add_body' id='add_body-ap' class="card-body bg-light" style="display:none;" >  
                      <form id='form_id--ap' name="{{'form_id--ap-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>proyectos/<?php echo($it['id']); ?>"> 
                        @csrf
                        <div class="form-row">
                          <label for="nombreProyecto--<?php echo ($it['id']); ?>" class="col-sm-2">Nombre del Proyecto:</label>
                          <input type="input" class="form-control col-sm-10" name='nombreProyecto' id="nombreProyecto--<?php echo ($it['id']); ?>" aria-describedby="nombreProyecto" placeholder="Nombre Proyecto" form='form_id--ap' maxlength="200" value="<?php echo($it['nombreProyecto'])?>" required <?php echo(!$editableAnte?'disabled':'' ) ?>>  
                        </div>
                        <div class="form-row">
                          <div class="form-group col-sm-3">
                            <label for="tipo_proyecto_id--<?php echo ($it['id']); ?>" >Tipo de Proyecto:</label>
                            <select id="tipo_proyecto_id--<?php echo ($it['id']); ?>" name='tipo_proyecto_id' form='form_id--ap' class='form-control' <?php echo(!$editableAnte?'disabled':'' ) ?>>
                              @foreach($tipos as $tip)
                                @if ($tip['tipo_proyecto_id']==$it['tipo_proyecto_id'])
                                  <option value="{{$it['tipo_proyecto_id']}}" selected>{{$tip['tipo_proyectos']['tipo']}}</option>
                                @else
                                  <option value="{{$it['tipo_proyecto_id']}}" >{{$tip['tipo_proyectos']['tipo']}}</option>
                                @endif
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group col-sm-9">
                            <label for="descTipo--<?php echo ($it['id']); ?>" >Tipo (si Otro):</label>
                            <input type="input" class="form-control " name='descTipo' id="descTipo--<?php echo ($it['id']); ?>" aria-describedby="descTipo" placeholder="Tipo de proyecto (si Otro)" form='form_id--ap' maxlength="60" value="<?php echo($it['descTipo'])?>" <?php echo(!$editableAnte?'disabled':'' ) ?> >  
                          </div>
                        </div>
                         <div class="form-row">
                          <div class="form-group col-sm-12">
                            <label for="textoPropuestaProyecto--<?php echo ($it['id']); ?>" >Propuesta de Proyecto:</label>
                            <textarea rows='5' class="form-control" id="textoPropuestaProyecto--<?php echo ($it['id']); ?>" name='textoPropuestaProyecto' placeholder="Describa el proyecto." form='form_id--ap' required <?php echo(!$editableAnte?'disabled':'' ) ?>>{{$it['textoPropuestaProyecto']}}</textarea>
                          </div>
                        </div>
                         <div class="form-row">
                          <div class="form-group col-sm-12">
                            <label for="textoRequisitosFuncionales--<?php echo ($it['id']); ?>" >Requisitos funcionales:</label>
                            <textarea rows='5' class="form-control" id="textoRequisitosFuncionales--<?php echo ($it['id']); ?>" name='textoRequisitosFuncionales' placeholder="Describa la funcionalidad mínima del proyecto." form='form_id--ap' required <?php echo(!$editableAnte?'disabled':'' ) ?>>{{$it['textoRequisitosFuncionales']}}</textarea>
                          </div>
                        </div>
                         <div class="form-row">
                          <div class="form-group col-sm-12">
                            <label for="textoModulosRelacionados--<?php echo ($it['id']); ?>" >Módulos relacionados:</label>
                            <textarea rows='5' class="form-control" id="textoModulosRelacionados--<?php echo ($it['id']); ?>" name='textoModulosRelacionados' placeholder="Indique los módulos del ciclo que se relacionan directamente con el proyecto así como las tecnologías a usar no usadas durante el ciclo formativo." form='form_id--ap' required <?php echo(!$editableAnte?'disabled':'' ) ?>>{{$it['textoModulosRelacionados']}}</textarea>
                          </div>
                        </div>
                        @if ($editableAnte)
                          <hr/>

                          <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark text-center" form='form_id--ap'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Guardar los datos.</button>         
                          </div>
                        @endif

                        </form>
                    </div>


              </div>
          </div>
      </div>


  <!--Docentes y Notas-->

      @php

        $editableIndiv=false;
        $editableCol=false;
        $editableNota=false;
        if ($mode=='col' && $it['estado']<4) {$editableCol=true;}
        if ($mode=='ind' && $it['estado']<4) {$editableIndiv=true;}
        if ($editableCol && $it['estado']<4) {$editableNota=true;}

        $receptorMensaje='alumn@';
        if ($mode=='al') {$receptorMensaje='docente';}
        if ($it['nombreProyecto']==null || $it['nombreProyecto']=="") {$nombreP="Sin Nombre";} else {$nombreP=$it['nombreProyecto'];}

      @endphp

      <div class="row justify-content-center">
          <div class="col-sm-12 mt-2">
              <div class="card bg-secondary sp-2" style='border-width: 5px'>
                  <div class="card-header bg-secondary form-row" style='color:white;'>
                    <h3 class='col-sm-11 text-center'>Docentes, mensajes y evaluación del Proyecto.</h3> 
                    <button type="button" id="btn_seecard-doev" class="btn btn-dark col-sm-1 notshowing"  title="Ver los detalles de la matrícula."><i class="material-icons" style="font-size:1em;color:white">visibility</i></button>  
                  </div>
                  <div class="card-header bg-success text-center" id="headerSaved-doev" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos modificados.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError-doev" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                  <div name='add_body' id='add_body-doev' class="card-body bg-light" style="display:none;" >  
                      <form id='form_id--doev' name="{{'form_id--doev-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>proyectos/<?php echo($it['id']); ?>"> 
                        @csrf
                        <div class="form-row">
                          <div class="form-group col-sm-10">
                            <label for="docente_id--<?php echo ($it['id']); ?>" >Tutor Individual:</label>
                            <select id="docente_id--<?php echo ($it['id']); ?>" name='docente_id' form='form_id--doev' class='form-control' <?php echo(!$editableCol?'disabled':'')?> >
                              @if ($it['docente_id']==null)
                                <option value selected>Tutor Individual pendiente de asignación por el Tutor Colectivo.</option>
                              @endif
                              @foreach($docentes as $do)
                              @if ($do['id']!=1)  <!--Evitamos mostrar el administrador-->
                                @if ($it['docente_id']!=null && $it['docente_id']==$do['id'])
                                  <option value="{{$do['id']}}" selected>{{$do['nombre'].' '.$do['apellido1'].' '.$do['apellido2'].'.  -- Email: '.$do['email']}}</option>
                                @else
                                  <option value="{{$do['id']}}" >{{$do['nombre'].' '.$do['apellido1'].' '.$do['apellido2'].'.  -- Email: '.$do['email']}}</option>
                                @endif
                              @endif
                              @endforeach
                            </select>
                          </div>
                          @if ($it['docente_id']!=null && ($mode=='al' || $mode=='ind'))
                            @php if ($mode=='al') {$receptor=$it['docente_id'];} else {$receptor=$it['alumno_id'];} @endphp
                            <div class="form-group col-sm-2"> 
                              <label for='sendMailTutInd'>Envíe mensaje al {{$receptorMensaje}}</label>
                               <button type="button" id='sendMailTutInd' name="{{'/mensaje/'.$mode.'/'.$code.'/'.$receptor.'/'.$it['id']}}"  title='Envíe mensaje al <?php echo($receptorMensaje)?>' class="btn btn-dark form-control text-center" ><i class="material-icons " style="font-size:1em;color:white">mail</i></button>  
                            </div>
                          @endif
                        </div>
                        <div class="form-row">
                          <div class="form-group col-sm-3" <?php echo($hidePublic);?>>
                            <label for="notaPrevia--<?php echo ($it['id']); ?>" >Nota Previa:</label>
                            <input type="number" min='0' max='10' class="form-control text-center" name='notaPrevia' id="notaPrevia--<?php echo ($it['id']); ?>" aria-describedby="notaPrevia" placeholder="notaPrevia" form='form_id--doev'  value="<?php echo($it['notaPrevia'])?>" <?php echo(!$editableIndiv?'disabled':'')?> >  
                          </div>
                          <div class="form-group col-sm-9" <?php echo($hidePublic);?>>
                            <label for="comentarioPrevio--<?php echo ($it['id']); ?>" >Comentario Previo:</label>
                            <input type="input" class="form-control " name='comentarioPrevio' id="comentarioPrevio--<?php echo ($it['id']); ?>" aria-describedby="comentarioPrevio" placeholder="Comentario Previo" form='form_id--doev' value="<?php echo($it['comentarioPrevio'])?>" title="<?php echo($it['comentarioPrevio'])?>" <?php echo(!$editableIndiv?'disabled':'')?> >  
                          </div>
                        </div>
                        <hr/>
                        <div class="form-row">
                          <div class="form-group col-sm-10">
                            <label for="tutorColectivo--<?php echo ($it['id']); ?>" >Tutor colectivo:</label>
                            @if ($tutColectivo!=null)
                            <input type="input" class="form-control "  aria-describedby="tutorColectivo" placeholder="tutorColectivo" form='form_id--doev'  value="<?php echo($tutColectivo[0]['docentes']['nombre'].' '.$tutColectivo[0]['docentes']['apellido1'].' '.$tutColectivo[0]['docentes']['apellido2'].'.  -- Email: '.$tutColectivo[0]['docentes']['email'])?>" disabled>  
                            @else
                              <input type="input" class="form-control "   aria-describedby="tutorColectivo" placeholder="tutorColectivo" form='form_id--doev'  value="Tutor colectivo del ciclo pendiente de asignar por Administrador" disabled>  
                            @endif
                          </div>
                          @if ($tutColectivo!=null && ($mode=='al' || $mode=='col'))
                            @php if ($mode=='al') {$receptor=$tutColectivo[0]['docente_id'];} else {$receptor=$it['alumno_id'];} @endphp
                            <div class="form-group col-sm-2">
                              <label for='sendMailTutCol'>Envíe mensaje al {{$receptorMensaje}}</label>
                               <button type="button" id='sendMailTutCol' name="{{'/mensaje/'.$mode.'/'.$code.'/'.$receptor.'/'.$it['id']}}"  title='Envíe mensaje al <?php echo($receptorMensaje)?>' class="btn btn-dark form-control text-center" ><i class="material-icons " style="font-size:1em;color:white">mail</i></button>  

                            </div>
                          @endif
                        </div>
                        <div class="form-row form-group" <?php echo($hidePublic);?>>
                          @if ($estados!=null)
                          <label for="estado--<?php echo ($it['id']); ?>" class="col-sm-4 " style='font-size: 2em;' >Estado del Proyecto:</label>
                          <select  id="estado--<?php echo ($it['id']); ?>" name='estado' form='form_id--doev' class='form-control col-sm-8 text-center' style='font-size: 3em;'<?php echo(($mode=='col' || $mode=='admin')?'':'disabled')?> >
                            @foreach ($estados as $es)
                              <option value="{{$es['codigo']}}" <?php echo( ($es['codigo']==$it['estado'])?'selected':'');?>>{{$es['estado']}}</option>
                            @endforeach
                          </select>
                          @endif
                        </div>                        

                        <div class="form-row form-group">
                          <label for="NotaFinal--<?php echo ($it['id']); ?>" class="col-sm-4 " style='font-size: 3em;' >Nota Final:</label>
                          <input type="number" min='0' max='10'  class="form-control col-sm-8 text-center"  style='font-size: 3em;' name='NotaFinal' id="NotaFinal--<?php echo ($it['id']); ?>" aria-describedby="NotaFinal" placeholder="Nota Final" form='form_id--doev'  value="<?php echo($it['NotaFinal'])?>" <?php echo(!$editableNota?'disabled':'')?> >  
                        </div>                        

                        @if ($editableCol || $editableIndiv || $mode=='admin' || $mode=='col')
                          <hr/>
                          <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark text-center" form='form_id--doev'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Guardar los datos.</button>         
                          </div>
                        @endif
                        </form>
                    </div>
              </div>
          </div>
      </div>

  <!--Documentos del proyecto -->
      @php
        $tiposDoc=['Proyecto Completo','Memoria','Imagen','Video','Anexo','Codigo','Otros'];
        if ($mode=='al' && $it['estado']<4) {$editableStatus=''; $changeDocs=true;} else {$editableStatus='disabled'; $changeDocs=false;}
        if ($mode=='admin' || $mode=='col') {$editablePublic=''; $title=" Pulse para cambiar el estado.";} else {$editablePublic='disabled'; $title="";}

      @endphp

      <div class="row justify-content-center">
          <div class="col-sm-12 mt-2">
              <div class="card bg-secondary sp-2" style='border-width: 5px'>
                  <div class="card-header bg-secondary form-row" style='color:white;'>
                    <h3 class='col-sm-11 text-center'>Documentos del proyecto</h3> 
                    <button type="button" id="btn_seecard-doc" class="btn btn-dark col-sm-1 notshowing"  title="Ver los documentos del proyecto."><i class="material-icons" style="font-size:1em;color:white">visibility</i></button>  
                  </div>
                  <div class="card-header bg-success text-center" id="headerSaved-doc" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos modificados.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError-doc" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                  <div name='add_body' id='add_body-doc' class="card-body bg-light" style="display:none;" > 
                      @if ($it['documentos_proyectos']!=null)
                        <div class="form-row">
                          <div class="form-group col-sm-5">
                            <label >Documento:</label>
                          </div>
                          <div class="form-group col-sm-2">
                            <label >Tipo:</label>
                          </div>
                          <div class="form-group col-sm-2">
                            <label >Link:</label>
                          </div>
                          <div class="form-group col-sm-1" <?php echo($hidePublic);?>>
                            <label >publico:</label>
                          </div>
                          <div class="form-group col-sm-1" <?php echo($hidePublic);?>>
                            <label >guardar:</label>
                          </div>
                          <div class="form-group col-sm-1" <?php echo($hidePublic);?>>
                            <label >borrar:</label>
                          </div>

                      </div>
                      @foreach ($it['documentos_proyectos'] as $doc)
                      @php  $editablePreeval=false;  @endphp
                      <div id="{{'full-doc-'.$doc['id']}}">
                        <form id="{{'form_id--doc-'.$doc['id']}}" name="{{'form_id--doc-'.$doc['id']}}" method='POST' action="<?php echo($apiUrl); ?>documentos_proyectos/<?php echo($doc['id']); ?>"> 
                          @csrf
                          <div class='form-row input-group' <?php echo($doc['publico']==0?$hidePublic:'');?> >
                              <div class="form-group col-sm-5">
                                <input type="input" class="form-control " name='descripcion' id="descripcion--<?php echo ($doc['id']); ?>" aria-describedby="descripcion" placeholder="Descripcion Documento" 
                                form="{{'form_id--doc-'.$doc['id']}}" maxlength="300" value="<?php echo($doc['descripcion'])?>" title="<?php echo($doc['descripcion'])?>" <?php echo($editableStatus) ?>> 
                              </div>
                              <div class="form-group col-sm-2">
                                <select id="tipoDocumento--<?php echo ($doc['id']); ?>" name='tipoDocumento' form="{{'form_id--doc-'.$doc['id']}}" class='form-control' <?php echo($editableStatus) ?>>
                                  @foreach($tiposDoc as $tip)
                                    @if ($tip==$doc['tipoDocumento'])
                                      <option value="{{$tip}}" selected>{{$tip}}</option>
                                    @else
                                      <option value="{{$tip}}" >{{$tip}}</option>
                                    @endif
                                  @endforeach
                                </select>
                              </div>
                              @php 
                                if ($doc['isFile']==1) {
                                  $texto=substr($doc['UriDocumento'],strrpos($doc['UriDocumento'],'/')+1);
                                } else {
                                  $texto=$doc['UriDocumento'];
                                }
                              @endphp


                              @if ($doc['isFile']==1)
                                <div class='input-group-append col-sm-2 justify-content-center'>
                                <a href="{{url($publicUrl.$doc['UriDocumento'])}}"  class="btn btn-dark   " role="button" title="{{'Pulse para descargar el fichero '.$texto}}" style="font-size:1em;" ><i class="material-icons " style="color:white" >description</i></a>
                                </div>
                              @else
                                <div class='input-group-append col-sm-2 justify-content-center'>
                                <a href="{{$doc['UriDocumento']}}"  target="_blank" class="btn btn-dark  " role="button" title="{{'Pulse para abrir el link '.$texto}}" style="font-size:1em;" ><i class="material-icons" style="color:white">language</i></a>
                                </div>
                              @endif

                              <input type="hidden" class="form-control " name='publico' id="{{'publico--doc-'.$doc['id']}}" value="<?php echo($doc['publico'])?>" form="{{'form_id--doc-'.$doc['id']}}">  
                              @if ($doc['publico']==1)
                                  <div class='input-group-append col-sm-1 justify-content-center'>
                                  <button type="button" id="{{'btn_change--doc-'.$doc['id']}}" class="btn btn-dark  justify-content-center " title="Documento en estado público.<?php echo($title)?>"  style='border:2px grey solid' <?php echo($editablePublic)?> <?php echo($hidePublic);?>><i class="material-icons " style="font-size:1em;color:white">public</i> </button>
                                  </div>
                              @else
                                  <div class='input-group-append col-sm-1 justify-content-center'>
                                  <button type="button" id="{{'btn_change--doc-'.$doc['id']}}" class="btn btn-dark justify-content-center " title="Documento en estado privado.<?php echo($title)?>"  style='border:2px grey solid' <?php echo($editablePublic)?> <?php echo($hidePublic);?>><i class="material-icons " style="font-size:1em;color:white">lock</i> </button>
                                  </div>
                              @endif
                              <div class='input-group-append col-sm-1 justify-content-center'>
                              <button type="submit" id="btn_submit--doc-<?php echo ($doc['id']); ?>" class="btn btn-dark justify-content-center " form="{{'form_id--doc-'.$doc['id']}}" style='border:2px grey solid' <?php echo($editableStatus) ?> <?php echo($hidePublic);?>><i class="material-icons " style="font-size:1em;color:white">save</i></button>         
                              </div>
                              <div class='input-group-append col-sm-1 justify-content-center'>
                              <button type="button" id="{{'btn_clear--doc-'.$doc['id']}}" class="btn btn-dark justify-content-center " form="{{'form_id--doc-'.$doc['id']}}" style='border:2px grey solid' <?php echo($editableStatus) ?> <?php echo($hidePublic);?>><i class="material-icons " style="font-size:1em;color:white">delete</i></button>         
                              </div>

                          </div>
                        </form>
                      </div>
                      @endforeach
                    @else
                      <h3> Aún no hay documentos subidos para este proyecto.</h3>
                  @endif
                  
                  @if ($changeDocs)
                  <hr/>
                  <h4 class='text-center'><strong>Agregar documento al proyecto.</strong></h4>
                  <hr/>
                  <form id='form_id-doc-0' name="form_id-doc-0" method='POST' action="<?php echo($apiUrl); ?>documentos_proyectos"> 
                    <div class='form-row'>
                        <label class='col-sm-9'>Descripción del documento:</label>
                        <label class='col-sm-3'>Tipo:</label>
                    </div>
                    <div class='form-row'>
                      <input type="hidden" class="form-control " name='proyecto_id' value="<?php echo($it['id'])?>" form="form_id-doc-0" > 
                      <input type="hidden" class="form-control " name='publico' value="1" form="form_id-doc-0" > 
                      <div class="form-group col-sm-9">
                        <input type="input" class="form-control " name='descripcion' id="descripcion--0" aria-describedby="descripcion" placeholder="Descripcion Documento" form='form_id-doc-0' maxlength="300"  title="Nombre descriptivo del documento" required> 
                      </div>
                      <div class="form-group col-sm-3">
                        <select id="tipoDocumento--0" name='tipoDocumento' form='form_id-doc-0' class='form-control'>
                          @foreach($tiposDoc as $tip)
                            <option value="{{$tip}}" >{{$tip}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group justify-content-center">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input urichange ml-2" type="radio" name="isFile" id="isFile1" value="1" form="form_id-doc-0" checked>
                        <label class="form-check-label urichange" for="isFile1">Fichero</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input urichange ml-2" type="radio" name="isFile" id="isFile2" value="0" form="form_id-doc-0">
                        <label class="form-check-label urichange" for="isFile2">Link</label>
                      </div>
                    </div>
                      <div class="form-row">
                        <label for="fichero" class='col-sm-2' id='lblfichero'>Fichero:</label>
                        <input type='file' class="form-control col-sm-10" id="fichero" name='fichero' form="form_id-doc-0" >
                        <label for="UriDocumento" id='lbluri' class='col-sm-2' style="display:none;">Link:</label>
                        <input type="url" class="form-control col-sm-10" name='UriDocumento' id="UriDocumento" aria-describedby="url" placeholder="Pegue o escriba aqui el link." form="form_id-doc-0" maxlength="200" style="display:none;">
                      </div>
                      <hr/>
                      <div class="form-group text-center">
                        <button type="submit" class="btn btn-dark text-center" form="form_id-doc-0"><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>         
                      </div>
                  </form>
                  @endif

                  </div>
              </div>
          </div>
      </div>



  


  <!--rúbricas -->

      @php
        $grupo=0; 
        $editableIndiv=false;
        $editableCol=false;
        if ($mode=='col' && $it['estado']<4) {$editableCol=true;}
        if ($mode=='ind' && $it['estado']<4) {$editableIndiv=true;}
        $notaInd=0;
        $notaCol=0;

      @endphp

      <div class="row justify-content-center" <?php echo($hidePublic);?>>
          <div class="col-sm-12 mt-2">
              <div class="card bg-secondary sp-2" style='border-width: 5px'>
                  <div class="card-header bg-secondary form-row" style='color:white;'>
                    <h3 class='col-sm-11 text-center'>Rúbricas y valoraciones.</h3> 
                    <button type="button" id="btn_seecard-rub" class="btn btn-dark col-sm-1 notshowing"  title="Ver los detalles de la matrícula."><i class="material-icons" style="font-size:1em;color:white">visibility</i></button>  
                  </div>

                  <div class="card-header bg-success text-center" id="headerSaved-rub" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos modificados.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError-rub" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                  <div name='add_body' id='add_body-rub' class="card-body bg-light" style="display:none;" >  
                      @foreach ($rubricas as $rub)
                        @if ($grupo!=$rub['grupo_rubrica_id'])
                          <hr/>
                          <div class='form-row text-center'>
                            <h3>Grupo de Rúbricas: {{$rub['grupo_rubricas']['grupo']}}
                          </div>
                          <div class="form-row">
                            <div class="form-group col-sm-2">
                              <label >Rúbrica:</label>
                            </div>
                            <div class="form-group col-sm-1">
                              <label >Porcentaje:</label>
                            </div>
                            <div class="form-group col-sm-4 text-center">
                              <label >Valoración y comentario Tut.Colectivo:</label>
                            </div>
                            <div class="form-group col-sm-4 text-center">
                              <label >Valoración y comentario Tut.Individual:</label>
                            </div>
                          </div>
                          @php $grupo=$rub['grupo_rubrica_id'] @endphp
                        @endif
                        <!-- Comprobamos si existe una rúbrica ya valorada o se creará nueva -->
                        @php 
                          $ruta=''; $evalcol=-1; $evalind=-1; $mescol=null; $mesind=null; $frmcol=""; $frmind="";  $idcol=null; $idind=null;
                          $frm="form_id-rub-".$rub['id'];
                          if ($it['tutor_evalua_proyectos']!=null) {
                            foreach ($it['tutor_evalua_proyectos'] as $eval) {
                              if ($eval['rubrica_id']== $rub['id']) {
                                if ($eval['esColectivo']==true) {
                                  $evalcol=$eval['nota'];
                                  $mescol=$eval['comentario'];
                                  $notaCol=$notaCol+($eval['nota']!=-1)*$eval['nota']*$rub['porcentaje']/100;
                                  $idcol=$eval['id'];
                                } else {
                                  $evalind=$eval['nota'];
                                  $mesind=$eval['comentario'];
                                  $notaInd=$notaInd+($eval['nota']!=-1)*$eval['nota']*$rub['porcentaje']/100;
                                  $idind=$eval['id'];
                                }
                              }
                            }
                          }
                          if ($mode=='col') {
                            $ruta=$idcol;  $frmcol=$frm; 
                          } else {
                            $ruta=$idind;  $frmind=$frm;
                          } 
                        @endphp
                        <form id="{{$frm}}" name="{{$frm}}" method='POST' action="<?php echo($apiUrl); ?>tutor_evalua_proyectos/<?php echo($ruta); ?>"> 
                          <div class='form-row'>
                          @csrf
                            <input type="hidden" class="form-control " name='proyecto_id' value="<?php echo($it['id'])?>" form="{{$frm}}" > 
                            <input type="hidden" class="form-control " name='rubrica_id' value="<?php echo($rub['id'])?>" form="{{$frm}}" > 
                            <input type="hidden" class="form-control " name='esColectivo' value="<?php echo(($mode=='col')?'1':'0')?>" form="{{$frm}}" > 
                            <input type="hidden" class="form-control " name='docente_id' value="<?php echo($code)?>" form="{{$frm}}" > 
                            <div class="form-group col-sm-2">
                              <input type="input" class="form-control "  id="rubrica--<?php echo ($rub['id']); ?>" aria-describedby="rubrica" placeholder="rubrica"  maxlength="200" value="<?php echo($rub['rubrica'])?>" title="<?php echo($rub['rubrica'])?>" style='font-size:0.8em;' disabled> 
                            </div>
                            <div class="form-group col-sm-1">
                              <input type="input" class="form-control "  id="porcentaje--<?php echo ($rub['id']); ?>" aria-describedby="porcentaje" placeholder="porcentaje"  value="<?php echo($rub['porcentaje'].' %')?>" disabled> 
                            </div>

                            <div class="form-group col-sm-2">
                              <select id="nota_col--<?php echo ($rub['id']); ?>" name='<?php echo(($mode=='col')?'nota':'')?>' form="{{$frmcol}}" class='form-control' <?php echo(!$editableCol?'disabled':'') ?> > 
                                  <option value='-1' <?php echo(($evalcol==-1)?'selected':'') ?>>Pdte Evaluar</option>                 
                                  <option value="3" <?php echo(($evalcol==3)?'selected':'') ?>>Excelente</option>
                                  <option value="2" <?php echo(($evalcol==2)?'selected':'') ?>>Bien</option>
                                  <option value="1" <?php echo(($evalcol==1)?'selected':'') ?>>Regular</option>
                                  <option value="0" <?php echo(($evalcol==0)?'selected':'') ?>>Insuficiente</option>
                              </select>
                            </div>
                            <div class="form-group col-sm-2">
                              <input type="input" class="form-control " name='<?php echo(($mode=='col')?'comentario':'')?>' id="comentario_col--<?php echo ($rub['id']); ?>" aria-describedby="Comentario Tutor colectivo" placeholder="Comentario Tutor Colectivo" form="{{$frmcol}}" value="{{$mescol}}" title='{{$mescol}}' <?php echo(!$editableCol?'disabled':'' ) ?>> 
                            </div>
                            <div class="form-group col-sm-2">
                              <select id="nota_ind--<?php echo ($rub['id']); ?>" name='<?php echo(($mode=='ind')?'nota':'')?>' form="{{$frmind}}" class='form-control' <?php echo(!$editableIndiv?'disabled':'') ?> > 
                                  <option value='-1' <?php echo(($evalind==-1)?'selected':'') ?>>Pdte Evaluar</option>                 
                                  <option value="3" <?php echo(($evalind==3)?'selected':'') ?>>Excelente</option>
                                  <option value="2" <?php echo(($evalind==2)?'selected':'') ?>>Bien</option>
                                  <option value="1" <?php echo(($evalind==1)?'selected':'') ?>>Regular</option>
                                  <option value="0" <?php echo(($evalind==0)?'selected':'') ?>>Insuficiente</option>
                              </select>
                            </div>
                            <div class="form-group col-sm-2">
                              <input type="input" class="form-control " name='<?php echo(($mode=='ind')?'comentario':'')?>' id="comentario_ind--<?php echo ($rub['id']); ?>" aria-describedby="Comentario Tutor individual" placeholder="Comentario Tutor Individual" form="{{$frmind}}" value="{{$mesind}}" title="{{$mesind}}" <?php echo(!$editableIndiv?'disabled':'' ) ?> > 
                            </div>
                            <div class="form-group col-sm-1">
                              <button type="button" id="btn_see_rub--<?php echo($rub['id']) ?>" class="btn btn-dark text-center notshowing"  title="Ver los detalles de la rúbrica."><i class="material-icons" style="font-size:1em;color:white">visibility</i></button>         
                            </div>
                        </div>
                        <div class='form-group' id='descripciones--<?php echo ($rub['id']); ?>' style="display:none;">
                          <div class='form-group'>
                            <label for='"descExcelente--<?php echo ($rub['id']); ?>"' >Desempeño para obtener calificación de Excelente en esta rúbrica:</label>
                            <textarea class="form-control " rows='1' name='descExcelente' id="descExcelente--<?php echo ($rub['id']); ?>" aria-describedby="excelente" placeholder="excelente"  title="<?php echo($rub['descExcelente'])?>"disabled> {{$rub['descExcelente']}}</textarea>
                          </div>
                          <div class='form-group'>
                            <label for='"descBien--<?php echo ($rub['id']); ?>"' >Desempeño para obtener calificación de Bien en esta rúbrica:</label>
                            <textarea class="form-control " rows='1' name='descBien' id="descBien--<?php echo ($rub['id']); ?>" aria-describedby="bien" placeholder="bien"   title="<?php echo($rub['descBien'])?>"disabled> {{$rub['descBien']}}</textarea>
                          </div>
                          <div class='form-group'>
                            <label for='"descRegular--<?php echo ($rub['id']); ?>"' >Desempeño para obtener calificación de Regular en esta rúbrica:</label>
                            <textarea class="form-control " rows='1' name='descRegular' id="descRegular--<?php echo ($rub['id']); ?>" aria-describedby="regular" placeholder="regular"    title="<?php echo($rub['descRegular'])?>"  disabled> {{$rub['descRegular']}}</textarea>
                          </div>
                          <div class='form-group'>
                            <label for='"descInsuficiente--<?php echo ($rub['id']); ?>"' >Desempeño para obtener calificación de Insuficiente en esta rúbrica:</label>
                            <textarea class="form-control " rows='1' name='descInsuficiente' id="descInsuficiente--<?php echo ($rub['id']); ?>" aria-describedby="insuficiente" placeholder="insuficiente"  title="<?php echo($rub['descInsuficiente'])?>" disabled>{{$rub['descInsuficiente']}} </textarea>
                          </div>
                        </div>
                      </form>
                      @endforeach
                      @if ($editableIndiv || $editableCol || $mode=='admin')
                        <hr/>
                        <div class="form-row text-center">
                          @if ($mode!='admin')
                            <button type="button" class="btn btn-dark text-center col-sm-3" name="forms_id-rub-" id='form_id_rub-'> <i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Guardar cambios.</button>  
                            <div class='col-sm-9 form-row justify-content-center'>
                          @else
                            <div class='col-sm-9 form-row justify-content-center offset-sm-3'>
                          @endif
                            <input type="input" class="form-control  mr-2 col-sm-3 text-center" name='notaCol'  aria-describedby="notaCol" placeholder="notaCol"  
                                value="<?php echo('T.Colec: '.round($notaCol,2).' / 3 = '.round(($notaCol/3*10),0) )?>" disabled> 
                            <input type="input" class="form-control mr-2 col-sm-3 " name='notaInd'  aria-describedby="notaInd" placeholder="notaInd"  
                                value="<?php echo('T.Indiv: '.round($notaInd,2).' / 3 = '.round(($notaInd/3*10),0) )?>" disabled> 
                            <input type="input" style="font-weight:bold;" class="form-control col-sm-3 text-center" name='notaFinal'  aria-describedby="notaFin" placeholder="notaFin"  
                                value="<?php echo('FINAL: '.round((($notaInd+$notaCol)/2),2).' / 3 = '.round((($notaInd+$notaCol)/2)/3*10,0) )?>" disabled> 
                          </div>

                        </div>
                      @endif
                  </div>
              </div>
          </div>
      </div>




  @endif
@section('content')

