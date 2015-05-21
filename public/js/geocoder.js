var points = Array();
var google;
var stops = Array();
var markers = Array();
var route;
var routeCoordinates = Array();

function search()
{
    points = [];
    
    if (markers) {
        for (i in markers) {
            markers[i].setMap(null);
        }
    }
    
    getLocationData(document.getElementById('from').value, function(locationData) {          
        var marker = new google.maps.Marker({
            position: locationData.geometry.location,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: '/public/images/start_point_pin.png',
            title: 'Початок маршруту'
        });

        markers.push(marker);        
        points.push(locationData.geometry.location);        
    });
    
    getLocationData(document.getElementById('to').value, function(locationData) {    
        var marker = new google.maps.Marker({
            position: locationData.geometry.location,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: '/public/images/stop_point_pin.png',
            title: 'Кінець маршруту'
        });

        markers.push(marker);        
        points.push(locationData.geometry.location);        
    });    
    
    setTimeout(function(){      
        var bounds = new google.maps.LatLngBounds();
        for(i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        
        map.setCenter( bounds.getCenter(), map.fitBounds(bounds));
        map.panTo(bounds.getCenter(), map.fitBounds(bounds));        
        
        getRoutes('/rest/getRoutes', function(response) {                           
            if(response.status === 'OK') {
                $('#result_box').show();
                var count = response.response.length;                
                if(count === 0)
                {
                    $('#result_box').append('<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-info-sign"></span> Нажаль маршрутів не виявлено.</div>');
                }   
                
                $.each(response.response, function(i, item) {
                    $('#result_box').append('<div class="well route" onclick="drawRoute('+ item.id +','+ item.start_position +','+ item.stop_position + ','+ item.name + ')"><div class="pull-right wheelchair"></div> <h4>' + item.subtype +' ' + item.title + '</h4>' + Data['price'] + ': ' + item.price + ' UAH.<br/>' + Data['distance'] + ': ' + item.distance + ' м.<br/><div class="route-detail" style="display: none; margin-top: 5px;"><p style="border-bottom: 2px dashed #798d8f;">' + Data['walk_to'] + ' <b>' + response.stops[0][item.start_num]['@value'] + '</b> ' + item.foots[0].distance + ' м. (' + item.foots[0].time + ' ' + Data['min'] + ')</p><p>' + Data['stop_start'] + ': <b>' + response.stops[0][item.start_num]['@value']  + '</b><br/>' + Data['on_the_road'] + ': ' + item.time + ' ' + Data['min'] + '<br/>' + Data['stop_end'] + ': <b>' + response.stops[0][item.stop_num]['@value']  + '</b></p><p style="border-top: 2px dashed #798d8f;">' + Data['walk_from'] + ' <b>' + response.stops[0][item.stop_num]['@value'] + '</b> ' + item.foots[1].distance + ' м. (' + item.foots[1].time + ' хв. </p></div></div>');
                });
            } else {
                $().toastmessage('showToast', {
                    text     : 'Виникла помилка на сервері!',                    
                    position : 'middle-right',
                    type     : 'error'                      
                });
            }
        });
    }, 1000);
}

function myGeo()
{
    navigator.geolocation.getCurrentPosition(showPosition);
}
   
function showPosition(position) {
    var input = position.coords.latitude + ',' + position.coords.longitude;
    var latlngStr = input.split(',', 2);
    var lat = parseFloat(latlngStr[0]);
    var lng = parseFloat(latlngStr[1]);
    var latlng = new google.maps.LatLng(lat, lng);
    
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            console.log(results);
            if (results[1]) {
                $( '#from' ).val(results[1].formatted_address);                
            } else {
                alert('No results found');
            }
        } else {
            alert('Geocoder failed due to: ' + status);
        }
    });
}

function reset()
{    
    if (markers) {
        for (i in markers) {
            markers[i].setMap(null);
        }
    }
    
    $( '#from' ).val('');
    $( '#to' ).val('');
    
    if(routeCoordinates.length > 0)
    {
        route.setMap(null);
    }
    
    $( '#result_box' ).html('');
}

