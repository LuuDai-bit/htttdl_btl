<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài tập lớn</title>

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="/libs/openlayers/css/ol.css" type="text/css" />
    <script src="/libs/openlayers/build/ol.js" type="text/javascript"></script>
    <script src="/libs/jquery/jquery-3.4.1.min.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body onload="initinizeMap();">
    <div id="map">Something wrong happened!</div>
    <div id="result">Click something on the map then result shows here.</div>

    <?php
    require_once 'pgsqlAPI.php';
    $G_con = initDB();
    function csLog($message)
    {
        echo "<script>console.log('$message')</script>\n";
    }
    ?>
    <?php
    //  #test getTables
    $call1 = getTables($G_con);
    $arr = $call1;
    csLog('[' . implode(', ', $arr) . ']');

    //  #test getBoundary
    $call2 = getBoundary($G_con, "boundary_area");
    csLog('[' . implode(', ', $call2) . ']');

    //  test getInfoToAjax
    ?>

    <script>
        <?php
        $arr = getBoundary($G_con, "boundary_area");
        echo "var boundary = ".json_encode($arr).";\n";
        ?>
        
        var cenX = (boundary[0] + boundary[2]) / 2;
        var cenY = (boundary[1] + boundary[3]) / 2;
        var mapLat = cenY;
        var mapLng = cenX;
        var mapDefaultZoom = 6;
        var map;
        function initinizeMap() {
            //  this fun is empty
            //  test pushing this comment line
            $('#map').html('Map is on loading!..');

            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            var upperLayer = new ol.layer.Image({
                ratio: 1,
                url: 'http://localhost:8080/geoserver/btl_workplace/wms?',
                params: {
                    'FORMAT': 'image/png',
                    'VERSION': '1.1.1',
                    STYLE: '',
                    LAYERS: 'btl_workspace:boundary_area',
                }
            });
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
            });
            map = new ol.Map({
                target: "map",
                // layers: [layerBG, upperLayer],
                layer: [layerBG],
                view: viewMap
            });
            var styleFunction = function(feature) {
                return styles[feature.getGeometry().getType()];
            };
            var vectorLayer = new ol.layer.Vector({
                //source: vectorSource,
                style: styleFunction
            });
            map.addLayer(vectorLayer);
            var styles = {
                'MultiPolygon': new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'orange'
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'yellow',
                        width: 2
                    })
                })
            };
        }
        $(document).ready(function() {
            $('#map').on({
                'click': function() {
                    // query here
                },
                'hover': function() {
                    // pop-up here
                }
            });
        });
    </script>
</body>

</html>