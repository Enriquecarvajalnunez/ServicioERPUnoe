<?PHP

class MDB_MetaData_mysql
{
    var $mdb;
    var $db;
    var $dbname;	

    function _construct()
	{
	  null;
	}
	
    function setConnectionDataBase($mdb,$dbname)
    {
        $this->mdb = $mdb;
		$this->dbname=$dbname;
    }

    function getConnectionDataBase()
    {
        return $this->mdb;
    }

    function setDataBase($db)
    {
        $this->db = $db;
    }

    function getDataBase()
    {
        return $this->db;
    }
    
    function getDataBaseName()
    {
	    //print_r("<pre>");
		//print_r($this->mdb);
        //$this->db['name_db'] =$this->mdb->databaseName; 
		$this->db['name_db'] =$this->dbname; 
        return $this->db['name_db'];
    }

    function getTableList()
    {
        $this->db['tables']['list'] = $this->mdb->MetaTables(false,$this->getDataBaseName(),false);
        if ($this->mdb->ErrorNo())
        {
            print('Error: al obtener los nombres de las tablas:'.$this->mdb->ErrorMsg().' <br>');
            return ' ';
        }
        return $this->db['tables']['list'];
    }
	
    function getColumnList($table)
    {
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

		
        $sql = "SHOW COLUMNS FROM $table";
        $result = $this->mdb->GetAll($sql);
		//print_r($result);
		//$sql= "SELECT * FROM ".$table." LIMIT 0"; //'SELECT * FROM $table LIMIT 0'
		//print_r($sql);
		//$rs = $this->mdb->query($sql);

		//print_r($this->mdb->MetaColumns($table));
		//$result=$this->mdb->MetaColumns($table);
        //$this->db['tables'][$table]['columns']['list'] = $this->mdb->MetaColumns($table); 
        if ($this->mdb->ErrorNo())
        {   
            print('Error: al obtener los nombres de las columnas: '.$this->mdb->ErrorMsg().'<br>' );
            return ' ';
        }
		/*
        for ($i = 0; $i < $rs->columnCount(); $i++) {
          $col = $rs->getColumnMeta($i);
		  print_r("<br><pre>");
		  print_r($col);
		  print_r("</pre><br>");
          //$columns[] = $col['name'];
		  $this->db['tables'][$table]['columns']['list'][$i]=$col['name'];
       }	
	   */
       //print_r($columns);	   
		/**/
        for($i=0; $i < (count($result)); $i++) {
            $array = $result[$i];
            $column = $array[0];
			$this->db['tables'][$table]['columns']['list'][$i]=strtoupper($column);
        }
        //print_r("<pre>");		
		//print_r($this->db['tables'][$table]['columns']['list']);
		//print_r("</pre>");
        return $this->db['tables'][$table]['columns']['list'];
    }

	
    function getColumnData($table, $name_column = '')
    {
        $i = 0;
        $j = 0;

	    $this->emptyVar($table,"No se ha definido el nombre de la tabla");

        $sql = "SHOW columns FROM $table";
        $result = $this->mdb->GetAll($sql);

		//print_r("<pre>");
		//print_r($result);
        //print_r("</pre>");		

        if ($this->mdb->ErrorNo())
        {
            print('Error: de Mysql al obtener la metatada de las columnas:'.$this->mdb->ErrorMsg().' <br>');
            return ' ';
        }
        
        for($i=0; $i < (count($result)); $i++) {
            $array = $result[$i];
            $column = strtoupper($array[0]);
            $this->db['tables'][$table]['columns'][$column]['name'] = strtoupper($array[0]);
            $type = $array[1];
            
            if(strstr($type,'(')) {
                $data_type = preg_split('/[(,)]/',$type);

                $this->db['tables'][$table]['columns'][$column]['type'] = $data_type[0];
                if(($data_type[0] == 'enum') || ($data_type[0] == 'set')) {
                    $this->db['tables'][$table]['columns'][$column]['precision'] =
                    preg_replace("/.*\((.*)\)/", "\\1",$type);
                    $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
                }else {
                    $this->db['tables'][$table]['columns'][$column]['precision'] = $data_type[1];
                    if($data_type[2]!= '') {
                        $this->db['tables'][$table]['columns'][$column]['scale'] = $data_type[2];
                    }else {
                        $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
                    }
                }
            }else {
                $this->db['tables'][$table]['columns'][$column]['type'] = $type;
                $this->db['tables'][$table]['columns'][$column]['precision'] = 'null';
                $this->db['tables'][$table]['columns'][$column]['scale'] = 'null';
            }

            if ($array[2] == 'NO') {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'not null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['null'] = 'null';
            }

            if ($array[4] == '') {
                $this->db['tables'][$table]['columns'][$column]['default'] = 'null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['default'] = $array[4];
            }
            
            if ($array[5] == '') {
                $this->db['tables'][$table]['columns'][$column]['counter'] = 'null';
            }else {
                $this->db['tables'][$table]['columns'][$column]['counter'] = 'auto_increment';
            }

        }

        if ($name_column == '') {
            return $this->db['tables'][$table]['columns'];
        } else {
		// print_r("<pre>");		
		//print_r($this->db['tables'][$table]['columns'][$name_column]);
		//print_r("</pre>");
            return $this->db['tables'][$table]['columns'][$name_column];
        }
        $this->mdb->freeResult($result);
    }
    
            
    function getPrimaryKeyTable($table)
    {
        $i = 0;
  	
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        //$sql = "SHOW KEYS FROM $table";
        //print_r($sql);
        $result = $this->mdb->MetaPrimaryKeys($table);
        if ($this->mdb->ErrorNo())
        {
            print('Error: de Mysql al obtener las llaves primarias de la tabla: ' . $this->mdb->ErrorMsg().'<br>');
            return ' ';
        }

		/*
        for($i=0; $i < (count($result)); $i++) {
            $array = $result[0];
            $index_type = $array[2];
            if($index_type == 'PRIMARY') {
                $column_key = $array[4];
                $this->db['tables'][$table]['primary_key'][$i] = $column_key;
            }
        }*/
		
		//Only for ADODB
		
		for($i=0; $i < (count($result)); $i++) {
                $this->db['tables'][$table]['primary_key'][$i] = strtoupper($result[$i]);
        }
		

        //$this->mdb->freeResult($result);
        return $this->db['tables'][$table]['primary_key'];
    }
        
