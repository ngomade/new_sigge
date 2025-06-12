@extends("sige_app.backend.template.backend")
@section("content")
<main id="main" class="main">
    <div class="pagetitle">
      <h1>Publication d'une Actualité</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/admin-index">Tableau de Bord</a></li>
          <li class="breadcrumb-item active">Publication d'une Actualité</li>
        </ol>
      </nav>
    </div>
    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-10 m-auto">
            <div class="card">
                <div class="card-header bg-primary mb-3 p-2" >
                    <span class="modal-title h4" style="color: white !important;">Publication d'une Actualité</span>
                </div>
                <form action="/publier_actualite" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <p class="alert alert-success text-center">Veuillez renseigner les informations de l'actualité. Les champs marqués par un <span class="text-danger">*</span> sont obligatoires.</p>
                        <div class="col-sm-12 mb-3">
                            <div class="row">
                                <label for="actu_title" class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Titre de l'actu: " name="actu_title" id="actu_title" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div class="row">
                                <div class="col-sm-12 .bordered">
                                    <textarea class="tinymce-editor" name="actu_content" id="actu_content" placeholder="Veuillez saisir le contenu de votre message">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="photo1" class="col-sm-4 col-form-label .bordered">Photo 1 <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" placeholder="Exp: Dschang" name="photo1" id="photo1" accept="image/png, image/jpeg, image/gif, image/bmp">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="photo2" class="col-sm-4 col-form-label">Photo 2 <span class="text-danger">*</span> </label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" placeholder="Exp: Dschang" name="photo2" id="photo2" accept="image/png, image/jpeg, image/gif, image/bmp">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="photo3" class="col-sm-4 col-form-label">Photo 3</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" placeholder="Exp: Dschang" name="photo3" id="photo3" accept="image/png, image/jpeg, image/gif, image/bmp">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="photo4" class="col-sm-4 col-form-label">Photo 4 </label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" placeholder="Exp: Dschang" name="photo4" id="photo4" accept="image/png, image/jpeg, image/gif, image/bmp">
                                    </div>
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
