// nothing is here yet
var arr;
var mode;
var styles = {
    'Polygon': new ol.style.Style({
        fill: new ol.style.Fill({
            color: 'orange'
        }),
        stroke: new ol.style.Stroke({
            color: 'yellow',
            width: 2
        })
    })
};
var styleFunction = function(feature) {
    return styles[feature.getGeometry().getType()];
};
var vectorLayer = new ol.layer.Vector({
    //source: vectorSource,
    style: styleFunction
});

function createJsonObj() {
    var temp = {
        type: mode,
        coordinates: arr
    };
    var result = JSON.stringify(temp);
    var geojsonObject = '{' +
        '"type": "FeatureCollection",' +
        '"crs": {' +
        '"type": "name",' +
        '"properties": {' +
        '"name": "EPSG:4326"' +
        '}' +
        '},' +
        '"features": [{' +
        '"type": "Feature",' +
        '"geometry": ' + result +
        '}]' +
        '}';
        console.log(geojsonObject);
    return geojsonObject;
}

// function highLightGeoJsonObj() {
function drawHighLight() {
    var paObjJson = JSON.parse(createJsonObj());
    var vectorSource = new ol.source.Vector({
        features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
            dataProjection: 'EPSG:4326',
            featureProjection: 'EPSG:3857'
        })
    });
    vectorLayer.setSource(vectorSource);
}

function clearHighlight(){
    if(arr == null || arr.length > 0){
        arr = [];
    }
    vectorLayer.setSource(null);
}

function highlightMultiPoint(lon, lat){
    if(mode != 'MultiPoint'){
        clearHighlight();
        mode = 'MultiPoint';
    }
    arr.push([lon, lat]);
    drawHighLight();
}

function highlightLineString(lon, lat){
    if(mode != 'LineString'){
        clearHighlight();
        mode = "LineString";
    }
    arr.push([lon,lat])
    drawHighLight();
}

function highlightPolygon(lon, lat){
    if(mode != 'Polygon'){
        clearHighlight();
        mode = "Polygon";
    }
    var first
    if(arr.length > 0){
        first = arr[0];
        arr.pop();
    } else {
        first = [lon, lat];
    }
    arr.push([lon,lat]);
    arr.push(first);
    drawHighLight();
}