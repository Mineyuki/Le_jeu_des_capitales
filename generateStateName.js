/*
 * Documentations :
 * https://leafletjs.com/examples/geojson/
 * https://leafletjs.com/reference-1.4.0.html
 * http://blog.mastermaps.com/2014/08/showing-geotagged-photos-on-leaflet-map.html
 */
$(document).ready(function()
{
    $('#principalPannel').hide();
    $('#thirdPannel').hide();
    $('#signUp').hide();
    $('#signIn').hide();

    $('#play').click(function ()
    {
        $('#secondPannel').hide(500);
        $('#thirdPannel').show(1000);
    });

    $('#playState').click(function ()
    {
        $('#thirdPannel').hide(500);
        $('#principalPannel').show(1000);
        $(this).data('clicked', true);
        executeRequest(readNext);
        map.addLayer(coucheStamenWatercolor); // Affichage de la carte
    });

    $('#playCapital').click(function ()
    {
        $('#thirdPannel').hide(500);
        $('#principalPannel').show(1000);
        $(this).data('clicked', true);
        executeRequest(readNext);
        map.addLayer(coucheStamenWatercolor); // Affichage de la carte
    });

    $('#signInLink').click(function ()
    {
        $('#signUp').hide(500);
        $('#signIn').show(1000);
    });

    $('#signUpLink').click(function ()
    {
        $('#signIn').hide(500);
        $('#signUp').show(1000);
    });

    // Pour tous les liens commençant par #.
    $("a[href^='#']").click(function (e) {
        var
            yPos,
            yInitPos,
            target = ($($(this).attr("href") + ":first"));

        // On annule le comportement initial au cas ou la base soit différente de la page courante.
        e.preventDefault();

        yInitPos = $(window).scrollTop();

        // On ajoute le hash dans l'url.
        window.location.hash = $(this).attr("href");

        // Comme il est possible que l'ajout du hash perturbe le défilement, on va forcer le scrollTop à son endroit inital.
        $(window).scrollTop(yInitPos);

        // On cible manuellement l'ancre pour en extraire sa position.
        // Si c'est un ID on l'obtient.
        target = ($($(this).attr("href") + ":first"));

        // Sinon on cherche l'ancre dans le name d'un a.
        if (target.length == 0) {
            target = ($("a[name=" + $(this).attr("href").replace(/#/gi,"") + "]:first"))
        }

        // Si on a trouvé un name ou un id, on défile.
        if (target.length == 1) {
            yPos = target.offset().top; // Position de l'ancre.

            // On anime le défilement jusqu'à l'ancre.
            $('html,body').animate({ scrollTop: yPos - 40 }, 1000); // On décale de 40 pixels l'affichage pour ne pas coller le bord haut de l'affichage du navigateur et on défile en 1 seconde jusqu'à l'ancre.
        }
    });
});

/*
************************************************************************************************************************
* Fonction pour recuperer les donnees dans un fichier JSON
************************************************************************************************************************
 */

var index = 0;
var world = [];

function getRandomInt(max)
{
    return Math.floor(Math.random() * Math.floor(max));
}

function executeRequest(callback)
{
    if(world.length === 0)
    {
        var xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
            {
                world = JSON.parse(xhr.responseText);
                callback();
            }
        }

        xhr.open("GET", "countries-master/countries.json", true);
        xhr.send();
    }
    else
    {
        callback();
    }
}

function readNext()
{
    var lengthWorld = world.length;
    index = getRandomInt(lengthWorld);
    if($('#playState').data('clicked'))
    {
        $('#nameQuestion').text((world[index].name).common);
        map.setZoom(2);
    }
    else if ($('#playCapital').data('clicked'))
    {
        $('#nameQuestion').text(world[index].capital);
        existCity(world[index].capital);
        map.setView(new L.LatLng(world[index].latlng[0],world[index].latlng[1]), 6);
        circleState(world[index].cca3);
    }
}

/*
************************************************************************************************************************
* Fonction pour la carte
************************************************************************************************************************
 */

// bornes pour empecher la carte StamenWatercolor de "dériver" trop loin...
var northWest = L.latLng(90, -180);
var southEast = L.latLng(-90, 180);
var bornes = L.latLngBounds(northWest, southEast);

// Initialisation de la couche StamenWatercolor
var coucheStamenWatercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
    attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    subdomains: 'abcd',
    ext: 'jpg'
});

