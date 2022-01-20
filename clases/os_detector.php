<?php

/**
Detector de Sistema Operativo
Author: Jack Rojas
Url: https://disenowebperu.co.pe
Version: 0.0.0.1 Alpha
Date: 2020-04-06
***/
Class OS_Detector{
	protected $userAgent = null;
	public $OS_res=NULL;
	function __construct(){
		$this->detect();
		/*echo "<pre>";
		print_r($_SERVER);
		echo "</pre>";
		*/
	}
	public $pruebas=array(
		"Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1",
		"Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko",
		"Mozilla/5.0 CK={} (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko",
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36",
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36",
		"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36",
		"Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_6; en-en) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/600.8.9 (KHTML, like Gecko)",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.7 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.7",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/605.1.15 (KHTML, like Gecko)",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1 Safari/605.1.15",
		"MacOutlook/16.19.0.181109 (Intelx64 Mac OS X Version 10.14 (Build 18A391))",
		"MacOutlook/0.0.0.150815 (Intel Mac OS X Version 10.10.3 (Build 14D136))",
		"",
		"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36",
		"Apache/2.4.34 (Ubuntu) OpenSSL/1.1.1 (internal dummy connection)",
		"Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:24.0) Gecko/20100101 Firefox/24.0",
		"Thunderstorm/1.0 (Linux)",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9a1) Gecko/20070308 Minefield/3.0a1",
		"Mozilla/5.0 (X11; Linux x86_64; rv:45.0) Gecko/20100101 Thunderbird/45.8.0",
		"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0",
		"Opera/9.80 (Linux armv7l) Presto/2.12.407 Version/12.51 , D50u-D1-UHD/V1.5.16-UHD (Vizio, D50u-D1, Wireless)",
		"Mozilla/5.0 (SMART-TV; Linux; Tizen 2.4.0) AppleWebkit/538.1 (KHTML, like Gecko) SamsungBrowser/1.1 TV Safari/538.1",
		"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/76.0.3803.0 Safari/537.36",
		"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.24 Safari/537.36",
		"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.34 (KHTML, like Gecko) Qt/4.8.2",
		"Mozilla/5.0 (Linux) Cobalt/11.119147-qa (unlike Gecko) Starboard/6, CVA_STB_BCM72604C0/KA99.00.17.09 (Arris, DCX4400, Wired)",
		"BrightSign/7.1.95 (XT1143) Mozilla/5.0 (Unknown; Linux arm) AppleWebKit/537.36 (KHTML, like Gecko) QtWebEngine/5.6.0 Chrome/45.0.2454.101 Safari/537.36",
		"",
		"Mozilla/5.0 (Linux; U; Android 2.2) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
		"Mozilla/5.0 (Linux; Android 4.2.1; en-us; Nexus 5 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko; googleweblight) Chrome/38.0.1025.166 Mobile Safari/535.19",
		"Mozilla/5.0 (Linux; Android 5.1.1; vivo X7 Build/LMY47V; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/48.0.2564.116 Mobile Safari/537.36 baiduboxapp/8.6.5 (Baidu; P1 5.1.1)",
		"Mozilla/5.0 (Linux; Android 9; SAMSUNG SM-G960U) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/10.1 Chrome/71.0.3578.99 Mobile Safari/537.36",
		"Dalvik/2.1.0 (Linux; U; Android 7.1.2; AFTA Build/NS6264) CTV",
		"Mozilla/5.0 (Linux; Android 7.0; SAMSUNG SM-G610M Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/7.4 Chrome/59.0.3071.125 Mobile Safari/537.36",
		"Mozilla/5.0 (Linux; Android 4.4.2; de-de; SAMSUNG GT-I9195 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/1.5 Chrome/28.0.1500.94 Mobile Safari/537.36",
		"",
		"Mozilla/5.0 (X11; U; AIX 6.1; en-US; rv:1.8.0.12) Gecko/20070626 Firefox/1.5.0.12",
		"Mozilla/5.0 (X11; U; AIX 7.2; en-US; rv:1.9.2.25) Gecko/20130116 Firefox/3.6.25",
		"",
		"Mozilla/4.0 (compatible; MSIE 5.12; Mac_PowerPC)",
		"Mozilla/5.0 (Macintosh; U; PPC; en-US; rv:1.0.1) Gecko/20020823 Netscape/7.0",
		"Mozilla/5.0 (Macintosh; U; PPC; en-US; rv:1.0.2) Gecko/20021120 Netscape/7.01",
		"iTunes/12.9.5 (Macintosh; OS X 10.14.5) AppleWebKit/607.2.6.1.1",
		"Mozilla/4.77C-CCK-MCD {C-UDP; EBM-APPLE} (Macintosh; U; PPC)",
		"Mozilla/4.0 (Macintosh)",
		"Mozilla/5.0 (Macintosh; U; PPC; en-US; rv:1.0.1) Gecko/20020823 Netscape/7.0 (OEM-SBC)",
		"",
		"",
		"",
		"",
		"",
		"Mozilla/4.77C-SGI [en] (X11; U; IRIX 6.5 IP32)",
		"Mozilla/3.04Gold (X11; U; IRIX 5.3 IP22)",
		"Mozilla/4.8C-SGI [en] (X11; U; IRIX64 6.5 IP27)",
		"",
		"Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148",
		"Outlook-iOS/709.2226530.prod.iphone (3.24.1)",
		"WeatherReport/1.2.2 CFNetwork/467.12 Darwin/10.3.1",
		"Mozilla/5.0 (iPhone; CPU iPhone OS 12_1_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/16D57",
		"Mozilla/5.0 (iPhone; CPU iPhone OS 13_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.4 Mobile/15E148 Safari/604.1",
		"Outlook-iOS/709.2189947.prod.iphone (3.24.0)",
		"Mozilla/5.0 (iPhone; CPU iPhone OS 11_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.0 Mobile/15E148 Safari/604.1",
		"Mozilla/5.0 (iPad; CPU OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1 Mobile/15E148 Safari/604.1",
		"Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		);
/*
Internet Explorer 7 (Windows Vista)	Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)
Internet Explorer 6 (Windows XP)	Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)
Internet Explorer 5.5 (Windows 2000)	Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0 )
Mozilla/1.22 (compatible; MSIE 2.0; Windows 3.1)
Mozilla/4.0 (compatible; MSIE 5.5; Windows 95; BCD2000)
*/

	 protected static $OS = array(
        'Windows'         	=> 'Windows NT\b',
        //'MacOSX'			=> '\bMac OS X\b',
        //'MacOSX'			=> '^(?!.*(\biPhone.*Mobile|\biPod|\biPad|AppleCoreMedia|CPU OS 13)).*\bMac OS X\b.*$',
        'Mac'				=> '^(?!.*(\biPhone.*Mobile|\biPod|\biPad|AppleCoreMedia|CPU OS 13)).*(\bMac OS X\b|Macintosh|Mac_PowerPC|PPC).*$',
        'AIX'				=> '\bAIX\b',
        'IRIX'				=> 'IRIX',
        'Linux'				=> '^(?!.*(Android|highworker|run)).*Linux.*$',
        'Android'         => 'Android',
        'BlackBerryOS'      => 'blackberry|\bBB10\b|rim tablet os',
        'PalmOS'            => 'PalmOS|avantgo|blazer|elaine|hiptop|palm|plucker|xiino',
        'SymbianOS'         => 'Symbian|SymbOS|Series60|Series40|SYB-[0-9]+|\bS60\b',
        // @reference: http://en.wikipedia.org/wiki/Windows_Mobile
        'WindowsMobileOS'   => 'Windows CE.*(PPC|Smartphone|Mobile|[0-9]{3}x[0-9]{3})|Windows Mobile|Windows Phone [0-9.]+|WCE;',
        // @reference: http://en.wikipedia.org/wiki/Windows_Phone
        // http://wifeng.cn/?r=blog&a=view&id=106
        // http://nicksnettravels.builttoroam.com/post/2011/01/10/Bogus-Windows-Phone-7-User-Agent-String.aspx
        // http://msdn.microsoft.com/library/ms537503.aspx
        // https://msdn.microsoft.com/en-us/library/hh869301(v=vs.85).aspx
        'WindowsPhoneOS'   => 'Windows Phone 10.0|Windows Phone 8.1|Windows Phone 8.0|Windows Phone OS|XBLWP7|ZuneWP7|Windows NT 6.[23]; ARM;',
        'iOS'               => '\biPhone.*Mobile|\biPod|\biPad|AppleCoreMedia|Outlook-iOS',
        // https://en.wikipedia.org/wiki/IPadOS
        'iPadOS' => 'CPU OS 13',
        // http://en.wikipedia.org/wiki/MeeGo
        // @todo: research MeeGo in UAs
        'MeeGoOS'           => 'MeeGo',
        // http://en.wikipedia.org/wiki/Maemo
        // @todo: research Maemo in UAs
        'MaemoOS'           => 'Maemo',
        'JavaOS'            => 'J2ME/|\bMIDP\b|\bCLDC\b', // '|Java/' produces bug #135
        'webOS'             => 'webOS|hpwOS',
        'badaOS'            => '\bBada\b',
        'BREWOS'            => 'BREW',
    );

	public function detect($xuser_agent=NULL){
		if(!empty($xuser_agent)){
			$user_agent=$xuser_agent;
		}else{
			$user_agent=$_SERVER["HTTP_USER_AGENT"];
		}

		 $rules = array_merge(
                self::$OS
            );
		 /*echo "<pre>";
		 print_r($rules);
		 echo "</pre>";
		 echo "<pre>";
		 print_r($this->pruebas);
		 echo "</pre>";*/
		 $resultado="";
		 //foreach ($this->pruebas as $clave => $valor) {
		 	//echo "<br> Comprobando ".$this->pruebas[$clave]."<br>";
		 	$OS=self::$OS;
		 	foreach ($OS as $clave2 => $valor2) {
		 		$resultado="";
		 		//if($this->match($OS[$clave2],$this->pruebas[$clave])){
		 		if($this->match($OS[$clave2],$user_agent)){
		 			$resultado=$clave2;
		 			break;
		 		}
		 	}
		 	//echo "RES : <strong>".$resultado."</strong> para ".$this->pruebas[$clave]."<br>\n";
		 	//echo "RES : <strong>".$resultado."</strong> para ".$user_agent."<br>\n";
		 	$this->OS_res=$resultado;
		 	
		 //}

	}
	public function obtener(){
		return $this->OS_res;
	}
	public function get(){
		return $this->OS_res;
	}

	    public function match($regex, $userAgent = null)
    {
        $match = (bool) preg_match(sprintf('#%s#is', $regex), (false === empty($userAgent) ? $userAgent : $this->userAgent), $matches);
        // If positive match is found, store the results for debug.
        if ($match) {
            $this->matchingRegex = $regex;
            $this->matchesArray = $matches;
        }

        return $match;
    }
}