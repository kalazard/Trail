var map;
var GPX;

$(window).load(function()
{
  var dispLat = $("<p>").attr("id","dispLat");
  var dispLng = $("<p>").attr("id","dispLng");
  map = new L.map('map');
  getLocation();
  $("#ok").click(moveToCoords);
  $("body").append(dispLat);
  $("body").append(dispLng);
  map.on('mousemove', displayCoords);
  map.on('zoomend', refreshZoom);

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
