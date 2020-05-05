<?php
class Data_type {

	var $email_regular_expression = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";
	var $preg;


	/**
	Copyright 2004 ? Spyro Solutions
	
	 Formatea cadenas de caracteres escapando doblemente algunos caracteres (') (") () (caracter null), para
	 ingresarlos a la base de datos escapados
	@param string $cadena
	@return string
	@author jmendoza
	@date 08-sep-2004 16:32:17
	@location Cali - Colombia
	*/
	function formatString($cadena) {
		
		if (!is_string($cadena))
			return $cadena;
		
		//se controlan los saltos de linea
		$cadena = str_replace("\r","",$cadena);
		//Quita las posibles marcas
		$cadena = stripslashes($cadena);
		//Adiciona doble mente los slashes
		$cadena = addslashes($cadena);
		$cadena = addslashes($cadena);
		return $cadena;
	}
	/**
	Copyright 2004 ? Spyro Solutions
	
	 Valida que el valor sea entero
	@param mixed $value 
	@return boolean
	@author jmendoza
	@date 08-sep-2004 16:23:13
	@location Cali - Colombia
	*/
	function isInteger($value) {
		if ($value == "NULL") {
			return true;
		}
		if (is_numeric($value)) {
			$value = $value * 1;
			if (is_integer($value))
				return true;
		}
		return false;
	}
	/**
	Copyright 2004 ? Spyro Solutions
	
	 Valida que la llave primaria no tenga caracteres especiales si es cadena y que no sea negativa si es numerica
	@param string-numeric $key 
	@return boolean true or false
	@author jmendoza
	@date 08-sep-2004 15:49:00
	@location Cali - Colombia
	*/
	function formatPrimaryKey($key) {
		if (!$key)
			return false;
		//Verifica primero el tipo de dato
		$dataType = gettype($key);
		//print_r($key);
		switch ($dataType) {
			case "integer" :
			case "double" :
				if ($key <= 0)
					return false;
				return true;
			case "string" :
				return $this->basePrimary($key);
			default :
				return false;
		}
	}

	/**
	Copyright 2004 ? Spyro Solutions
		
	 descripcion
	@param datatype paramname description
	@return datatype description
	@author jmendoza
	@date 08-sep-2004 16:05:04
	@location Cali - Colombia
	*/
	function basePrimary($cadena) {
		$base = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890.";
		$nureg = strlen($cadena);
		for ($nucont = 0; $nucont < $nureg; $nucont ++) {
			if (strstr($base, $cadena[$nucont]) === false)
				return false;
		}
		return true;
	}
	/**
		Copyright 2004 ? Spyro Solutions
		
		 Valida que el valor sea Double
		@param mixed $value 
		@return boolean
		@author jmendoza
		@date 30-sep-2004 13:54
		@location Cali - Colombia
	*/
	function isDouble($value) {
		if ($value == "NULL") {
			return true;
		}
		if (is_numeric($value)) {
			$value = $value * 1;
			if (is_integer($value))
				return true;
			if (is_double($value))
				return true;
		}
		return false;
	}
	/**
	    Copyright 2004 ? Spyro Solutions
	
	     Convierte una cadena que representa una cantidad en bytes a bytes
	    @param string $isbvalue (cadena con el valor en bytes)
	    @return numeric
	    @author jmendoza
	    @date 01-Oct-2004 13:54
	    @location Cali - Colombia
	*/
	function string_to_bytes($isbvalue) {

		settype($sbmeasurement, "string");
		settype($sbvalue, "string");

		$isbvalue = trim($isbvalue);
		if ($isbvalue) {
			$sbvalue = $isbvalue * 1;
			$sbmeasurement = substr($isbvalue, strpos($isbvalue, $sbvalue) + 1);
			switch ($sbmeasurement) {
				case 'k' :
					return (int) $sbvalue * 1024;
				case 'K' :
					return (int) $sbvalue * 1024;
					break;
				case 'm' :
					return (int) $sbvalue * 1048576;
				case 'M' :
					return (int) $sbvalue * 1048576;
					break;
				default :
					return $sbvalue;
			}
		} else {
			return 0;
		}
	}
	/**
	Copyright 2004 ? Spyro Solutions
		
	 Valida si la cadena pasada como parametro tiene formato de mail
	@param string $isbcadena (Cadena con la direccion de email) 
	@return boolean true o false
	@author jmendoza
	@date 19-Oct-2004 12:24
	@location Cali - Colombia
	*/
	function IsEmail($isbcadena) {
		if (isset ($this->preg)) {
			if (strlen($this->preg)) {
				return (preg_match($this->preg, $isbcadena));
			}
		} else {
			$this->preg = (function_exists("preg_match") ? "/".str_replace("/", "\\/", $this->email_regular_expression)."/" : "");
			return ($this->IsEmail($isbcadena));
		}
		return (eregi($this->email_regular_expression, $isbcadena) != 0);
	}
	
