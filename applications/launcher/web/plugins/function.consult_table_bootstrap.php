<?php
/**
 * Smarty plugin
 */
/**
 * Smarty {consult_table_referencia} plugin
 * Type:     function<br>
 * Name:     consult_table_referencia<br>
 * Purpose:  Crea una tabla en html, la cual es llenada con los datos de
 *           una tabla en la base de datos.<br>
 * Input:<br>
 *           table_name = nombre de la tabla en la base de datos (required)
 *           llaves = nombre de los identificadores de la tabla (required) si los identificadores son mas de uno deben ser separados por ','
 *           form_name = nombre de la forma que contiene el consult_table (required)
 *           titulos = encabezados de la tabla que se crea(optional)
 *           cambiar_valor = son los campos de la tabla que seran reemplazados por otros valores en otras tablas. (optional)
 *                           la cadena debe tener el siguiente formato :
 *                           1. Nombre del campo que va a cambiar de la tabla 'table_name'
 *                           2. Nombre Tabla de donde se sacara el valor nuevo
 *                           3. Nombre del indice de la tabla de donde se sacara el valor nuevo
 *                           4. Nombre del campo de la tabla expesificada en numeral (2), este sera el nuevo valor.
 *           DataGateway = Compuerta de fila que se utilizara sino se esta presente se utiliza la pordefeco
 *           cantidad_registros = es el numero de registros que cargara la consulta como maximo.(optional)
 *           commnad = nombre del comando que se invocar cuando el usuario desea cambiar a la pagina siguiente
 *           filter  = si se quiere hacer algun filtro campodetabla,campoveusuario(sedebe contruir en la compuerta el metodo getallBykeyword )
 *                     sino se esta presente se utiliza el metodo de la compuerta por defecto sin filtro
 *           title   = Titulo que se le muestra al usuario en la pantalla
 * 
 *
 *<pre>
 * Examples: 
 *           {consult_table_referencia table_name="ciudad"
 *                          llaves="Codigo_Ciudad,Codigo_Pais"
 *                          form_name="FrmCiudad"
 *                          titulos="Codigo Ciudad,Nombre Ciudad,Pais"
 *                          cambiar_valor="Codigo_Pais,pais,Codigo_Pais,Nombre_Pais"
 *                          cantidad_registros = 30
 *                          command = "CmdShowListCiudad"
 *                          type ="LOV" list of values
 *                          filter=userlogin,Login,username,Name,userlana,LastName
 *                          title= " Titulo de la consulta"
                            parameter = parametro para la funcion gateway
 *           }
 *<pre>
 * Nota: Necesita ser parte de una forma!
 *
 * @author   José Feranndo Mendoza
 * @version  1.0.0
 * @param array
 * @param Smarty
 * @return string
 * @copyright Spyro Solutions - 28 - jul - 2006
 */