function getLocationData(location, callback) {
    geocoder = new google.maps.Geocoder();   
  
    if( geocoder ) {
        geocoder.geocode({ 'address': location }, function (results, status) {
        if( status === google.maps.GeocoderStatus.OK ) {
            callback(results[0]);
        } else {
            
            var type;
            var text;
            
            if(status === google.maps.GeocoderStatus.ZERO_RESULTS)
            {
                text = 'Така адреса не знайдена';
                type = 'warning';
            }            
            if(status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT)
            {
                text = 'Квота перевищена';
                type = 'error';
            }            
            if(status === google.maps.GeocoderStatus.REQUEST_DENIED)
            {
                text = 'Запит відхилений';
                type = 'error';
            }
            if(status === google.maps.GeocoderStatus.INVALID_REQUEST)
            {
                text = 'Виникла помилка при запиті';
                type = 'error';
            }
            if(status === google.maps.GeocoderStatus.UNKNOWN_ERROR)
            {
                text = 'Виникла помилка при запиті';
                type = 'error';
            }
            
            $().toastmessage('showToast', {
                text     : text,                    
                position : 'middle-right',
                type     : type                      
            });            
        }
    });
    }
}

function getRandomInt(min, max)
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function drawRoute(id, start, stop)
{          
    //$( '#ok' ).text('id=' + id + '&start=' + start + '&stop=' + stop);
    $.ajax({        
        dataType: 'json',                
        type: 'GET',
        url: '/rest/getPoints',          
        //url: '/public/test.json',
        data: 'id=' + id + '&start=' + start + '&stop=' + stop,
        beforeSend: function()
        {
            $('#map-loader').show();
        },
        complete: function()
        {
            $('#map-loader').hide();
        },
        success: function(response)
        {   
            if(response.status === 'OK') {                        
                //routeCoordinates = new Array();
                
                if(routeCoordinates.length > 0)
                {
                    routeCoordinates = Array();
                    route.setMap(null);
                }
                
                $.each(response.response.route.points.point, function(key, item) { 
                    /*if(item["@attributes"].is_stop === 'true') {                        
                        var markers = new google.maps.Marker({
                                position: new google.maps.LatLng(item.lat, item.lng),
                                map: map,
                        });                        
                        markers.setMap(map);                        
                        stops.push( new google.maps.LatLng(item.lat, item.lng) );                        
                    }*/
                    
                    routeCoordinates.push( new google.maps.LatLng(item.lat, item.lng) );                    
                });
                
                var colors = (['#1fa8d0', '#6ad01f', '#d01f3c'] );

                route = new google.maps.Polyline({
                    path: routeCoordinates,                                                            
                    strokeColor: colors[getRandomInt(0, 2)],
                    strokeOpacity: 0.5,
                    strokeWeight: 5
                });                
                
                route.setMap(map);

                var latlngbounds = new google.maps.LatLngBounds();
                for ( var i = 0; i < routeCoordinates.length; i++ )
                {                        
                    latlngbounds.extend(routeCoordinates[i]);
                }
                
                map.setCenter( latlngbounds.getCenter(), map.fitBounds(latlngbounds));
                map.panTo(latlngbounds.getCenter(), map.fitBounds(latlngbounds));
            } 
        }
    });
}

function getRoutes(url, callback)
{   
    var bus = $( '#bus' ).prop("checked");
    var trol = $( '#trol' ).prop("checked");            
    //$( '#ok' ).text('start=' + points[0] + '&end=' + points[1] + '&bus=' + bus + '&trol=' + trol);
    $.ajax({        
        dataType: 'json',                
        type: 'GET',
        url: url,       
        data: 'start=' + points[0] + '&end=' + points[1] + '&bus=' + bus + '&trol=' + trol,
        beforeSend: function()
        {
            $( '#result_box' ).html('');
            $( '#result_box' ).addClass( 'loader' );
        },
        complete: function()
        {         
            $('#result_box').removeClass('loader');
        },
        error: function()
        {
            $('#result_box').append('<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-info-sign"></span> Нажаль маршрутів не виявлено.</div>');
        },
        success: function(response)
        {            
            callback(response);            
        }
    }); 
}