<?php 
class Response {
	
	private $requestContentType;
	private  $contentType;
	private $httpVersion = "HTTP/1.1";
    private $httpStatus = array(
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
			306 => '(Unused)',  
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
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');

    function __construct()
    {
    	$this->requestContentType = $_SERVER['HTTP_ACCEPT'];
    	//$this->getContentType();
    	//echo $this->requestContentType;
    	//print_r($_SERVER);
    }

    public function getRequestContentType()
    {
    	if(strpos($this->requestContentType,'application/json') !== false)
    	{
			$this->contentType = 'application/json';
		} else 
		    if(strpos($this->requestContentType,'text/html') !== false)
		    {
                $this->contentType = 'text/html';
		    } else 
		        if(strpos($this->requestContentType,'application/xml') !== false)
		        {
                  $this->contentType = 'application/xml';
		        }    	
    }

    public function getContentType()
    {
     	return $this->contentType ;
  	
    }    

    public function setContentType($contentType)
    {
     	$this->contentType = $contentType;
  	
    }


    public function contentTypeResponse($rawData)
    {
    	//echo $this->contentType;
    	if($this->contentType == 'application/json'){
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if($this->contentType == 'text/html'){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if($this->contentType == 'application/xml'){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
    }

	public function setHttpHeaders($statusCode){
		
		$statusMessage = $this -> getHttpStatusMessage($statusCode);
		
		header($this->httpVersion. " ". $statusCode ." ". $statusMessage);		
		header("Content-Type:". $this->contentType);
	}
	
	public function getHttpStatusMessage($statusCode){

		return ($this->httpStatus[$statusCode]) ? $this->httpStatus[$statusCode] : $status[500];
	}

	public function encodeHtml($responseData) {
	
		$htmlResponse = "<table border='1'>";
		foreach($responseData as $key=>$value) {
    			$htmlResponse .= "<tr><td>". $key. "</td><td>". $value. "</td></tr>";
		}
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	public function encodeJson($responseData) {
		ob_end_clean();
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		ob_end_clean();
		$xml = new SimpleXMLElement('<?xml version="1.0"?><Response></Response>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}	
}
?>