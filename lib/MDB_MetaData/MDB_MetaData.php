<?PHP
//require_once "MDB2.php";
//require_once "PEAR.php";
require_once "adodb.inc.php";

class MDB_MetaData
{

    function &connect($db_type,$host, $user, $pass, $db_name)
    {
	
	  $conn=ADONewConnection('pdo');
	  //$conn = ADONewConnection($db_type);
	  $host= $db_type.":host=".$host;
      if (!$conn->Connect($host, $user, $pass, $db_name)) {
          die('error connecting to the database');
          return NULL;
       }
	    //print_r("<pre>");print_r(get_class_methods($conn));
        include_once "MDB_MetaData/${db_type}.php";
        $classname = "MDB_MetaData_${db_type}";
    
        if (!class_exists($classname)) {
            //return PEAR::raiseError(null, MDB_ERROR_NOT_FOUND, null, null, null, 'MDB_Error', true);
            die ('Error al cargar el manejador: la clase manejadora no existe: ' . MDB2::errorMessage($classname));
        }
        $obj  = new $classname;
        $obj->setConnectionDataBase($conn,$db_name);
  	    return $obj;
    }
}
?>
