<?php

/**
*   Copyright 2006 - Spyro Solutions
*   
*   @author Spyro Solutions - Jose Fernando Mendoza
*   @date 14-Dic-2006 10:43:34
*   @location Cali-Colombia
*/  

require_once "WebRegistry.class.php";
require_once "WebSession.class.php";

set_time_limit(0);

class FrontController {

    public static function execute() {
        
		if (isset($_SESSION["_iauthSession"]["sessiontime"]))
		  $sessiontime = $_SESSION["_iauthSession"]["sessiontime"] * 60; //Parametro 64 de la tabla Types se activa en AuthManager
	    else
			$sessiontime = 180; // 30min
		
        WebSession::checkSession($sessiontime); 
        $command =WebRegistry::getWebCommand();

        if (@PEAR::isError($command)) {
            echo "command not found :".$_REQUEST["action"];
        }

        $result = $command->execute();
        //print_r($result);
        if (in_array($result, array("ajax","application/json","application/xml","text/html"))) 
        {
            //Realiza el print de la informacion desde el comando, la salida no es por inteface grafica
        
        } 
		else 
		{		
			if (@PEAR::isError($result)) {
				echo $result->getMessage();
			} 
			else 
			{
				
				$view_name = WebRegistry::getWebCommandView($result);
				if (@PEAR::isError($view_name)) 
				{
					echo $view_name->getMessage();
					
				} 
				else 
				{
					$view = WebRegistry::getWebView($view_name);
					
					if (@PEAR::isError($view))
					{
						echo $view->getMessage();
					} 
					else 
					{
						//WebRequest::setProperty("template",Application::getTemplate());
											
						$view->show();
					}
				}
			}
		
		}

    }

}


?>

