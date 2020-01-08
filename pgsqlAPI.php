<?php
if (isset($_POST['functionName'])) {
    $con = initDB();
    $SRID = '4326';
    $paPoint = $_POST['paPoint'];
    $tableName = $_POST['tableName'];
    $functionName = $_POST['functionName'];

    $result = 'null';
    if ($functionName == 'getInfoToAjax') {
        $result = getInfoToAjax($con, $paPoint, $tableName, $SRID);
    }
    echo $result;

    closeDB($paPoint);
}

/**
 * Tao doi tuong PDO co san thong tin ket noi
 * returns doi tuong PDO cua co so du lieu
 */
function initDB()
{
    $host = 'localhost';
    $database = 'BTL_DB';
    $port = '5432';
    $user = 'postgres';
    $password = 'HTTTDL58TH1';
    // Kết nối CSDL
    $paPDO = new PDO("pgsql:host=$host;dbname=$database;port=$port", $user, $password);
    return $paPDO;
}
/**
 * truy van sql vao co so du lieu
 * $paPDO doi tuong co so du lieu
 * $sql cau lenh truy van co the bind parameters
 *  vidu: select * from :parameter1 where :parameter2 = value
 * $parameters 1 mang key value cac ten parameter va gia tri cua chung
 *  vidu: array('parameter1'=>'boundary_points', 'parameter2'=>'200')
 * returns ket qua cua truy van
 */
function query($paPDO, $sql, $parameters)
{
    try {
        // Khai báo exception
        $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $paPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);

        $stmt = null;

        if ($parameters != null && count($parameters) > 0) {
            $stmt = $paPDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stmt->execute($parameters);
        } else {
            $stmt = $paPDO->prepare($sql);
            $stmt->execute();
        }


        // Khai báo fetch kiểu mảng kết hợp
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Lấy danh sách kết quả
        $paResult = $stmt->fetchAll();
        return $paResult;
    } catch (PDOException $e) {
        echo "Thất bại, Lỗi: " . $e->getMessage();
        return null;
    }
}
/**
 * dong co so du lieu
 * $paPDO PDO csdl
 */
function closeDB($paPDO)
{
    // Ngắt kết nối
    $paPDO = null;
}
/**
 * lay ra tat ca cac bang trong co so du lieu
 * $con PDO csdl
 * returns cot ten cac bang trong co so du lieu
 */
function getTables($con)
{
    $sql = "SELECT table_name
            from information_schema.tables 
            where table_type = 'BASE TABLE' and table_schema='public' 
                and table_name != 'spatial_ref_sys';";
    $result = query($con, $sql, null);
    // log debug
    $array = [];
    if ($result != null) {
        foreach ($result as $row) {
            array_push($array, $row['table_name']);
        }
    }
    // return '['.implode(', ', $array).']';
    // return json_encode(['tableNames' => $array]);
    return $array;
}
/**
 * lay ra bien cua layout tu bang trong co so du lieu
 * $con PDO csdl
 * $tableName ten bang (layout)
 * returns mang gia tri [minX, minY, maxX, maxY]
 */
function getBoundary($con, $tableName)
{
    // nguyen nhan "select... from 'boundary_area';"
    // $sql = "SELECT st_extent(geom) from :tablename ;";
    // $result = query($con, $sql, [':tablename' => $tableName]);
    $sql = "SELECT st_extent(geom) from $tableName;";
    $result = query($con, $sql, null);

    $result = $result[0]['st_extent'];
    $firstPos = strpos($result, '(');
    $lastPos = strpos($result, ')');
    $str = substr($result, $firstPos + 1, $lastPos - $firstPos - 1);
    $str = str_replace(',', ' ', $str);
    $arr = explode(' ', $str);
    return $arr;
}
/**
 * hien thi thong tin diem
 */
function getInfoToAjax($con, $paPoint, $tableName, $SRID)
{
    if($SRID == null || $SRID == 0){
        $SRID = '4326';
    }
    $paPoint = str_replace(',', ' ', $paPoint);
    // $sql = "SELECT id1, shape_leng, shape_area
    //     from :tableName
    //     where st_within('SRID=4326;$paPoint'::geometry, geom);";
    $sql = "SELECT osm_id
        from $tableName
        where st_within(:param1::geometry, geom);";
    $result = query($con, $sql, ['param1'=> "SRID=$SRID;$paPoint"]);
    return json_encode($result);
}
