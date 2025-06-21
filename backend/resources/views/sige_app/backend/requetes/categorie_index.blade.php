@extends("sige_app.backend.template.backend")
@section("js")
<script>
function confirmDelete(code_cat, label_cat) {
    if (confirm('Êtes-vous sûr de vouloir supprimer la catégorie "' + label_cat + '" ?\n\nCette action est irréversible.')) {
        document.getElementById('delete-form-' + code_cat).submit();
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection

@section("content")
{{-- <?php $user = \Session::get("user");?> --}}

<!-- Modal d'ajout -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'une catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.requetes.categories.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <input type="text" class="form-control" placeholder="Nom de la catégorie" name="label_cat" id="label_cat" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <textarea class="form-control" name="desc_cat" id="desc_cat" placeholder="Description de la catégorie (optionnel)" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals de modification (générés dynamiquement) -->
@foreach ($categories as $category)
<div class="modal fade" id="editModal{{$category->code_cat}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning p-2">
                <h5 class="modal-title text-dark">Modification de la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.requetes.categories.update', $category->code_cat) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <input type="text" class="form-control" placeholder="Nom de la catégorie" name="label_cat" value="{{$category->label_cat}}" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <textarea class="form-control" name="desc_cat" placeholder="Description de la catégorie (optionnel)" rows="4">{{$category->desc_cat}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="card" style="width: 90%; margin:auto;">
    <div class="card-header" style="text-align: right;">
        <h2 style="float: left;">Gestion des Catégories</h2>
        <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal" data-bs-target="#addModal">
            Ajouter &nbsp; <i class="ri-add-circle-fill"></i>
        </button>
    </div>
    
    <div class="card-body">
        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Code</th>
                        <th>Nom de la catégorie</th>
                        <th>Description</th>
                        <th>Nombre de requêtes</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge bg-info">{{ $category->code_cat }}</span></td>
                        <td><strong>{{ $category->label_cat }}</strong></td>
                        <td style="width: 30%; overflow: hidden;">
                            {{ $category->desc_cat ? Str::limit($category->desc_cat, 100) : 'Aucune description' }}
                        </td>
                        <td>
                            {{-- Vérification de l'existence de la relation avant de l'utiliser --}}
                            @php
                                try {
                                    $requestCount = $category->requests()->count();
                                } catch (\Exception $e) {
                                    $requestCount = 0;
                                }
                            @endphp
                            <span class="badge bg-secondary">{{ $requestCount }}</span>
                        </td>
                        <td>{{ $category->created_at->format("d/m/Y H:i") }}</td>
                        <td style="text-align: center;">
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{$category->code_cat}}" title="Modifier">
                                <i class='bx bx-pencil'></i>
                            </button>
                            
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{$category->code_cat}}', '{{$category->label_cat}}')" title="Supprimer">
                                <i class='bx bx-trash'></i>
                            </button>
                            
                            <!-- Formulaire de suppression caché -->
                            <form id="delete-form-{{$category->code_cat}}" action="{{ route('admin.requetes.categories.destroy', $category->code_cat) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="py-4">
                                <i class="bx bx-folder-open" style="font-size: 3em; color: #ccc;"></i>
                                <p class="mt-2 text-muted">Aucune catégorie trouvée</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection