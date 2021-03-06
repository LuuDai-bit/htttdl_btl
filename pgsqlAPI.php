<?php
if (isset($_POST['functionName'])) {
    $connect = initDB();
    $SRID = '4326';
    $functionName = $_POST['functionName'];
    $result = 'null';
    // if ($functionName == 'getInfoToAjax') {
    //     $result = getInfoToAjax($connect, $paPoint, $tableName, $SRID);
    // }
    switch ($functionName) {
        case 'getBoundary':
            $tableName = $_POST['tableName'];
            $result = getBoundary($connect, $tableName);
            break;
        case 'getInfoArea':
            $paPoint = $_POST['paPoint'];
            $admLevel = $_POST['admLevel'];
            $result = getInfoArea($connect, $paPoint, $SRID, $admLevel);
            break;
        case 'getExtraInfoArea':
            $paPoint = $_POST['paPoint'];
            $admLevel = $_POST['admLevel'];
            $result = getExtraInfoArea($connect, $paPoint, $SRID, $admLevel);
            break;
        case 'st_distance':
            $point1 = $_POST['paPoint1'];
            $point2 = $_POST['paPoint2'];
            $result = st_distance($connect, $point1, $point2);
            break;
        case 'st_area':
            $pointsArr = $_POST['paPoint'];
            $result = st_area($connect, $pointsArr);
            break;
        case 'st_length':
            $pointsArr = $_POST['paPoint'];
            $result = st_length($connect, $pointsArr);
            break;
        case 'getExtraTravelPoints':
            $geom = $_POST['geom'];
            $result = getExtraTravelPoints($connect, $geom);
            break;
        default:
            $result = 'wrong functionName';
    }
    echo $result;
    // closeDB($paPoint);
    pg_close($connect);
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
    $options = '--client_encoding=latin1';
    // Kết nối CSDL
    $db_connection = pg_connect("host=$host port=$port dbname=$database user=$user password=$password options=$options")
        or die('Could not connect ' . pg_last_error());
    return $db_connection;
}
/**
 * truy van sql vao co so du lieu
 * $paPDO doi tuong co so du lieu
 * $query cau lenh truy van co the bind parameters
 *  vidu: select * from $1 where $2 = value
 *  Cac parameter truyen theo ham
 *  vidu: query($conn, $query, '10', '20');
 * returns ket qua cua truy van
 */
function query($conn, $query, ...$params)
{
    // $args = func_get_args();
    // $params = array_slice($args, 2);
    $q = null;
    // if ($params == null || count($params) == 0) {
    //     $q = pg_query($conn, $query);
    // } else {
    //     $q = pg_query_params($conn, $query, $params);
    // }
    $q = pg_query_params($conn, $query, $params);
    $f = pg_fetch_all($q);
    return $f;
}
/**
 * dong ket noi co so du lieu
 */
function closeDB($conn)
{
    pg_close($conn);
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
    $result = query($con, $sql);
    // log debug
    $array = [];
    if ($result != null) {
        foreach ($result as $row) {
            array_push($array, $row['table_name']);
        }
    }
    // return '['.implode(', ', $array).']';
    return json_encode($array);
    // return $array;
}
/**
 * lay ra bien cua layout tu bang trong co so du lieu
 * $con PDO csdl
 * $tableName ten bang (layout)
 * returns mang gia tri [minX, minY, maxX, maxY]
 */
function getBoundary($con, $tableName)
{
    $tableName = pg_escape_string($tableName);
    $sql = "SELECT st_extent(geom) as boundary from $tableName;";
    $result = query($con, $sql);
    $result = $result[0]['boundary'];
    $firstPos = strpos($result, '(');
    $lastPos = strpos($result, ')');
    $str = substr($result, $firstPos + 1, $lastPos - $firstPos - 1);
    $str = str_replace(',', ' ', $str);
    $arr = explode(' ', $str);
    return json_encode($arr);
}

/**
 * truy van thong tin vung
 * $paPoint: diem can truy van. $admLevel ['2' => 'country', '4' => 'city/province', '6' => 'district', '5,8,9,10,11,12' => 'others']
 * tra ve: ten vung, dien tich(kilomet vuong - string), so san bay(string):LK:./, so ngan hang(string)
 * vidu: {"name":"Hà Nội","area":"3370.82963823946","airports":"2","banks":"54"}
 */
