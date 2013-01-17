var lost_map = {
	init: function()
        {
          this.latitude  = $('input[name=latitude]').val();
          this.longitude = $('input[name=longitude]').val();
          this.last_seen = $('input[name=last_seen]').val();
          lost_map.mapInit();
	},
        
        mapInit: function() {
          center = new google.maps.LatLng(lost_map.latitude,lost_map.longitude);
          lost_map.initGoogleMapContainer(center);
          lost_map.addMarker(center);
          lost_map.addWindowInfo(lost_map.last_seen);
        },
        
        initGoogleMapContainer: function(center){
          lost_map.myOptions = {
                                      streetViewControl: false,
                                      zoom: 10,
                                      mapTypeId: google.maps.MapTypeId.ROADMAP,
                                      center: center,
                                      streetViewControl: true,
                                      mapTypeControl: false,
                                      zoomControl: true,
                                      zoomControlOptions: {
                                          style: google.maps.ZoomControlStyle.Ml
                                      }
                                }
          lost_map.map          = new google.maps.Map(document.getElementById("location-map"), lost_map.myOptions);
          lost_map.infowindow   = new google.maps.InfoWindow();
        },
        
        addWindowInfo: function(description){
          lost_map.infowindow.close();
          lost_map.infowindow.setContent('<b>Last Seen: </b><p>' + description + '</p>');
          lost_map.infowindow.open(lost_map.map, lost_map.marker);
        },
        
        addMarker: function(location){
            if ( this.marker ) {
                this.marker.setPosition(location);
            }else{
                this.marker = new google.maps.Marker({
                                                      animation: google.maps.Animation.DROP,
                                                      position: location,
                                                      map: lost_map.map
                                                    });
            }
        }
        
        
}

$(function() {
    if($('#lost-cotainer').length)
      lost_map.init();
});