function smarty_function_consult_table_bootstrap($params, &$smarty)
{

  extract($params);
 require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Toolsbar.lan";
 $gatewayReferencia = Application::getDataGateway($DataGateway);
 $ruta='varios';
 $report_dir = Application::getReportDirectory()."/".$ruta."/generados";
 $genfile='on';
 $archivo='varios';

  if(!isset($loadTable)) {
  	$loadTable = "getAll".ucfirst($table_name);
  }



  if(isset($filter)) { 
     if($_REQUEST["key_word"] == ''){

	 //consulta todos los registros
          $registros_tabla = call_user_func(array($gatewayReferencia,$loadTable));
      
     }else{
          
          //$registros_tabla = call_user_func(array($gatewayReferencia,$loadTable));
          $registros_tabla = call_user_func(array($gatewayReferencia,"getallBykeyword"),$_REQUEST["key_word"],$_REQUEST["sel_condiction"],$_REQUEST["sel_condicionfield"]); 
          //unset($_REQUEST["key_word"]);
          //unset($filter);
        

    }
   }
   else
   {
     if (isset($parameters))
         $registros_tabla = call_user_func(array($gatewayReferencia,"$loadTable"),$parameters); 
      else
         $registros_tabla = call_user_func(array($gatewayReferencia,$loadTable));
     
    

   }

  // asigna el valor por defecto a la cantidad de registros
  if(!isset($cantidad_registros))
  {
     $cantidad_registros = 25;
  }

  //calcula la cantidad de paginas
  $cantidad_paginas = ceil(count($registros_tabla)/$cantidad_registros);

  //obtiene el numero de la pagina actual
  if(!isset($_REQUEST[$table_name."__pagina_consult"])){
     $numero_pagina = 1;
  }else{
     if($_REQUEST[$table_name."__pagina_consult"] > $cantidad_paginas ){
       $numero_pagina = $cantidad_paginas;
     }else{
          if($_REQUEST[$table_name."__pagina_consult"] < 1 ){
             $numero_pagina = 1;
          }else{
             $numero_pagina = $_REQUEST[$table_name."__pagina_consult"];
          }
     }
  }

  $html_tabla = '';
  
   /*
   if($cantidad_paginas > 1){
     CrearMenuPaginasSiguientes($html_tabla,$table_name,$form_name,$numero_pagina,$cantidad_paginas,$command);
  }
  */
  
  $html_tabla .= '
  
	  <!--===================================================-->
	  <!--Begin Data Table-->  
      <div class="card card-style-1">
        <div class="card-body">  
          <table id="example" class="table table-striped table-bordered table-sm dt-responsive display nowrap w-100">
            <!-- Filter columns -->		
  
  ';


     if(!isset($title)) {
         $title = $form_name;
     }


  if( (isset($titulos) || is_array($registros_tabla)) && (count($registros_tabla)>0) ){
     CrearEncabezadoTabla($html_tabla,$registros_tabla,$titulos);
  }

  // SE tiene en cuenta si esta una lista de valores o una consulta
  $typenew ='LIST';

  if( (is_array($registros_tabla)) &&  (count($registros_tabla)>0)){

     if(!isset($type)) {
         $typenew = 'LIST';
     }
     else
     {
      $typenew=$type;
     }


     CrearCuerpoTabla($html_tabla,$registros_tabla,$table_name,$llaves,$cambiar_valor,$cantidad_registros,$numero_pagina,$typenew,$command_showbyid,$form_name);
     
  }
  
  else
  {
     //$html_tabla .= "<tr> <td colspan = 3 align='center'> NO SE ENCONTRARON DATOS </td></tr>";
	
	 $html_tabla = '
	  <br>
	  <br>
      <div class="card card-style-1">
        <div class="card-body">
												<div class="media-body">
													<h4 class="alert-title">Atenci&oacute;n</h4>
													<p class="alert-message">No se Encontraron Datos, Intente con Otra Consulta !!!</p>
													<p class="alert-message">No data Found, Retry  !!!</p>
												</div>

        </div>
      </div>';
	
      
  }
  

 
  CrearVariablesOcultas($html_tabla,$table_name,$llaves);
  
  //Fin de Tabla
  $html_tabla .= '
		  </table> <!-- End Table -->

	    </div> <!-- card-body -->
	  </div>
	<!--===================================================-->
	<!--End Data Table-->
					
  ';


  $html_result = $html_tabla;

  print $html_result;

}

/*
* Crea el encabezado de la tabla, con el valor de la variable $ titulos,
* si titulos no esta setiado por defecto el encabezado de la tabla
* son los nombre de los campos de la tabla en la base de datos
*/
function CrearEncabezadoTabla(&$html_tabla,$registros_tabla,$titulos){
    require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tableheader.lan";
    //si la variable titulos esta setiada crea un vector con los titulos
    //sino crea un vector con los indices del vector $registros_tabla
     if(isset($titulos)){
        $m = explode(",",$titulos);
     }else{
        //pasa los titulos a un vector
        $m = array_keys($registros_tabla[0]);
     }

    // crea el encabezado de la tabla
    $html_tabla .= '
            <thead class="thead-primary">';
				   

    $html_tabla .= "
			   <tr>
			     <th>A</th>
                   ";

	/* Crea los encabezados */
	for($i=0;$i < count($m);$i++){
        $html_tabla .= '
                       <th>'.$Tableheaders[$m[$i]].'</th>';		

    }
    $html_tabla .= "
			   </tr>
			   
			</thead>   ";
			   

}

