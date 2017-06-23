function initMap(lat, lng) {
    var place = {lat: lat, lng: lng};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: place
    });
    var marker = new google.maps.Marker({
        position: place,
        map: map
    });
}