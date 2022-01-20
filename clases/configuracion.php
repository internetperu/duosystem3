<?php
//009-69121691025587871
//http://www.themezaa.com/html/pofo/home-classic-corporate.html
//http://avehtml.liquid-themes.com/index-digital-agency.html
Class Configuracion{

	public $general=array(
		"base_de_datos"=>0,
		"dir_data"=>"w", // directorio desde donde se tomara los archivos en este caso w
		//"dir_mobile"=>"mobile", // si es celular se tomara w/mobile
		//"dir_tablet"=>"tablet",	// si es tablet se tomarta w/tablet
		//"dir_desktop"=>"desktop",	// si es desktop se tomara w/desktop

		"dir_mobile"=>"generic", // si es celular se tomara w/mobile
		"dir_tablet"=>"generic",	// si es tablet se tomarta w/tablet
		"dir_desktop"=>"generic",	// si es desktop se tomara w/desktop

		"engine"=>"indice.php", // archivo del motor principal
		"base"=>"", // raiz del script ejemplo: /s2/ si se esta ejecutando desde /s2 , si es desde el directorio raiz se devolvera vacio
		"base_doc"=>"", // raiz del directorio ejemplo /dir/eee  si se llamo a /dir/eee/aaa.html
		"base_size"=>0,
		"xml_ext"=>"xml",
		"xml_hidden"=>0,
		"template_mime_type"=>"text/html",
		"sep"=>"<!-- DS.SEP-CO001 -->", // SEPARADOR A USAR dentro de los HTML si no se usa no se pasaran los valores a title, meta, head, ni scripts
		"ext"=>array("html","htm"), // extensiones para las cuales procesar con template, en caso de no tener condiciones EXPERIMENTAL
		"index"=>array("index.html","index.html","indice.html","indice.html","default.html","default.htm","Default.html","Default.html","home.html"),
		"tipo"=>1 // Tipo de chequeo principal directorio, 1, movil, 2 tablet, 3 desktop EXPERIMENTAL

		);
	/* explicacion de titulo_pos
	EXPERIMENTAL 
	si se llama a un archivo ejemplo.html y este tiene un <title>HOLA</title>
	si titulo pos es 1
	se generara <title>Duosystem - HOLA</title>
	el separador tambien se personaliza
	si es 2
	se generara <title>HOLA - Duosystem</title>
	si es 0
	se generara <title>HOLA</title>

	si el archivo llamado no tiene title o esta en blanco, se usara el del template
	se generara <title>Duosystem</title>

	TIPOS DE DISPOSITIVO
		0: PC de Escritorio
		1: Celular
		2: Tablet
		***
		tipos propuestos
		***
		3: Smartwatch
		4: SmartTV (webTV)
		5: Consola de Juego (Nintendo, Ps, Xbox, Atari, Etc)
	*/
	public $plantilla=array(
		"contenedor"=>"themes",
		"plantilla"=>"general",
		"entorno"=>"frontend", // entorno del template ejemplo /themes/general/frontend
		"template_main"=>"template_main.html",
		"titulo"=>"Duosystem",
		"titulo_pos"=>1, // 1 Al inicio ,2 al final, 0 usar titulo enviado por archivo, si no tiene se usa el titulo anterior
		"titulo_sep"=>" - ", // Separador de titulo
		"plantilla_mobile"=>"general",  // Experimental
		"plantilla_tablet"=>"general",  // Experimental
		"plantilla_desktop"=>"general", // Experimental
		"plantilla_sistema"=>"general", // Experimental
		"dispositivo"=>array(
			0=>array(
				"plantilla"=>"general",
				"entorno"=>"frontend",
				"template_main"=>"template_main.html"
				),
			1=>array(
				"plantilla"=>"general",
				"entorno"=>"frontend",
				"template_main"=>"template_main.html"
				),
			2=>array(
				"plantilla"=>"general",
				"entorno"=>"frontend",
				"template_main"=>"template_main.html"
				)

			),
		"http_codes"=>array(
			"404"=>"404.html",
			"500"=>"500.html",
			"505"=>"500.html",
			)
		);
	public function __construct(){
		$this->base_url();
	}
	public function base_url(){
		$base=$_SERVER["SCRIPT_NAME"];
		//$cant=strlen($this->general["engine"]);
		$size_path=strlen($base) - strlen($this->general["engine"]);
		//echo "OTRO ES ".$size_path."<br>\n";
		$this->general["base"] = substr($base, 0, $size_path); 
		if(strlen($this->general["base"]) && $this->general["base"]=="/"){
			$this->general["base"]="";
		} 

		if(mb_substr($this->general["base"], -1)=="/"){
			$this->general["base"]=mb_substr($this->general["base"], 0, -1);
		}

		$this->general["base_size"]=strlen($this->general["base"]);
		//echo $this->general["base"];
		//echo "<br><br>\n";
		
	}
}