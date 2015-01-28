<?php
require_once("config.php");
class mapModel{
    function get(){
        $left = $_GET['l'];
        $right = $_GET['r'];
        $width = $_GET['w'];
        $height = $_GET['h'];

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $db = new PDO($dsn, DB_USER, DB_PWD);

        $strsql = "select * from district ";
        $db->query("SET NAMES utf8");
        $rs = $db->prepare($strsql);
        $rs->execute();
        $num = $rs->rowCount();

        $totalArray = array();

        if ($num == 0) {
            $totalArray['error'] = '1';
            $totalArray['errmsg'] = '服务器数据异常';
        } else {
            while ($row = $rs->fetch()) {

                if (rectInRect($left, $right, $width, $height
                    , $row['longitude'], $row['latitude'], $row['width'], $row['height'])) {//找到范围内的景区了
                    $district_id = $row['id'];

                    //查询地图文件
                    $strsql1 = "SELECT * FROM map where district_id ='$district_id'";
                    $db->query("SET NAMES utf8");
                    $rs1 = $db->query($strsql1);
                    $i = 0;
                    while ($row1 = $rs1->fetch()) {
                        $totalArray['map'][$i] = array('lon' => $row1['longitude'], 'lat' => $row1['latitude']
                        , 'url' => stripslashes($row1['url']), 'level' => $row1['level']);
                        $i++;
                    }

                    //查询POI
                    $strsql2 = "SELECT * FROM poi where district_id ='$district_id'";
                    $db->query("SET NAMES utf8");
                    $rs2 = $db->query($strsql2);
                    $i = 0;
                    while ($row2 = $rs2->fetch()) {
                        $totalArray['poi'][$i] = array('lon' => $row2['longitude'], 'lat' => $row2['latitude']
                        , 'name' => urlencode($row2['name']), 'addr' => urlencode($row2['addr']), 'tel' => $row2['tel']
                        , 'type' => $row2['type']);
                        $i++;
                    }

                    $totalArray['error'] = '0';
                    $totalArray['errmsg'] = '0';
                } else {
                    $totalArray['error'] = '2';
                    $totalArray['errmsg'] = '无景点';
                }
            }//while
        }

        return $totalArray;
    }
}

?>