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
        if(func_num_args() == 2)
            return pg_query($conn, $query) ;

        $args = func_get_args();
        $params = array_splice($args, 2);
        return pg_query_params($conn, $query, $params) ;
    }
    function closeDB($connect)
    {
        // Ngắt kết nối
        pg_close($connect);
    }
    function hienThiVung($paPDO,$paSRID,$paPoint)
    {
    
        $paPoint = str_replace(',', ' ', $paPoint);
        $mySQLStr = "SELECT id_1, shape_leng, shape_area from cmr_adm1 where ST_Within('SRID=".pg_escape_string($paSRID).";".pg_escape_string($paPoint)."'::geometry,geom)";
        $arr = my_query($paPDO, $mySQLStr);
        $result = pg_fetch_all($arr);
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
    function toMauVung($paPDO,$paSRID,$paPoint)
    {

        $paPoint = str_replace(',', ' ', $paPoint);
    
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from \"cmr_adm1\" where ST_Within('SRID=".pg_escape_string($paSRID).";".pg_escape_string($paPoint)."'::geometry,geom)";

        $arr = my_query($paPDO, $mySQLStr);
        $result = pg_fetch_all($arr);
        
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
    function toMauDuong($paPDO,$paSRID,$paPoint)
    {
      
        $paPoint = str_replace(',', ' ', $paPoint);
        $strDistance = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) from cmr_roads";
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from cmr_roads where ".$strDistance." = (".$strMinDistance.") and ".$strDistance." < 0.05";
       
        $arr = my_query($paPDO, $mySQLStr);
        $result = pg_fetch_all($arr);
        
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
    function hienThiDuong($paPDO,$paSRID,$paPoint){
        $paPoint1 = str_replace(',', ' ', $paPoint);
        $strDistance1 = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance1 = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) from cmr_roads";
        $info ="SELECT * from cmr_roads where ".$strDistance1." = (".$strMinDistance1.") and ".$strDistance1." < 0.05";
        $arr = my_query($paPDO, $info);
        $result1 = pg_fetch_all($arr);
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
    function tinhToanDuong($paPDO,$paSRID,$paPoint)
    {
        //echo $paPoint;
        //echo "<br>";
        $paPoint = str_replace(',', ' ', $paPoint);
        //echo $paPoint;
        //echo "<br>";
        $strDistance = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) from cmr_roads";
        $mySQLStr = "SELECT gid, ST_Length(geom) as leng from cmr_roads where ".$strDistance." = (".$strMinDistance.") and ".$strDistance." < 0.05";
        
        //echo $mySQLStr;
        //echo "<br><br>";
        $arr = my_query($paPDO, $mySQLStr);
        $result = pg_fetch_all($arr);
        
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