years = ""
for (let i =2023; i >1985; i--){
        years+= "<option value="+i+">"+i+"</option>";
}
annee = ""
for (let i =2023; i <=2100; i++){
    annee+= "<option value="+i+">"+i+"</option>";
}
$("#ca_annee_diplome").html("")
$("#ca_annee_diplome").append(years)

$("#annee").html("")
$("#annee").append(annee)
$("#annee_edit").append(annee)

$("#ca_nationali").fadeOut(50)
function changePays(pays) {
    if (pays != "CMR") {
        $("#region").fadeOut(500)
        $("#departement").fadeOut(500)
        $("#ca_nationali").fadeIn(50)
    }else{
        $("#region").fadeIn(500)
        $("#departement").fadeIn(500)
        $("#ca_nationali").fadeOut(50)
        regions = ["Adamaoua","Centre","Est","Extrême-Nord","Littoral", "Nord", "Nord-Ouest", "Ouest","Sud","Sud-Ouest "]
        content = "";
        regions.forEach(
            (r)=>{
                content+="<option value="+r+">"+r+"</option>";
        });
        $("#region_origine").html("<option value=''>Votre région ?</option>")
        $("#region_origine").append(content)
    }
}

function changeDepart(region) {
    $departement = [
        {
            "nom": "Adamaoua",
            "depart": ["Djérem","Faro-et-Déo","Mayo-Banyo","Mbéré","Vina"]
        },
        {
            "nom": "Centre",
            "depart": ["Haute-Sanaga","Lekié","Mbam-et-Inoubou", "Mbam-et-Kim","Méfou-et-Afamba","Méfou-et-Akono", "Mfoundi","Nyong-et-Kellé","Nyong-et-Mfoumou", "Nyong-et-So’o" ]
        },
        {
            "nom": "Est",
            "depart": ["Boumba-et-Ngoko","Haut-Nyong","Kadey","Lom-et-Djérem"]
        },
        {
            "nom": "Extrême-Nord",
            "depart": ["Diamaré","Logone-et-Chari","Mayo-Danay","Mayo-Kani","Mayo-Sava", "Mayo-Tsanaga"]
        },
        {
            "nom": "Littoral",
            "depart": ["Moungo","Nkam","Sanaga-Maritime","Wouri"]
        },
        {
            "nom": "Nord",
            "depart": ["Bénoué","Faro","Mayo-Louti","Mayo-Rey"]
        },
        {
            "nom": "Nord-Ouest",
            "depart": ["Boyo","Bui","Donga-Mantung","Menchum", "Mezam", "Momo", "Ngo-Ketunjia"]
        },
        {
            "nom": "Ouest",
            "depart": ["Bamboutos","Haut-Nkam","Hauts-Plateaux","Koung-Khi", "Menoua", "Mifi", "Ndé", "Noun"]
        },
        {
            "nom": "Sud",
            "depart": ["Dja-et-Lobo","Mvila","Océan","Vallée-du-Ntem"]
        }
        ,
        {
            "nom": "Sud-Ouest",
            "depart": ["Fako","Koupé-Manengouba","Lebialem","Manyu", "Meme", "Ndian"]
        }
    ]
    $departement.forEach(
        (dept)=>{
            if(dept.nom == region){
                contenu = ""
                dept.depart.forEach(
                       (d)=>{
                        contenu += "<option value="+d+">"+d+"</option>";
                       }
                );
            }
        }
    );
    $("#depart_origine").html("")
    $("#depart_origine").append(contenu)
}

function changeSerie(serie) {
    BACC = ["A", "ACA", "ACE", "BT", "C", "CG", "D", "E", "F", "F1", "F2", "F3", "F4", "F5", "F6", "F7", ,"MEB", "ESF", "IH", "TI"]
    GCE = ["Arts", "Sciences"]
    //cursus = document.getElementById("cursus").value;
    if (serie == "BACCALAUREAT") {
       // if (cursus == "EBTTL") {
            content = ""
            BACC.forEach((b)=>{content += "<option value="+b+">"+b+"</option>";})
        /*} else {
            content = ""
            BACC.forEach((b)=>{if (b != "A") {
                content += "<option value="+b+">"+b+"</option>";
            }})
        }*/
    } else {
        //if (cursus == "EBTTL") {
        content = ""
        GCE.forEach((b)=>{content += "<option value="+b+">"+b+"</option>";})
        /*} else {
            content = ""
            GCE.forEach((b)=>{if (b != "Arts") {
                content += "<option value="+b+">"+b+"</option>";
            }})
        }*/
    }
    $("#serie").html("")
    $("#serie").append(content)
}
function changeCursus() {
   content = '<option value=""></option><option value="BACCALAUREAT">Baccalauréat</option><option value="GCE">GCE-- General Certificate of Education </option>'
   $("#diplome").html("")
    $("#diplome").append(content)
    document.getElementById('serie').innerHTML=""
}

var previewPicture  = function (e) {
    var image = document.getElementById("image_carte");
    const [picture] = e.files
    if (picture) {
        image.src = URL.createObjectURL(picture)
    }
}

function changeHandicap(val){
   if (val == "Oui") {
    $("#handicap_pre").fadeIn(500)
   }else{
    $("#handicap_pre").fadeOut(500)
   }
}
function showDeleteSessionModal(id_session){
    document.getElementById("id_session").value = id_session
    $("#confirmDeleteModal").modal("show");
}

function showEditSessionModal(id_session){
    document.getElementById("id_session_edit").value = id_session
    $("#editSessionModal").modal("show");
}
function showDeleteCandidatModal(code){
    document.getElementById("cand_code").value = code
    $("#confirmDeleteCandModal").modal("show");
}
