<!DOCTYPE html>
<html lang='<?=$lang?>'>
    <head>
        <title><?=TITLE?></title>
        <meta charset='utf-8' />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content='' name='description' />
        <meta content='Medunitsa Vladimir' name='author' />        
        <link rel="stylesheet" href="/public/css/bootstrap.min.css"/>    
        <link rel="stylesheet" href="/public/css/custom.css"/>    
        <link rel="stylesheet" href="/public/css/toastmessage/jquery.toastmessage.css"/>    
        <!--[if lt IE 9]>
            <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->    
    </head>  
    <body>        
        <div class='navbar navbar-default navbar-static-top'>
            <div class='container-fluid'>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class='navbar-brand' href='/?city=<?=$city?>'><?=TITLE?></a>
                   
                </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="social hidden-xs">
                        <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareQuickServices="vkontakte,facebook,twitter" data-yashareTheme="counter"></div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-map-marker"></span>
                        <?php if ($city == 'kyiv') { echo KYIV; }?>
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="?city=kyiv"><?=KYIV?></a></li>
                        </ul>
                    </li>
                    <li class="dropdown language">
                        <?php
                            if($lang == 'uk') {
                                echo '<a href="#" class="dropdown-toggle hidden-xs" data-toggle="dropdown" role="button" aria-expanded="false" style="background: url(/public/images/ukraine.png);background-repeat: no-repeat;background-position: center center;"><span class="caret"></span></a>';
                            }
                            if($lang == 'en') {
                                echo '<a href="#" class="dropdown-toggle hidden-xs" data-toggle="dropdown" role="button" aria-expanded="false" style="background: url(/public/images/english.png);background-repeat: no-repeat;background-position: center center;"><span class="caret"></span></a>';
                            }
                            if($lang == 'ru') {
                                echo '<a href="#" class="dropdown-toggle hidden-xs" data-toggle="dropdown" role="button" aria-expanded="false" style="background: url(/public/images/russian.png);background-repeat: no-repeat;background-position: center center;"><span class="caret"></span></a>';
                            }
                        ?>
                        <a href="#" class="dropdown-toggle visible-xs" data-toggle="dropdown" role="button"><?=LANGUAGE?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/index/language?lang=uk">Українська</a></li>
                            <li><a href="/index/language?lang=ru">Русский</a></li>
                            <li><a href="/index/language?lang=en">English</a></li>                    
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-left">
                    <!--<button onclick="drawRoute(356, 502, 651)">draw</button>-->
                    <li><a href="/?city=<?=$city?>"><?=NAV_MAIN?></a></li>
                    <li><a href="/index/about"><?=NAV_ABOUT?></a></li>
                </ul>                       
            </div><!--/.nav-collapse -->
        </div>
    </div>
    <div class='container-fluid'>
        <div id="ok"></div>
        <?php include ($contentPage); ?>      
        <div class='row'>
            <div class='col-md-12 text-right copy'>
            <small>&copy; 2015 <a href='/?city=<?=$city?>' title='Wheelchair Route'>Wheelchair Route</a>. <?=COPY?> <a href='http://www.eway.in.ua' title='EasyWay' target="_blank">EasyWay</a></small>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/public/js/jquery-1.10.2.min.js"></script>    
    <script type="text/javascript" src="/public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/public/js/geocoder.js"></script>
    <script type="text/javascript" src="/public/js/jquery.toastmessage.js"></script>
    <script type="text/javascript" src="/public/js/lang.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyA7fWDOX_qHumelQOEeOZNHBqgAl8YAMYA&sensor=false&libraries=places&language=<?=$lang;?>"></script>
    <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
    <script type='text/javascript'>        
      //<![CDATA[
        $(window).resize(function () {
            var h = $(window).height(),
            offsetTop = 105,
            wellHeight = $('.well').height();
        
            $('#map').css('height', (h - offsetTop));            
            $('#result_box').css('height', (h - wellHeight - offsetTop - 60));
        }).resize();

        var map;
        var style = 'custom_style';
        var geocoder = new google.maps.Geocoder();

        var autoOptions = {
            language: '<?=$lang;?>',
            types: ['geocode'],
            componentRestricts: {country: "uk"}
        }
        
        new google.maps.places.Autocomplete(document.getElementById('from'), autoOptions);
        new google.maps.places.Autocomplete(document.getElementById('to'), autoOptions);

        function initialize() {		
            directionsDisplay = new google.maps.DirectionsRenderer();
            var featureOpts = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}];

            geocoder = new google.maps.Geocoder();
            var mapOptions = {
                zoom: 13,
                <?php if ($city == 'kyiv'): ?>
                center: new google.maps.LatLng(50.4501, 30.5234),
                <?php endif ?>
                <?php if ($city == 'odesa'): ?>
                center: new google.maps.LatLng(46.482526, 30.7233095),
                <?php endif ?>
                disableDefaultUI: true,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, style]
                },                
                mapTypeId: style
            };
            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            directionsDisplay.setMap(map);

            var styledMapOptions = {
                name: 'Custom Style'
            };

            var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);

            map.mapTypes.set(style, customMapType);	
            
            var minusControlDiv = document.createElement('div');
            var minusControl = new MinusControl(minusControlDiv, map);

            minusControlDiv.index = 2;
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(minusControlDiv);
            
            var plusControlDiv = document.createElement('div');
            var plusControl = new PlusControl(plusControlDiv, map);

            plusControlDiv.index = 1;
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(plusControlDiv);
        }
        
        function PlusControl(controlDiv, map) {            
            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = '#fff';
            controlUI.style.border = '2px solid #fff';
            controlUI.style.borderRadius = '3px';
            controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
            controlUI.style.cursor = 'pointer';
            controlUI.style.marginTop = '22px';
            controlUI.style.marginRight = '22px';
            controlUI.style.textAlign = 'center';
            controlUI.title = 'Click to recenter the map';
            controlDiv.appendChild(controlUI);
            
            var controlText = document.createElement('div');
            controlText.style.fontSize = '16px';
            controlText.style.lineHeight = '25px';
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.innerHTML = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
            controlUI.appendChild(controlText);
            
            google.maps.event.addDomListener(controlUI, 'click', function() {                                
                var zoom = map.getZoom();
                map.setZoom(zoom + 1);
            });
        }
        
        function MinusControl(controlDiv, map) {            
            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = '#fff';
            controlUI.style.border = '2px solid #fff';
            controlUI.style.borderRadius = '3px';
            controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
            controlUI.style.cursor = 'pointer';
            controlUI.style.marginTop = '22px';
            controlUI.style.marginRight = '22px';
            controlUI.style.textAlign = 'center';
            controlUI.title = 'Click to recenter the map';
            controlDiv.appendChild(controlUI);
            
            var controlText = document.createElement('div');                        
            controlText.style.fontSize = '16px';
            controlText.style.lineHeight = '25px';
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.innerHTML = '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>';
            controlUI.appendChild(controlText);
            
            google.maps.event.addDomListener(controlUI, 'click', function() {                                
                var zoom = map.getZoom();
                map.setZoom(zoom - 1);
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
                
        $( "#search" ).click(function() {
            search();
        });
        $( "#reset" ).click(function() {
            reset();
        });
        $( "#find_me" ).click(function() {
            myGeo();
        });

        $(document).on('click', ".route", function () {
            //$('.route').addClass('.route-hover');
            $('.route').css({
                "background-color" : "#ecf0f1",
            });

            $(this).css({
                "background-color": "#e0e0e0",
                "cursor": "pointer"
            });

            $('.route').children(".route-detail").hide();

            $(this).children(".route-detail").show();

            return false;
        });
        
        translate();
      //]]>
    </script>
    </body>
</html>