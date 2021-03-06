<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài tập lớn</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="style.css" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body onload="initinizeMap()" style="background-color:#dfe1e3;font-family: Serif;">        
    <table>
            <tr>
                <td>
                    <div id="map" ></div>
                    <div id="popup" class="ol-popup">
                        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                        <div id="popup-content"></div>
                    </div>
                </td>
                <td>
                    <div class="layer" >
                        <div class="layer-child">
                            <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;"> Layer Options</div>  
                            <input type="checkbox"  id="world_bank_aid_points"/><label for="world_bank_aid_points">world_bank_aid_points</label>
                            <input type="checkbox" id="boundary_points"/><label for="boundary_points">boundary_points</label>
                            <hr/>
                            <input type="checkbox" id="boundary_area"/><label for="boundary_area">boundary_area</label>
                            <input type="checkbox" id="boundary_lines"/><label for="boundary_lines">boundary_lines</label>
                            <input type="checkbox" id="airport_points"/><label for="airport_points">airport_points</label>
                            <hr/>
                            <input type="checkbox" id="hotel_points"/><label for="hotel_points">hotel_points</label>
                            <input type="checkbox" id="historic_battlefield"/><label for="historic_battlefield">historic_battlefield</label>
                            <input type="checkbox" id="theme_park"/><label for="theme_park">theme_park</label>
                        </div>
                    </div>
                    <div class="Feature" >
                        <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;">Feature Option</div>
                        <div class="layer-child">
                            <button type="button" class="btn btn-secondary" onclick="Caculate(true);" id="Information" >Information</button>
                            <button type="button" class="btn btn-secondary" onclick="Caculate(false);"id="Caculate">Caculate</button>
                            <div id="An" style="display:none">
                                <hr/>
                                <button type="button" class="btn btn-secondary" onclick="Perimeter()"id="Perimeter" >Perimeter</button>
                                <button type="button" class="btn btn-secondary"id="Acreage" onclick="Acreage()">Acreage</button>
                                <button type="button" class="btn btn-secondary"id="Distance" onclick="Distance()">Distance</button>
                                <hr/>
                                <button type="button" class="btn btn-secondary" onclick="submit()"id="submit">Submit</button>
                            </div>
                        <div id="Hien" style="display:block">
                            <hr/>
                            <button type="button" class="btn btn-secondary" onclick="country();"id="country">country</button>
                            <button type="button" class="btn btn-secondary" onclick="city();"id="city">city</button>
                            <button type="button" class="btn btn-secondary" onclick="district();"id="district">district</button>
                        </div>
                        </div>
                    </div>
                    
                    <div id="broad1" style="top:50%;">
                        <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;"> Information broad</div>
                        <div style="background-color:#ced7db;" >  
                            <button style="background-color:#ced7db ;color:#37474f" onclick="reset1()">Reset</button>
                        </div>
                        <hr/>
                        <div id="broad-content1" style="color:#4A4A4A;"></div>
                        <div id="xemthem" onclick="xemthem()">See more</div>
                        <div style="display: flex;justify-content:center;align-items: center;">
                            <div id="LoaderBalls__item3" ></div>
                            <div id="LoaderBalls__item4"></div>
                            <div id="LoaderBalls__item5"></div>
                        </div>
                    </div> 
                    
                    <div id="broad">
                        <div style="background-color:#37474f; color:white;font-weight: bold;text-align:center;">Detail</div>
                        <table class="table table-hover table-dark" id="content">
                        <button  type="button" class="btn btn-secondary" id="close" style="width:69%;position:fixed;" >Cancel</button>
                        </table>
                    </div>
                  
                </td>
            </tr>
        </table>
    <script src="tinhToan.js" type="text/javascript"></script> 
    <script src="chuanBi.js" type="text/javascript"></script> 
    <script src="thongtin.js" type="text/javascript"></script> 
    <?php
        require_once 'pgsqlAPI.php'
    ?>
    <script>
        <?php
        $G_con = initDB();
        $arr = getBoundary($G_con, "boundary_area");
        echo "var boundary = $arr;\n";
        ?>
        var format = 'image/png';
        var map;
        boundary = boundary.map(function(i) {
            return parseInt(i, 10);
        });
        var poly="";
        var format = 'image/png';
        var map;
        var cenX = (boundary[0] + boundary[2]) / 2;
        var cenY = (boundary[1] + boundary[3]) / 2;
        var mapLat = cenY;
        var mapLng = cenX;
        var airport_points = new ol.layer.Image({
            source: new ol.source.ImageWMS({
                ratio: 1,
                url: 'http://localhost:8080/geoserver/btl_workspace/wms?',
                params: {
                    'FORMAT': format,
                    'VERSION': '1.1.1',
                    STYLES: '',
                    LAYERS: 'airport_points',
                }
            }),
        });
        var boundary_lines = new ol.layer.Image({
            source: new ol.source.ImageWMS({
                ratio: 1,
                url: 'http://localhost:8080/geoserver/btl_workspace/wms?',
                params: {
                    'FORMAT': format,
                    'VERSION': '1.1.1',
                    STYLES: '',
                    LAYERS: 'boundary_lines',
                }
            }),
        });
        var boundary_area = new ol.layer.Image({
            source: new ol.source.ImageWMS({
                ratio: 1,
                url: 'http://localhost:8080/geoserver/btl_workspace/wms?',
                params: {
                    'FORMAT': format,
                    'VERSION': '1.1.1',
                    STYLES: '',
                    LAYERS: 'boundary_area',
                }
            }),
        });
        var boundary_points = new ol.layer.Image({
            source: new ol.source.ImageWMS({
                ratio: 1,
                url: 'http://localhost:8080/geoserver/btl_workspace/wms?',
                params: {
                    'FORMAT': format,
                    'VERSION': '1.1.1',
                    STYLES: '',
                    LAYERS: 'boundary_points',
                }
            }),
        });
        var world_bank_aid_points = new ol.layer.Image({
            source: new ol.source.ImageWMS({
                ratio: 1,
                url: 'http://localhost:8080/geoserver/btl_workspace/wms?',
                params: {
                    'FORMAT': format,
                    'VERSION': '1.1.1',
                    STYLES: '',
                    LAYERS: 'world_bank_aid_points',
                }
            }),
        });

        

        
        function initinizeMap(){
            document.getElementById("close").onclick = function() {
                document.getElementById("broad").style.display="none";
            };
            closer.onclick = function() {
                overlay.setPosition(undefined);
                closer.blur();
                return false;
            };
            var viewMap = new ol.View({
                    center: ol.proj.fromLonLat([mapLng, mapLat]),
                    zoom: mapDefaultZoom
                });
            const feat = new ol.source.Vector();
            const Feat = new ol.layer.Vector({
                source: feat
            });
            map = new ol.Map({
                overlays: [overlay],
                target: "map",
                layers: [
                    layersOSM,boundary_area,boundary_lines,airport_points,hotel_points,
                    boundary_points,historic_battlefield,theme_park,world_bank_aid_points
                ],
                overlays: [overlay],
                view: viewMap
            });
            map.addOverlay(overlay);
            map.addLayer(Feat);
            var F=new ol.interaction.Draw({
                type: 'Polygon',
                source: feat,
                style:new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: '#6c757d21'
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#37474f', 
                        width: 2
                    })
                })
            })
            document.getElementById("Caculate").onmouseover=function() {
                map.addInteraction(F);
                Feat.setSource(feat);
            };
            document.getElementById("Information").onmouseover=function() {
                map.removeInteraction(F);     
                Feat.setSource(null);
            };
            map.on('singleclick', function (evt) {
                    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                    var lon = lonlat[0];
                    var lat = lonlat[1];
                    var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                    let npoint = {
                        'longtitude': lon,
                        'latitude': lat
                    }
                if(mode){
                    //popup
                    TT(myPoint,evt.coordinate,poly);
                }
                    
                else {
                    tinhDienTich(npoint);
                }
            })
            map.on("moveend", function() {
                var zoom = parseInt(map.getView().getZoom(),10);
                console.log(zoom);
                if(zoom>=8 && hotel==true){
                    hotel_points.setVisible(true);
                }
                else hotel_points.setVisible(false);
                if(zoom>=8 && his==true){
                    historic_battlefield.setVisible(true);
                }
                else historic_battlefield.setVisible(false);
                if(zoom>=8 && park==true){
                    theme_park.setVisible(true);
                }
                else theme_park.setVisible(false);
            });
        }
        function xemthem(){
            $.ajax({
                type: "POST",
                url: "pgsqlAPI.php",
                // dataType:'json',
                data: {functionName: 'getExtraTravelPoints', geom:poly},
                success : function (result, status, erro) {
                    kq=JSON.parse(result);
                    Detail(kq);
                },
                error: function (req, status, error) {
                    alert(req + " " + status + " " + error);
                }
            });
        }
        
        $( document ).ready(function() {
            world_bank_aid_points.setVisible(false);
            airport_points.setVisible(false);
            boundary_points.setVisible(false);
            boundary_area.setVisible(false);
            boundary_lines.setVisible(false);
            historic_battlefield.setVisible(false);
            theme_park.setVisible(false);
            hotel_points.setVisible(false);
        });
        
            $("#world_bank_aid_points").change(function () {
                if($("#world_bank_aid_points").is(":checked")){
                    world_bank_aid_points.setVisible(true);
                }
                else{
                    world_bank_aid_points.setVisible(false);
                }
            });
            $("#airport_points").change(function () {
                if($("#airport_points").is(":checked"))
                    airport_points.setVisible(true);
                else
                    airport_points.setVisible(false);
            });
            $("#boundary_points").change(function () {
                if($("#boundary_points").is(":checked")){
                    boundary_points.setVisible(true);
            }
                else{
                    boundary_points.setVisible(false);
                }
            });
            $("#boundary_area").change(function () {
                if($("#boundary_area").is(":checked")){
                    boundary_area.setVisible(true);
                }
                else{
                    boundary_area.setVisible(false);
                }
            });
            $("#boundary_lines").change(function () {
                if($("#boundary_lines").is(":checked")){
                    boundary_lines.setVisible(true);
                }
                else{
                    boundary_lines.setVisible(false);
                }
            });
            $("#historic_battlefield").change(function () {
                if($("#historic_battlefield").is(":checked")){
                    his=true;
                }
                else{
                    historic_battlefield.setVisible(false);
                    his=false;
                }
            });
            $("#theme_park").change(function () {
                if($("#theme_park").is(":checked")){
                    park=true;
                }
                else{
                    theme_park.setVisible(false);
                    park=false;
                }
            });
            $("#hotel_points").change(function () {
                if($("#hotel_points").is(":checked")){
                    hotel=true;
                }
                else{
                    hotel_points.setVisible(false);
                    hotel=false;
                }
            });   
    </script>
</body>

</html>