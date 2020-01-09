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
            #LoaderBalls__item,#LoaderBalls__item3 {
                width: 20px; 
                height: 20px; 
                border-radius: 50%; 
                background: #37474f; 
                animation: bouncing 0.4s alternate infinite;
            }
            #LoaderBalls__item1,#LoaderBalls__item4{
                width: 20px; 
                height: 20px; 
                border-radius: 50%; 
                background: #37474f; 
                animation: bouncing 0.4s 0.1s alternate infinite;
            }
            #LoaderBalls__item2,#LoaderBalls__item5{
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
                animation: moved 1s;
                display: flex;
	            justify-content: center;
                align-items: center;
                display:none;
                box-sizing: border-box;
            }
            #broad1{
                position:absolute;
                top:10%;height:350px;width:250px;
                right:0px;
                animation: moved1 1.2s;
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
                animation: movedL 1s;
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
            @keyframes moved1 {
                0% {
                    top:15px;height:100px;width:10px;
                 }
                 50%{
                    top:15px;height:10px;width:100px;
                 }
                100% {
                    top:20%;height:550px;width:250px;
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
                    <div id="map" style="width: 75vw; height: 100vh;box-shadow: 5px 5px 10px 8px #888888; " ></div>
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
                        <input type="checkbox" id="cmr_roads" checked /><label for="cmr_roads">cmr_road</label>
                    </div>
                </div>
                    <div id="broad">
                        <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;"> BROADVung</div>
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
                    <div id="broad1">
                        <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;"> BROADDuong</div>
                        <div style="background-color:#ced7db;" >  
                            <button style="background-color:#ced7db ;color:#37474f" onclick="reset1()">Reset</button>
                        </div>
                        <hr/>
                        <div id="broad-content1" style="color:#4A4A4A;">
                        </div>
                        <div style="display: flex;justify-content: center;align-items: center;">
                            <div id="LoaderBalls__item3" ></div>
                            <div id="LoaderBalls__item4"></div>
                            <div id="LoaderBalls__item5"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    <?php
        require_once 'pgsqlAPI.php'
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
        var onOffDuong=true;
        var onOffVung=true;
        function reset(){
            document.getElementById("LoaderBalls__item").style.display = "block";
            document.getElementById("LoaderBalls__item1").style.display = "block";
            document.getElementById("LoaderBalls__item2").style.display = "block";
             document.getElementById("broad-content").innerHTML="";
        }
        function reset1(){
            document.getElementById("LoaderBalls__item3").style.display = "block";
            document.getElementById("LoaderBalls__item4").style.display = "block";
            document.getElementById("LoaderBalls__item5").style.display = "block";
            document.getElementById("broad-content1").innerHTML="";
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
        var cmr_roads = new ol.layer.Image({
                    source: new ol.source.ImageWMS({
                        ratio: 1,
                        url: 'http://localhost:8080/geoserver/example/wms?',
                        params: {
                            'FORMAT': format,
                            'VERSION': '1.1.1',
                            STYLES: '',
                            LAYERS: 'cmr_roads',
                        }
                    })
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
                    layersOSM,cmr_adm1,cmr_roads
                ],
                overlays: [overlay],
                view: viewMap
            });
            map.addOverlay(overlay);
            var stylesDuong = {
                'MultiLineString': new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: '#37474f', 
                        width: 3
                    })
                }),
            };
            var stylesVung = {
                'MultiPolygon': new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: '#37474f'
                        }),
                        stroke: new ol.style.Stroke({
                            color: 'yellow', 
                            width: 2
                        })
                    })
            };
            var styleFunctionDuong = function (feature) {
                return stylesDuong[feature.getGeometry().getType()];
            };
            var styleFunctionVung = function (feature) {
                return stylesVung[feature.getGeometry().getType()];
            };
            var vectorLayerDuong = new ol.layer.Vector({
                style: styleFunctionDuong
            });
            var vectorLayerVung = new ol.layer.Vector({
                style: styleFunctionVung
            });
            map.addLayer(vectorLayerDuong);
            map.addLayer(vectorLayerVung);
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
            function displayObjInfoBroadVung(result,coordinate )
                {
                    if(onOffVung==true){
                    document.getElementById("LoaderBalls__item").style.display = "none";
                    document.getElementById("LoaderBalls__item1").style.display = "none";
                    document.getElementById("LoaderBalls__item2").style.display = "none";
                    document.getElementById("broad").style.display = "block";
                    document.getElementById("broad-content").innerHTML=result;
                    }
                }
            function displayObjInfoBroadDuong(result,coordinate )
                {
                    if(onOffDuong==true){
                    document.getElementById("LoaderBalls__item3").style.display = "none";
                    document.getElementById("LoaderBalls__item4").style.display = "none";
                    document.getElementById("LoaderBalls__item5").style.display = "none";
                    document.getElementById("broad1").style.display = "block";
                    document.getElementById("broad-content1").innerHTML=result;
                    }
                }
                
                
            function highLightduong(paObjJson) {
                    var vectorSource = new ol.source.Vector({
                        features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        })
                    });
					vectorLayerDuong.setSource(vectorSource);
            }
            function highLightvung(paObjJson) {
                    var vectorSource = new ol.source.Vector({
                        features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                            dataProjection: 'EPSG:4326',
                            featureProjection: 'EPSG:3857'
                        })
                    });
					vectorLayerVung.setSource(vectorSource);
            }
            function highLightObj(result) {
                    var strObjJson = createJsonObj(result);
                    var objJson = JSON.parse(strObjJson);
                    if(onOffDuong==true)
                    highLightduong(objJson);
                    if(onOffVung==true)
                    highLightvung(objJson);
                }
            map.on('singleclick', function (evt) {
                    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                    var lon = lonlat[0];
                    var lat = lonlat[1];
                    var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                    
                    //Hiện info của đường ra broad
                    $.ajax({
                        type: "POST",
                        url: "pgsqlAPI.php",
                        // dataType:'json',
                        data: {functionname: 'hienThiDuong', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfoBroadDuong(result,evt.coordinate);
                            
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                    //Hiện info của vùng ra broad
                    $.ajax({
                        type: "POST",
                        url: "pgsqlAPI.php",
                        // dataType:'json',
                        data: {functionname: 'hienThiVung', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfoBroadVung(result,evt.coordinate);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                    //Tô màu vùng
                    $.ajax({
                        type: "POST",
                        url: "pgsqlAPI.php",
                        // dataType:'json',
                        data: {functionname: 'toMauVung', paPoint: myPoint},
                        success : function (result, status, erro) {
                            highLightObj(result);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                    //Tô màu đường
                    $.ajax({
                        type: "POST",
                        url: "pgsqlAPI.php",
                        // dataType:'json',
                        data: {functionname: 'toMauDuong', paPoint: myPoint},
                        success : function (result, status, erro) {
                            highLightObj(result);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                });       
                map.on('dblclick', function (evt) {
                    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                    var lon = lonlat[0];
                    var lat = lonlat[1];
                    var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                    //Hiện popup về diện tích ,chu vi.... duong
                    $.ajax({
                        type: "POST",
                        url: "pgsqlAPI.php",
                        // dataType:'json',
                        data: {functionname: 'tinhToanDuong', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfo(result,evt.coordinate);
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                });  
            };
            $("#cmr_adm1").change(function () {
                if($("#cmr_adm1").is(":checked")){
                    cmr_adm1.setVisible(true);
                    onOffVung=true;
                }
                else{
                    cmr_adm1.setVisible(false);
                    onOffVung=false;
                }
            });
            $("#layersOSM").change(function () {
                if($("#layersOSM").is(":checked"))
                    layersOSM.setVisible(true);
                else
                    layersOSM.setVisible(false);
            });
            $("#cmr_roads").change(function () {
                if($("#cmr_roads").is(":checked")){
                    cmr_roads.setVisible(true);
                    onOffDuong=true;
                }
                else{
                    cmr_roads.setVisible(false);
                    onOffDuong=false;
                }
            });
    </script>
</body>

</html>