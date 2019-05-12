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
        if (world[index].capital == "") {
            readNext();
        }
        map.setView(new L.LatLng(world[index].latlng[0],world[index].latlng[1]), 6);
        surligner(world[index].cca3);
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
var city;

// Fonction de conversion au format GeoJSON
function coordGeoJSON(latlng,precision) {
    return '[' +
        L.Util.formatNum(latlng.lng, precision) + ',' +
        L.Util.formatNum(latlng.lat, precision) + ']';
}

// Fonction qui réagit au clic sur la carte (e contiendra les données liées au clic)
function onMapClick(e) {
    if(counter < 7)
    {
        if(clickMap == 0)
        {
            progress = progress + 15; // Ajoute 15% a chaque fois
            $('.progress-bar-info').css("width",progress+'%'); // Change le css associe a la barre de progression

            clickMap = 1;
            latlong = e.latlng;

            var coordinates;
            var state = (world[index].name).common;
            var result;

            if($('#playState').data('clicked'))
            {
                map.setZoom(2);
                coordinates = world[index].latlng;
                result = state;
                var distance = latlong.distanceTo(L.latLng(coordinates[0],coordinates[1]))/1000;
                printCircle(coordinates,500000);
                popup.setLatLng(e.latlng)
                .setContent("Vous vous êtes trompés de <br/> "
                    + distance
                    + " km.<br/>")
                .openOn(map);
            }
            else if ($('#playCapital').data('clicked'))
            {
                map.setZoom(6);
                result = world[index].capital;
                getCoordinatesCity(result);
            }

            // Ajoute le nom du pays dans l'historique
            $('#history').append('<div class="row button-padding-bottom"><button type="button" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-info">'
                + result
                + '</button></div>');

            $('#wikipedia').html('<iframe title="Wikipedia" src="https://en.wikipedia.org/wiki/'
                + result
                + '"class="height-40 col-xs-12 col-sm-12 col-md-12"></iframe>');

            $.ajaxPrefilter(function (options) {
                if (options.crossDomain && jQuery.support.cors) {
                    var https = (window.location.protocol === 'http:' ? 'http:' : 'https:');
                    options.url = https + '//cors-anywhere.herokuapp.com/' + options.url;
                }
            });

            $("#image0").html('<img src="http://www.travel-images.com/pht/'
                + result.replace(' ', '-').toLowerCase().sansAccent()
                + '1.jpg">');
            $("#image1").html('<img src="http://www.travel-images.com/pht/'
                + result.replace(' ', '-').toLowerCase().sansAccent()
                + '2.jpg">');
            $("#image2").html('<img src="http://www.travel-images.com/pht/'
                + result.replace(' ', '-').toLowerCase().sansAccent()
                + '3.jpg">');
        }
        else
        {
            clickMap = 0;
            counter = counter + 1;
            map.removeLayer(circle); // Enleve le cercle concentrique
            map.removeLayer(contour); // Enleve le contour
            map.closePopup(); // Ferme le popup
            if(counter < 7)
            {
                executeRequest(readNext); // Pays suivant
            }
        }
    }

}

function surligner(cca3)
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
 * Radius : en metre
 */
function printCircle(coordinates, rad)
{
    circle = L.circle(coordinates,{
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: rad}).addTo(map);
}

function getCoordinatesCity(capital)
{
    $.getJSON("geojson-world-master/capitals.geo.json", function (data)
    {
        var getCity = (data.features).find(state => state.properties.city === capital[0]);
        var coordinates = [];
        coordinates[0] = getCity.geometry.coordinates[1];
        coordinates[1] = getCity.geometry.coordinates[0];
        printCircle(coordinates,50000);
        var distance = latlong.distanceTo(L.latLng(getCity.geometry.coordinates[1],getCity.geometry.coordinates[0]))/1000;
        popup.setLatLng(e.latlng)
            .setContent("Vous vous êtes trompés de <br/> "
                + distance
                + " km.<br/>")
            .openOn(map);
    });
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