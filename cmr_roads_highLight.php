<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài tập lớn</title>

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>

    <!-- <link rel="stylesheet" href="/libs/openlayers/css/ol.css" type="text/css" />
    <script src="/libs/openlayers/build/ol.js" type="text/javascript"></script>
    <script src="/libs/jquery/jquery-3.4.1.min.js" type="text/javascript"></script> -->
    <style>
            .ol-popup {
                position: absolute;
                background-color:#dfe1e3;
                filter: drop-shadow(0 1px 4px rgba(0,0,0,0.2));
                padding: 15px;
                border-radius: 10px;
                border: 1px solid #cccccc;
                bottom: 12px;
                left: -50px;
                min-width: 280px;
            }
            .ol-popup-closer {
                text-decoration: none;
                position: absolute;
                top: 2px;
                right: 8px;
            }
            .ol-popup-closer:after {
                content: "✖";
                color:#37474f
            }
            #LoaderBalls__item{
                width: 20px; 
                height: 20px; 
                border-radius: 50%; 
                background: #37474f; 
                animation: bouncing 0.4s alternate infinite;
            }
            #LoaderBalls__item1{
                width: 20px; 
                height: 20px; 
                border-radius: 50%; 
                background: #37474f; 
                animation: bouncing 0.4s 0.1s alternate infinite;
            }
            #LoaderBalls__item2{
                width: 20px; 
                height: 20px; 
                border-radius: 50%; 
                background: #37474f; 
                animation: bouncing 0.4s 0.2s alternate infinite;
            }
            #broad{
                position:absolute;
                top:55%;height:350px;width:250px;
                right:0px;
                animation: moved 0.9s;
                display: flex;
	            justify-content: center;
                align-items: center;
                display:none;
                box-sizing: border-box;
            }
            .layer{
                position:absolute;
                top:5px;
                right:0px;
                background-color:#ced7db;
            }
            .layer-child{
                animation: movedL 0.7s;
            }
             @keyframes bouncing {
                0% {
                    transform: translate3d(0, 8px, 0) scale(1.2, 0.85);
                 }
                100% {
                    transform: translate3d(0, -20px, 0) scale(0.9, 1.1);
                }
            }
            @keyframes moved {
                0% {
                    top:15px;height:100px;width:200px;
                 }
                100% {
                    top:60%;height:550px;width:250px;
                }
            }
            @keyframes movedL {
                0% {
                    top:15px;height:0%;width:0%;
                 }
                50% {
                    top:15px;height:50%;width:50%;
                 }
                100% {
                    top:15px;height:100%;width:100%;
                }
            }  
        </style>
</head>

