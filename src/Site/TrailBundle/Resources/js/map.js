var map, GPX, routeControl,pointArray,latlngArray, polyline, elevationScript, elevationChartScript;
var isCreateRoute = false;
var elevationURL = "http://open.mapquestapi.com/elevation/v1/profile?key=Fmjtd%7Cluu8210720%2C7a%3Do5-94bahf&callback=getElevation&shapeFormat=raw";
var elevationChartURL = "http://open.mapquestapi.com/elevation/v1/chart?key=Fmjtd%7Cluu8210720%2C7a%3Do5-94bahf&inFormat=kvp&shapeFormat=raw&width=425&height=350";
var graph = $("<img>").css("display","none");

var Point = function(lat,lng)
{
    this.lat = lat;
    this.lng = lng;
};

$(window).load(function()
{
  var dispLat = $("<p>").attr("id","dispLat");
  var dispLng = $("<p>").attr("id","dispLng");
  map = new L.map('map');
  getLocation();
  $("#ok").click(moveToCoords);
  $("#iti").click(createRoute);
  $("body").append(dispLat);
  $("body").append(dispLng);
  map.on('mousemove', displayCoords);
  map.on('zoomend', refreshZoom);
  graph.appendTo("body");
});

//Coordonnées à partir du navigateur
function getLocation() {
          if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(goToPosition);
          } else {
              $("#map").html("Votre navigateur ne supporte pas la géolocalisation");
          }
      }

//Place la map à la position récupérée dans getLocation
function goToPosition(position) {

  //Définition des attributs de la carte et positionnement
  $("#map").css("height", "70%").css("width", "60%").css("margin","auto");
  $("#controls").css("width", "20%").css("margin","auto");
  map.setView([position.coords.latitude, position.coords.longitude], 13);

  //Mise en place de l'overlay (controles personnalisés)
  var geocodeSearch = L.Control.extend({
      options: {
          position: 'bottomleft'
      },

      onAdd: function (map) {
          var container = L.DomUtil.create('div', 'leaflet-control-command');
          $(container).html("<div class='input-group' id='geocodeControl'> " +
                              "<span class='input-group-addon' id='basic-addon1'>Recherche</span>" + 
                              "<input type='text' class='form-control' placeholder='Ville' aria-describedby='basic-addon1' id='ville'>" + 
                            "</div><div class='btn-group'role='group' aria-label='...' id='boutonGroup'>" + 
                              "<button type='button' class='btn btn-default' id='okVille'>Se centrer</button></div>");
          container.addEventListener('mouseover', function () 
          {
            map.dragging.disable();
          });
          container.addEventListener('mouseout', function () 
          {
              map.dragging.enable();
          });
          return container;
      }
  });
  map.addControl(new geocodeSearch());

  //Ajout du fond de carte Landscape obtenu sur Thunderforest
  L.tileLayer('http://tile.thunderforest.com/landscape/{z}/{x}/{y}.png', {
      attribution: 'Landscape'
  }).addTo(map);


  L.marker([position.coords.latitude, position.coords.longitude]).addTo(map)
      .bindPopup('Vous êtes ici :D')
      .openPopup();

  //Définition de l'écouteur
  $("#okVille").click(geocode);


  $("#geocodeControl").css("width","20%");
  $("#map").css("cursor","move");
}

//Déplace la map aux coordonnées indiquées
function moveToCoords(lat,lng,zoom)
{
  map.setView([$("#lat").val(), $("#lng").val()], $("#zoom").val());
}

//Affiche les coordonnées
function displayCoords(event)
{
    $("#lat").val(event.latlng.lat);
    $("#lng").val(event.latlng.lng);
}

function refreshZoom()
{
  $("#zoom").val(map.getZoom());
}

function parseGPX()
{
  
  var gpx = '/Trail2/Trail/web/test4.gpx'; // URL to your GPX file or the GPX itself
  GPX = new L.GPX(gpx, {async: true,
  marker_options: {
    startIconUrl: '/Trail2/Trail/web//pin-icon-start.png',
    endIconUrl: '/Trail2/Trail/web//pin-icon-end.png',
    shadowUrl: '/Trail2/Trail/web//pin-shadow.png'
  }}).on('loaded', function(e) {
    map.fitBounds(e.target.getBounds());
  }).addTo(map);
}

function geocode()
{
    var geo = MQ.geocode({ map: map })
      .search($("#ville").val());
}

function createRoute()
{
  if(!isCreateRoute)
  {
      isCreateRoute = true;
      var routeValidate = L.Control.extend({
        options: {
            position: 'topright'
        },

        onAdd: function (map) {
            var container = L.DomUtil.create('div', 'leaflet-control-command');
            $(container).html("<div class='btn-group'role='group' aria-label='...' id='routeGroup'>" + 
                                "<button type='button' class='btn btn-default' id='routeOk'>Valider</button></div>");
            container.addEventListener('mouseover', function () 
            {
              map.dragging.disable();
              map.off("click",drawRoute);
            });
            container.addEventListener('mouseout', function () 
            {
                map.dragging.enable();
                map.on("click",drawRoute);
            });
            return container;
        }
    });
    routeControl = new routeValidate();
    map.addControl(routeControl);
    $("#routeOk").click(saveRoute);
    pointArray = [];
    latlngArray = [];
    map.on("click",drawRoute);
    $("#map").css("cursor","pointer");
    map.dragging.disable();
  }
}

function drawRoute(event)
{

  pointArray.push(new Point(event.latlng.lat,event.latlng.lng));
  latlngArray.push(event.latlng);
  if(pointArray.length > 1)
  {
      polyline = L.polyline(latlngArray, {color: 'blue', opacity : '50%'}).addTo(map);
      var URL = elevationURL + '&latLngCollection=';
      var URLChart = elevationChartURL + '&latLngCollection=';
      for(var i = 0; i < latlngArray.length; i++)
      {
        var lat = latlngArray[i].lat;
        var lng = latlngArray[i].lng;
        URL += lat + "," + lng;
        URLChart += lat + "," + lng;
        if(i !== latlngArray.length - 1)
        {
          URL += ",";
          URLChart += ",";
        }
          
      }
      URL.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      URLChart.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      elevationScript = document.createElement('script');
      elevationScript.type = 'text/javascript';
      elevationScript.src = URL;
      elevationChartScript = document.createElement('script');
      elevationChartScript.type = 'text/javascript';
      elevationChartScript.src = URLChart;
      $("body").append(elevationScript);
      graph.attr("src",elevationChartScript.src);
      graph.css("display","block");
  }
}

function saveRoute()
{
  routeControl.removeFrom(map);
  map.off("click",drawRoute);
  $("#map").css("cursor","move");
  map.dragging.enable();
  $("body").append("<p>" + pointArray + "</p>");
  isCreateRoute = false;
}

function getElevation(response)
{
  for(var i = 0; i < pointArray.length; i++)
      {
        pointArray[i].elevation = response.elevationProfile[i].height;
      }
}
