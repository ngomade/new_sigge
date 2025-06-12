@extends("sige_app.frontend.template.frontend")
@section('js')

@endsection
@section('content')
    <div class="container mt-3">
        <div class="card card-form">
            <div class="card-header p-1 pt-2" style="background-color: green; color:white;">
                <h4>Liste des éléments constitutifs (ECs)</h4>
            </div>
          <div class="card-body" style="background-color: rgb(245,245,249);">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Code UE</th>
                      <th>Code EC</th>
                      <th>Intitulé</th>
                      <th>Crédits</th>
                      <th>VH</th>
                      <th>CM</th>
                      <th>TD</th>
                      <th>TP</th>
                      <th>TPE</th>
                      @if($inscription->statut_ins >= 1)
                        <th>Actions</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($ues as $ue)
                    <tr>
                        <td rowspan="{{$ue->ecs->count()+1}}" style="vertical-align: middle;">{{$ue->code_ue}}</td>
                    </tr>
                        @foreach ($ue->ecs as $ec)
                        <tr>
                            <td > {{$ec->code_ec}}  </td>
                            <td>{{$ec->intitule_ec}}</span></td>
                            <td> {{$ec->credit_ec}}  </td>
                            <td> {{$ec->vh_ec}}  </td>
                            <td> {{$ec->cm_ec}}  </td>
                            <td> {{$ec->td_ec}}  </td>
                            <td> {{$ec->tp_ec}}  </td>
                            <td> {{$ec->tpe_ec}}  </td>
                            @if($inscription->statut_ins  >= 1 || $inscription->statut_ins  == 0)
                                <td style="text-align: center;">
                                    <a class="btn-success" href="/download_ec/{{$ec->code_ec}}"
                                        target="blank" ><i class="ri-folder-download-fill"></i> Télécharger</a>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    @endforeach
                  </tbody>
                </table>
              </div>
          </div>
        </div>
    </div>
@endsection
