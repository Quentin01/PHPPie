<?php

/*
 * Class Response
 * Created on 25/01/12 at 15:28
 */

namespace PHPPie\HTTP;

class Response {
	protected $status;
	protected $statusText;
	protected $contents;
	protected $headers;
	protected $charset;
	protected $version;
	
	protected $cookies;
	
	static public $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );
    
    public function __construct($content = '', $status = 200, $headers = array())
    {
		$this->content = $content;
		$this->setStatusCode($status);
		$this->headers = $headers;
		$this->setCharset("UTF-8");
		$this->setProtocolVersion("1.0");
		
		$this->cookies = new \PHPPie\HTTP\Response\Cookies();
	}
	
	public function __get($name)
 	{
		if($name === "cookies")
			return $this->cookies;
	}
	
	public function send()
	{
		\PHPPie\Event\Handler::fireEvent('sendResponse', array());
		
		if (headers_sent()) {
			return;
		}
		
		if (!$this->hasHeader('Content-Type')) {
            $this->setHeader('Content-Type', 'text/html; charset='.$this->charset);
        } elseif ('text/' === substr($this->getHeader('Content-Type'), 0, 5) && false === strpos($this->getHeader('Content-Type'), 'charset')) {
            $this->setHeader('Content-Type', $this->getHeader('Content-Type').'; charset='.$this->charset);
        }
        
        if ($this->hasHeader('Transfer-Encoding')) {
            $this->removeHeader('Content-Length');
        }
		
		header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText));
		 
		foreach ($this->headers as $name => $value) {
			header($name.': '.$value, false);
        }
        
        $this->cookies->send();
        
        echo $this->content;
        
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
	}
	
	public function setContent($content)
    {
        $this->content = (string) $content;
    }
    
    public function getContent()
    {
		return $this->content;
	}
	
	public function setStatusCode($status)
	{
		if(!isset(self::$statusTexts[$status]))
			throw new \PHPPie\Exception\Exception('Invalid HTTP code : '.$status);
			
		$this->statusCode = $status;
		$this->statusText = self::$statusTexts[$status];
	}
	
	public function setHeader($name, $value)
	{
		return ($this->headers[$name] = $value);
	}
	
	public function getHeader($name)
	{
		if($this->hasHeader($name))
			return $this->headers[$name];
		else
			return false;
	}
	
	public function hasHeader($name)
	{
		return isset($this->headers[$name]);
	}
	
	public function removeHeader($name)
	{
		unset($this->headers[$name]);
	}
	
	public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    public function getStatusText()
    {
		return $this->statusText;
	}
    
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
    
    public function getCharset()
    {
        return $this->charset;
    }
    public function setProtocolVersion($version)
    {
        $this->version = $version;
    }
    
    public function redirect($uri) {
		$this->setHeader('Location', $uri);
		$this->send();
	}

    public function getProtocolVersion()
    {
        return $this->version;
    }
    
    public function getMimeType($extension) {
		$mimes = array(
			"323" => "text/h323",
			"acx" => "application/internet-property-stream",
			"ai" => "application/postscript",
			"aif" => "audio/x-aiff",
			"aifc" => "audio/x-aiff",
			"aiff" => "audio/x-aiff",
			"asf" => "video/x-ms-asf",
			"asr" => "video/x-ms-asf",
			"asx" => "video/x-ms-asf",
			"au" => "audio/basic",
			"avi" => "video/x-msvideo",
			"axs" => "application/olescript",
			"bas" => "text/plain",
			"bcpio" => "application/x-bcpio",
			"bin" => "application/octet-stream",
			"bmp" => "image/bmp",
			"c" => "text/plain",
			"cat" => "application/vnd.ms-pkiseccat",
			"cdf" => "application/x-cdf",
			"cer" => "application/x-x509-ca-cert",
			"class" => "application/octet-stream",
			"clp" => "application/x-msclip",
			"cmx" => "image/x-cmx",
			"cod" => "image/cis-cod",
			"cpio" => "application/x-cpio",
			"crd" => "application/x-mscardfile",
			"crl" => "application/pkix-crl",
			"crt" => "application/x-x509-ca-cert",
			"csh" => "application/x-csh",
			"css" => "text/css",
			"dcr" => "application/x-director",
			"der" => "application/x-x509-ca-cert",
			"dir" => "application/x-director",
			"dll" => "application/x-msdownload",
			"dms" => "application/octet-stream",
			"doc" => "application/msword",
			"dot" => "application/msword",
			"dvi" => "application/x-dvi",
			"dxr" => "application/x-director",
			"eps" => "application/postscript",
			"etx" => "text/x-setext",
			"evy" => "application/envoy",
			"exe" => "application/octet-stream",
			"fif" => "application/fractals",
			"flr" => "x-world/x-vrml",
			"gif" => "image/gif",
			"gtar" => "application/x-gtar",
			"gz" => "application/x-gzip",
			"h" => "text/plain",
			"hdf" => "application/x-hdf",
			"hlp" => "application/winhlp",
			"hqx" => "application/mac-binhex40",
			"hta" => "application/hta",
			"htc" => "text/x-component",
			"htm" => "text/html",
			"html" => "text/html",
			"htt" => "text/webviewhtml",
			"ico" => "image/x-icon",
			"ief" => "image/ief",
			"iii" => "application/x-iphone",
			"ins" => "application/x-internet-signup",
			"isp" => "application/x-internet-signup",
			"jfif" => "image/pipeg",
			"jpe" => "image/jpeg",
			"jpeg" => "image/jpeg",
			"jpg" => "image/jpeg",
			"js" => "application/x-javascript",
			"latex" => "application/x-latex",
			"lha" => "application/octet-stream",
			"lsf" => "video/x-la-asf",
			"lsx" => "video/x-la-asf",
			"lzh" => "application/octet-stream",
			"m13" => "application/x-msmediaview",
			"m14" => "application/x-msmediaview",
			"m3u" => "audio/x-mpegurl",
			"man" => "application/x-troff-man",
			"mdb" => "application/x-msaccess",
			"me" => "application/x-troff-me",
			"mht" => "message/rfc822",
			"mhtml" => "message/rfc822",
			"mid" => "audio/mid",
			"mny" => "application/x-msmoney",
			"mov" => "video/quicktime",
			"movie" => "video/x-sgi-movie",
			"mp2" => "video/mpeg",
			"mp3" => "audio/mpeg",
			"mpa" => "video/mpeg",
			"mpe" => "video/mpeg",
			"mpeg" => "video/mpeg",
			"mpg" => "video/mpeg",
			"mpp" => "application/vnd.ms-project",
			"mpv2" => "video/mpeg",
			"ms" => "application/x-troff-ms",
			"mvb" => "application/x-msmediaview",
			"nws" => "message/rfc822",
			"oda" => "application/oda",
			"p10" => "application/pkcs10",
			"p12" => "application/x-pkcs12",
			"p7b" => "application/x-pkcs7-certificates",
			"p7c" => "application/x-pkcs7-mime",
			"p7m" => "application/x-pkcs7-mime",
			"p7r" => "application/x-pkcs7-certreqresp",
			"p7s" => "application/x-pkcs7-signature",
			"pbm" => "image/x-portable-bitmap",
			"pdf" => "application/pdf",
			"pfx" => "application/x-pkcs12",
			"pgm" => "image/x-portable-graymap",
			"pko" => "application/ynd.ms-pkipko",
			"pma" => "application/x-perfmon",
			"pmc" => "application/x-perfmon",
			"pml" => "application/x-perfmon",
			"pmr" => "application/x-perfmon",
			"pmw" => "application/x-perfmon",
			"pnm" => "image/x-portable-anymap",
			"pot" => "application/vnd.ms-powerpoint",
			"ppm" => "image/x-portable-pixmap",
			"pps" => "application/vnd.ms-powerpoint",
			"ppt" => "application/vnd.ms-powerpoint",
			"prf" => "application/pics-rules",
			"ps" => "application/postscript",
			"pub" => "application/x-mspublisher",
			"qt" => "video/quicktime",
			"ra" => "audio/x-pn-realaudio",
			"ram" => "audio/x-pn-realaudio",
			"ras" => "image/x-cmu-raster",
			"rgb" => "image/x-rgb",
			"rmi" => "audio/mid",
			"roff" => "application/x-troff",
			"rtf" => "application/rtf",
			"rtx" => "text/richtext",
			"scd" => "application/x-msschedule",
			"sct" => "text/scriptlet",
			"setpay" => "application/set-payment-initiation",
			"setreg" => "application/set-registration-initiation",
			"sh" => "application/x-sh",
			"shar" => "application/x-shar",
			"sit" => "application/x-stuffit",
			"snd" => "audio/basic",
			"spc" => "application/x-pkcs7-certificates",
			"spl" => "application/futuresplash",
			"src" => "application/x-wais-source",
			"sst" => "application/vnd.ms-pkicertstore",
			"stl" => "application/vnd.ms-pkistl",
			"stm" => "text/html",
			"svg" => "image/svg+xml",
			"sv4cpio" => "application/x-sv4cpio",
			"sv4crc" => "application/x-sv4crc",
			"t" => "application/x-troff",
			"tar" => "application/x-tar",
			"tcl" => "application/x-tcl",
			"tex" => "application/x-tex",
			"texi" => "application/x-texinfo",
			"texinfo" => "application/x-texinfo",
			"tgz" => "application/x-compressed",
			"tif" => "image/tiff",
			"tiff" => "image/tiff",
			"tr" => "application/x-troff",
			"trm" => "application/x-msterminal",
			"tsv" => "text/tab-separated-values",
			"txt" => "text/plain",
			"uls" => "text/iuls",
			"ustar" => "application/x-ustar",
			"vcf" => "text/x-vcard",
			"vrml" => "x-world/x-vrml",
			"wav" => "audio/x-wav",
			"wcm" => "application/vnd.ms-works",
			"wdb" => "application/vnd.ms-works",
			"wks" => "application/vnd.ms-works",
			"wmf" => "application/x-msmetafile",
			"wps" => "application/vnd.ms-works",
			"wri" => "application/x-mswrite",
			"wrl" => "x-world/x-vrml",
			"wrz" => "x-world/x-vrml",
			"xaf" => "x-world/x-vrml",
			"xbm" => "image/x-xbitmap",
			"xla" => "application/vnd.ms-excel",
			"xlc" => "application/vnd.ms-excel",
			"xlm" => "application/vnd.ms-excel",
			"xls" => "application/vnd.ms-excel",
			"xlt" => "application/vnd.ms-excel",
			"xlw" => "application/vnd.ms-excel",
			"xof" => "x-world/x-vrml",
			"xpm" => "image/x-xpixmap",
			"xwd" => "image/x-xwindowdump",
			"z" => "application/x-compress",
			"zip" => "application/zip"
		);
		
		if(!isset($mimes[$extension]))
			return 'text/plain';
			
		return $mimes[$extension];
	}
}
?>
