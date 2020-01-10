<?php
    if(isset($_POST['functionname']))
    {
        $connect = initDB();
        $paSRID = '4326';
        $paPoint = $_POST['paPoint'];
        $functionname = $_POST['functionname'];
        
        $aResult = "null";
        if ($functionname == 'toMauDuong')
            $aResult = toMauDuong($connect, $paSRID, $paPoint);
        else if ($functionname == 'tinhToanDuong')
            $aResult = tinhToanDuong($connect, $paSRID, $paPoint);
        else if ($functionname == 'hienThiDuong')
            $aResult = hienThiDuong($connect, $paSRID, $paPoint);
        else if ($functionname == 'toMauVung')
            $aResult = toMauVung($connect, $paSRID, $paPoint);
        else if ($functionname == 'hienThiVung')
            $aResult = hienThiVung($connect, $paSRID, $paPoint);

        echo $aResult;
    
        closeDB($connect);
    }
    function initDB()
    {
        // Kết nối CSDL
        $host = 'localhost';
        $database = 'example';
        $port = '5432';
        $user = 'postgres';
        $password = 'HTTTDL58TH1';
        $db_connection = pg_connect("host=$host port=$port dbname=$database  user=$user password= $password") or die('Could not connect: ' . pg_last_error());;
        return $db_connection;
    }
    function my_query($conn, $query)
    {
        if(func_num_args() == 2){
            $que=pg_query($conn, $query);
            $fet=pg_fetch_all($que);
            return $fet ;
        }

        $args = func_get_args();
        $params = array_splice($args, 2);
        $q=pg_query_params($conn, $query, $params);
        $f=pg_fetch_all($q);
        return  $f;
    }
    function closeDB($connect)
    {
        // Ngắt kết nối
        pg_close($connect);
    }
    function hienThiVung($connect,$paSRID,$paPoint)
    {
    
        $paPoint = str_replace(',', ' ', $paPoint);
        $mySQLStr = "SELECT id_1, shape_leng, shape_area from cmr_adm1 where ST_Within('SRID=".pg_escape_string($paSRID).";".pg_escape_string($paPoint)."'::geometry,geom)";
        $result = my_query($connect, $mySQLStr);
        if ($result != null)
        {
            $resFin = '<table>';
            // Lặp kết quả
            foreach ($result as $item){
                $resFin = $resFin.'<tr><td>id_1: '.$item['id_1'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Chu vi: '.$item['shape_leng'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Diện tích: '.$item['shape_area'].'</td></tr>';
                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }
    function toMauVung($connect,$paSRID,$paPoint)
    {

        $paPoint = str_replace(',', ' ', $paPoint);
    
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"cmr_adm1\" where ST_Within('SRID=".pg_escape_string($paSRID).";".pg_escape_string($paPoint)."'::geometry,geom)";

        $result = my_query($connect, $mySQLStr);
        if ($result != null)
        {
            // Lặp kết quả
            foreach ($result as $item){
                return $item['geo'];
            }
        }
        else
            return "null";
    }
    function toMauDuong($connect,$paSRID,$paPoint)
    {
      
        $paPoint = str_replace(',', ' ', $paPoint);
        $strDistance = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) as huhu   from cmr_roads";
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from cmr_roads where cast(".$strDistance." as text) = ($1) and ".$strDistance." < 0.05";
        $QstrMinDistance=my_query($connect,$strMinDistance);
        foreach($QstrMinDistance as $itemstrMinDistance){
            $result = my_query($connect, $mySQLStr,$itemstrMinDistance['huhu']);
            break;

        }
        if ($result != null)
        {
            // Lặp kết quả
            foreach ($result as $item){
                return $item['geo'];
            }
        }
        else
            return "null";
    }
    
    function hienThiDuong($connect,$paSRID,$paPoint){
        $paPoint1 = str_replace(',', ' ', $paPoint);
        echo $paPoint1;
        $strDistance1 = " ST_Distance('".$paPoint1."',ST_AsText(geom))";
        $strMinDistance1 = "SELECT min(".$strDistance1.") as haha from cmr_roads;";
        $info ="SELECT * from cmr_roads where cast(".$strDistance1." as text) = ($1) and ".$strDistance1."< 0.05";
        $a=my_query($connect,$strMinDistance1) ;
        foreach($a as $item){
             $result1 = my_query($connect, $info,$item['haha']);
            break;
        };
        
        if ($result1 != null)
        {
            $resFin1 = '<table>';
            // Lặp kết quả
            foreach ($result1 as $item){
                $resFin1 = $resFin1.'<tr><td>gid: '.$item['gid'].'</td></tr>';
                $resFin1 = $resFin1.'<tr><td> med_descri: '.$item['med_descri'].'</td></tr>';
                $resFin1 = $resFin1.'<tr><td> rtt_descri: '.$item['rtt_descri'].'</td></tr>';
                $resFin1 = $resFin1.'<tr><td> f_code_des: '.$item['f_code_des'].'</td></tr>';
                $resFin1 = $resFin1.'<tr><td> iso: '.$item['iso'].'</td></tr>';
                $resFin1 = $resFin1.'<tr><td> isocountry: '.$item['isocountry'].'</td></tr>';
                break;
            }
            $resFin1 = $resFin1.'</table>';
            return $resFin1;
        }
        else
            return "null";
    }
    function tinhToanDuong($connect,$paSRID,$paPoint)
    {
        //echo $paPoint;
        //echo "<br>";
        $paPoint = str_replace(',', ' ', $paPoint);
        //echo $paPoint;
        //echo "<br>";
        $strDistance = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) as haha from cmr_roads";
        $mySQLStr = "SELECT gid, ST_Length(geom) as leng from cmr_roads where cast(".$strDistance." as text) = ($1) and ".$strDistance." < 0.05";
        
        $a=my_query($connect,$strMinDistance) ;
        
        foreach($a as $item){
            $result = my_query($connect, $mySQLStr,$item['haha']);
        };
        
        if ($result != null)
        {
            $resFin = '<table>';
            // Lặp kết quả
            foreach ($result as $item){
                $resFin = $resFin.'<tr><td>Mã đối tương: '.$item['gid'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Chiều dài: '.$item['leng'].'</td></tr>';
                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }
?>