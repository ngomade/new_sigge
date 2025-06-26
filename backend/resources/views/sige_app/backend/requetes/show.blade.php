@extends("sige_app.frontend.template.frontend")

@section('css')
@endsection

@section("js")
<script>
    let confirmDeleteModal;
    let currentDeleteForm = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation du modal de suppression
        confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

        // Fonction d'impression améliorée
        window.printProgressTable = function() {
            var printContents = document.getElementById('progressTable').innerHTML;
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Suivi de la requête</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        @media print {
                            .btn { display: none !important; }
                            body { margin: 20px; }
                        }
                    </style>
                </head>
                <body>
                    <h3>Suivi du parcours de la requête</h3>
                    ${printContents}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        };

        // Gestion des boutons de suppression de fichiers
        document.querySelectorAll('.delete-fichier-form').forEach(form => {
            const deleteBtn = form.querySelector('button[type="submit"]');
            
            // Empêcher la soumission par défaut
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                return false;
            });

            // Gérer le clic sur le bouton
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const fileName = this.closest('li').querySelector('div:first-child').textContent.trim().split('(')[0].trim();
                
                // Stocker la référence du formulaire
                currentDeleteForm = form;
                
                // Mettre à jour le contenu du modal
                document.getElementById('fileNameToDelete').textContent = fileName;
                
                // Afficher le modal
                confirmDeleteModal.show();
            });
        });

        // Confirmation de la suppression
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentDeleteForm) {
                // Désactiver le bouton pour éviter les doubles clics
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Suppression...';
                
                confirmDeleteModal.hide();
                
                // Créer un nouveau formulaire temporaire
                const tempForm = document.createElement('form');
                tempForm.method = 'POST';
                tempForm.action = currentDeleteForm.action;
                tempForm.style.display = 'none';
                
                // Copier les inputs CSRF et METHOD
                const csrfToken = currentDeleteForm.querySelector('input[name="_token"]').value;
                const methodInput = currentDeleteForm.querySelector('input[name="_method"]').value;
                
                tempForm.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="${methodInput}">
                `;
                
                document.body.appendChild(tempForm);
                tempForm.submit();
            }
        });

        // Réinitialiser le bouton quand le modal se ferme
        document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function() {
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Supprimer le fichier';
            currentDeleteForm = null;
        });
    });
</script>
@endsection

@section('content')
<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-danger shadow">
            <div class="modal-header bg-danger p-2 d-flex justify-content-between align-items-center" style="color: white">
                <h5 class="modal-title mb-0" style="color: white">{{ $requete->titre_requete }}</h5>
                <a href="{{ route('requetes.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
            </div>
            <div class="modal-body">
                <!-- Détails de la requête -->
                <div class="mb-3">
                    <h6 class="text-muted">
                        <i class="fas fa-barcode me-1"></i>Code: {{ $requete->code_requete }}
                    </h6>
                </div>

                <!-- Progress Tracking Table -->
                <div class="mb-4" id="progressTable">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6><i class="fas fa-route me-2"></i>Suivi du parcours de la requête</h6>
                        <button class="btn btn-sm btn-outline-primary" onclick="printProgressTable()">
                            <i class="fas fa-print me-1"></i>Imprimer
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-tasks me-1"></i>Étape</th>
                                    <th><i class="fas fa-calendar me-1"></i>Date</th>
                                    <th><i class="fas fa-building me-1"></i>Bureau</th>
                                    <th><i class="fas fa-user-tie me-1"></i>Personne en charge</th>
                                    <th><i class="fas fa-paper-plane me-1"></i>Expéditeur</th>
                                    <th><i class="fas fa-inbox me-1"></i>Destinataire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($progressSteps as $step)
                                <tr>
                                    <td>{{ $step['step'] }}</td>
                                    <td>{{ $step['date'] ? $step['date']->format('d/m/Y à H:i') : 'Non effectué' }}</td>
                                    <td>{{ $step['bureau']->label_bureau ?? 'N/A' }}</td>
                                    <td>{{ $step['manager'] ? $step['manager']->nom_pers . ' ' . $step['manager']->prenom_pers : 'N/A' }}</td>
                                    @if($step['step'] === 'Soumission')
                                        <td>{{ $requete->user->nom_user ?? $requete->user->nom_pers ?? 'N/A' }}</td>
                                    @else
                                        <td>{{ $step['sender'] ? $step['sender']->nom ?? $step['sender']->nom_pers ?? 'N/A' : 'N/A' }}</td>
                                    @endif
                                    <td>{{ $step['recipient'] ? $step['recipient']->nom ?? $step['recipient']->nom_pers ?? 'N/A' : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $progressSteps->appends(request()->except('page'))->links('pagination::bootstrap-5', ['prevText' => 'Précédent', 'nextText' => 'Suivant']) }}
                    </div>
                </div>

                 <!-- Fichiers joints --> 
                @if($requete->fichiers->count() > 0)
                    <div class="mb-3"> 
                        <h6><i class="fas fa-paperclip me-2"></i>Fichiers joints</h6>
                        <ul class="list-group"> 
                            @foreach($requete->fichiers as $fichier) 
                                <li class="list-group-item d-flex justify-content-between align-items-center"> 
                                    <div> 
                                        <i class="fas fa-file me-2 text-muted"></i> 
                                        {{ $fichier->nom_original }} 
                                        <small class="text-muted">({{ number_format($fichier->taille / 1024, 2) }} KB)</small>
                                    </div> 
                                    <div>
                                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye me-1"></i>Examiner
                                        </a>
                                        @if($requete->status === 'en attente')
                                            <form action="{{ route('requetes.deleteFichier', $fichier->id_fichier) }}" method="POST" class="d-inline delete-fichier-form"> 
                                                @csrf
                                                @method('DELETE') 
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                 @endif

                <!-- Réponses -->
                @if($requete->reponses && $requete->reponses->count() > 0)
                    <div class="mb-3">
                        <h6><i class="fas fa-comments me-2"></i>Réponses</h6>
                        <div class="list-group">
                            @foreach($requete->reponses as $reponse)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong><i class="fas fa-user me-1"></i>{{ $reponse->user->nom ?? 'Administrateur' }}</strong>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $reponse->created_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                    <p class="mb-0">{{ $reponse->contenu }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer justify-content-between">
                <a href="{{ route('requetes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list me-1"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression de fichier -->

@endsection