function CrearEncabezadoTablaSearch(&$html_tabla,$registros_tabla,$titulos){
    require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tableheader.lan";
    //si la variable titulos esta setiada crea un vector con los titulos
    //sino crea un vector con los indices del vector $registros_tabla
     if(isset($titulos)){
        $m = explode(",",$titulos);
     }else{
        //pasa los titulos a un vector
        $m = array_keys($registros_tabla[0]);
     }

    // crea el encabezado de la tabla
    $html_tabla .= '
            <thead class="thead-primary">
 			   <tr>';  

    $html_tabla .= '<th>>A</th>';
	/* Crea los encabezados */
	for($i=0;$i < count($m);$i++){
        $html_tabla .= '
                       <th>'.$Tableheaders[$m[$i]].'</th>';		

    }
    $html_tabla .= "
			   </tr>
			   
			</thead>   ";
			   

}

/*
* Crea una tabla en html con los datos de la tabla de la base de datos
*/
function CrearCuerpoTabla(&$html_tabla,$registros_tabla,$table_name,$llaves,$cambiar_valor,$cantidad_registros,$numero_pagina,$type,$command_showbyid,$form_name){

   //obtener las llav,s de la tabla y pasarlas a un vector
   $keys = explode(",",$llaves);
   
      $html_tabla .="
			<tbody>";     

   //for($i=(($numero_pagina-1)*$cantidad_registros);($i < $numero_pagina*$cantidad_registros )&&($i < count($registros_tabla));$i++)
   for($i=0;$i < count($registros_tabla);$i++)
	   {


      if(isset($cambiar_valor)){

          CambiarValorTabla($registros_tabla,$cambiar_valor,$i);

      }

       //obtener una fila completa de la tabla de la base de datos.
        $m = array_values($registros_tabla[$i]);
        $html_tabla .= '<tr>
                  <td class="text-center">
                    <div class="btn-group btn-group-xs" role="group">';		
        $html_tabla .= "<a href='#' onClick=\"";

        $html_tabla .= $form_name.".".$table_name."__".strtoupper($keys[0]).".value = '".$m[0]."'";


        $html_tabla .=";".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\"><i class='material-icons' style='top:12px;'>edit</i></a>";
					  
        $html_tabla .= '	
		
		
                    </div>';
					
            CrearRadioButton($html_tabla,$table_name,$registros_tabla,$keys,$i,$valor_estilo,$command_showbyid,$form_name);					
        $html_tabla .= '			
                  </td>';
			  
        for($j=0;$j < count($m);$j++){


				  
        	
           $html_tabla .= "<td class='text-wrap'>";

           if($m[$j] != ""){
               /* 
               if (($j == 0 || $j == 1) && ($type !='LOV') )
               {
                 $html_tabla .= "<a href='#' onClick=\"";

                $html_tabla .= $form_name.".".$table_name."__".strtoupper($keys[0]).".value = '".$m[0]."'";


                $html_tabla .=";".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\">".$m[$j]."</a>";

               }
               else
				   */
                  $html_tabla .= $m[$j];

           }else{
               $html_tabla .= "&nbsp;";
           }
           //$html_tabla .= "</DIV>";
           $html_tabla .= "</td>";

        }
		
      $html_tabla .= "</tr>";
   }
      $html_tabla .="
									</tbody>";        
}

/*
* Cambia los valores de del vector '$registros_tabla' que son indices
* de otras tablas en la base de datos
*/
function CambiarValorTabla(&$registros_tabla,$change_value,$indice){
 //convierte la cadena a un vector
 $parametros_cambiar = explode(",",$change_value);

  for($i=0;$i < count($parametros_cambiar);$i+=4){
    //llama la clase de la tabla
     $gateway = Application::getDataGateway($parametros_cambiar[$i+1]);
    //optiene todos los datos de la tabla
     $datos = call_user_func(array($gateway,"getAll".$parametros_cambiar[$i+1]));
     for($z=0;$z < count($datos);$z++){
         //cambia el valor del vector por el nombre si los codigos son iguales
         $data='';
         if($registros_tabla[$indice][$parametros_cambiar[$i]] == $datos[$z][$parametros_cambiar[$i+2]] ){
            $param=explode('/',$parametros_cambiar[$i+3]);
            for($j=0;$j<count($param);$j++)
            {
             //$registros_tabla[$indice][$parametros_cambiar[$i]] = $datos[$z][$parametros_cambiar[$i+3]];
             $data.=$datos[$z][$param[$j]]." ";
            }
            //$registros_tabla[$indice][$parametros_cambiar[$i]] = $datos[$z][$parametros_cambiar[$i+3]];
            $registros_tabla[$indice][$parametros_cambiar[$i]] = $data;
            break;
         }
         //$z++;
     }

  }
}




