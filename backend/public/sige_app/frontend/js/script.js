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
$("#region_origine").fadeOut(500)
$("#ca_nationali").fadeOut(50)
function changePays(pays) {
    if (pays != "CMR") {
        $("#region").fadeOut(500)
        $("#departement").fadeOut(500)
        $("#arrondissement").fadeOut(500)
        $("#ca_nationali").fadeIn(50)
    }else{
        $("#region").fadeIn(500)
        $("#departement").fadeIn(500)
        $("#arrondissement").fadeIn(500)
        $("#ca_nationali").fadeOut(50)
        regions = ["Adamaoua","Centre","Est","Extrême-Nord","Littoral", "Nord", "Nord-Ouest", "Ouest","Sud","Sud-Ouest "]
        content = "";
        regions.forEach(
            (r)=>{
                content+="<option value="+r+">"+r+"</option>";
        });
        $("#region_origine_user").html("<option value=''>Votre région ?</option>")
        $("#region_origine_user").append(content)
    }
}

function chargeRegion(pays) {
    if ((pays == "CMR") || (pays.toUpperCase() == "Cameroun".toUpperCase())) {
        $("#region").fadeIn(500)
        $("#departement").fadeIn(500)
        $("#arrondissement").fadeIn(500)
        regions = ["Adamaoua","Centre","Est","Extrême-Nord","Littoral", "Nord", "Nord-Ouest", "Ouest","Sud","Sud-Ouest "]
        content = "";
        regions.forEach(
            (r)=>{
                content+="<option value="+r+">"+r+"</option>";
        });
        $("#region_pers").html("<option value=''>Votre région ?</option>")
        $("#region_pers").append(content)
        $("#region_pers").fadeIn(500)
        $("#region_origine").fadeOut(50)
    }else{
        $("#region").fadeIn(500)
        $("#region_origine").fadeIn(500)
        $("#region_pers").fadeOut(50)
        $("#departement").fadeOut(500)
        $("#arrondissement").fadeOut(500)

    }
}