    function getForeignKeyTable($table)
    {
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");
        
        $sql = "SHOW TABLE STATUS LIKE '$table'";
		$sql = "
                 select
                     column_name
                     from information_schema.key_column_usage
                     where referenced_table_name is not null 
					 and   table_name = '".$table."'";	
		$sql = "					 
                 select
                   TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
                 from INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                 where
                 REFERENCED_TABLE_NAME ='".$table."'";					 
        //print_r($sql);
        $result = $this->mdb->GetAll($sql);
		

        //$result = $this->mdb->MetaForeignKeys($table);
		//print_r("<pre>");
		//print_r($result);
        //print_r("</pre>");		
        if ($this->mdb->ErrorNo())
        {
            print('Error: de Mysql al obtener las llaves foraneas de la tabla: ' . $this->mdb->ErrorMsg().'<br>');
            return ' ';
        }

        for($i=0; $i < (count($result)); $i++) {
            $array = $result[$i];
            $db_type = $array[1];
            if($db_type == 'InnoDB') {
                $pieces = explode(';', $array[14]);
                $num_pieces = count($pieces)-1;
                
                if($num_pieces == 0) {
                    //print ('La tabla '.$table.' no tiene foreign key <br>');
                    return ' ';
                }

                for($j=1; $j <= ($num_pieces); $j++) {
                    //el nombre del constraint que es el mismo que el del foreign key /
                    //se obtiene de la consulta 'show create table name_table'
                    if( $j == 1 ) {
                        $this->db['tables'][$table]['foreign_key'][$j-1]['name'] = $table.'_A';
                    }else {
                        $k = $j-1;
                        $this->db['tables'][$table]['foreign_key'][$j-1]['name'] = $table.'_A'.$k;
                    }
                    
                    $this->db['tables'][$table]['foreign_key'][$j-1]['local_table'] = $table;
                    $this->db['tables'][$table]['foreign_key'][$j-1]['local_table_columns'] = explode(' ',preg_replace("/.*\((.*)\).*\/(.*)\((.*)\)/", "\\1",$pieces[$j]));
                    $this->db['tables'][$table]['foreign_key'][$j-1]['reference_table'] = preg_replace("/.*\((.*)\).*\/(.*)\((.*)\)/", "\\2",$pieces[$j]);
                    $this->db['tables'][$table]['foreign_key'][$j-1]['reference_table_columns'] = explode(' ',preg_replace("/.*\((.*)\).*\/(.*)\((.*)\)/", "\\3",$pieces[$j]));
                }
                return $this->db['tables'][$table]['foreign_key'];
            }else {
                //print('Error: los Foreign Key para la tabla '.$table.' no son soportados el SMBD<br>');
                return ' ';
            }

        }
    }

