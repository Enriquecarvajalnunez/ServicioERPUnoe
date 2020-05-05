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

function smarty_function_consult_table_bootstrapbasic($params, &$smarty)
{

  extract($params);
  require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Toolsbar.lan";
  $html_tabla = '';
  $html_tabla .= '
	  <!--===================================================-->
	  <!--Begin Data Table hhh-->  
      <div class="card card-style-1">
        <div class="card-body">  
          <table id="tableDataServer" class="table table-striped table-bordered table-sm dt-responsive display nowrap w-100">
 
  ';
     CrearEncabezadoTabla2($html_tabla,$titulos);
  //Fin de Tabla
  $html_tabla .= '
		  </table> <!-- End Table -->

	    </div> <!-- card-body -->
	  </div>
	<!--===================================================-->
	<!--End Data Table-->
					
  ';

  CrearVariablesOcultas2($html_tabla,$table_name,$llaves);
  $html_result = $html_tabla;

  print $html_result;

}

/*
* Crea el encabezado de la tabla, con el valor de la variable $ titulos,
* si titulos no esta setiado por defecto el encabezado de la tabla
* son los nombre de los campos de la tabla en la base de datos
*/
function CrearEncabezadoTabla2(&$html_tabla,$titulos){
	
    require Application::getLanguageDirectory().'/'.Application::getLanguage()."/Tableheader.lan";
    //si la variable titulos esta setiada crea un vector con los titulos
    //sino crea un vector con los indices del vector $registros_tabla
    $m = explode(",",$titulos);
    
    //print_r($m);
    // crea el encabezado de la tabla
    $html_tabla .= '
            <thead class="thead-primary">';
				   

    $html_tabla .= "
			   <tr>
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



/*
* Crea campos ocultos segun la cantidad de llaves tenga la tabla en la base de
* datos.
*/
function CrearVariablesOcultas2(&$html_tabla,$table_name,$llaves)
{
 $keys = explode(",",$llaves);

 for($i=0;$i < count($keys);$i++){
    $html_tabla .= "<input type='hidden' name='".$table_name."__".strtoupper($keys[$i])."'>";
 }
}






?>