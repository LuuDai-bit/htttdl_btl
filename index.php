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

<body onload="initializeMap();">
    <div id="map"></div>
    <div id="result">Click something on the map then result shows here.</div>

    <?php
    require_once 'pgsqlAPI.php';
    $G_con = initDB();
    function csLog($message)
    {
        echo "<script>console.log('$message')</script>\n";
    }

    //  #test getTables
    $call1 = getTables($G_con);
    $arr = $call1;
    // csLog('[' . implode(', ', $arr) . ']');
    csLog($arr);

    //  #test getBoundary
    $call2 = getBoundary($G_con, "boundary_area");
    csLog($call2);

    //  #test getInfoArea
    $paPoint = 'POINT(105.91 21.3)';
    $call3 = getInfoArea($G_con, $paPoint, 4326, 4);
    csLog($call3);

    // #test getExtraInfoArea
    $call4 = getExtraInfoArea($G_con, $paPoint, 4326, 4);
    csLog($call4);
    ?>
    <script>
        <?php
            echo "var arr1 = $call1;\n";
            echo "var arr2 = $call2;\n";
            echo "var arr3 = $call3;\n";
            echo "var arr4 = $call4;\n";
        ?>
        console.log(arr1);
        console.log(arr2);
        console.log(arr3);
        console.log(arr4);
    </script>
    <script>
        <?php
        $arr = getBoundary($G_con, "boundary_area");
        echo "var boundary = $arr;\n";
        ?>
        var format = 'image/png';
        var map;
        boundary = boundary.map(function(i) {
            return parseInt(i, 10);
        });
        // var minX = boundary[0];
        // var minY = boundary[1];
        // var maxX = boundary[2];
        // var maxY = (boundary[3];
        // var cenX = (minX + maxX) / 2;
        // var cenY = (minY + maxY) / 2;
        var cenX = (boundary[0] + boundary[2]) / 2;
        var cenY = (boundary[1] + boundary[3]) / 2;
        var mapLat = cenY;
        var mapLng = cenX;
        var mapDefaultZoom = 5.5;

        function initializeMap() {

            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });

            var upperLayer = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/btl_workspace/wms',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.1',
                        STYLES: '',
                        LAYERS: 'boundary_area',
                    }
                })
            });
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
            });
            map = new ol.Map({
                target: "map",
                layers: [layerBG, upperLayer],
                view: viewMap
            });
            // var styles = {
            //     'MultiPolygon': new ol.style.Style({
            //         fill: new ol.style.Fill({
            //             color: 'orange'
            //         }),
            //         stroke: new ol.style.Stroke({
            //             color: 'yellow',
            //             width: 2
            //         })
            //     })
            // };
            // var styleFunction = function(feature) {
            //     return styles[feature.getGeometry().getType()];
            // };
            // var vectorLayer = new ol.layer.Vector({
            //     //source: vectorSource,
            //     style: styleFunction
            // });
            // map.addLayer(vectorLayer);
            map.on('singleclick', function(e) {
                var lonlat = ol.proj.transform(e.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                console.log('request: ' + myPoint);
                $.ajax({
                    type: "POST",
                    url: "pgsqlAPI.php",
                    //dataType: 'json',
                    data: {
                        functionName: 'getInfoArea',
                        paPoint: myPoint,
                        admLevel: 4
                    },
                    success: function(result, status, error) {
                        console.log(result);
                    },
                    error: function(req, status, error) {
                        console.log(req + "\n" + status + "\n" + error);
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "pgsqlAPI.php",
                    //dataType: 'json',
                    data: {
                        functionName: 'getExtraInfoArea',
                        paPoint: myPoint,
                        admLevel: 4
                    },
                    success: function(result, status, error) {
                        console.log(result);
                    },
                    error: function(req, status, error) {
                        console.log(req + "\n" + status + "\n" + error);
                    }
                });
            });
        }
    </script>
</body>

</html>