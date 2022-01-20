<?php
class Resolver{
	public $partes=NULL;
	public $estado=0; // 0 Error de URL mostrar Error tipo BAD Request, 1 OK, COntinuar
	public $resultado=array(
		"OS"=>"",
		"tipo_mt"=>NULL);
	public $url="";
	public $camino=NULL;
	public $camino2=NULL;
	public $base_path=NULL;
	public $base_path2=NULL;
	public $base_path3=NULL;
	public function __construct(){

		$this->obtener_url();
		$this->obtener_base_url();
		$this->resolver_dispositivo();
		$this->resolver_contenido();
		//echo "Resultados<br>\n";
		//echo "<pre>";
		//print_r($this->resultado);
		//print_r($_SERVER);
		//echo "<pre>";

	}
	//$rest = substr("abcdef", -3, 1); // devuelve "d"
	public function obtener_url(){
		global $configuracion;
		$subpartes=explode("?", $_SERVER["REQUEST_URI"]);
		if(mb_substr($subpartes[0], -1)=="/"){
			$subpartes[0]=mb_substr($subpartes[0], 0, -1);
		}
		/*echo "<br>SUBPARTES<br>\n";
		echo "<pre>";
		print_r($subpartes);
		echo "</pre>";
		echo "base sixe ".$configuracion->general["base_size"];
		echo "<br>FIN SUBPARTES<br>\n"; */
		//chequea si ultimo caracter es / , no se chequea \ por que es la URL recibida
		$general_base_size=0;
		if($configuracion->general["base_size"]==0){
			$general_base_size=1;
		}else{
			$general_base_size=$configuracion->general["base_size"];
		}
		$this->url=mb_substr($_SERVER["REQUEST_URI"],$general_base_size);
		$this->url=mb_substr($subpartes[0],$configuracion->general["base_size"]);
		//echo "EL URL a parsear es ".$this->url;
		$this->partes=explode("/",$this->url);
		//echo "<br>\n";
		//$p=explode(delimiter, string)
		//echo "<br>\n";
		//print_r($this->partes);

	}
	public function obtener_base_url(){
		
	
		
	}
	public function resolver_dispositivo(){
		global $dispositivo,$os_detector;
		//echo "resolviendo Dispositivo <br>\n";
		if(!isset($_SESSION["detectado"])){
			//echo "Detectando <br>\n";
			$this->resultado["tipo_mt"]=9;
			if($dispositivo->isMobile()){
				// ES UN CELULAR o TABLET, Its a Mobile or Tablet
				$this->resultado["tipo"]=1;

				if(!$dispositivo->isTablet()){
					// Si es Celular , If uts Mobile
					$this->resultado["tipo_mt"]=1;
					$this->resultado["tipo_disp"]=1;
				}else{
					//si es tablet, If its tablet
					$this->resultado["tipo_disp"]=2;
					$this->resultado["tipo_mt"]=0;
				}
			}else{
				//ES UNA PC , Its a Desktop PC
				$this->resultado["tipo"]=0;
				$this->resultado["tipo_disp"]=0;
			}

			/*
			if($dispositivo->isiOS() ){
				$this->resultado["OS"]="iOS";
			}
			if($dispositivo->isAndroidOS() ){
				$this->resultado["OS"]="android";
			}*/
			$this->resultado["OS"]=$os_detector->obtener();

			$this->resultado["UA"]=$_SERVER["HTTP_USER_AGENT"];
			//$_SESSION["detectado"]=1;
			$_SESSION["detectado_UA"]=$this->resultado["UA"];
			$_SESSION["detectado_OS"]=$this->resultado["OS"];
			$_SESSION["detectado_tipo"]=$this->resultado["tipo"];
			$_SESSION["detectado_tipo_disp"]=$this->resultado["tipo_disp"];
			$_SESSION["detectado_tipo_mt"]=$this->resultado["tipo_mt"];
		}else{
			/*$this->resultado["tipo_disp"]=$_SESSION["detectado_tipo_disp"];
			$this->resultado["tipo"]=$_SESSION["detectado_tipo"];
			$this->resultado["tipo_mt"]=$_SESSION["detectado_tipo_mt"];
			$this->resultado["OS"]=$_SESSION["detectado_OS"];
			$this->resultado["UA"]=$_SESSION["detectado_UA"];*/
			//echo "Ya detectado, no pulsear por las webas <br>\n";
		}
	}
	public function resolver_contenido(){
		global $configuracion;
		$i=0;
		$url_path=NULL;
		$verificado=1;
		//echo "VERIFICADO ES  ".$verificado."<br>";
		foreach ($this->partes as $clave => $valor) {
			if(!empty($this->partes[$clave])){
				if($i==0){
					$i=$i+1;
					$url_path.=urldecode($this->partes[$clave]);
				}else{
					$url_path.=DIRECTORY_SEPARATOR.urldecode($this->partes[$clave]);
				}
				if(!$this->verificar_cadena_archivo(urldecode($this->partes[$clave]))){$verificado=0;}
				
			}
			
			
		}
		$this->estado=$verificado;
		if($this->resultado["tipo"] && $this->resultado["tipo_mt"]==1){
			$camino=".".DIRECTORY_SEPARATOR.$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_mobile"].DIRECTORY_SEPARATOR.$url_path;
			$camino2=$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_mobile"].DIRECTORY_SEPARATOR.$url_path;
			$this->base_path3=$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_mobile"];
			//$this->resolver

		}
		if($this->resultado["tipo"] && $this->resultado["tipo_mt"]==0){
			$camino=".".DIRECTORY_SEPARATOR.$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_tablet"].DIRECTORY_SEPARATOR.$url_path;
			$camino2=$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_tablet"].DIRECTORY_SEPARATOR.$url_path;
			$this->base_path3=$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_tablet"];
		}
		if($this->resultado["tipo"]==0){
			$camino=".".DIRECTORY_SEPARATOR.$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_desktop"].DIRECTORY_SEPARATOR.$url_path;
			$camino2=$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_desktop"].DIRECTORY_SEPARATOR.$url_path;
			$this->base_path3=$configuracion->general["dir_data"].DIRECTORY_SEPARATOR.$configuracion->general["dir_desktop"];

		}

		$this->camino=$camino;
		$this->camino2=$camino2;
		//echo "VERIFICADO ES  ".$verificado."<br>";
		//echo "EL CAMINO ES ".$camino."<br>";
		//echo "EL CAMINO URL decode ES ".urldecode($camino)."<br>";
		//echo "EL URL:PATH ES ".$url_path."<br>";
		//echo "Verificador cadena ".$this->verificarcadena($camino)." <br>\n";
		//echo "Verificador cadena ".$this->verificarcadena("hola/w/we2.html")." <br>\n";
		//echo "<pre>";
		//preg_match('/(^\.|\s)$/', 'foobarbaz <>:"/\|?*.s ', $matches, PREG_OFFSET_CAPTURE);
		//print_r($matches);

		//preg_match('/(\.\.)(\.\.\\/)()/', 'foobarbaz <>:"/\|?*', $matches, PREG_OFFSET_CAPTURE);
		//print_r($matches);

		//print_r($_SERVER);
		//echo "<pre>";

	}
	public function verificar_cadena_archivo($cadena){
		if($this->verificarcadena_primer_filtro($cadena)){
			return 1;
		}else{
			return 0;
		}
	}
	public function verificarcadena_primer_filtro($cadena){
		//si es un archivo de 1 caracter ~
			//AUN NO IMPLEMENTADO
		// Si el archivo tiene 1 caracter y este es <>:"/\|?*. se rechaza
		// Si el archivo tiene 2 caracteres y contiene 2 puntos se rechaza
		// Si tiene mas de 3 caracteres se pasa al segundo filtro
		// Windows acepta archivoc tipo 1..3 hola...cuca asi que se permite
		// Si tiene mas de 3 caracteres t
		$tam=strlen($cadena);
		if( ($tam==1 && !preg_match("/^[<>:\"\/\\\\|?*\.]/", $cadena) )  || ($tam==2 && $cadena!="..") || ($tam>=3) ) {
			if($this->verificarcadena_segundo_filtro($cadena)){
				return 1;
			}else{
				return 0;
			}
		}
		/*if($tam==2 && $cadena!=".."){
			if($this->verificarcadena_segundo_filtro($cadena)){
				return 1;
			}else{
				return 0;
			}
		} */

	}
	public function verificarcadena_segundo_filtro($cadena){

		//^[\.]{0,1}?[\._~A-Za-z0-9-]{0,}[A-Za-z0-9]$
		//^[\.]{0,1}?[\._~%;&\sA-Za-z0-9-]{0,}[A-Za-z0-9~_%;&]$
		if(preg_match("/^[\.]{0,1}?[\._~%;&\sA-Za-z0-9-]{0,}[A-Za-z0-9~_%;&]$/", $cadena)){
			return 1;
		}
		else{ return 0;}
	}
	public function verificarcadena($string){
		//if (preg_match('/^[0-9a-zA-Z_\-\d_]{4,28}$/i', $string)) {	
		//if (preg_match('/^[0-9a-zA-Z_\-\d_]{1,2058}$/i', $string)) {
		if (preg_match('#^[0-9a-zA-Z_\-\d\.\\\_/]{1,2058}$#i', $string)) {
		return 1;
		}else{return 0;}
	}
}