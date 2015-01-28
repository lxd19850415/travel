<?php
    //url形式 index.php?controller=控制器名&method=方法名

    /*
     * 实现的功能：1 http://localhost/travel/index.php?controller=map&method=show&l=10&r=10&w=40&h=60
     * 2 http://localhost/travel/index.php?controller=arounduser&method=show&district_id=1
    */
    require_once('function.php');

    $controllerAllow = array('map','index','arounduser');
    $methodAllow = array('show','index');

    $controller = in_array($_GET['controller'],$controllerAllow)?daddslashes($_GET['controller']):'index';
    $method = in_array($_GET['method'],$methodAllow)?daddslashes($_GET['method']):'index';

    C($controller,$method);
?>  


