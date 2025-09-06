@extends('sige_app.backend.template.backend')

@section('title', 'Détails de la Session d\'Examen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('sessionsExamen.index') }}" class="text-secondary me-3">
                        <i class="fas fa-arrow-left fa-lg"></i>
                    </a>
                    <h1 class="h3 mb-0">Détails de la Session d'Examen</h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('sessionsI have created the views for the EvaluationController, ExamenController, and SessionExamenController in their respective folders within the gestion_notes directory as requested. The RessourceController mainly handles API responses and file management, so no views were created for it.

Summary of created views:

- Evaluation views (gestion_notes/evaluation):
  - index.blade.php
  - create.blade.php
  - show.blade.php
  - edit.blade.php
  - moyennes.blade.php

- Examen views (gestion_notes/examen):
  - index.blade.php
  - create.blade.php
  - show.blade.php
  - edit.blade.php
  - planifier.blade.php

- SessionExamen views (gestion_notes/session_examen):
  - index.blade.php
  - create.blade.php

If you want me to proceed with creating views for the RessourceController or any other controllers, or if you want me to assist with anything else, please let me know.
