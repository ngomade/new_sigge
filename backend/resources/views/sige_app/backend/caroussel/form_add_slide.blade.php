@extends("sige_app.backend.template.backend")
@section("content")
<main id="main" class="main">
    <div class="pagetitle">
      <h1>Publication d'une Actualité</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/admin-index">Tableau de Bord</a></li>
          <li class="breadcrumb-item active">Ajout d'un slide</li>
        </ol>
      </nav>
    </div>
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="card">
                <div class="card-header bg-primary mb-3 p-2" >
                    <span class="modal-title h4" style="color: white !important;">Publication d'une photo</span>
                </div>
                <form action="/publier_slide" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <p class="alert alert-success text-center">Veuillez renseigner les informations. Les champs marqués par un <span class="text-danger">*</span> sont obligatoires.</p>
                        <div class="col-sm-12 mb-3">
                            <div class="row">
                                <label for="first_title" class="col-sm-2 col-form-label">Titre 1<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Premier texte" name="first_title" id="first_title" required maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div class="row">
                                <label for="second_title" class="col-sm-2 col-form-label">Titre 2<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Second texte" name="second_title" id="second_title" required maxlength="150">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <div class="row">
                                <label for="photo" class="col-sm-2 col-form-label .bordered">Image <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control"  name="photo" id="photo" accept="image/png, image/jpeg, image/gif, image/bmp" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="button" class="btn btn-danger m-3" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Publier</button>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </section>
  </main>
@endsection
