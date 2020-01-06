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

<body onload="initinizeMap()">
    <div id="map">Something wrong happened!</div>

    <?php
    require_once 'pgsqlAPI.php'
    ?>

    <script>
        function initinizeMap() {
            //  this fun is empty
            //  test pushing this comment line
            $('#map').html('Map is on loading!..');
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