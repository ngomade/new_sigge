@extends("sige_app.frontend.template.frontend")
@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-header p-2 pt-2" style="text-align: justify;">
                <div style="text-align: center;" class="h3">Organigramme de L'ESTLC</div>
            </div>
            <div class="card-body container">
                <div class="row mb-4 mt-2">
                    <div class="col-sm-12">
                        <img src="{{asset("sige_app/frontend/img/organigramme.png")}}" alt="organigramme de l'ESTLC" width="100%;" >
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
