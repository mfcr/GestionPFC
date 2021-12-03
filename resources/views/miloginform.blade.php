@section('content')

<script> 

$(document).ready(function() {

    $("[id^='form_id-']").bind('submit', function (e) {
          var currentId=$(this).attr('id').split('-')[1]; //Obtenemos el ID del form.
          e.preventDefault();
          if (currentId==2) {
            if(!($('#password-2').val() === $('#password_check-2').val())) {
                return false;
            }
          }
          $(document.body).css({'cursor' : 'wait'});
          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')    }});
          $.ajax({
              type: $(this).attr('method'),
              url: $(this).attr('action'),
              data: $(this).serialize(),
              crossDomain: true,

              success: function (result,txt,code) {
                if (result[0]=='Ok') { //Entrar
                    $('#headerSaved-'+currentId).show("slow").delay(1000).hide("slow");                    
                    window.location.href = '/';
                } else { //Mostrar formulario cambio de contraseña
                    $('#change').show();
                    $('#log').hide();
                }
              },
              error: function (result,txt,code) { 
                $('#headerError-'+currentId).show("slow").delay(4000).hide("slow");
              },
          });
          $(document.body).css({'cursor' : 'default'});
          return false;
    }); 
    $('#password-2, #password_check-2').on('keyup', function () {
      if ($('#password-2').val() === $('#password_check-2').val()) {
            $('#message').html('Matching').css('color', 'green');
      } else {
            $('#message').html('Not Matching').css('color', 'red');
      }
    });

});
</script>

    <div class="container">
        <div id='log' name='log' class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-center" id="headerSaved-1"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos correctos, accediendo.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="headerError-1" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Datos incorrectos, inténtelo de nuevo.</h3>   </div>
                    <h3 class="card-header text-center">Login</h3>

                    <div class="card-body">
                        <form id='form_id-1' method="POST" action="/custom_login" >
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" placeholder="Email" id="email" class="form-control" name="email" form='form_id-1' required
                                    autofocus>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="Password" id="password-1" class="form-control" name="password"  form='form_id-1' required>
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="d-grid mx-auto">
                                <button type="submit" form='form_id-1' class="btn btn-dark btn-block">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div id='change' name='change' class="row justify-content-center" style="display:none;">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-center" id="headerSaved-2"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Datos correctos, accediendo.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="headerError-2" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Datos incorrectos, inténtelo de nuevo.</h3>   </div>
                    <h4 class="card-header text-center">Este es su primer acceso, debe cambiar su contraseña.</h4>

                    <div class="card-body">
                        <form id='form_id-2' method="POST" action="/changePassword" >
                            @csrf
                            <div class="form-group mb-3">
                                <input type="password" minlength='8' placeholder="Password" id="password-2" class="form-control" name="password"  form='form_id-2' value="" required>
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="Repita la contraseña" minlength='8' id="password_check-2" class="form-control" name="password_check"  value="" form='form_id-2' required>
                                <span id="message"></span>
                            </div>

                            <div class="d-grid mx-auto">
                                <button type="submit" form='form_id-2' class="btn btn-dark btn-block">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>



    </div>


@section('content')
