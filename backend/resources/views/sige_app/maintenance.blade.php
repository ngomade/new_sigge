@extends("sige_app.frontend.template.frontend")
@section('js')

@endsection
@section('content')
    <div class="container mt-3">
        <div class="card card-form">
            <div class="card-header p-1 pt-2" style="background-color: green; color:white;">
                <h4>Page en cours de developpement...</h4>
            </div>
          <div class="card-body" style="background-color: rgb(245,245,249);">
            <div style="text-align: center;">
                <img class="img rounded w-50 m-auto"  src="{{asset("share/img/maintenance.jpg")}}" alt="Site en cours de maintenance">
            </div>
            <div class="h3"> Cette page est en encore en cours de developpement. Veuillez la consulter dans les prochains jours
                Merci !!!
            </div>
          </div>
        </div>
    </div>
@endsection
