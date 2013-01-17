var autocomplite_options = {
    varName: 'term',
    OptionalData: 'type',
    requestType: 'GET',

    url: SYS.baseUrl + "pets/ajax_get_breeds?",

    /**
      * var item - clicked item (html objected extended by jQuery)
      * var obj - clicked object (initial object used for rendering of autocomplete items) 
      */
    selected: function(item, obj, e)
    {
        this.val(obj.breed);
        this.focus();
    },

    attachToBox: $('#autocomplete'),

    /**
      * var @item - object from response 
      * returns value used for rendering of each autocomplete item 
      */
    parseItem: function(item)
    {
        return item.html;
    },

    preFormatData: function(data)
    {
        return data != null ? data : '';
    }
};

var edit = {
	init: function() {
          this.status_radio = $('input[name=isLost]');
          url = location.href.split('/');
          this.pet_id = url[url.length - 1];
          this.InitDatePicker();
          this.InitImageUploader();
          this.autocompliteInit();
          this.TypeSelectObserver();
          this.validateInit();
          this.lostSelectorObserver();
          //this.changeStatusObserver();
          $.validator.addMethod(
                "petBreed",
                function(value) {
                    return eval(
                                  $.ajax({
                                                url   : SYS.baseUrl + '/pets/ajax_check_breed',
                                                data  : jQuery.param({breed:value}),
                                                async : false,
                                                type  : 'post'
                                              }).responseText
                              );
                },
                "Breed don't exists!"
            );
          $.validator.addMethod(
                  "date",
                  function(value) {
                      return value.match(/\d{2}-\d{2}\-\d{4}/);
                  },
                  "mm-dd-yyyy"
              );
	},
        
        changeStatusObserver: function(el){
          this.old_status_container = $(el).clone();
          statusSquare = '<input type="text" name="status" value="'+this.old_status_container.text()+'"> <a class="btn btn-mini btn-primary" onclick="javascript:edit.saveStatus()">Change Status </a>';
          $(el).replaceWith(statusSquare);
        },
        
        saveStatus: function(){
          new_status = $('input[name="status"]').val();
          $.ajax({
            url: SYS.baseUrl + 'api/pets/setTextStatus',
            type: 'POST',
            dataType: 'json',
            data: $.param({pet_id:edit.pet_id, text_status:new_status}),
            success: function(res){
              if(res.text == 'success'){
                if(new_status == '')
                  new_status = 'change status';
                else
                  alert.setStatus('success').setMessage('Status was changed').render();
                
                edit.old_status_container.text(new_status);
                $('#status-container').html(edit.old_status_container);
              }
            }
          })
        },
        
        lostSelectorObserver: function(){
          this.status_radio.change(function(){
              if($(this).filter(':checked').val() == 1){
//                $('#lost-form-container');
                $.ajax({
                  url: SYS.baseUrl + 'pets/ajax_get_lost_container',
                  type: 'post',
                  dataType: 'json',
                  data: $.param({pet_id: $('input[name=id]').val()}),
                  success: function(res){
                    $('#lost-form-container').html(res.data.html);
                    lost.init();
                  }
                  
                })
              }else{
                $('#lost-form-container').empty();
              }
          });
        },
        
        validateInit:function(){
        this.form = $('form').validate({
                rules: {
                    name: {
                        required: true
                    },
                    breed:{
                        required: true,
                        petBreed: true
                    },
                    description:{
                        required: true
                    }
                }
            });
        },
        TypeSelectObserver: function(){
            $('select[name="type"]').change(function(){
                $('#autocomplete').val('');
            });
        },
        
        InitDatePicker: function(){
          $('.date').datepicker({
                    dateFormat: 'mm-dd-yy',
                    startDate: new Date()
                });
        },
        
        
        autocompliteInit:function(){
            $('#autocomplete').autocomplete(autocomplite_options);
        },
        
        InitImageUploader: function(){
              $('input[type=file]').fileupload({
                url: SYS.baseUrl + 'upload/images',
                maxFileSize: 5000000,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                placeholder: '',
                process: [
                    {
                        action: 'load',
                        fileTypes: /^image\/(gif|jpe?g|png)$/,
                        maxFileSize: 20000000 // 20MB
                    },
                    {
                        action: 'resize',
                        maxWidth: 1440,
                        maxHeight: 900
                    },
                    {
                        action: 'save'
                    }
                ],

                dataType: 'json',
                done: function (e, data) {
                      if(data.result.text == 'success'){
                        loadingStop();
                        $('div.span3').find('a > img').attr({src: SYS.baseUrl + 'images/temp/' + data.result.data.file})
                        $('input[name=picture]').val(data.result.data.file);
                      }
                }
            }).bind('fileuploadstart', function (e, data) {
                loadingStart($('.thumbnail'));
            }).bind('fileuploadstop', function (e) {

            });
        }
};