function changeDepart(region) {
    $departement = [
        {
            "nom": "Adamaoua",
            "depart": ["Djérem","Faro et Deo","Mayo Banyo","Mbere","Vina"]
        },
        {
            "nom": "Centre",
            "depart": ["Haute Sanaga","Lekie","Mbam et Inoubou", "Mbam et Kim","Mefou et Afamba","Mefou et Akono", "Mfoundi","Nyong et Kelle","Nyong et Mfoumou", "Nyong-et-So’o" ]
        },
        {
            "nom": "Est",
            "depart": ["Boumba et Ngoko","Haut Nyong","Kadey","Lom et Djérem"]
        },
        {
            "nom": "Extrême-Nord",
            "depart": ["Diamare","Logone et Chari","Mayo Danay","Mayo Kani","Mayo Sava", "Mayo Tsanaga"]
        },
        {
            "nom": "Littoral",
            "depart": ["Moungo","Nkam","Sanaga Maritime","Wouri"]
        },
        {
            "nom": "Nord",
            "depart": ["Benoue","Faro","Mayo Louti","Mayo Rey"]
        },
        {
            "nom": "Nord-Ouest",
            "depart": ["Boyo","Bui","Donga Mantung","Menchum", "Mezam", "Momo", "Ngo Ketunjia"]
        },
        {
            "nom": "Ouest",
            "depart": ["Bamboutos","Haut Nkam","Hauts Plateaux","Koung Khi", "Menoua", "Mifi", "Nde", "Noun"]
        },
        {
            "nom": "Sud",
            "depart": ["Dja et Lobo","Mvila","Ocean","Vallee du Ntem"]
        }
        ,
        {
            "nom": "Sud-Ouest",
            "depart": ["Fako","Koupe Manengouba","Lebialem","Manyu", "Meme", "Ndian"]
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
    $("#depart_pers").html("")
    $("#depart_pers").append(contenu)
    $("#depart_origine_user").append(contenu)
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
function chargeArrond(depart) {
    arrond = [
        {
            "nom" : "Djerem",
            "arrond" : ["NGAOUNDAL", "TIBATI"]
        },
        {
            "nom" : "FARO ET DEO",
            "arrond" : ["MAYO-BALEO", "KONTCHA","TIGNERE","GALIM-TIGNERE"]
        },
        {
        "nom" : "MAYO BANYO",
        "arrond" : ["BANYO", "BANKIM","TIMAYO-DARLE"],
       },
       {
        "nom" : "MBERE",
        "arrond" : ["MEIGANGA", "DJOHONG","DIR","NGAOUI"]
    },
       {
        "nom" : "NGAOUNDERE",
        "arrond" : ["MEIGANGA", "	NGAOUNDERE 1er","NGAOUNDERE 2e","NGAOUNDERE 3e","BELEL","MBE","NGANHA","NYAMBAKA","MARTAP"]
    },

    {
        "nom" : "BOUMBA ET NGOKO",
        "arrond" : ["MOLOUNDOU", "SALAPOUMBE","GARI-GOMBO","YOKADOUMA"]
    },
    {
        "nom" : "HAUT NYONG",
        "arrond" : ["ABONG-MBANG", "DOUME","LOMIE","MESSAMENA","NGUELEMENDOUKA","DIMAKO","NGOYLA","BEBEND","MBOUANZ","DJA","DOUMAINTANG","MESSOK","SAMALOMO","MBOMA"]
    },
    {
        "nom" : "KADEY",
        "arrond" : ["BATOURI", "NDELELE","KETTE","MBANG","NDEM-NAM","BOMBE","MBOTORO"]
    },
    {
        "nom" : "LOM ET DJEREM",
        "arrond" : ["BERTOUA 1er", "BERTOUA 2e","BETARE-OYA","BELABO","GAROUA-BOULAÏ","MANDJOU","NGOURA"]
    },
    {
        "nom" : "MOUNGO",
        "arrond" : ["MOMBO", "FIKO","NJOMBE-PENJA","BOBARE-BAKEM","NLONAKO","NKONGSAMBA 3e","NKONGSAMBA 2e","NKONGSAMBA 1er","MELONG","MBANGA ","MANJO","LOUM","DIBOMBARI"]
    },
    {
        "nom" : "NKAM",
        "arrond" : ["NKONDJOCK ", "YABASSI","YINGUI","NORD-MAKOMBE"]
    },
    {
        "nom" : "SANAGA MARITIME",
        "arrond" : ["DIZANGUE", "EDEA 1er","EDEA 2e","NDOM ","NGAMBE","POUMA","MOUANKO" ,"DIBAMBA","NGWEI","NYANON","MASSOCK-SONGLOULOU"]
    },
    {
        "nom" : "WOURI",
        "arrond" : ["DOUALA 1er ", "DOUALA 2e","DOUALA 3e","DOUALA 4e","DOUALA 5e","DOUALA 6e"]
    },
    {
        "nom" : "BENOUE",
        "arrond" : ["GAROUA 1er  ", "GAROUA 2e","GAROUA 3e","BIBEMI","PITOA","LAGDO" ,"DEMBO" ,"TCHEBOA" ,"MAYO HOURNA","TOUROUA","BASCHEO","DEMSA"]
    },
    {
        "nom" : "FARO",
        "arrond" : ["POLI ", "BEKA"]
    },
    {
        "nom" : "MAYO LOUTI",
        "arrond" : ["DGUIDER ", "DMAYO-OULO","FIGUIL"]
    },
    {
        "nom" : "MAYO REY",
        "arrond" : ["REY-BOUBA ", "TCHOLLIRE","TOUBORO","MADINGRING"]
    },
    {
        "nom" : "DJA ET LOBO",
        "arrond" : ["BENGBIS  ", "DJOUM","SANGMELIMA","ZOETELE","OVENG","MINTOM " ,"MEYOMESSALA" ,"MEYOMESSI"]
    },
    {
        "nom" : "VALLEE DU NTEM",
        "arrond" : ["AMBAM", "MA'AN","OLAMZE","KYE OSSI"]
    },
    {
        "nom" : "MVILA",
        "arrond" : ["EBOLOWA 1er  ", "EBOLOWA 2e","	BIWONG-BANE","MVANGAN","MENGONG","NGOULEMAKONG " ,"EFOULAN" ,"BIWONG BULU"]
    },
    {
        "nom" : "OCEAN",
        "arrond" : ["BENAKOM II  ", "CAMPO","KRIBI 1er","KRIBI 2e","LOLODORF","MVENGUE" ,"BIPINDI" ,"LOKOUNDJE","NIETE"]
    },

    {
        "nom" : "FAKO",
        "arrond" : ["MUYUKA", "TIKO","LIMBE 1er","LIMBE 2e","LIMBE 3e","BUEA" ,"WEST-COAST" ]
    },

    {
        "nom" : "FAKKUPE MANENGUBAO",
        "arrond" : ["BANGEM", "	NGUTI","TOMBEL"]
    },
    {
        "nom" : "LEBIALEM",
        "arrond" : ["FONTEM", "ALOU","WABANE"]
    },
    {
        "nom" : "MANYU",
        "arrond" : ["AKWAYA", "MAMFE","EYUMODJOCK","UPPER-BAYANG"]
    },
    {
        "nom" : "MEME",
        "arrond" : ["KUMBA 1er", "KUMBA 2e","KUMBA 3e","KONYE","BONGE"]
    },

    {
        "nom" : "NDIAN",
        "arrond" : ["BAMUSSO", "EKONDO-TITI ","	ISANGUELE","MUNDEMBA","KOMBO ABEDIMO","	KOMBO IDINTI" ,"DABATO" ,"DIKOME-BALUE","TOKO" ]
    },


    {
        "nom" : "HAUTE-SANAGA",
        "arrond" : ["MBANDJOCK", "MINTA","NANGA-EBOKO","NKOTENG","BIBEY","NSEM" ,"LEMBE-YEZOUM"]
    },

    {
        "nom" : "LEKIE",
           "arrond" : ["EVODOULA", "MONATELE","OBALA","OKOLA","SA'A","ELIG-MFOMO" ,"EBEBDA" ,"BATSCHENGA","LOBO" ]
    },

    {
        "nom" : "MBAM et INOUBOU",
        "arrond" : ["BAFIA", "BOKITO","DEUK","MAKENENE","NDIKINIMEKI","OMBESSA" ,"KIIKI" ,"KON-YAMBETTA","NITOUKOU"]
    },


    {
        "nom" : "MBAM et KIM",
        "arrond" : ["NTUI", "NGAMBE-TIKAR","NGORO","YOKO","MBANGASSINA"]
    },

    {
        "nom" : "MEFOU et AFAMBA",
        "arrond" : ["MFOU", "ESSE","AWAE","SOA","AFANLOUM","ASSAMBA" ,"EDZENDOUAN" ,"NKOLAFAMBA","NITOUKOU"]
    },

    {
        "nom" : "MEFOU et  AKONO",
        "arrond" : ["NGOUMOU", "AKONO","MBANKOMO","BIKOK"]
    },

    {
        "nom" : "MFOUNDI",
        "arrond" : ["YAOUNDE I","YAOUNDE II","YAOUNDE III","YAOUNDE IV","YAOUNDE V","YAOUNDE VI","YAOUNDE VII"]
    },

    {
        "nom" : "NYONG ET KELLE",
        "arrond" : ["BOT-MAKAK","ESEKA","MAKAK","NGOG-MAPUBI","MATOMB","DIBANG","NGUIBASSAL","BONDJOCK","BIYOUHA"]
    },
    {
        "nom" : "NYONG et MFOUMOU",
        "arrond" : ["AKONOLINGA","AYOS","ENDOM","MENGANG","YAKOKOMBO"]
    },

    {
        "nom" : "NYONG et SO'O",
        "arrond" : ["DZENG","MBALMAYO","NGOMEDZAP","AKOEMAN","MENGUEME","NKOL-METET"]
    },

    {
        "nom" : "DIAMARE",
        "arrond" : ["BOGO ","MAROUA 1er ","MAROUA 2e","MAROUA 3e","GAZAWA","PETTE","DARGALA","NDOUKOULA"]
    },

    {
        "nom" : "LOGONE ET CHARI",
          "arrond" : ["KOUSSERI ","MAKARY","LOGONE-BIRNI","GOULFEY","WAZA","FOTOKOL","HILE-HALIFA","DARAK","ZINA"]
    },

    {
        "nom" : "MAYO DANAY ",
        "arrond" : ["KAR-HAY ","DATCHEKA ","YAGOUA","GUERE","MAGA","KALFOU","WINA","VELE","TCHATIBALI","GOBO","KAÏ-KAÏ"]
    },

    {
        "nom" : "MAYO KANI",
        "arrond" : ["KAELE ","GUIDIGUIS","MINDIF","MOUTOURWA","MOULVOUDAYE","PORHI","TAIBONG"]
    },
    {
        "nom" : "MAYO SAVA",
        "arrond" : ["MORA ","TOKOMBERE","KOLOFATA"]
    },

    {
        "nom" : "MAYO TSANAGA",
        "arrond" : ["MOKOLO ","BOURRHA","KOZA","HINA","MOGODE","MAYO-MASKOTA","SOULEDE-ROUA"]
    },

    {
        "nom" : "BUI",
        "arrond" : ["JAKIRI ","KUMBO","OKU","NONI","NKUM","MAYO-MASKOTA","SOULEDE-ROUA"]
    },

    {
        "nom" : "BOYO",
        "arrond" : ["FUNDONG ","BELO","BUM","NONI","NJINIKOM"]
    },
    {
        "nom" : "BUI",
        "arrond" : ["JAKIRI ","KUMBO","OKU","NONI","NKUM","MAYO-MASKOTA","SOULEDE-ROUA"]
    },
    {
        "nom" : "DONGA MANTUNG ",
        "arrond" : ["NKAMBE ","	NWA","AKO","NDU","MISAJE"]
    },
    {
        "nom" : "MENCHUM",
        "arrond" : ["WUM ","FURU-AWA","	MENCHUM VALLEY","FUNGOM"]
    },
    {
        "nom" : "MEZAM",
        "arrond" : ["BAMENDA 1er","BAMENDA 2e","BAMENDA 3e","BALI","TUBAH","BAFUT","SANTA"]
    },

    {
        "nom" : "MOMO",
        "arrond" : ["BATIBO ","MBENGWI","NGIE","WIDIKUM-MENKA"]
    },
    {
        "nom" : "NGO KENTUNJIA",
        "arrond" : ["NDOP ","BABESSI","BALIKUMBAT"]
    },
    {
        "nom" : "BAMBOUTOS",
        "arrond" : ["MBOUDA","GALIM","BATCHAM","BABADJOU"]
    },
    {
        "nom" : "HAUTS PLATEAUX ",
        "arrond" : ["BAHAM","BAMENDJOU","BANGOU","BATIE"]
    },
    {
        "nom" : "HAUT NKAM",
        "arrond" : ["BAFANG","BANA","BANDJA","KEKEM","BAKOU","BANKA","	BANWA"]
    },
    {
        "nom" : "KOUNG KHI",
        "arrond" : ["POUMOUGNE","BAYANGAM","DJEBEM"]
    },
    {
        "nom" : "MENOUA",
        "arrond" : ["DSCHANG","PENKA-MICHEl","FOKOUE","NKONG-NI","SANTCHOU","FONGO TONGO","	BANWA"]
    },

    {
        "nom" : "MIFI",
        "arrond" : ["BAFOUSSAM 1er","BAFOUSSAM 2el","BAFOUSSAM 3e"]
    },
    {
        "nom" : "MENOUA",
        "arrond" : ["DSCHANG","PENKA-MICHEl","FOKOUE","NKONG-NI","SANTCHOU","FONGO TONGO","	BANWA"]
    },
    {
        "nom" : "NOUN",
        "arrond" : ["FOUMBAN","FOUMBOT","MALENTOUEN","MASSANGAM","KOUTABA","BANGOURAIN","KOUOPTAMO","NJIMON"]
    }]
    arrond.forEach(
        (ar)=>{
            if(ar.nom.toUpperCase() == depart.toUpperCase()){
                contenu_a = ""
                ar.arrond.forEach(
                       (d)=>{
                        contenu_a += "<option value="+d+">"+d+"</option>";
                       }
                );
            }
        }
    );
    $("#arrond_origine_user").html("")
    $("#arrond_origine_user").append(contenu_a)
}
var password = document.getElementById("pwd_user")
  , confirm_password = document.getElementById("conf_pwd_user");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Les mots de passe ne sont pas identiques");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;

function filterFunction() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("filterInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("filterTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td1 = tr[i].getElementsByTagName("td")[3];
        td2 = tr[i].getElementsByTagName("td")[2];
        if (td1 || td2) {
        txtValue1 = td1.textContent || td1.innerText;
        txtValue2 = td2.textContent || td2.innerText;
        if ((txtValue1.toUpperCase().indexOf(filter) > -1) || txtValue2.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
        }
    }
    }