/*
* Crea un Radio Buton en la primera columna de la tabla en html que se esta generando,
* Por cada Radio Button se genera un codigo en JavaScript para en la propiedad
* 'Onclick' para asignar los vales de la llaves de la tabla en la base de datos
* en los campos ocultos. (Ver CrearVariablesOcultas)
*/
function CrearRadioButton(&$html_tabla,$table_name,$registros_tabla,$keys,$i,$valor_estilo,$command_showbyid,$form_name){

  $html_tabla .= "<input type='radio'";
  $html_tabla .= " name='".$table_name."__keys' onClick=\"";
  for($z=0;$z < count($keys);$z++){
      if($z == 0 ){
         $html_tabla .= $form_name.".".$table_name."__".strtoupper($keys[$z]).".value = '".$registros_tabla[$i][strtoupper($keys[$z])]."'";
      }else{
         $html_tabla .= ";".$form_name.".".$table_name."__".strtoupper($keys[$z]).".value = '".$registros_tabla[$i][strtoupper($keys[$z])]."'";
      }
  }
  $html_tabla .= "\">";
//  $html_tabla .= ";disableButtons();".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\">";


}


function CrearRadioButtonlov(&$html_tabla,$table_name,$registros_tabla,$keys,$i,$valor_estilo){

	$html_tabla .= "<td class='detailedViewHeader'>";
	$html_tabla .= "<input type='radio' ";
	$html_tabla .= " name='Keys' value=\"";
	for($z=0;$z < count($keys);$z++){
		$html_tabla .= $i;
	}

      //  $html_tabla .= ";disableButtons();".$form_name.".action.value='".$command_showbyid."';".$form_name.".submit();\">";

	$html_tabla .= "\">";
	$html_tabla .= "</td>";
}



/*
* Crea campos ocultos segun la cantidad de llaves tenga la tabla en la base de
* datos.
*/
function CrearVariablesOcultas(&$html_tabla,$table_name,$llaves)
{
 $keys = explode(",",$llaves);

 for($i=0;$i < count($keys);$i++){
    $html_tabla .= "<input type='hidden' name='".$table_name."__".strtoupper($keys[$i])."'>";
 }
}




/*
* Crea Archivo con Arreglo de datos
* datos.
*/
function GenerarArchivo($titulos,$datos,$ruta,$archivo="data",$path)
{
      //print_r($datos);
      require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tableheader.lan";
      $archivo=$archivo.date("ymddms");
     //$fd = fopen ("/usr/local/apache2/htdocs".$ruta."/".$archivo.".xls", "w");
	  $fd = fopen ($ruta."/".$archivo.".xls", "w");
	  
	  if (!$fd)
	  {
		echo "No se pudo crear los archivos";
		return 100;
	  }
	  
	  if(isset($titulos) && $titulos !='')
	  {
	  	$m=explode(",",$titulos);
	  }
	  else
	  {
        //pasa los titulos a un vector
        $m = array_keys($datos[0]);
	  }
	  //print_r("total->".sizeof($datos[0]));
	  if(sizeof($m)>=sizeof($datos[0])){
	                 
		//$data[$i]["RUTA"]    ="report/".$_REQUEST["folder"]."/generados/".$archivo;
        //$html_tabla .= "<a  href='./".$m[4]."'>".$m[$j]."</a>";		
	  	$html_tabla="<a href='./report/".$path."/generados/".$archivo.".xls'><img src='web/images/menubar/save_f2.png' width=16 height=16>Descargar Reporte ".$archivo.".xls<a><p>";
	  	for($i=0;$i<sizeof($m);$i++){
		    if ($m[$i] != '.')
	  		  $cadena.=trim($Tableheaders[$m[$i]]).chr(9);
                        
		}
		$cadena.="\r\n";
		fwrite($fd,$cadena);
	  }
	  else{
	  	return "Hay diferencia de campos entre la consulta y el encabezado";
	  }


	  if (count($datos) >0)
      {
	  	for($j=0;$j<sizeof($datos);$j++)
        {
	  	 $cadena="";
	  	 while (list($key, $value) = each($datos[$j])) 
                       $cadena.=str_replace(chr(10), '',trim($value)).chr(9);
	      		                       
	    	
	    	 $cadena.="\r\n";
	    	 fwrite($fd,$cadena);
	    }

      }
         fclose ($fd);
		echo $html_tabla;

}


?>