    function getIndex($table)
    {
        $j = 0;
        $falt = 'FALSE';
        
        $this->emptyVar($table, "No se ha definido el nombre de la tabla");

        $sql = "SHOW INDEX FROM $table";

        $result = $this->mdb->GetAll($sql);

        if ($this->mdb->ErrorNo())
        {
            print('Error: de Mysql al obtener los indices de la tabla: ' . $this->mdb->ErrorMsg().'<br>');
            return ' ';
        }
        /*
		print_r("<pre>----- Result ----------");
		print_r($result);
		print_r("<br>------End---------</pre>");
		
		print_r("<pre>---- db[tables]--------");
		print_r($this->db);
		print_r("<br>------End---------</pre>");		
		*/
        for($i=0; $i < (count($result)); $i++) {
            $array = $result[$i];
            $falt = 'FALSE';

            if($array[2] != 'PRIMARY') {
                
                if(isset($this->db['tables'][$table]['index'])) {
                    $count_array = count($this->db['tables'][$table]['index']);
					if ($count_array == 1)
						$count_array = 0;  //JFMI
					//print_r("<pre> Count ->".$count_array."</pre>");
					//print_r($this->db['tables'][$table]['index']);
                    for($k=0; $k <= ($count_array); $k++) {
                        if (($this->db['tables'][$table]['index'][$k]['name']) == ($array[2])) {
                            $count_column = count($this->db['tables'][$table]['index'][$k]['column_name']);
                            $this->db['tables'][$table]['index'][$k]['columns_name'][$count_column+1] = $array[4];
                            $falt = 'TRUE';
                        } 
                    }
                }
            }
                if($falt == 'FALSE') {

                    $this->db['tables'][$table]['index'][$j]['name'] = $array[2];

                    if ($array[1] == 0) {
                        $this->db['tables'][$table]['index'][$j]['type'] = 'unique';
                    }else {
                        $this->db['tables'][$table]['index'][$j]['type'] = 'nonunique';
                    }

                    $this->db['tables'][$table]['index'][$j]['access_method'] = $array[10];

                    if ($array[5] == 'A') {
                        $this->db['tables'][$table]['index'][$j]['collation'] = 'asc';
                    }else {
                        $this->db['tables'][$table]['index'][$j]['collation'] = 'null';
                    }

                    $this->db['tables'][$table]['index'][$j]['columns_name'][0] = $array[4];
                    $j++;
                        
                }
            
        } 
		
		//print_r("<pre>");
		//print_r($this->db['tables'][$table]['index']);
        return $this->db['tables'][$table]['index'];
    }

    function getSequence( )
    {
        // print('Error: Las sequencias no son soportados por el SMBD<br>');
         return ' ';
    }
    
    function emptyVar ($var, $message)
    {
        if (empty($var))
        {
            print('Error: ' . $message);
            return ' ';
        }
    }

}

?>
