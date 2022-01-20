<?php
session_start();
header("Accept-CH: DPR, Width, Viewport-Width, RTT, ECT, Downlink");
header("Accept-CH-Lifetime: 86400");
/*
https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/client-hints
Implementar Posteriormente los Client Hints
*/
include "clases/configuracion.php";
include "clases/Mobile_Detect.php";
include "clases/log.php";
include "clases/resolver.php";
include "clases/condiciones.php";
include "clases/plantilla.php";
include "clases/sesiones.php";
include "clases/os_detector.php";


$configuracion=new Configuracion();
$sesiones=new Sesiones();
$dispositivo=new Mobile_Detect();
$os_detector=new OS_Detector();
$resolver=new Resolver();
$condiciones=new Condiciones();
$plantilla=new Plantilla();

$log=new Log();
function resolver(){
	global $configuracion, $resolver, $plantilla, $log;
	//echo "resolviendo ".__FILE__;

}

