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
  map.on('contextmenu',context);
  graph.appendTo("body");
});

/*
var eauIcone = L.icon({
    iconUrl: '../eau.png',
    iconSize:     [30, 85], // size of the icon
});
var giteIcone = L.icon({
    iconUrl: '../gite.png',
    iconSize:     [30, 85], // size of the icon
});
var bugIcone = L.icon({
    iconUrl: '../bug.png',
    iconSize:     [30, 85], // size of the icon
});

var marker = L.marker([event.latlng.lat, event.latlng.lng], {icon: eauIcone}).addTo(map);
*/

function context(event)
{
    $(function()
    {
        var lat = event.latlng.lat;
        var lng = event.latlng.lng;
        var poi = {"key": {name: "Ajouter POI", "items": {
                    "eau": {"name": "Point d'eau", callback: function(){
            var marker = L.marker([lat, lng]).addTo(map);
            savePoi(lat, lng, 1);
              }},
                    "gite": {"name": "Gîte", callback: function(){
            var marker = L.marker([lat, lng]).addTo(map);
            savePoi(lat, lng, 1);
              }},
                    "bug": {"name": "Bug", callback: function(){
            var marker = L.marker([lat, lng]).addTo(map);
            savePoi(lat, lng, 1);
              }}
        }}};

        $.contextMenu( 'destroy' );
        $.contextMenu({
            selector: '.context-menu-one', 
            items: poi,
            position: function(opt, x, y)
            {
              opt.$menu.css({top: y - 40, left: x + 10});
            }
            });
      });

}

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
  var marker = L.circleMarker([event.latlng.lat, event.latlng.lng]);
  map.addLayer(marker);
  if(pointArray.length > 1)
  {
      polyline = L.polyline(latlngArray, {color: 'blue', opacity : '0.5'}).addTo(map);
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
  
  $("#save").modal('show');
  $("#saveiti").on("click",function()
    {
      $.post("map/createRoute",
                            {
                                   points: pointArray,
                                   longueur : pointArray[pointArray.length - 1].distance,
                                   elevation : pointArray[pointArray.length - 1].elevation,
                                   nom : $("#nom").val(),
                                   numero : $("#numero").val(),
                                   typechemin : $("#typechemin").val(),
                                   commentaire : $("#commentaire").val(),
                                   difficulte : $("#difficulte").val()
                                },
                            function(data, status){
                                /*alert("Data: " + data + "\nStatus: " + status);*/
                                console.log(data);
                            });
      $("#save").modal('hide');
    });

  isCreateRoute = false;
}

function savePoi(lat, lng, alt)
{
  $("#addpoi").modal('show');
  $("#savepoi").on("click",function()
    {
      $.post("map/createPoi",
                            {
                                   latitude: lat,
                                   longitude : lng,
                                   altitude : alt,
                                   titre : $("#titre").val(),
                                   description : $("#description").val(),
                                },
                            function(data, status){
                                /*alert("Data: " + data + "\nStatus: " + status);*/
                                console.log(data);
                            });
      $("#addpoi").modal('hide');
    });
}

function getElevation(response)
{
  for(var i = 0; i < pointArray.length; i++)
      {
        pointArray[i].elevation = response.elevationProfile[i].height;
        pointArray[i].distance = response.elevationProfile[i].distance;
      }
}
