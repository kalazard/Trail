var map;
var GPX;

$(window).load(function()
{
  var dispLat = $("<p>").attr("id","dispLat");
  var dispLng = $("<p>").attr("id","dispLng");
  map = new L.map('map');
  getLocation();
  $("#ok").click(moveToCoords);
  $("#okVille").click(geocode);
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
              $("#map").html("Geolocation is not supported by this browser.");
          }
      }

//Place la map à la position récupérée dans getLocation
function goToPosition(position) {
  $("#map").css("height", "500px").css("width", "60%").css("margin","auto");
  $("#controls").css("width", "20%").css("margin","auto");
  //$("#boutonGroup").css("width", "10%").css("margin","auto");
  map.setView([position.coords.latitude, position.coords.longitude], 13);

  L.tileLayer('http://tile.thunderforest.com/landscape/{z}/{x}/{y}.png', {
      attribution: 'Landscape'
  }).addTo(map);

  L.marker([position.coords.latitude, position.coords.longitude]).addTo(map)
      .bindPopup('Je suis ici :D')
      .openPopup();
}

//Déplace la map aux coordonnées indiquées
function moveToCoords(lat,lng,zoom)
{
  map.setView([$("#lat").val(), $("#lng").val()], $("#zoom").val());
  
}

//Affiche les coordonnées
function displayCoords(event)
{
    /*$("#dispLat").text("Latiude : " + event.latlng.lat);
    $("#dispLng").text("Longitude : " + event.latlng.lng);*/
    $("#lat").val(event.latlng.lat);
    $("#lng").val(event.latlng.lng);
    //$("#zoom").val(map.getZoom());
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
      console.log(geo);
      //map.setView([$("#lat").val(), $("#lng").val()], $("#zoom").val());
}
