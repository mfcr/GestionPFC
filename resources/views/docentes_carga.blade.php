@section('content')
<script type="text/javascript">
  
  $(document).ready(function() {
    $("#form_id-0").bind('submit', function (e) { //los filtros son propios de cada pantalla. hay que hacer una funcion par cada una
          e.preventDefault();
          var currentId=0;
          var metodo=$(this).attr('method');
          var thisUrl=$(this).attr('action');
          var datawithFile=new FormData(this);
          alert('Se va a proceder a enviar el fichero, esta operación puede tardar.\nEspere por el resultado');
          $('#headerSaved'+currentId).hide("slow");
          $('#headerError'+currentId).hide("slow");
          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
          $.ajax({
              type: metodo,
              url: thisUrl,
              crossDomain: true,
              processData: false,
              contentType: false,            
              data: datawithFile,
              success: function (result,txt,code) { 
                $('#headerSaved'+currentId).show("slow"); //.delay(2000).hide("slow");
                console.log(result);
                console.log(txt);
                console.log(code);
                if (confirm('¿Desea actualizar los menús con los datos actualizados?')) {
                  document.location.href="/";
                }
              },
              error: function (result,txt,code) { 
                $('#headerError'+currentId).show("slow"); //.delay(4000).hide("slow");
                console.log(result.responseText);
                console.log(txt);
                console.log(code);
              },
          });
          return false;
    }); 
  })



</script>

<div class="container">
    <h1 style='text-align: center;' >Carga de fichero de docentes</h1>
      <div class="row justify-content-center">
          <div class="col-sm-7 mt-2">
              <div class="card bg-secondary sp-2" style='border-width: 5px'>
                  <div class="card-header bg-secondary text-center"> <h3 style='color:white;'>Cargue el fichero CSV</h3>   </div>
                  <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Fichero cargado y leido.</h3>   </div>
                  <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ups, Ha habido un error.</h3>   </div>

                  <div class="card-body bg-light " style='font-size: 2em;'>
                      <form id='form_id-0' name='form_id-0' method='POST' action="{{'/DocentesAlta/'.$curso}}" enctype="multipart/form-data"> 
                          @csrf
                          <div class="form-group">
                            <label for="fichero" id='lblfichero'>Cargue el fichero <strong>docentes.csv</strong> para este curso:</label>
                            <input type='file' class="form-control" id="fichero" name='fichero' form='form_id-0'  required>
                          </div>
                          <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark text-center" form='form_id-0'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar</button>         
                          </div>
                        </form>

                    </div>
              </div>
          </div>
      </div>
</div>
@section('content')

