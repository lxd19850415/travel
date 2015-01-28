<?php
require_once("config.php");
class aroundUserModel{
    function get(){
        $district_id=$_GET['district_id'];
//        $filter=$_GET['filter'];

        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
        $db = new PDO($dsn, DB_USER, DB_PWD);

        $strsql="select district_user.longitude, district_user.latitude,  user.name,user.sex,user.icon from user ,district_user
 where district_user.user_id = user.id and district_user.district_id='$district_id'";

        $db->query("SET NAMES utf8");
        $rs = $db->prepare($strsql);
        $rs->execute();

        $num = $rs->rowCount();

        $totalArray = array();
        if($num == 0){
            $totalArray['error'] = '1';
            $totalArray['errmsg'] = '无数据';
        }else{
            $i=0;
            while($row = $rs->fetch()){
                $totalArray['around'][$i] =array ('lon'=>$row['longitude'],'lat'=>$row['latitude']
                ,'name'=>urlencode($row['name']),'sex'=>urlencode($row['sex']),'icon'=>$row['icon']);
                $i++;
            }
            $totalArray['error'] = '0';
            $totalArray['errmsg'] = '0';
        }

        return $totalArray;
    }
}

?>