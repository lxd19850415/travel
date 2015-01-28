<?php
    class aroundUserController{
        function show(){

            $mapModel = M('aroundUser');
            $data = $mapModel->get();

            $mapView = V('aroundUser');

            $mapView->display($data);
        }
    }

?>