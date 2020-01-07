<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>OpenStreetMap &amp; OpenLayers - Marker Example</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
        <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
        <!-- <link rel="stylesheet" href="http://localhost:8081/libs/openlayers/css/ol.css" type="text/css" />
        <script src="http://localhost:8081/libs/openlayers/build/ol.js" type="text/javascript"></script> -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
        <!-- <script src="http://localhost:8081/libs/jquery/jquery-3.4.1.min.js" type="text/javascript"></script> -->
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
    <body onload="initialize_map();" style="background-color:#dfe1e3;font-family: Serif;">
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
                        <input type="checkbox"  id="cmr_roads" checked /><label for="cmr_roads">cmr_roads</label>
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
        <?php include 'cmr_roads_pgsqlAPI.php' ?>
        <?php
            //$myPDO = initDB();
            //$mySRID = '4326';
            //$pointFormat = 'POINT(12,5)';

            //example1($myPDO);
            //example2($myPDO);
            //example3($myPDO,'4326','POINT(12,5)');
            //$result = getResult($myPDO,$mySRID,$pointFormat);

            //closeDB($myPDO);
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
                autoPanAnimation: {
               
                }
            });
            function reset(){
                    document.getElementById("LoaderBalls__item").style.display = "block";
                    document.getElementById("LoaderBalls__item1").style.display = "block";
                    document.getElementById("LoaderBalls__item2").style.display = "block";
                    document.getElementById("broad-content").innerHTML="";
            }
            var cmr_roads = new ol.layer.Group({
                    layers: [ new ol.layer.Image({
                    source: new ol.source.ImageWMS({
                        ratio: 1,
                        url: 'http://localhost:8080/geoserver/example/wms?',
                        params: {
                            'FORMAT': format,
                            'VERSION': '1.1.1',
                            STYLES: '',
                            LAYERS: 'cmr_roads',
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
            function initialize_map() {
                closer.onclick = function() {
                    overlay.setPosition(undefined);
                    closer.blur();
                    return false;
                };
                
                //*
                // layerBG = new ol.layer.Tile({
                //     source: new ol.source.OSM({}),
                // });
                //*/
                
                var viewMap = new ol.View({
                    center: ol.proj.fromLonLat([mapLng, mapLat]),
                    zoom: mapDefaultZoom
                    //projection: projection
                });
                map = new ol.Map({
                    overlays: [overlay],
                    target: "map",
                    layers: [
                        layersOSM,cmr_roads
                    ],
                    overlays: [overlay],
                    //layers: [layerDuongDiaGioi],
                    view: viewMap
                });
                
                //map.getView().fit(bounds, map.getSize());
                map.addOverlay(overlay);
                var styles = {
                    'MultiLineString': new ol.style.Style({
                        stroke: new ol.style.Stroke({
                            color: 'yellow', 
                            width: 3
                        })
                    }),
                    'Polygon': new ol.style.Style({
                        stroke: new ol.style.Stroke({
                            color: 'yellow', 
                            width: 3
                        })
                    })
                };
                var styleFunction = function (feature) {
                    return styles[feature.getGeometry().getType()];
                };
                var vectorLayer = new ol.layer.Vector({
                    //source: vectorSource,
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
                    content.innerHTML = result 
                    overlay.setPosition(coordinate);
                }
                function displayObjInfoB(result,coordinate )
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
                    //alert("result: " + result);
                    var strObjJson = createJsonObj(result);
                    //alert("Full Json: " + strObjJson);
                    var objJson = JSON.parse(strObjJson);
                    //alert("Full Json: " + JSON.stringify(objJson));
                    //drawGeoJsonObj(objJson);
                    highLightGeoJsonObj(objJson);
                }
                map.on('singleclick', function (evt) {
                    //alert("coordinate: " + evt.coordinate);
                    //var myPoint = 'POINT(12,5)';
                    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                    var lon = lonlat[0];
                    var lat = lonlat[1];
                    var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                    //alert("myPoint: " + myPoint);
                    //*
                    $.ajax({
                        type: "POST",
                        url: "cmr_roads_pgsqlAPI.php",
                        //dataType: 'json',
                        data: {functionname: 'getGeoCMRRoadsToAjax', paPoint: myPoint},
                        success : function (result, status, erro) {
                            highLightObj(result);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                        
                    });
                    //*/
                    $.ajax({
                        type: "POST",
                        url: "cmr_roads_pgsqlAPI.php",
                        //dataType: 'json',
                        data: {functionname: 'getInfoCMRRoadsToAjax', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfo(result,evt.coordinate);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "cmr_roads_pgsqlAPI.php",
                        //dataType: 'json',
                        data: {functionname: 'getinfor', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfoB(result,evt.coordinate);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                        
                    });
                });      
            };
            $("#cmr_roads").change(function () {
                if($("#cmr_roads").is(":checked"))
                {
                    cmr_roads.setVisible(true);
                }
                else
                {
                    cmr_roads.setVisible(false);
                }
            });
            $("#layersOSM").change(function () {
                if($("#layersOSM").is(":checked"))
                {
                    layersOSM.setVisible(true);
                }
                else
                {
                    layersOSM.setVisible(false);
                }
            });
            
        </script>
    </body>
</html>