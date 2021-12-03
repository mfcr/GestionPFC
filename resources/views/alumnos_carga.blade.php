@section('content')
<script type="text/javascript">
  
  $(document).ready(function() {
    $("#form_id-0").bind('submit', function (e) { 
          e.preventDefault();
          $(document.body).css('cursor','wait');
    
          var currentId=0;
          var metodo=$(this).attr('method');
          var thisUrl=$(this).attr('action');
          alert('Se va a proceder a enviar el fichero, esta operación puede tardar.\nEspere por el resultado');
          $('#headerSaved'+currentId).hide();
          $('#headerError'+currentId).hide();
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
                $('#headerSaved'+currentId).show("slow"); //.delay(4000).hide("slow");
                $(document.body).css('cursor','default');     
                if (confirm('¿Desea actualizar los menús con los datos actualizados?')) {
                  document.location.href="/";
                }
                console.log(result);
                console.log(txt);
                console.log(code);
              },
              error: function (result,txt,code) { 
                $('#headerError'+currentId).show("slow"); //.delay(5000).hide("slow");
                $(document.body).css('cursor','default');             
                console.log(result);
                console.log(txt);
                console.log(code);
              },

          });
          return false;
    }); 
  })



</script>

<div class="container">
    <h1 style='text-align: center;' >Carga de fichero de alumnos</h1>
      <div class="row justify-content-center">
          <div class="col-sm-7 mt-2">
              <div class="card bg-secondary sp-2" style='border-width: 5px'>
                  <div class="card-header bg-secondary text-center"> <h3 style='color:white;'>Cargue el fichero CSV</h3>   </div>
                  <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Fichero cargado y leido.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error en la carga.</h3>   </div>

                  <div class="card-body bg-light " style='font-size: 2em;'>
                      @if ($ciclos!=null)
                      <form id='form_id-0' name='form_id-0' method='POST' action="Alumnos/AltaFile" enctype="multipart/form-data"> 
                          @csrf
                          <div class="form-row" >
                            <label for="curso" class='col-sm-3'>Curso:</label>
                            <input type="number" id="curso" name="curso" onKeyDown="return false"   class="col-sm-3 text-center form-control"   min="<?php echo($curso);?>"  value="<?php echo($curso);?>" form='form_id-0'>
                          </div>
                          <div class="form-row">
                            <label for="ciclo_id" class='col-sm-3'>Ciclo:</label>
                            <select id="ciclo_id" class="col-sm-9 form-control" name='ciclo_id' form='form_id-0'>
                                @foreach ($ciclos as $ci)
                                    <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                                @endforeach
                            </select>
                          </div>
                          <div class="form-row">
                            <label for="fichero" id='lblfichero' class='col-sm-3'>Fichero:</label>
                            <input type='file' class="form-control col-sm-9" id="fichero" name='fichero' form='form_id-0' required>
                          </div>
                          <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark text-center" form='form_id-0'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar</button>         
                          </div>
                        </form>
                      @else
                        <h2>Para poder comenzar a cargar alumnos debe crear primero los ciclos de estudios.</h2>
                      @endif

                    </div>
              </div>
          </div>
      </div>
</div>
@section('content')