// Initialisation de la carte et association avec la div
var map = new L.Map('maDiv', {
    center: [48.858376, 2.294442],
    minZoom: 2,
    maxZoom: 18,
    zoom: 2,
    maxBounds: bornes
});

// Juste pour changer la forme du curseur par défaut de la souris
$('#maDiv').css({'cursor': 'crosshair'})

//map.fitBounds(bornes);

// Initilisation d'un popup
var popup = L.popup();

var latlong; // Variable stockant les coordonnes d'un pays
var circle; // Variable stockant le cercle concentrique d'un pays
var contour; // Variable stockant le contour d'un pays
var progress = 0; // Variable pour la changer la barre de progression
var clickMap = 0; // Variable pour le changement d'etat lors d'un click
var counter = 0; // Variable pour le compte des questions
var maxQuestion = 7; // Nombre maximale de questions possible
var radiusCity = 50000; // Rayon du cercle pour la ville
var radiusState = 500000; // Rayon du cercle pour le pays
var score = 0; // Variable stockant le score

// Fonction de conversion au format GeoJSON
function coordGeoJSON(latlng,precision) {
    return '[' +
        L.Util.formatNum(latlng.lng, precision) + ',' +
        L.Util.formatNum(latlng.lat, precision) + ']';
}

/*
 * Fonction qui réagit au clic sur la carte (e contiendra les données liées au clic)
 */
function onMapClick(e) {
    if(counter < maxQuestion)
    { // Nombre de question autorise
        if(clickMap == 0)
        { // Donne la reponse
            var coordinates; // Variable contenant les coordonnes
            var state = (world[index].name).common; // Variable contenant le nom du pays
            var result; // Variable contenant la reponse de la question

            progress = progress + 15; // Ajoute 15% a chaque fois
            $('.progress-bar-info').css("width",progress+'%'); // Change le css associe a la barre de progression

            clickMap = 1; // Efface la reponse
            latlong = e.latlng; // Recupere les coordonnes de la carte

            if($('#playState').data('clicked'))
            { // Si on est au questionnaire des pays
                map.setZoom(2); // Zoom a 2
                coordinates = world[index].latlng; // coordonnes du pays
                result = state; // Reponse a la question
                // Distance entre le point central et le point clique
                var distance = latlong.distanceTo(L.latLng(coordinates[0],coordinates[1]))/1000;
                printCircle(coordinates,radiusState); // Affiche un cercle d'un certain rayon
                // Affiche un popup sur la carte
                popup.setLatLng(e.latlng)
                .setContent("Vous vous êtes trompés de <br/> "
                    + distance
                    + " km.<br/>")
                .openOn(map);
                updateScore(distance, radiusState);
            }
            else if ($('#playCapital').data('clicked'))
            { // Si on est au questionnaire des capitales
                map.setZoom(6); // Zoom a 6
                result = world[index].capital; // Reponse a la question
                getCoordinatesCity(result,e); // Donne les coordonnes de la ville
            }

            // Ajoute la reponse dans l'historique
            $('#history').append('<div class="row button-padding-bottom"><button type="button" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-info">'
                + result
                + '</button></div>');

            // Ajoute une frame pour wikipedia
            $('#wikipedia').html('<iframe title="Wikipedia" src="https://en.wikipedia.org/wiki/'
                + result
                + '"class="height-40 col-xs-12 col-sm-12 col-md-12"></iframe>');

            $.ajaxPrefilter(function (options) {
                if (options.crossDomain && jQuery.support.cors) {
                    var https = (window.location.protocol === 'http:' ? 'http:' : 'https:');
                    options.url = https + '//cors-anywhere.herokuapp.com/' + options.url;
                }
            });

            // Change les images du carousel
            $("#image0").html('<img src="http://www.travel-images.com/pht/'
                + state.replace(' ', '').toLowerCase().sansAccent()
                + '1.jpg">');
            $("#image1").html('<img src="http://www.travel-images.com/pht/'
                + state.replace(' ', '-').toLowerCase().sansAccent()
                + '2.jpg">');
            $("#image2").html('<img src="http://www.travel-images.com/pht/'
                + state.replace(' ', '-').toLowerCase().sansAccent()
                + '3.jpg">');
        }
        else
        { // Efface tout et change de question
            clickMap = 0; // Donne la reponse
            counter = counter + 1; // Incremente le compteur de question
            map.removeLayer(circle); // Enleve le cercle concentrique
            if ($('#playCapital').data('clicked'))
            {
                map.removeLayer(contour); // Enleve le contour
            }
            map.closePopup(); // Ferme le popup
            if(counter < maxQuestion)
            { // N'affiche pas la question apres la derniere
                executeRequest(readNext); // Pays suivant
            }
        }
    }

}

