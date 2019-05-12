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
    });

    $('#playCapital').click(function ()
    {
        $('#thirdPannel').hide(500);
        $('#principalPannel').show(1000);
    });
});

/*
************************************************************************************************************************
* Fonction pour recuperer les donnees dans un fichier JSON
************************************************************************************************************************
 */

function getRandomInt(max)
{
    return Math.floor(Math.random() * Math.floor(max));
}

var index = 0;
var world = [];

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

    $('#nameState').text((world[index].name).common);
    $('#nameCapital').text(world[index].capital);
}

executeRequest(readNext);

$('#buttonChangeState').click(function()
{
    executeRequest(readNext);
});

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

// Affichage de la carte
map.addLayer(coucheStamenWatercolor);

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
var historyState = []; // Variable pour le stockage des noms des pays

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
            clickMap = 1;

            var cca3 = world[index].cca3;
            var state = (world[index].name).common;
            historyState[counter] = (world[index].name).common;

            latlong = e.latlng;
            surligner(cca3);

            map.setZoom(2);

            // Cree un cercle sur le pays
            circle = L.circle(world[index].latlng,{
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 500000}).addTo(map);

            var distance = latlong.distanceTo(L.latLng((world[index].latlng)[0],(world[index].latlng)[1]))/1000;

            popup.setLatLng(e.latlng)
                .setContent("Vous vous êtes trompés de <br/> "
                    + distance
                    + " km.<br/>")
                .openOn(map);

            progress = progress + 15; // Ajoute 15% a chaque fois
            $('.progress-bar-info').css("width",progress+'%'); // Change le css associe a la barre de progression

            // Ajoute le nom du pays dans l'historique
            $('#history').append('<div class="row button-padding-bottom"><button type="button" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-info">'
                + state
                + '</button></div>');

            $('#wikipedia').html('<iframe title="Wikipedia" src="https://en.wikipedia.org/wiki/'
                + state
                + '"class="height-40 col-xs-12 col-sm-12 col-md-12"></iframe>');

            $.ajaxPrefilter(function (options) {
                if (options.crossDomain && jQuery.support.cors) {
                    var https = (window.location.protocol === 'http:' ? 'http:' : 'https:');
                    options.url = https + '//cors-anywhere.herokuapp.com/' + options.url;
                }
            });

            $("#image0").html('<img src="http://www.travel-images.com/pht/'
                + state.replace(' ', '-').toLowerCase().sansAccent()
                + '1.jpg">');
            $("#image1").html('<img src="http://www.travel-images.com/pht/'
                + state.replace(' ', '-').toLowerCase().sansAccent()
                + '2.jpg">');
            $("#image2").html('<img src="http://www.travel-images.com/pht/'
                + state.replace(' ', '-').toLowerCase().sansAccent()
                + '3.jpg">');


            /*$.get(
                'https://en.wikipedia.org/w/api.php?action=parse&format=json&prop=text&section=0&page=' + state.replace(' ', '_') + '&callback=?',

                function (response) {
                    var m;
                    var urls = [];
                    var regex = /<img.*?src=\\"(.*?)\\"/gmi;
                    var index = 0;

                    while (m = regex.exec(response)) {
                        urls.push(m[1]);
                    }

                    urls.forEach(function (url) {
                        if(index == 0)
                        {
                            $(".carousel-indicators").append('<li data-targer="#myCarrousel" data-slide-to="'
                                                            + index
                                                            + '" class="active"></li>\n');
                            $(".carousel-inner").append('<div class="item active"><img src="'
                                                        + window.location.protocol
                                                        + url
                                                        + '"></div>');
                        }
                        else
                        {
                            $(".carousel-indicators").append('<li data-targer="#myCarrousel" data-slide-to="'
                                                            + index
                                                            + '></li>\n');
                            $(".carousel-inner").append('<div class="item"><img src="'
                                + window.location.protocol
                                + url
                                + '" style="height: 50vh"></div>');
                        }
                        index = index + 1;
                    });
                });*/
        }
        else
        {
            clickMap = 0;
            counter = counter + 1;
            map.setZoom(2);
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
        $.each(data,function(i,field)
        {
            if(i === "features")
            { // Recupere les coordonnes du pays et dessines son contour
                contour = L.geoJSON(field[0].geometry,{
                    "color": "#ff7800",
                    "weight": 4,
                    "opacity": 0.65
                }).addTo(map);
            }
        });
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