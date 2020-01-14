
function TT(myPoint,coordinate){
    $.ajax({
        type: "POST",
        url: "pgsqlAPI.php",
        // dataType: 'json',
        data: {functionName: 'getInfoArea', paPoint: myPoint,admLevel:adm_leve},
        success : function (result, status, error) {
            var kq =JSON.parse(result);
            poly=kq['geom'];
            console.log(kq);
            popUp(kq,coordinate);
        },
        error: function (req, status, error) {
            alert(req + " " + status + " " + error);
        }
        
    });
    //xem thêm
    $.ajax({
        type: "POST",
        url: "pgsqlAPI.php",
        // dataType:'json',
        data: {functionName: 'getExtraInfoArea', paPoint: myPoint,admLevel:adm_leve},
        success : function (result, status, erro) {
            kq=JSON.parse(result);
            
            displayObjInfoBroadVung(kq,coordinate);
        },
        error: function (req, status, error) {
            alert(req + " " + status + " " + error);
        }
    });
    
}
function popUp(result,coordinate ){   
    var kq="";
    kq=kq+"name"+" : "+result['name']+"<hr/>";
    kq=kq+"area"+" : "+result['area']+"<hr/>";
    kq=kq+"airports"+" : "+result['airports']+"<hr/>";
    kq=kq+"banks"+" : "+result['banks'];
    content.innerHTML=kq;
    overlay.setPosition(coordinate);
    
}
function Detail(result){
    document.getElementById("broad").style.display= "block";
    var kq="";
    var id="";
    Object.keys(result).forEach(function (item) {
        console.log(result[item]);
        kq+="<tr><th>"+item+"</th><td>"+result[item]+"</td></tr>";
    });
    document.getElementById("content").innerHTML = kq;
}
function displayObjInfoBroadVung(result,coordinate )
    {   if(cheDo=='thong tin'){
        document.getElementById("LoaderBalls__item3").style.display = "none";
        document.getElementById("LoaderBalls__item4").style.display = "none";
        document.getElementById("LoaderBalls__item5").style.display = "none";
        document.getElementById("broad1").style.display = "block";
        document.getElementById("xemthem").style.display = "block";
        var kq="";
        Object.keys(result).forEach(function(item){
            kq=kq+item+" : "+result[item]+"<hr/>";
        });
        document.getElementById("broad-content1").innerHTML=kq;
    }
    }