<table class="table table-bordered" >
    <thead>
        <tr>
            <th scope="col">N°</th>
            <th scope="col">Matricule</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Sexe</th>
            <th scope="col">Date Naissance</th>
            <th scope="col">Lieu Naissance</th>
            <th scope="col">N° CNI</th>
            <th scope="col">Tél</th>
            <th scope="col">Région d'Origine</th>
            <th scope="col">Département d'Origine</th>
            <th scope="col">Statut Ins</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td style="width: 30px;"> {{ $loop->index + 1 }} </td>
                <td style="width: 70px;"> {{ $user->code_user}} </td>
                <td style="width: 200px;"> {{ \Str::upper($user->nom_user) }} </td>
                <td style="width: 200px;"> {{ \Str::upper($user->prenom_user) }} </td>
                <td>
                    @if ($user->sexe_user == "MASCULIN")
                            M
                    @else
                            F
                    @endif
                </td>
                <td style="width: 85px;"> {{ $user->date_naissance_user->format("d/m/Y") }} </td>
                <td style="width: 140px;"> {{ $user->lieu_naissance_user }} </td>
                <td style="width: 160px;"> {{ $user->numero_cni_user}} </td>
                <td style="width: 140px;"> {{ $user->first_phone_user}} </td>
                <td style="width: 200px;"> {{ $user->region_origine_user}} </td>
                <td style="width: 200px;"> {{ $user->depart_origine_user}} </td>
                <td style="width: 200px;"> {{ $user->statut_ins}} </td>
            </tr>
        @endforeach
    </tbody>
</table>
