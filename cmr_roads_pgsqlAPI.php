<?php
    if(isset($_POST['functionname']))
    {
        $paPDO = initDB();
        $paSRID = '4326';
        $paPoint = $_POST['paPoint'];
        $functionname = $_POST['functionname'];
        
        $aResult = "null";
        if ($functionname == 'getGeoCMRRoadsToAjax')
            $aResult = getGeoCMRRoadsToAjax($paPDO, $paSRID, $paPoint);
        else if ($functionname == 'getInfoCMRRoadsToAjax')
            $aResult = getInfoCMRRoadsToAjax($paPDO, $paSRID, $paPoint);
        else if ($functionname == 'getinfor')
            $aResult = getinfor($paPDO, $paSRID, $paPoint);
        echo $aResult;
    
        closeDB($paPDO);
    }

    function initDB()
    {
        // Kết nối CSDL
        $paPDO = new PDO('pgsql:host=localhost;dbname=example;port=5432', 'postgres', 'HTTTDL58TH1');
        return $paPDO;
    }
    function query($paPDO, $paSQLStr)
    {
        try
        {
            // Khai báo exception
            $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Sử đụng Prepare 
            $stmt = $paPDO->prepare($paSQLStr);
            // Thực thi câu truy vấn
            $stmt->execute();
            
            // Khai báo fetch kiểu mảng kết hợp
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
            // Lấy danh sách kết quả
            $paResult = $stmt->fetchAll();   
            return $paResult;                 
        }
        catch(PDOException $e) {
            echo "Thất bại, Lỗi: " . $e->getMessage();
            return null;
        }       
    }
    function closeDB($paPDO)
    {
        // Ngắt kết nối
        $paPDO = null;
    }
    function getGeoCMRRoadsToAjax($paPDO,$paSRID,$paPoint)
    {
        //echo $paPoint;
        //echo "<br>";
        $paPoint = str_replace(',', ' ', $paPoint);
        //echo $paPoint;
        //echo "<br>";
        $strDistance = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) from cmr_roads";
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from cmr_roads where ".$strDistance." = (".$strMinDistance.") and ".$strDistance." < 0.05";
        //echo $mySQLStr;
        //echo "<br><br>";
        $result = query($paPDO, $mySQLStr);
        
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
    function getinfor($paPDO,$paSRID,$paPoint){
        $paPoint1 = str_replace(',', ' ', $paPoint);
        $strDistance1 = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance1 = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) from cmr_roads";
        $info ="SELECT * from cmr_roads where ".$strDistance1." = (".$strMinDistance1.") and ".$strDistance1." < 0.05";
        $result1= query ($paPDO, $info);
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
    function getInfoCMRRoadsToAjax($paPDO,$paSRID,$paPoint)
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
        $result = query($paPDO, $mySQLStr);
        
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