/*
 * Cree un contour pour le pays
 * @param cca3 Acronyme du pays en 3 lettres
 */
function circleState(cca3)
{
    // Acces a chaque donnee de chaque pays
    $.getJSON("countries-master/data/"+cca3.toLowerCase()+".geo.json",function (data)
    {
        // Recupere les coordonnes du pays et dessines son contour
        contour = L.geoJSON(data.features[0].geometry,{
            "color": "#ff7800",
            "weight": 5,
            "opacity": 1
        }).addTo(map);
    });
}

/*
 * Dessines un cercle sur la carte
 * @param coordinates Coordonnes fournis
 * @param rad Rayon en metre
 */
function printCircle(coordinates, rad)
{
    circle = L.circle(coordinates,{
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: rad}).addTo(map);
}

/*
 * Donne les coordonnes de la ville
 * @param capital Nom de la ville
 */
function getCoordinatesCity(capital, e)
{
    $.getJSON("geojson-world-master/capitals.geo.json", function (data)
    { // Recupere les donnees de la capitale du JSON
        // Trouve les donnes de la capitale qu'on souhaite
        var getCity = (data.features).find(state => state.properties.city === capital[0]);
        var coordinates = []; // Coordoonees
        // Interverti les coordonnes - les coordonnees ont ete inverses dans le fichier
        coordinates[0] = getCity.geometry.coordinates[1];
        coordinates[1] = getCity.geometry.coordinates[0];
        printCircle(coordinates,radiusCity); // Dessine un cercle
        var distance = latlong.distanceTo(L.latLng(getCity.geometry.coordinates[1],getCity.geometry.coordinates[0]))/1000;
        popup.setLatLng(e.latlng)
            .setContent("Vous vous êtes trompés de <br/> "
                + distance
                + " km.<br/>")
            .openOn(map);
        updateScore(distance, radiusCity);
    });
}

function existCity(capital)
{
    $.getJSON("geojson-world-master/capitals.geo.json", function (data)
    {
        if((data.features).find(state => state.properties.city === capital[0]) == undefined)
        {
            readNext();
        }
    });
}

/*
 * Met a jour le score obtenu
 * @param distance Distance entre le point clique et le point centrale du cercle
 * @param radius Rayon du cercle concentrique
 */
function updateScore(distance, radius)
{
    radius = radius / 1000;

    if(distance == 0)
    {
        score = score + 100;
    }
    else if (distance <= radius * 0.10)
    {
        score = score + 90;
    }
    else if (distance <= radius * 0.20)
    {
        score = score + 80;
    }
    else if (distance <= radius * 0.30)
    {
        score = score + 70;
    }
    else if (distance <= radius * 0.40)
    {
        score = score + 60;
    }
    else if (distance <= radius * 0.50)
    {
        score = score + 50;
    }
    else if (distance <= radius * 0.60)
    {
        score = score + 40;
    }
    else if (distance <= radius * 0.70)
    {
        score = score + 30;
    }
    else if (distance <= radius * 0.80)
    {
        score = score + 20;
    }
    else if (distance <= radius * 0.90)
    {
        score = score + 10;
    }
    else if (distance <= radius)
    {
        score = score + 1;
    }
    else
    {
        score = score + 0;
    }

    $('#score').text(score);
}

// Association Evenement/Fonction handler
map.on('click', onMapClick);

String.prototype.sansAccent = function()
{
    var accent = [
        /[\300-\306]/g, /[\340-\346]/g, // A, a
        /[\310-\313]/g, /[\350-\353]/g, // E, e
        /[\314-\317]/g, /[\354-\357]/g, // I, i
        /[\322-\330]/g, /[\362-\370]/g, // O, o
        /[\331-\334]/g, /[\371-\374]/g, // U, u
        /[\321]/g, /[\361]/g, // N, n
        /[\307]/g, /[\347]/g, // C, c
    ];
    var noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c'];

    var str = this;
    for(var i = 0; i < accent.length; i++){
        str = str.replace(accent[i], noaccent[i]);
    }

    return str;
}