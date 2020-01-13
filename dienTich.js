var mode=true;
var cheDo="dientich";
function Caculate(m){
    mode=m;
    if(mode){
        document.getElementById("Information").style.background= "#6c757d6e";
        document.getElementById("Caculate").style.background= "#6c757d";
        document.getElementById("An").style.display= "none";
        document.getElementById("submit").style.background= "#6c757d";
    }
    else{
        document.getElementById("Information").style.background= "#6c757d";
        document.getElementById("Caculate").style.background= "#6c757d6e";
        document.getElementById("An").style.display= "block";
        document.getElementById("submit").style.background= "#6c757d";
    }
}
function Perimeter(){
    dienTich=false;
    document.getElementById("Perimeter").style.background= "#6c757d6e";
    document.getElementById("submit").style.background= "#6c757d";
    document.getElementById("Distance").style.background= "#6c757d";
    document.getElementById("Acreage").style.background= "#6c757d";
    cheDo='chuvi';
}
function Acreage(){
    dienTich=true;
    document.getElementById("Perimeter").style.background= "#6c757d";
    document.getElementById("Acreage").style.background= "#6c757d6e";
    document.getElementById("Distance").style.background= "#6c757d";
    document.getElementById("submit").style.background= "#6c757d";
    cheDo='dientich';
}
function Distance(){
    document.getElementById("Distance").style.background= "#6c757d6e";
    document.getElementById("Acreage").style.background= "#6c757d";
    document.getElementById("Perimeter").style.background= "#6c757d";
    document.getElementById("submit").style.background= "#6c757d";
    cheDo='khoangcach';
}
var arrayPoints=[];
function tinhDienTich(point){
    arrayPoints.push(point);
}
function submit(){
    document.getElementById("submit").style.background= "#6c757d6e";
    if(arrayPoints.length <= 1) return;
    let ham = 'st_distance';
    if(arrayPoints.length>2 && cheDo=='dientich'){
        arrayPoints.push(arrayPoints[0]);
        ham = 'st_area';
        let str = convertArrayToPolygon(arrayPoints);
        $.ajax({
            type: "POST",
            url: "pgsqlAPI.php",
            // dataType: 'json',
            data: {functionName: ham, paPoint:str},
            success : function (result, status, error) {
                displayObjInfoBroadVung(result );
            },
            error: function (req, status, error) {
                alert(req + " " + status + " " + error);
            }
        });
    } 
    if(arrayPoints.length>2 && cheDo=='chuvi'){
        console.log(arrayPoints);
        // arrayPoints.push(arrayPoints[0]);
        console.log(arrayPoints);
        ham = 'st_length';
        let str = convertArrayToLineString(arrayPoints);
        $.ajax({
            type: "POST",
            url: "pgsqlAPI.php",
            // dataType: 'json',
            data: {functionName: ham, paPoint: str},
            success : function (result, status, error) {
                displayObjInfoBroadVung(result );
            },
            error: function (req, status, error) {
                alert(req + " " + status + " " + error);
            }
        });
    }    
    else if(cheDo=='khoangcach'){
        var myPoint1='POINT(' + arrayPoints[0].longtitude + ' ' + arrayPoints[0].latitude + ')';
        var myPoint2='POINT(' + arrayPoints[1].longtitude + ' ' + arrayPoints[1].latitude + ')';
        $.ajax({
            type: "POST",
            url: "pgsqlAPI.php",
            // dataType: 'json',
            data: {functionName: ham, paPoint1: myPoint1,paPoint2: myPoint2},
            success : function (result, status, error) {
                displayObjInfoBroadVung(result);
            },
            error: function (req, status, error) {
                alert(req + " " + status + " " + error);
            }
        });
    }
    
    function displayObjInfoBroadVung(result )
    {     
        document.getElementById("LoaderBalls__item3").style.display = "none";
        document.getElementById("LoaderBalls__item4").style.display = "none";
        document.getElementById("LoaderBalls__item5").style.display = "none";
        document.getElementById("broad1").style.display = "block";
        document.getElementById("broad-content1").innerHTML=result;
    }  
    
}
function convertArrayToPolygon(){
    let polygon_data = 'MULTIPOLYGON(((';
    for(let i = 0; i < arrayPoints.length; i++){
        polygon_data  = polygon_data + 
        arrayPoints[i]["longtitude"] + ' ' + arrayPoints[i]['latitude'] + ' '
        ;
    }

    polygon_data += ')))';
    console.log(polygon_data);
    return polygon_data;
}

function convertArrayToLineString(){
    let polygon_data = 'MULTILINESTRING((';
    for(let i = 0; i < arrayPoints.length; i++){
        polygon_data  = polygon_data + 
        arrayPoints[i]["longtitude"] + ' ' + arrayPoints[i]['latitude'] ;
        if(i != arrayPoints.length-1) polygon_data+= ', ';
        
    }

    polygon_data += '))';
    console.log(polygon_data);
    return polygon_data;
}