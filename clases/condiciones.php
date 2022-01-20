<?php
Class Condiciones{
	public $estado=0;
	public $resultado=array(
		"xml"=>"",
		"archivo"=>"",
		"template"=>"",
		"entorno"=>"",
		"template_main"=>"",
		"base_path"=>"",
		"base_path2"=>"",
		"download"=>"");
	public function __construct(){
		global $resolver;
		/*echo "<pre>";
		print_r($resolver);
		echo "</pre>";*/
		//print_r($_SERVER);

		if($resolver->estado){
			// Solo si $resolver->estado es 1, continuar,
			/**
			1. Verificar que el camino a consultar sea 
				-un archivo
					Si existe, agregar a la cadena de camino el .xml y verificar si hay condiciones
					si no existe el .xml se procede a mostrar el archivo (parse) segun mime type
					si no existe devolver 0, el gestor de plantilla  llamara a  plantilla y cargar plantilla de error
				-un directorio
				agregar a la cadena index.html index.html indice.html indice.html default.html default.htm
					segun cada caso se procede al paso de archivo (paso anterior)


			*/
			//$this->verificar_camino_xml();
			$this->procesar();
		}

	}
	
	public function procesar(){
		global $resolver,$configuracion;
		//echo mime_content_type($resolver->camino2) . "\n";
		
		/*echo "CAMINO OBJETIVO ".$resolver->camino2."<br>";
		echo "Linea :".__LINE__."<br>\n";
		$num=(count($resolver->partes) -1 );
		$this->resultado["archivo"]=$resolver->partes[$num];
		echo "<pre>"; */
		//echo print_r($resolver);
		//echo print_r($this);
		/*echo "III resolver camino2 : ".$resolver->camino2."<br>\n";
		echo "<pre>";
		echo "Linea :".__LINE__."<br>\n";*/
			if($this->verificar_archivo($resolver->camino2) && !is_dir($resolver->camino2)){
				//echo "<strong>SI EXISTE ARCHIVO</strong><br>";
				//echo "Linea :".__LINE__."<br>\n";
				$this->obtener_base_path($resolver->camino2);
				/*echo "<pre>";
				print_r($resolver);
				echo "</pre>";*/
				$num=(count($resolver->partes) -1 );
				//$this->resultado["archivo"]="aaa".$resolver->camino2;
				$this->resultado["archivo"]=$resolver->partes[$num];
				if($this->verificar_xml_proceso($this->resultado["archivo"])){
					//echo "SI Existe el archivo Objetivo, y si sus condiciones <br>\n";
					//echo "Linea :".__LINE__."<br>\n";
					$this->procesar_condiciones_xml();

				}
				

			}else if($this->verificar_xml_proceso($this->resultado["archivo"]) && !is_dir($resolver->camino2)){
				//echo "Linea :".__LINE__."<br>\n";
				//echo "No Existe el archivo Objetivo, pero si sus condiciones <br>\n";
				$this->obtener_base_path($resolver->camino2);
				$this->procesar_condiciones_xml();
			}
			else{
				//echo "Linea :".__LINE__."<br>\n";
				//echo "Buscando Directorio ".$resolver->camino2."<br>";
				if(is_dir($resolver->camino2)){
					$this->obtener_base_path($resolver->camino2,2);
					$this->verificar_directorio($resolver->base_path2);	
				}
				

			}
		
		/*echo "<pre>";
		print_r($this->resultado);
		echo "<pre>";*/
	}
	//Procesar condiciones XML
	public function obtener_url_path($camino,$tipo=1){
		global $resolver,$configuracion;
		//echo "function obtener_url_path('".$camino."',".$tipo.".)</strong><br>\n";
		//echo "configuracion base ".$configuracion->general["base"]."<br>\n";
		if(mb_substr($camino, -1)==DIRECTORY_SEPARATOR){
			$camino=mb_substr($camino, 0, -1);
		}
		//echo "camino ".$camino."<br>\n";
		$cadena="";
		if(empty($configuracion->general["base"])){
			$n_base="";
		}else{
			$n_base=$configuracion->general["base"];
		}
		if($tipo==1){
			$cant=count($resolver->partes);
			//echo "Partes count es ".$cant."<br>\n";
			$i=1;
			$obj=$cant-1;
			foreach ($resolver->partes as $clave => $valor) {
				# code...
				if(!empty($resolver->partes[$clave]) && $i<$cant){
					//echo "PARTE ES ::".$valor."<br>\n";
					$cadena.="/".$valor;
				}
				$i++;
			}
			$cadena=$n_base.$cadena;
			//echo "THIS ".$cadena." <br>\n";
			
		}else{
			$cant=count($resolver->partes);
			//echo "Partes count es ".$cant."<br>\n";
			$i=1;
			$obj=$cant-1;
			foreach ($resolver->partes as $clave => $valor) {
				# code...
				if(!empty($resolver->partes[$clave])){
					//echo "PARTE ES ::".$valor."<br>\n";
					$cadena.="/".$valor;
				}
				$i++;
			}
			$cadena=$n_base.$cadena;
			//echo "THIS ".$cadena." <br>\n";

		}
		$configuracion->general["base_doc"]=$cadena;
	}
	public function obtener_base_path($camino,$tipo=1){
		global $resolver;
		//echo "function obtener_base_path('".$camino."',".$tipo.".)</strong><br>\n";
		$this->obtener_url_path($camino,$tipo);
		if(mb_substr($camino, -1)==DIRECTORY_SEPARATOR){
			$camino=mb_substr($camino, 0, -1);
		}
		//echo "camino ".$camino."<br>\n";

		if($tipo==1){
			$cant=count($resolver->partes);
			/*echo "Partes count es ".$cant."<br>\n";
			echo "camino ".$camino."<br>\n";
			echo "tipo ".$tipo."<br>\n";*/
			$obj=$cant-1;
			/*echo "<strong> CADENA A BUSCAR :".DIRECTORY_SEPARATOR.$resolver->partes[$obj]."<br>\n";
			echo "<strong> ($cant) -1  :".$obj."<br>\n";
			echo "<strong> CAMINO      :".$camino."<br>\n";*/
			$camino2=str_replace(DIRECTORY_SEPARATOR.$resolver->partes[$obj], "", $camino);
			$resolver->base_path2=$camino2;
			$resolver->base_path=$camino2.DIRECTORY_SEPARATOR;
			$this->resultado["base_path2"]=$camino2;
			$this->resultado["base_path"]=$camino2.DIRECTORY_SEPARATOR;

		}else{
			$resolver->base_path2=$camino;
			$resolver->base_path=$camino.DIRECTORY_SEPARATOR;
			$this->resultado["base_path2"]=$camino;
			$this->resultado["base_path"]=$camino.DIRECTORY_SEPARATOR;
		}
		
		//echo "CAMINO BASE PATH ".$camino2."<br>";
		/*echo "<strong>INICIO</strong><BR>\n";
		echo "<pre>";
		print_r($resolver);
		print_r($this);
		echo "</pre>";
		echo "<strong>--- FIN ---</strong><BR>\n";
		*/

	}
	public function procesar_condiciones_xml(){
		global $resolver;
		/**
		1. Abrir XML (en el paso anterior se verifico que exista el archivo)
		2. Hacer un Bucle sobre las condiciones, si se cumple alguna condicion romper el bucle
		3. Si no se cumple ninguna condicion, buscar el parametro <default></default>
		4. Todo se pasa al motor de plantillas.
		5. El motor de plantillas se encargara de validar la existencia de los archivos, o tmb se podra hacer aca.. esta en desarrollo
		Maldita cuarentena
		*/
		$resultado=0;
		//echo "Cargabndo";
		$xml=simplexml_load_file($this->resultado["xml"]); // or die("Error: Cannot create object");
		$condicion_tag=$xml->xpath("/condiciones/condicion");
		//	echo "<pre>";
		//	print_r($condicion_tag);
		//	echo "</pre>";
		//	echo "<strong>FIN</strong><br>";
		//foreach($xml->condicion->children() as $condiciones) {
		//foreach($xml->children() as $condiciones) {	
		$c1=0;$c2=0;$c3=0;$c4=0;		
		foreach($condicion_tag as $condiciones) {	
		$c1=0;$c2=0;$c3=0;$c4=0;		
			//echo "<strong>FOREACH 132</strong><pre>";
			/*print_r($condiciones);
			echo "</pre>"; */
			/*echo "<br><strong>INICIO BUCLE </strong><br>\ndevice : ".$condiciones->device."<br>";
			echo "dispositivo : ".$condiciones->dispositivo."<br>";
			echo "OS : ".$condiciones->OS."<br>";
			echo "---------<br>\n";
			*/
			if(is_numeric($condiciones->device->__toString()) && isset($condiciones->device)){ 
				 //echo "devicexxx : ".$condiciones->device."<br>";  
				if($resolver->resultado["tipo_disp"]==$condiciones->device){
					$c2=1;
				}
			}else{
				//echo "<br> NO SE CUMPLIO DEVICE ".$condiciones->device." ISSET ".isset($condiciones->device)."<br>\n";
				$c2=1;
			}

			if(!empty($condiciones->OS) && isset($condiciones->OS)){ 
				 //echo "Os: ".$resolver->resultado["OS"]." | OS Condicion : ".$condiciones->OS."<br>"; 
				if($resolver->resultado["OS"]==$condiciones->OS->__toString()){
					$c1=1;
				}
			}else{$c1=1;}
			//echo "C1 :".$c1."<br>\n";
			//echo "-----------<br><br>\n";
			//echo "device : ".$condiciones->device."<br>";  
			
			//if(!empty($condiciones->OS2) && isset($condiciones->OS2)){echo "OS2 : ".$condiciones->OS2."<br>";}
			/*echo "<strong>Foreach CONDICIONES </strong>";
			echo "<pre>";
			print_r($condiciones);
			echo "</pre>"; */
		    /*echo $books->title . ", ";
		    echo $books->author . ", ";
		    echo $books->year . ", ";
		    echo $books->price . "<br>"; */
		   // echo "<br>c1 :".$c1."| c2: ".$c2."<br>\n";
		    if($c1 && $c2){
		    	//echo "<br><br>EEEEEEEEEEEEEEE<br>\n";
		    	if(!empty($condiciones->template)){ /*echo "template : ".$condiciones->template."<br>";*/ $this->resultado["template"]=$condiciones->template->__toString();}
		    	if(!empty($condiciones->entorno)){ $this->resultado["entorno"]=$condiciones->entorno->__toString();}
		    	if(!empty($condiciones->templateless)){ $this->resultado["templateless"]=$condiciones->templateless->__toString();}
		    	if(!empty($condiciones->download)){ $this->resultado["download"]=$condiciones->download->__toString();}
				if(!empty($condiciones->template_main)){ /*echo "template_main : ".$condiciones->template_main."<br>";*/ $this->resultado["template_main"]=$condiciones->template_main->__toString();}
				if(!empty($condiciones->cargar)){ /*echo "cargar : ".$condiciones->cargar."<br>"; */$this->resultado["archivo"]=$condiciones->cargar->__toString();}
				$resultado=1;
				break;
		    }
		}
		//echo "resultado es ".$resultado;
		if($resultado==0){
			$condicion_tag=$xml->xpath("/condiciones/default");
				foreach($condicion_tag as $default) {
					if(!empty($default->template)){ /*echo "template : ".$default->template."<br>"; */ $this->resultado["template"]=$default->template->__toString();}
					if(!empty($default->entorno)){ $this->resultado["entorno"]=$default->entorno->__toString();}
					if(!empty($default->templateless)){ $this->resultado["templateless"]=$default->templateless->__toString();}
		    		if(!empty($default->download)){ $this->resultado["download"]=$default->download->__toString();}
					if(!empty($default->template_main)){ 
						//echo "template_main : ".$default->template_main."<br>"; 
						$this->resultado["template_main"]=$default->template_main->__toString();
					}
					if(!empty($default->cargar)){ /*echo "cargar : ".$default->cargar."<br>"; */ $this->resultado["archivo"]=$default->cargar->__toString(); $resultado=1;}
				}
		}
		return $resultado;
	}
	// Verifica el archivo
	// Si existe archivo Guarda en variable de clase y pone resultado a 1
	//  si eciste XML se procesa las condiciones
	// se devuelve resultado (1 si existe o 0 si no existe)
	public function verificador_archivo($archivo){
		if($this->verificar_archivo($archivo)){
			$this->resultado["archivo"]=$archivo;
			$resultado=1;
				if($this->verificar_xml_proceso($this->resultado["archivo"])){
					$this->procesar_condiciones();
				}
		}else{
			$resultado=0;

		}
	}

	// segun la cadena archivo, se verifica que exista el XML, si existe se procesa las condiciones
	// si se procesa correctamente las condiciones se devuelve 1

	public function verificar_xml_proceso($archivo){
		global $resolver,$configuracion;
		$resultado=0;
		//echo "Llamado function verificar_xml_proceso(".$archivo.")<br>\n";
		//echo "";
		//$this->resultado["xml"]=$resolver->camino2.".xml";
		$this->resultado["xml"]=$archivo.".xml";
		//echo "verificar XML ".$this->resultado["xml"]."<br>\n";
		if(mb_substr($this->resultado["xml"], -5)!=DIRECTORY_SEPARATOR.".xml"){
			//echo "Parte final no es /.xml o \\.xml <br>\n";
			if($this->verificar_archivo_xml($this->resultado["xml"]) && $this->procesar_condiciones()){
			$resultado=1;	
			}
		}else{
			//echo "Parte final SI es /.xml o \\.xml <br>\n";

		}
		
		return $resultado;

	}
	// Procesador de condiciones XML
	public function procesar_condiciones(){
		//echo "<br> procesando Condiciones <br>\n";
		$resultado=1;
		return $resultado;
		// Verificar que exista archivo
		// Cargar XML
		// Procesar condiciones
	}


	public function verificar_archivo($archivo){
		if(is_file($archivo)){
			$resultado=1;
		}else{
			$resultado=0;
			//echo "NO es archivo <br>\n";
		}
		//echo "Verificando que sea archivo devolvio  ".$resultado." para ".$archivo."<br>\n";
		return $resultado;
	}

	public function verificar_archivo_xml($archivo){
		if(is_file($archivo)){
			//echo "es archivo <br>\n";
			//if(is_file($archivo.".xml")){
			if(is_file($archivo)){
				//echo "existe archivo XML aa <br>\n";
				$resultado=1;
			}else{
				//echo "NO existe archivo XML aa<br>\n";
				$resultado=0;
			}
		}else{
			$resultado=0;
			//echo "NO es archivo <br>\n";
		}
		return $resultado;

	}
	public function verificar_directorio($archivo){
		global $configuracion,$resolver;
		if(is_dir($archivo)){
			//SI es DIRECTORIO
			//print_r($configuracion->general["index"]);
			$indices=$configuracion->general["index"];
			foreach ($configuracion->general["index"] as $clave => $valor) {
				# code...
				$nuevo_camino=$archivo.DIRECTORY_SEPARATOR.$configuracion->general["index"][$clave];
				//echo "<br> directorio, verificando <strong>".$nuevo_camino."</strong><br>\n";
				if($this->verificar_archivo($nuevo_camino)){
					//$this->resultado["archivo"]=$configuracion->general["index"][$clave];
					//echo "se econtro un indice \n";
					//Poner el indice como archivo, en caso no se encuentre condiciones en XML
					$this->resultado["archivo"]=$configuracion->general["index"][$clave];
					//$this->verificar_xml_proceso($nuevo_camino);

					if($this->verificar_xml_proceso($nuevo_camino)){
					//echo "SI Existe el archivo Objetivo, y si sus condiciones <br>\n";
					//echo "Linea :".__LINE__."<br>\n";
					$this->procesar_condiciones_xml();
					break;
					}
					
					// Si se encontro un indice, se procesa y se para el bucle
					break;	
				}else{
					//echo "NO EXISTE ARCHIVO , verificar si existe  CONDICIONES xml<br>\n";

					if($this->verificar_xml_proceso($nuevo_camino)){
					//echo "SI Existe el archivo Objetivo, y si sus condiciones <br>\n";
					//echo "Linea :".__LINE__."<br>\n";
					$this->procesar_condiciones_xml();
					break;
					}


				}
				
			}
			
		}

	}

	/*public function verificar_camino_xml(){
		global $resolver,$configuracion;
		echo " <br> <strong>CONDICIONES</strong><br>";
		
		echo "<pre>";
		
		if(is_file($resolver->camino2)){
			echo "es archivo <br>\n";
			if(is_file($resolver->camino2.".xml")){
				echo "existe archivo XML <br>\n";
			}else{
				echo "NO existe archivo XML <br>\n";
			}
		}else{
			echo "NO es archivo <br>\n";
		}
		

		echo "</pre>";

	}
	*/
	
}
Class Router extends Condiciones{
}