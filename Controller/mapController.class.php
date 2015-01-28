<?php
    class mapController{
        function show(){

            $mapModel = M('map');
            $data = $mapModel->get();

            $mapView = V('map');

            $mapView->display($data);
        }
    }

?>