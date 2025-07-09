@extends('sige_app.frontend.layouts.app')

@section('title', 'Gestion des candidatures')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class='bx bx-user-plus'></i> Candidatures en attente
                    </h4>
                </div>
                <div class="card-body">
                    {{-- @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif --}}

                    @if($candidatures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Laboratoire</th>
                                        <th>Date candidature</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidatures as $candidature)
                                        <tr>
                                            <td>
                                                <strong>{{ $candidature->nom_user_ext }} {{ $candidature->prenom_user_ext }}</strong>
                                            </td>
                                            <td>{{ $candidature->email_user_ext }}</td>
                                            <td>{{ $candidature->tel_user_ext }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $candidature->laboratoire->label_labo }}</span>
                                            </td>
                                            <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('labo.candidatures.show', $candidature->id_user_ext) }}"
                                                       class="btn btn-sm btn-info">
                                                        <i class='bx bx-show'></i> Voir
                                                    </a>
                                                    <form action="{{ route('labo.candidatures.approve', $candidature->id_user_ext) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('Approuver cette candidature ?')">
                                                            <i class='bx bx-check'></i> Approuver
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('labo.candidatures.reject', $candidature->id_user_ext) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Rejeter cette candidature ?')">
                                                            <i class='bx bx-x'></i> Rejeter
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $candidatures->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class='bx bx-user-plus' style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="mt-3 text-muted">Aucune candidature en attente</h5>
                            <p class="text-muted">Toutes les candidatures ont été traitées.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