var lost = {
        init:function()
        {
            this.latitude        = $('input#latitude');
            this.longitude       = $('input#longitude');
            this.geocoder        = new google.maps.Geocoder();
            this.address         = document.getElementById('lost-address');
            this.initGoogleMaps();
            this.initValidateFormForLostPart();
        },
        
        
        initValidateFormForLostPart:function(){
          $('#lost-address').rules("add", {
            required: true
          });
          $("#latitude").rules("add", {
            required: true,
            number: true
          });
          $("#longitude").rules("add", {
            required: true,
            number: true
          });
        },
        
        
        detectCoordinats: function(){
            if(lost.latitude.val() != '' && lost.longitude.val() != ''){
                newPosition = new google.maps.LatLng(lost.latitude.val(), lost.longitude.val());
                lost.addMarker(newPosition);
            }
        },
        
        initGoogleMaps: function(){
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                  center = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                  lost.initGoogleMapContainer(center);
                  lost.detectCoordinats();
                });
            }else{
                  center = new google.maps.LatLng(0, 0);
                  lost.initGoogleMapContainer(center);
            }

        },

        initGoogleMapContainer: function(center){
          lost.myOptions = {
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

          lost.map          = new google.maps.Map(document.getElementById("location-map"), lost.myOptions);
          lost.autocomplete = new google.maps.places.Autocomplete(lost.address); 
          lost.infowindow   = new google.maps.InfoWindow();
          
          google.maps.event.addListener(lost.map, 'click', function(event) {
              lost.locationChangeListeners(event.latLng);
          });
          
          google.maps.event.addListener(lost.autocomplete, 'place_changed', lost.autocompliteListener);
          
          $(lost.address).keyup(function(e){
              lost.geocoderFromAddress($(this).val(), 10);
          });
          
        },
        
        autocompliteListener: function(){
            place = lost.autocomplete.getPlace();
            lost.addMarker(place.geometry.location);
            lost.changeCoordsInForm(place.geometry.location);
            if (place.geometry.viewport) {
                lost.map.fitBounds(place.geometry.viewport);
            } else {
                lost.map.setCenter(place.geometry.location);
                lost.map.setZoom(10);
            }
            lost.windowInfoListner(place.name, place.formatted_address);
        },
        
        windowInfoListner: function(name, description){
            
            lost.infowindow.close();
            lost.infowindow.setContent('<b>' + name + '</b><p>' + description + '</p>');
            lost.infowindow.open(lost.map, lost.marker);
        },
        
        locationChangeListeners: function(location){
            lost.changeCoordsInForm(location);
            lost.addMarker(location);
            lost.geocoderFromLocation(location);
        },
        
        addMarker: function(location){
            if ( this.marker ) {
                this.marker.setPosition(location);
            }else{
                this.marker = new google.maps.Marker({
                                                      animation: google.maps.Animation.DROP,
                                                      position: location,
                                                      map: lost.map
                                                    });
            }
        },
        
        geocoderFromLocation: function(location) {
            lost.geocoder.geocode({'latLng': location}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                            $(lost.address).val(results[1].formatted_address);
                            lost.windowInfoListner(results[0].address_components[1].short_name, results[0].formatted_address);
                    }
                }
            });
        },
        
        geocoderFromAddress: function (address, zoom) {
            lost.geocoder.geocode({'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    lost.map.setCenter(results[0].geometry.location);
                    lost.map.setZoom(zoom);
                }
            });               
        },
        
        changeCoordsInForm: function(location)
        {
            lost.latitude.val(location.lat());
            lost.longitude.val(location.lng());
        },
        
        
        changeGoogleMarkerByCoordiante: function()
        {
          $('#latitude, #longitude').keyup(function(){
              newPosition = new google.maps.LatLng(lost.latitude.val(), lost.longitude.val());
              lost.addMarker(newPosition);
          });
        }
};

$(function() {
    edit.init();
    
    if(edit.status_radio.filter(':checked').val() == 1){
      lost.init();
    }
});