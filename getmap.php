<?php
require("config.php");

//测试地址：http://localhost/travel/getmap.php?l=10&r=10&w=50&h=60
  $left=$_GET['l'];
  $right=$_GET['r'];
  $width=$_GET['w'];
  $height=$_GET['h'];

    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
    $db = new PDO($dsn, DB_USER, DB_PWD);

  $strsql="select * from district ";
  $db->query("SET NAMES utf8");
  $rs = $db->prepare($strsql);
  $rs->execute();

  $num = $rs->rowCount();

if($num == 0)
{
  echo '<p><h1>没有任何景区</h1></p>';
}
else{
  while($row = $rs->fetch()){

    if( rectInRect($left,$right,$width,$height
        ,$row['longitude'],$row['latitude'],$row['width'],$row['height'])) {//找到范围内的景区了
      $district_id = $row['id'];
      $totalArray = array();

      //查询地图文件
      $strsql1="SELECT * FROM map where district_id ='$district_id'";
      $db->query("SET NAMES utf8");
      $rs1 = $db->query($strsql1);
      $i=0;
      while($row1 = $rs1->fetch()){
        $totalArray['map'][$i] =array ('lon'=>$row1['longitude'],'lat'=>$row1['latitude']
        ,'url'=>stripslashes($row1['url']),'level'=>$row1['level']);
        $i++;
      }

      //查询POI
      $strsql2="SELECT * FROM poi where district_id ='$district_id'";
      $db->query("SET NAMES utf8");
      $rs2 = $db->query($strsql2);
      $i=0;
      while($row2 = $rs2->fetch()){
        $totalArray['poi'][$i] =array ('lon'=>$row2['longitude'],'lat'=>$row2['latitude']
        ,'name'=>urlencode($row2['name']),'addr'=>urlencode($row2['addr']),'tel'=>$row2['tel']
        ,'type'=>$row2['type']);
        $i++;
      }

//      echo stripslashes(json_encode($totalArray));
      echo urldecode (stripslashes(json_encode($totalArray)));
//      echo json_encode($totalArray);
    }//if
    else{
//      echo "无景点";
    }
  }//while

}


function xyInRect($x,$y,$rect_x,$rect_y,$rect_w,$rect_h)
{
  if(($x >= $rect_x && $x <=$rect_x +$rect_w) && ($y >= $rect_y && $y <=$rect_y +$rect_h) )
  {
    return true;
  }
  return false;
}
function rectInRect($rect1_x,$rect1_y,$rect1_w,$rect1_h,$rect2_x,$rect2_y,$rect2_w,$rect2_h)
{
  if(xyInRect($rect1_x,$rect1_y,$rect2_x,$rect2_y,$rect2_w,$rect2_h)
      ||xyInRect($rect1_x+$rect1_w,$rect1_y,$rect2_x,$rect2_y,$rect2_w,$rect2_h)
      ||xyInRect($rect1_x+$rect1_w,$rect1_y+$rect1_w,$rect2_x,$rect2_y,$rect2_w,$rect2_h)
      ||xyInRect($rect1_x,$rect1_y+$rect1_w,$rect2_x,$rect2_y,$rect2_w,$rect2_h) )
  {
    return true;
  }
  return false;
}
?>