<body onload="initinizeMap()" style="background-color:#dfe1e3;font-family: Serif;">
    <table>
            <tr>
                <td>
                    <div id="map" style="width: 80vw; height: 100vh;box-shadow: 5px 5px 10px 8px #888888; " ></div>
                    <div id="popup" class="ol-popup">
                        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                        <div id="popup-content"></div>
                    </div>
                </td>
                <td>
                <div class="layer" >
                    <div class="layer-child">
                        <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;"> Layer Options</div>  
                        <input type="checkbox"  id="cmr_adm1" checked /><label for="cmr_adm1">cmr_adm1</label>
                        <input type="checkbox" id="layersOSM" checked /><label for="layersOSM">layersOSM</label>
                    </div>
                </div>
                    <div id="broad">
                        <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;"> BROAD</div>
                        <div style="background-color:#ced7db;" >  
                            <button style="background-color:#ced7db ;color:#37474f" onclick="reset()">Reset</button>
                        </div>
                        <hr/>
                        <div id="broad-content" style="color:#4A4A4A;">
                        </div>
                        <div style="display: flex;justify-content: center;align-items: center;">
                            <div id="LoaderBalls__item" ></div>
                            <div id="LoaderBalls__item1"></div>
                            <div id="LoaderBalls__item2"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    <?php
        require_once 'cmr_roads_pgsqlAPI.php'
    ?>
    <script>
        var format = 'image/png';
        var map;
        var minX = 8.8262996673584;
        var minY = 1.97410714626312;
        var maxX = 15.5927391052246;
        var maxY = 12.7209100723267;
        var cenX = (minX + maxX) / 2;
        var cenY = (minY + maxY) / 2;
        var mapLat = cenY;
        var mapLng = cenX;
        var mapDefaultZoom = 7;
        var container = document.getElementById('popup');
        var content = document.getElementById('popup-content');
        var closer = document.getElementById('popup-closer');
        var overlay = new ol.Overlay({
            element: container,
            autoPan: true,
        });
        
        function reset(){
            document.getElementById("LoaderBalls__item").style.display = "block";
            document.getElementById("LoaderBalls__item1").style.display = "block";
            document.getElementById("LoaderBalls__item2").style.display = "block";
             document.getElementById("broad-content").innerHTML="";
        }
        var cmr_adm1 = new ol.layer.Group({
            layers: [ new ol.layer.Image({
             source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/example/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.1',
                        STYLES: '',
                        LAYERS: 'cmr_adm1',
                    }
                }),
            })]
        });
        var layersOSM = new ol.layer.Group({
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ]
        });
        function initinizeMap(){
            closer.onclick = function() {
                overlay.setPosition(undefined);
                closer.blur();
                return false;
            };
            var viewMap = new ol.View({
                    center: ol.proj.fromLonLat([mapLng, mapLat]),
                    zoom: mapDefaultZoom
                });
            map = new ol.Map({
                overlays: [overlay],
                target: "map",
                layers: [
                    layersOSM,cmr_adm1
                ],
                overlays: [overlay],
                view: viewMap
            });
            map.addOverlay(overlay);
            var styles = {
                'MultiLineString': new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: '#37474f', 
                        width: 3
                    })
                }),
                'Polygon': new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: '#37474f', 
                         width: 3
                    })
                })
            };
            var styleFunction = function (feature) {
                return styles[feature.getGeometry().getType()];
            };
            var vectorLayer = new ol.layer.Vector({
                style: styleFunction
            });
            map.addLayer(vectorLayer);
            function createJsonObj(result) {                    
                    var geojsonObject = '{'
                            + '"type": "FeatureCollection",'
                            + '"crs": {'
                                + '"type": "name",'
                                + '"properties": {'
                                    + '"name": "EPSG:4326"'
                                + '}'
                            + '},'
                            + '"features": [{'
                                + '"type": "Feature",'
                                + '"geometry": ' + result
                            + '}]'
                        + '}';
                    return geojsonObject;
            }
            function displayObjInfo(result,coordinate )
                {
                    content.innerHTML = result;
                    overlay.setPosition(coordinate);
                }
            function displayObjInfoBroad(result,coordinate )
                {
                    document.getElementById("LoaderBalls__item").style.display = "none";
                    document.getElementById("LoaderBalls__item1").style.display = "none";
                    document.getElementById("LoaderBalls__item2").style.display = "none";
                    document.getElementById("broad").style.display = "block";
                    document.getElementById("broad-content").innerHTML=result;
                }
                
            function highLightGeoJsonObj(paObjJson) {
                    var vectorSource = new ol.source.Vector({
                        features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        })
                    });
					vectorLayer.setSource(vectorSource);
            }
            function highLightObj(result) {
                    var strObjJson = createJsonObj(result);
                    var objJson = JSON.parse(strObjJson);
                    highLightGeoJsonObj(objJson);
                }
            map.on('singleclick', function (evt) {
                    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                    var lon = lonlat[0];
                    var lat = lonlat[1];
                    var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                    //Tô màu
                    $.ajax({
                        type: "POST",
                        url: "cmr_roads_pgsqlAPI.php",
                        dataType:'json',
                        data: {functionname: 'getGeoCMRRoadsToAjax', paPoint: myPoint},
                        success : function (result, status, erro) {
                            highLightObj(result);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                    //Hiện popup về diện tích ,chu vi....
                    $.ajax({
                        type: "POST",
                        url: "cmr_roads_pgsqlAPI.php",
                        dataType:'json',
                        data: {functionname: 'getCaculatorToAjax', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfo(result,evt.coordinate);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                    //Hiện info của vùng ra broad
                    $.ajax({
                        type: "POST",
                        url: "cmr_roads_pgsqlAPI.php",
                        dataType:'json',
                        data: {functionname: 'getInfoToAjax', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfoBroad(result,evt.coordinate);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                        
                    });
                });      
            };
            $("#cmr_adm1").change(function () {
                if($("#cmr_adm1").is(":checked"))
                    cmr_adm1.setVisible(true);
                else
                    cmr_adm1.setVisible(false);
            });
            $("#layersOSM").change(function () {
                if($("#layersOSM").is(":checked"))
                    layersOSM.setVisible(true);
                else
                    layersOSM.setVisible(false);
            });
    </script>
</body>

</html>