@extends('sige_app.backend.template.backend')
@section('content')
    <?php $user = \Session::get('user'); ?>
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary p-2" style="color: white">
                    <h5 class="modal-title" style="color: white">Ajout d'une division</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/ajouter_division" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <select name="code_ecole" id="code_ecole" class="form-select">
                                    @foreach (\App\Models\Ecole::all() as $ecole)
                                        <option value="{{ $ecole->code_ecole }}"> {{ $ecole->intitule_ecole }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <input type="text" class="form-control" placeholder="Code Division" name="code_division"
                                    id="code_division" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <input type="text" class="form-control" placeholder="Label de la division"
                                    name="label_div" id="label_div" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <textarea class="tinymce-editor w-100" name="desc_div" id="desc_div"
                                    placeholder="Veuillez faire une breve description de la division ici" rows="8">
                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-0">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card" style="width: 90%; margin:auto;">

        <div class="card-header" style="text-align: right;">
            <h2 style="float: left;">Nos Divisions</h2>
            <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal"
                data-bs-target="#addModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N° </th>
                            <th>Code </th>
                            <th>Label</th>
                            <th>Description</th>
                            <th>date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (\App\Models\Division::all() as $division)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $division->code_division }} </td>
                                <td>{{ $division->label_div }}</td>
                                <td style="width: 30%; overflow: hidden;"> {!! $division->desc_div !!} </td>
                                <td> {{ $division->created_at->format('d/m/Y H:i') }} </td>
                                <td style="text-align: center;">
                                    <a href="/delete_division/{{ $division->code_division }}"
                                        class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                    <a href="/update_division/{{ $service->code_division }}"
                                        class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
