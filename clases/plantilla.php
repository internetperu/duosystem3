<?php
class Plantilla{
	/**
	SI $resolver->estado==0; mostrar Pagina tipo Error 505
	Si $resolver->estado==1 y 
	*/
	public $plantilla_entorno=NULL;
	public $TEMPLATE=NULL;
	public $http_codes=array();
	public function __construct(){
		$this->procesar();
	}
	public function procesar(){
		global $resolver, $configuracion,$condiciones;
		/*echo "<pre>";
		print_r($resolver);
		print_r($condiciones);
		print_r($configuracion);
		echo "</pre>";*/
		if(!empty($condiciones->resultado["entorno"])){ 
			$this->set_Plantilla_entorno($condiciones->resultado["entorno"]);  
		}
		else{ 
			//$resolver->resultado["tipo_disp"]
			$this->set_Plantilla_entorno($configuracion->plantilla["dispositivo"][$resolver->resultado["tipo_disp"]]["entorno"]);  
			//$this->set_Plantilla_entorno($configuracion->plantilla["entorno"]);  
		}
		if(!empty($condiciones->resultado["template_main"])){ 
			$this->set_Plantilla($this->directorio_plantilla().$condiciones->resultado["template_main"]); 
		}
		else{ 
			$this->set_Plantilla($this->directorio_plantilla().$configuracion->plantilla["dispositivo"][$resolver->resultado["tipo_disp"]]["template_main"]); 
			//$this->set_Plantilla($this->directorio_plantilla().$configuracion->plantilla["template_main"]); 
		} 
		$this->http_codes["404"]=$this->directorio_plantilla().$configuracion->plantilla["http_codes"]["404"];
		$this->http_codes["500"]=$this->directorio_plantilla().$configuracion->plantilla["http_codes"]["500"];
		//echo "<pre>";
		//print_r($this);
		//echo "</pre>";
		//echo "<pre>";
		//print_r($configuracion);
		//print_r($_SERVER);
		//echo "</pre>";

		$this->reemplazar_valores_plantilla();
	}
	/* 
	$quoted = sprintf('"%s"', addcslashes(basename($file), '"\\'));
$size   = filesize($file);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $quoted); 
header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $size);
	*/
	public function directorio_plantilla(){
        global $configuracion, $condiciones,$resolver;
        $separador=DIRECTORY_SEPARATOR;
        $entorno="";
           if(!empty($this->plantilla_entorno)){
                $entorno=$this->plantilla_entorno.$separador;
            }
        //$configuracion->plantilla["dispositivo"][$resolver->resultado["tipo_disp"]]["plantilla"]      
        $plantilla=dirname(dirname(__FILE__)).$separador.$configuracion->plantilla["contenedor"].$separador.$configuracion->plantilla["dispositivo"][$resolver->resultado["tipo_disp"]]["plantilla"].$separador.$entorno;
        //$plantilla=dirname(dirname(__FILE__)).$separador.$configuracion->plantilla["contenedor"].$separador.$configuracion->plantilla["plantilla"].$separador.$entorno;
        return $plantilla;
    }
    public function set_Plantilla_entorno($plantilla) { 
        $this->plantilla_entorno = $plantilla; 
    }
    public function get_Plantilla_entorno() { 
        return $this->plantilla_entorno; 
    }
	public function set_Plantilla_archivo($plantilla) { 
        $this->plantilla = $plantilla; 
    }
    public function get_Plantilla_archivo() { 
        return $this->plantilla; 
    }
    public function set_Plantilla($plantilla) { 
        $this->plantilla = $plantilla; 
    }
    public function get_Plantilla() { 
        return $this->plantilla; 
    }
    public function plantilla_title($cadena){
    	global $configuracion;
    	$pattern = "/<title>(.*)<\/title>/";
    	preg_match($pattern, $cadena, $matches);
    	//echo "aAAAAAAAAAAAAAAAAAAAAAA ".$configuracion->plantilla["titulo_pos"];
    	switch($configuracion->plantilla["titulo_pos"]){
    		case "1":
    			$tit=$configuracion->plantilla["titulo"].$configuracion->plantilla["titulo_sep"].$matches[1];
    		break;
  			case 2:
  				$tit=$matches[1].$configuracion->plantilla["titulo_sep"].$configuracion->plantilla["titulo"];
  			break;
  			case 3:
  				$tit=$matches[1];
  			break;  		
    	}
    	return $tit;
    }
	public function reemplazar_valores_plantilla(){
    	global $resolver, $configuracion,$condiciones;
    	$abrir_archivo_plantilla=NULL;
    	$TITULO=NULL;
    	$META=NULL;
    	$HEAD=NULL;
    	$CONTENIDO=NULL;
    	$SCRIPTS=NULL;
    	if(empty($configuracion->general["base"])){ $general_base="/";}else{$general_base=$configuracion->general["base"]."/";}
       	//$valor_this=$configuracion->general["base"].$configuracion->plantilla["contenedor"]."/".$configuracion->plantilla["plantilla"];
       	$valor_this=$general_base.$configuracion->plantilla["contenedor"]."/".$configuracion->plantilla["plantilla"];
       	$valor_this2=$configuracion->general["base_doc"];
       	$valor_base=$configuracion->general["base"];
       	$archivo=$condiciones->resultado["base_path"].$condiciones->resultado["archivo"];
       	if(!empty($this->plantilla)){ $abrir_archivo_plantilla.=$this->plantilla;
		    if(file_exists($abrir_archivo_plantilla)){	        }
		}
			/*echo "\n<br><br> Plantilla ".__LINE__." <br>\n";
			echo "EL ARCHIVO es ".$archivo."<br>\n";
		    echo "<pre>";
		    print_r($configuracion);
		    print_r($condiciones);
		    print_r($resolver);
		    echo "</pre>";
			*/
       		if($resolver->estado==1 && is_file($archivo)){ // poner is_file
		    		
		    	
		    	
		    	/* 	Si es HTML poner plantilla segun condiciones 
					si no es htm o html simplemente devolverlo tal como es, con el mime type
					si es html o cualquier extension, y tiene activo download, prepararlo para descarga
		    	*/
				//echo "<br> EL ARCH ".$archivo."<br>\n";
				$partes_ruta = pathinfo($archivo);
				if(isset($partes_ruta['extension'])){
					// Existe archivo y tiene extension
					$ext_archivo=strtolower($partes_ruta['extension']);
					//echo "<br> ext ".$ext_archivo."<br>\n";
					if($ext_archivo=="html" || $ext_archivo=="html"){
						//echo "<br>".__LINE__."<br>\n";
						if(!isset($condiciones->resultado["templateless"]) || $condiciones->resultado["templateless"]==0 ){
							// CON PLANTILLA
							//echo "<br>".__LINE__."Archivo ".$archivo."<br>\n";

							if(file_exists($archivo)){
			    				//$archivo_res=implode("", file($archivo));
			    				//echo "<br>".__LINE__."<br>\n";
			    				if($condiciones->resultado["download"]=="1"){
									header('Content-type: text/html');
									header('Content-Disposition: attachment; filename="' . $condiciones->resultado["archivo"] . '"');
									header('Content-Length: ' . filesize($archivo));

								}
			    				$archivo_res=file_get_contents($archivo);
			    				$archivo_res = str_replace("{(BASE)}",$valor_base,$archivo_res);
			    				$archivo_res = str_replace("{(THIS)}",$valor_this2,$archivo_res);
			    				$archivo_partes=explode($configuracion->general["sep"], $archivo_res);
			    				$num_partes=count($archivo_partes);
			    				//echo "<br> Tiene ".$num_partes."partes <br>\n";
			    				if($num_partes==5){
			    					$TITULO=$this->plantilla_title($archivo_partes[0]);
			    					$META=$archivo_partes[1];
			    					$HEAD=$archivo_partes[2];

			    					$CONTENIDO=$archivo_partes[3];
			    					$SCRIPTS=$archivo_partes[4];
			    				}else{
			    					$TITULO=$configuracion->plantilla["titulo"];
			    					$CONTENIDO=$archivo_res;
			    				}
			    			}else{
			    				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
			    				//echo "<br>".__LINE__."<br>\n";
			    				//print_r(apache_get_modules() );
			    				$TITULO=$configuracion->plantilla["titulo"];
			    				$CONTENIDO=file_get_contents($this->http_codes["404"]);
			    				$CONTENIDO = str_replace("{(BASE)}",$valor_base,$CONTENIDO);
			    				$CONTENIDO = str_replace("{(THIS)}",$valor_this2,$CONTENIDO);
			    				//error 404 No encontrado.
			    			}
							//echo "<br>".__LINE__."<br>\n";
							$TEMPLATE2=implode("", file($abrir_archivo_plantilla));
			    			$TEMPLATE2 = str_replace("{(THIS)}",$valor_this,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("{(BASE)}",$valor_base,$TEMPLATE2); 


			    			$TEMPLATE2 = str_replace("%%TITULO%%",$TITULO,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("%%META%%",$META,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("%%HEAD%%",$HEAD,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("%%CONTENIDO%%",$CONTENIDO,$TEMPLATE2);  
			    			$TEMPLATE2 = str_replace("%%SCRIPTS%%",$SCRIPTS,$TEMPLATE2);  
							$this->TEMPLATE=$TEMPLATE2;
						}else{
							// SIN PLANTILLA
							if(file_exists($archivo)){
								if($condiciones->resultado["download"]=="1"){
									header('Content-type: text/html');
									header('Content-Disposition: attachment; filename="' . $condiciones->resultado["archivo"] . '"');
									header('Content-Length: ' . filesize($archivo));

								}else{
									$TEMPLATE2=file_get_contents($archivo);	
								}
								
							}
							else{
								header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
			    				//echo "<br>".__LINE__."<br>\n";
			    				//print_r(apache_get_modules() );
			    				$TITULO=$configuracion->plantilla["titulo"];
			    				$CONTENIDO=file_get_contents($this->http_codes["404"]);
							}
						}

					}else{
						/*echo "<pre>";
						print_r($condiciones);
						echo "<pre>";
						echo "AQUI ".$archivo; */
						
						//header("Content-Description: File Transfer"); 
						/*header('Content-Type: application/octet-stream');
						header("Content-Transfer-Encoding: Binary"); 
						header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); */
						if(file_exists($archivo)){
							if($ext_archivo=="pdf"){
								header('Content-type: application/pdf');
								if($condiciones->resultado["download"]=="1"){
									header('Content-Disposition: attachment; filename="' . $condiciones->resultado["archivo"] . '"');
								}else{
									header('Content-Disposition: inline; filename="' . $condiciones->resultado["archivo"] . '"');	
								}
								
								header('Content-Transfer-Encoding: binary');
								header('Content-Length: ' . filesize($archivo));
								header('Accept-Ranges: bytes');
								@readfile($aechivo);

							}else{
								header('Content-Type: '.mime_content_type($archivo));
								if($condiciones->resultado["download"]=="1"){
									header('Content-Disposition: attachment; filename="' . $condiciones->resultado["archivo"] . '"');
									header("Content-Transfer-Encoding: Binary");
								}
								header('Content-Length: ' . filesize($archivo));
								@readfile($archivo);
								
							}
							//PONER Header del mime type y transferir archivo
							
							////
							/* 
							PARA PDF
							$file = 'dummy.pdf';
							$filename = 'dummy.pdf';
							header('Content-type: application/pdf');
							header('Content-Disposition: inline; filename="' . $filename . '"');
							header('Content-Transfer-Encoding: binary');
							header('Content-Length: ' . filesize($file));
							header('Accept-Ranges: bytes');
							@readfile($file);
							*/
						}else{
							//
							header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
			    			//echo "<br>".__LINE__."<br>\n";
			    			//print_r(apache_get_modules() );
			    			$TITULO=$configuracion->plantilla["titulo"];
			    			$CONTENIDO=file_get_contents($this->http_codes["404"]);
			    			$CONTENIDO = str_replace("{(BASE)}",$valor_base,$CONTENIDO);
			    			$CONTENIDO = str_replace("{(THIS)}",$valor_this2,$CONTENIDO);
			    			$TEMPLATE2=implode("", file($abrir_archivo_plantilla));
			    			$TEMPLATE2 = str_replace("{(THIS)}",$valor_this,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("{(BASE)}",$valor_base,$TEMPLATE2);
			    			$TEMPLATE2 = str_replace("%%TITULO%%",$TITULO,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("%%META%%",$META,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("%%HEAD%%",$HEAD,$TEMPLATE2); 
			    			$TEMPLATE2 = str_replace("%%CONTENIDO%%",$CONTENIDO,$TEMPLATE2);  
			    			$TEMPLATE2 = str_replace("%%SCRIPTS%%",$SCRIPTS,$TEMPLATE2);  
							$this->TEMPLATE=$TEMPLATE2;

						}

					}
				}else{
					// si existe y no tiene extension

				}
					
			}
			elseif ($resolver->estado==1 && !is_file($archivo)) {
				// 
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
			    //echo "<br>".__LINE__."<br>\n";
			    //print_r(apache_get_modules() );
			    $TITULO=$configuracion->plantilla["titulo"];
			    $CONTENIDO=file_get_contents($this->http_codes["404"]);
			    $CONTENIDO = str_replace("{(BASE)}",$valor_base,$CONTENIDO);
			    $CONTENIDO = str_replace("{(THIS)}",$valor_this2,$CONTENIDO);
			    $TEMPLATE2=implode("", file($abrir_archivo_plantilla));
			    $TEMPLATE2 = str_replace("{(THIS)}",$valor_this,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("{(BASE)}",$valor_base,$TEMPLATE2);
			    $TEMPLATE2 = str_replace("%%TITULO%%",$TITULO,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("%%META%%",$META,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("%%HEAD%%",$HEAD,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("%%CONTENIDO%%",$CONTENIDO,$TEMPLATE2);  
			    $TEMPLATE2 = str_replace("%%SCRIPTS%%",$SCRIPTS,$TEMPLATE2);  
				$this->TEMPLATE=$TEMPLATE2;
				
			}
			else{
				//ERROR 500
				header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error"); 
		    	//echo "<br>".__LINE__."<br>\n";
		    	//print_r(apache_get_modules() );
		    	$TITULO=$configuracion->plantilla["titulo"];
		    	$CONTENIDO=file_get_contents($this->http_codes["500"]);
		    	$CONTENIDO = str_replace("{(BASE)}",$valor_base,$CONTENIDO);
		    	$CONTENIDO = str_replace("{(THIS)}",$valor_this2,$CONTENIDO);
		    	$TEMPLATE2=implode("", file($abrir_archivo_plantilla));
			    $TEMPLATE2 = str_replace("{(THIS)}",$valor_this,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("{(BASE)}",$valor_base,$TEMPLATE2);
			    $TEMPLATE2 = str_replace("%%TITULO%%",$TITULO,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("%%META%%",$META,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("%%HEAD%%",$HEAD,$TEMPLATE2); 
			    $TEMPLATE2 = str_replace("%%CONTENIDO%%",$CONTENIDO,$TEMPLATE2);  
			    $TEMPLATE2 = str_replace("%%SCRIPTS%%",$SCRIPTS,$TEMPLATE2);  
				$this->TEMPLATE=$TEMPLATE2;
			}

    	

    	

    	//echo "Plantilla ".$abrir_archivo_plantilla."<br>\n";
    	//echo "Archivo ".$archivo."<br>\n";
			/*echo "<pre>";
			print_r($condiciones);
			print_r($resolver);
			echo "</pre>"; */
    	echo $this->TEMPLATE;
    	
	}

	public function mostrar_resultado_plantilla(){
	   // global $TEMPLATE2;
	    echo $this->TEMPLATE;
	}

}