function getInfoArea($conn, $paPoint, $SRID, $level)
{
    // chuan bi truy van
    if ($SRID == null || $SRID == 0) {
        $SRID = '4326';
    }
    $paPoint = str_replace(',', ' ', $paPoint);
    // truy van ten, dien tich, geom cua vung
    $sql1 =
        "SELECT name, st_area(geom::geography) / pow(10,6) as area, geom
        from boundary_area 
        where st_within($1, geom) and admin_leve = $2
        limit 1;";
    $query1 = query($conn, $sql1, "SRID=$SRID;$paPoint", $level)[0];
    // dem so san bay cua vung
    $sql2 =
        "SELECT count(*) as airports from airport_points
        where st_within(geom, $1);";
    $query2 = query($conn, $sql2, $query1['geom'])[0];
    // dem so ngan hang
    $sql3 =
        "SELECT count(*) as banks from world_bank_aid_points
        where st_within(geom, $1);";
    $query3 = query($conn, $sql3, $query1['geom'])[0];
    // gom ket qua
    
    $result = array_merge($query1, $query2, $query3);
    return json_encode(($result),JSON_UNESCAPED_UNICODE);
}
/**
 * truy van thong tin them cua vung
 * tra ve: dan so, dien tich(km2 - string)
 * vi du: {"population":"7587800","area":"3370.82963823946"}
 */
function getExtraInfoArea($conn, $paPoint, $SRID, $admLevel)
{
    // chuan bi truy van
    if ($SRID == null || $SRID == 0) {
        $SRID = '4326';
    }
    $paPoint = str_replace(',', ' ', $paPoint);
    $sql1 =
        "SELECT population, st_area(geom::geography) / pow(10,6) as area
        from boundary_area 
        where st_within($1, geom) and admin_leve = $2
        limit 1;";
    $query1 = query($conn, $sql1, "SRID=$SRID;$paPoint", $admLevel)[0];
    $result = $query1;
    return json_encode($result,JSON_UNESCAPED_UNICODE);
}
/**
 * tinh khoang cach
 * dau vao point1, point2
 * vi du POINT(0 1), POINT(0 0)
 * tra ve khoang cach don vi met
 */
function st_distance($conn, $point1, $point2)
{
    $sql = "SELECT st_distance($1::geography, $2::geography);";
    $result = query($conn, $sql, $point1, $point2);
    return $result[0]['st_distance'];
}
/**
 * tinh chieu dai cac diem
 * dau vao la cac diem can cua duong
 */
function st_length($conn, $pointsArr)
{
    $sql = "SELECT st_length($1::geography);";
    $result = query($conn, $sql, $pointsArr);
    // echo($sql);
    return $result[0]['st_length'];
}
/**
 * tinh dien tich cac diem
 * dau vao la cac diem cua bien polygon
 */
function st_area($conn, $pointsArr)
{
    $sql = "SELECT st_area($1::geography);";
    $result = query($conn, $sql, $pointsArr);
    return $result[0]['st_area'];
}
function getExtraTravelPoints($conn, $geom)
{
    $sql1 =
        "SELECT name from theme_park 
    where st_within(geom, $1);";
    $query1 = query($conn, $sql1, $geom);
    $sql2 =
        "SELECT name from hotel_points
    where st_within(geom, $1);";
    $query2 = query($conn, $sql2, $geom);
    $sql3 =
        "SELECT name from historic_battlefield
    where st_within(geom, $1);";
    $query3 = query($conn, $sql3, $geom);
    $result = [];
    if ($query1 != false) {
        foreach ($query1 as $item) {
            if($item['name'] != null)
            array_push($result, $item['name']);
        }
    }
    if ($query2 != false) {
        foreach ($query2 as $item) {
            if($item['name'] != null)
            array_push($result, $item['name']);
        }
    }
    if ($query3 != false) {
        foreach ($query2 as $item) {
            if($item['name'] != null)
            array_push($result, $item['name']);
        }
    }
    return json_encode($result, JSON_UNESCAPED_UNICODE);
}