	/**
	Copyright 2008 ? Spyro Solutions
	 Valida si la cadena pasada como parametro es un URL
	@param string $value (Cadena con la direccion url )
	@return boolean true o false
	@author jmendoza
	@date 01-Oct-2008 01:47
	@location Cali - Colombia
	*/
	function isURL($value) {
    	if(strlen($value) == 0)
        	return $empty;

    	return preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i', $value)
                	|| preg_match('!^http(s)?://localhost!', $value);
            	// quick and dirty hack: review  --NoWhereMan
	}

	/**
	Copyright 2004 ? Spyro Solutions

	 Convierte un texto a html 
	@param string $isbcadena (Cadena con texto) 
	@return string $osbresult (Cadena con el texto formateado)
	@author jmendoza
	@date 19-Oct-2004 14:09
	@location Cali - Colombia
	*/
	function formatStringHtml($isbcadena) {
		settype($osbcadena, "string");
		$osbcadena = str_replace("\n", "<br>", $isbcadena);
		$osbcadena = str_replace("\r","",$osbcadena);
		if ($osbcadena) {
			$osbcadena = htmlentities($osbcadena);
		}
		return $osbcadena;
	}

	/**
	Copyright 2004 ? Spyro Solutions

        Change the filed to date format for SQL langage
	@param string $stringdate (Cadena con texto)
	@return string $tmpString (Cadena con el texto formateado)
	@author jmendoza
	@date 19-Oct-2004 14:09
	@location Cali - Colombia
	*/

	function formatDateDb($stringdate) {
		if (!is_string($stringdate))
			return $stringdate;

	        $tmpString ='';
		if (Application::getDbusedateformat() =='1')  //Use
                {                   
                  if ($this->valformatDate($stringdate)) // es una fecha valida
                  {
                   $tmpString .=Application::getDbfundate()."('".$stringdate."','".Application::getDbdateformat()."')";
                  }
                }
                else
                {
                  if ($this->valformatDate($stringdate)) // es una fecha valida
                  {
                   $tmpString .=Application ::getDbfundate()."('".$stringdate."')";
                  }
                }

		return $tmpString;

	}



	/**
	Copyright 2004 ? Spyro Solutions

    Valid format date for saveconfiguration parameters
	@param string $stringdate (Cadena con texto)
	@return boolean
	@author jmendoza
	@date 19-Oct-2004 14:09
	@location Cali - Colombia
	*/
        function valformatDate($stringdate)
        {
            $dateFormatSeparator = Application ::getDbdateformatseparator();
            $format= explode($dateFormatSeparator,Application ::getDbdateformat());
            $dataDate = explode($dateFormatSeparator,$stringdate);
            if ((strlen( $format[0])== strlen($dataDate[0])) &&
                (strlen( $format[1])== strlen($dataDate[1])) &&
                (strlen( $format[2])== strlen($dataDate[2]))

               ) return true;
            return false;
        }
		
    //Brian Andres Valencia: 17/04/2017, permite validar un campo con una sola sentencia
    public static function notEmpty($value)
    {
        try{
            if ($value == null || $value == "" || strval( $value) == '0') {
                return false;
            } else {
                return true;
            }
        }catch (Exception $e){
            return false;
        }
    }

    //Brian Andres Valencia: 17/04/2017, permite validar un campo con una sola sentencia
    public static function cryptPass($value)
    {
        if($value!=null && $value!='')
            return md5($value);
        else
            return null;
    }		

}
?>