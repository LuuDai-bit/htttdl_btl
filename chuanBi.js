var adm_leve;
var mapDefaultZoom = 6;
var container = document.getElementById('popup');
var content = document.getElementById('popup-content');
var closer = document.getElementById('popup-closer');
var overlay = new ol.Overlay({
    element: container,
    autoPan: true,
});
var hotel=false;var his=false;var park=false;
function reset1(){
    document.getElementById("LoaderBalls__item3").style.display = "block";
    document.getElementById("LoaderBalls__item4").style.display = "block";
    document.getElementById("LoaderBalls__item5").style.display = "block";
    document.getElementById("broad-content1").innerHTML="";
    arrayPoints=[];
    document.getElementById("Distance").style.background= "#6c757d";
    document.getElementById("Acreage").style.background= "#6c757d";
    document.getElementById("Perimeter").style.background= "#6c757d";
    document.getElementById("submit").style.background= "#6c757d";
    document.getElementById("city").style.background= "#6c757d";
    document.getElementById("district").style.background= "#6c757d";
    document.getElementById("country").style.background= "#6c757d";
    document.getElementById("xemthem").style.display= "none";
    
}
function country(){
    document.getElementById("country").style.background= "#6c757d6e";
    document.getElementById("city").style.background= "#6c757d";
    document.getElementById("district").style.background= "#6c757d";
    adm_leve=2;
}
function city(){
    document.getElementById("city").style.background= "#6c757d6e";
    document.getElementById("country").style.background= "#6c757d";
    document.getElementById("district").style.background= "#6c757d";
    adm_leve=4;
}
function district(){
    document.getElementById("district").style.background= "#6c757d6e";
    document.getElementById("country").style.background= "#6c757d";
    document.getElementById("city").style.background= "#6c757d";
    adm_leve=6;
}
var layersOSM = new ol.layer.Group({
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM()
        })
    ]
});
var hotel_styles = new ol.style.Style({
    image: new ol.style.Icon({
        anchor: [0.5, 0.5],
        anchorXUnits: "fraction",
        anchorYUnits: "fraction",
        src: "../icons8-condo-48.png",
    })
});
var historic_battlefield_styles = new ol.style.Style({
    image: new ol.style.Icon({
        anchor: [0.5, 0.5],
        anchorXUnits: "fraction",
        anchorYUnits: "fraction",
        src: "../icons8-mark-iv-tank-48.png",
    })
});
var theme_park_styles = new ol.style.Style({
    image: new ol.style.Icon({
        anchor: [0.5, 0.5],
        anchorXUnits: "fraction",
        anchorYUnits: "fraction",
        src: "../icons8-theme-park-48.png",
    })
});
var hotel_points = new ol.layer.Vector({
    source: new ol.source.Vector({
        url: '../data/countries.geojson',
        format: new ol.format.GeoJSON()
    }),
    style:hotel_styles
});
var historic_battlefield = new ol.layer.Vector({
    source: new ol.source.Vector({
        url: '../data/historic_battlefield.geojson',
        format: new ol.format.GeoJSON()
    }),
    style:historic_battlefield_styles
});
var theme_park = new ol.layer.Vector({
    source: new ol.source.Vector({
        url: '../data/theme_park.geojson',
        format: new ol.format.GeoJSON()
    }),
    style:theme_park_styles
});
