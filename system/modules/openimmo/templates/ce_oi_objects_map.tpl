<div class="ce_oi_objects_map">
    <div class="image">
        <img src="http://maps.google.com/maps/api/staticmap?size=<? echo $this->cropSize; ?>&amp;maptype=roadmap&amp;sensor=false<? echo $this->mapMakers; ?>" alt="Google Maps" title="" border="0" />
    </div>
    <div class="link">
        <a onclick="openGMap();">
            Karte vergrößern
        </a>
    </div>
</div>

<div id="mb_mapDummy" style="display:none;"></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
<!--
function openGMap() {

    Mediabox.open('#mb_mapDummy', 'Übersicht unserer Objektstandorte', '');
    initGMap();
}

function initGMap() {
    
    document.getElementById('mbImage').innerHTML = '<div id="mapC" style="width:640px; height:360px;"></div>';

    var mapContainer = document.getElementById('mapC');

    var options = {
        center: new google.maps.LatLng(47.635784,4.96582)
    ,   zoom: 4
    ,   mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(mapContainer, options);

    var markers = [
        <?php foreach( $this->aMapMakers as $i => $marker ) { ?>

            {
                name    : '<?=addslashes($marker['name']);?>'
            ,   position: new google.maps.LatLng(<?=$marker['lon'];?>,<?=$marker['lat'];?>)
            ,   addr    : '<?=addslashes($marker['addr']);?>'
            ,   link    : '<?=addslashes($marker['link']);?>'
            ,   img     : '{{image::<?=$marker['img'];?>?width=60&height=60}}'
            },
            
        <? } ?>
    ];
    
    // zoom to markers center
    var bounds = new google.maps.LatLngBounds();

    markers.forEach(function(element, index, array) {

        try {
            bounds.extend(element.position)
        } catch( e ) {}
    });

    map.fitBounds(bounds);
    /////

    var infoWindow;

    markers.forEach(function(element, index, array) {

        try {
            var marker = new google.maps.Marker({
                position: element.position
            ,   map     : map
            ,   title   : element.name+' - '+element.addr
            });

            google.maps.event.addListener(marker, 'click', function() {

                if( !infoWindow ) {
                    infoWindow = new google.maps.InfoWindow();
                }
                

                var content = '<div class="oi_map_infowindow">' +
                              '     '+element.img+
                              '     <div class="text"> '+
                              '         <h1>'+element.name+'</h1> '+
                              '         <h2>'+element.addr+'</h2> '+
                              '         <a href="'+element.link+'">Angebot ansehen</a> '+
                              '     </div> '+
                              '</div>';
            
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            });
        } catch( e ) {}
    });
}
-->
</script>