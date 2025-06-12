@extends('sige_app.frontend.template.frontend')
@section('style')

    <link rel="stylesheet" href="{{ asset('share/css/fiche_academique.css') }}">
@endsection
@section("js")
<script>
    document.getElementById('select-all1').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('input[name="selected_ues1[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
    });
    document.getElementById('select-all2').addEventListener('change', function(e) {
        const checkboxes1 = document.querySelectorAll('input[name="selected_ues2[]"]');
        checkboxes1.forEach(checkbox => checkbox.checked = e.target.checked);
    });
    niveau = document.getElementById("niveau").value
    const elements1 = document.getElementById('S1');
    const elements2 = document.getElementById('S2');
    if (niveau != 1) {
        $('#S1').fadeOut(500);
    }
    function rattrape(e){
        if(e.checked){
            $('#S1').fadeIn(500);
        }else{
            $('#S1').fadeOut(500);
        }
    }
</script>
@endsection
@section('content')
    <input type="hidden" value="{{$filiere_niveau->code_niveau}}" id="niveau">
    <div class="container mt-3">
        <div class="card card-form">
            <div class="card-header p-1 pt-2" style="background-color: green; color:white; text-align: center;">
                <h3>Inscription Academique de <span class="info-titre"> {{ $user->nom_user }} {{ $user->prenom_user }}</span>
                    Matricule
                    <span class="info-titre">{{ $user->code_user }} </span>
                </h3>
            </div>
            <div class="card-body" style="background-color: rgb(245,245,249); text-align: justify;">
                <div class="alert alert-success text-justify">
                    Bienvenue sur la plateforme d'inscription acadmémique. Ci-dessous la liste de vos différentes unités
                    d'enseignements. (UEs)
                    Veuillez vous inscrire à chacune d'elle en la cochant afin de pouvoir participer à tout examens les
                    concernant.
                    <span class="text-danger">Pour les étudiants du niveau 2 ayant encore des ECs à rattraper au niveau 1, bien vouloir côcher
                    uniquement les UEs contenant ces ECs!!!!</span>
                </div>
                @if($filiere_niveau->code_niveau != 1)
                    <div class="text-center">
                        <input type="checkbox" name="rattrape" id="rattrape" onclick="rattrape(this)"> &nbsp;
                        <label for="rattrape" class="h5">Cochez ici si vous rattrapez certaines matières</label>
                    </div>
                @endif
                <div class="row">
                        <form action="/academique_inscription" method="post">
                            {{ csrf_field() }}
                            @if ($filiere_niveau->code_niveau == 2)
                            <table class="table table-bordered w-100" id="S3">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>N°</th>
                                        <th>CODE UE</th>
                                        <th>INTITULE</th>
                                        <th>SEM</th>
                                        <th>CREDIT</th>
                                        <th><input type="checkbox" id="select-all1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ues as $ue)
                                        @if ($ue->code_sem == "S3" || $ue->code_sem == "S4")
                                        <tr>
                                            <td>{{$loop->index +1}}</td>
                                            <td> {{$ue->code_ue}} </td>
                                            <td> {{$ue->intitule_ue}} </td>
                                            <td> {{$ue->semestre->code_sem}} </td>
                                            <td style="text-align: center;">{{\App\Helper\get_nb_credit($ue->code_ue)}}</td>
                                            <td style="text-align: center;"> <input type="checkbox" name="selected_ues1[]" value="{{$ue->code_ue}}"></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    {{-- <tr>
                                        <td colspan="4" style="text-align: center; font-weight: bold;"> TOTAL</td>
                                        <td colspan="2" style="font-weight: bold; text-align: center; color:white;"
                                            class="bg-success">{{$credit}} </span></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                            @endif
                            @if ($filiere_niveau->code_niveau == 2 || $filiere_niveau->code_niveau == 1)
                            <table class="table table-bordered w-100" id="S1">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>N°</th>
                                        <th>CODE UE</th>
                                        <th>INTITULE</th>
                                        <th>SEM</th>
                                        <th>CREDIT</th>
                                        <th><input type="checkbox" id="select-all2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $credit = 0;?>
                                    @foreach ($ues as $ue)
                                    @if ($ue->code_sem == "S1" || $ue->code_sem == "S2")
                                    <tr>
                                        <td>{{$loop->index +1}}</td>
                                        <td> {{$ue->code_ue}} </td>
                                        <td> {{$ue->intitule_ue}} </td>
                                        <td> {{$ue->semestre->code_sem}} </td>
                                        <td style="text-align: center;">{{\App\Helper\get_nb_credit($ue->code_ue)}}</td>
                                        <td style="text-align: center;"> <input type="checkbox" name="selected_ues2[]" value="{{$ue->code_ue}}"></td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    {{-- <input type="hidden" name="total_credit" value="{{$credit}}">
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-weight: bold;"> TOTAL</td>
                                        <td colspan="2" style="font-weight: bold; text-align: center; color:white;"
                                            class="bg-success">{{$credit}} </span></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                            @endif
                            <div class="valider">
                                <input class="btn btn-outline-primary" type="submit"
                                    value="Je valide mon inscription académique">
                            </div>
                        </form>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
