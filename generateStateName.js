/*
 * Documentations :
 * https://leafletjs.com/examples/geojson/
 * https://leafletjs.com/reference-1.4.0.html
 */
$(document).ready(function()
{
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
    //var map = L.map('maDiv').setView([48.858376, 2.294442],5);
    // Affichage de la carte
    map.addLayer(coucheStamenWatercolor);
    // Juste pour changer la forme du curseur par défaut de la souris
    $('#maDiv').css({'cursor': 'crosshair'})
    //map.fitBounds(bornes);
    // Initilisation d'un popup
    var popup = L.popup();

    var latlong;

    // Fonction de conversion au format GeoJSON
    function coordGeoJSON(latlng,precision) {
        return '[' +
            L.Util.formatNum(latlng.lng, precision) + ',' +
            L.Util.formatNum(latlng.lat, precision) + ']';
    }

    // Fonction qui réagit au clic sur la carte (e contiendra les données liées au clic)
    function onMapClick(e) {
        var cca3 = world[index].cca3;

        latlong = e.latlng;
        surligner(cca3);

        map.setZoom(2);

        // Cree un cercle sur le pays
        var circle = L.circle(world[index].latlng,{
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 500000}).addTo(map);

        var distance = latlong.distanceTo(L.latLng((world[index].latlng)[0],(world[index].latlng)[1]))/1000;

        popup.setLatLng(e.latlng)
            .setContent("Vous vous êtes trompés de <br/> "
                + distance
                + " km.<br/>"
                + latlong
                + "<br/>"
                + L.latLng((world[index].latlng)[0],(world[index].latlng)[1]))
            .openOn(map);

        executeRequest(readNext);

        /*map.closePopup();
        marker.remove();*/
    }

    function surligner(cca3) {
        /*$.getJSON("countries-master/data/"+cca3+".geo.json",function(data){
            // add GeoJSON layer to the map once the file is loaded
            var datalayer = L.geoJson(data ,{
                onEachFeature: function(feature, featureLayer) {
                    featureLayer.bindPopup(feature.properties.NAME_1);
                }
            }).addTo(map);
            map.fitBounds(datalayer.getBounds());
        });*/

        // Acces a chaque donnee de chaque pays
        $.getJSON("countries-master/data/"+cca3.toLowerCase()+".geo.json",function (data)
        {
            $.each(data,function(i,field)
            {
                if(i=="features")
                { // Recupere les coordonnes du pays et dessines son contour
                    L.geoJSON(field[0].geometry,{
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

});
