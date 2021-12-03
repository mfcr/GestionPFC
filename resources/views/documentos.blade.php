@section('content')
<div class="container">
    <h1 style='text-align: center;'>Documentos 
        @if ($user==0) publicos @endif 
        @if ($ciclo_doc!=null) {{' del ciclo: '.$ciclo_doc['nombreCiclo']}} @else Generales @endif
    </h1>

    @if ($docs!=null)
        <div class="row justify-content-center">
            @foreach ($docs as $doc)
                <div class="col-sm-12 mt-2">
                    <div class="card bg-secondary sp-2 mt-2" style='border-width: 5px'>
                        <div class="card-header bg-secondary text-center"> <h3 style='color:white;'>{{$doc['nombre']}}</h3>   </div>
                        <div class="card-body bg-light">
                            <div class='form-row'>
                               <label  class='col-sm-2'>Tipo:</label>
                               <p style='font-size:2em;' class='col-sm-10' >{{$doc['tipo']}}</p>
                            </div>
                            <div class='form-row'>
                               <label  class='col-sm-2'>Descripción:</label>
                               <p class='col-sm-10' style='font-size:1.5em;' >{{$doc['descripcion']}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-secondary text-center" >
                        @if  ($doc['uri']=='')
                            <button class='btn btn-light col-sm-11' style="font-size:2em;"disabled><i class="material-icons col-sm-1" style="color:red">dangerous</i> No hay link al recurso.</button>
                        @else
                            @if ($doc['isFile']==1)
                                <a href="{{url($publicUrl.$doc['uri'])}}" class="btn btn-light col-sm-11" role="button" style="font-size:2em;"><i class="material-icons mr-3 " style="color:black" >description</i> Pulse para descargar el documento.</a>
                            @else
                                <a href="{{$doc['uri']}}"  target="_blank" class="btn btn-light col-sm-11" role="button" style="font-size:2em;"><i class="material-icons col-sm-1" style="color:black">travel_explore</i>Link al recurso: {{$doc['uri']}}</a>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-body bg-light">
                        <h2 style='text-align: center;'>No hay documentos guardados. </br></h2>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@section('content')