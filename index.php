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
    ?>
    <?php
    $call1 = getTables($G_con);
    echo "<script>console.log('$call1')</script>";
    $arr = json_decode($call1);
    foreach ($arr as $name) {
        $call2 = getBoundary($G_con, $name);
        echo "<script>console.log('$call2')</script>";
    }
    ?>

    <script>
        function initinizeMap() {
            //  this fun is empty
            //  test pushing this comment line
            $('#map').html('Map is on loading!..');

            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            var layer = new ol.layer.Image({
                ratio: 1,
                url: 'http://localhost:8080/geoserver/btl_workplace/wms?',
                params: {
                    'FORMAT': 'image/png',
                    'VERSION': '1.1.1',
                    STYLE: '',
                    LAYERS: 'btl_workspace:boundary_area',
                }
            });
            // var viewMap = new ol.View({
            //     center: ol.proj.fromLonLat([mapL])
            // })
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