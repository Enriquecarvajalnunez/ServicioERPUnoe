<?php
/*
 * Smarty plugin
 * --------------------------------------------------------------------
 * Type:     function
 * Name:     spyro_info
 * Version:  1.0
 * Date:     Sep 20, 2005
 * Author:   Spyro Solutions 
 * Purpose:  Imprime una tabla con info de la aplicacion si la configuracion esta en modo desarrollo, si
 * 			 esta en modo produccion no imprime nada.	
 * Input:
 *
 *
 * Examples : {ingravity_info}
 *
 * --------------------------------------------------------------------
 */

function smarty_function_spyro_info($params, &$smarty)
{
    extract($params);
	
	

    //Valida el modo de la aplicacion  !Application::getMode()
	$i=0;
    if(!Application::getMode()){

		//obtiene la info de los plugins del template
	    $array_smarty_plugins = $smarty->getPluginsDir();
		//print_r("<pre>");
		//print_r($smarty);
		//print_r("</pre>");
		//print_r(time());
		$timepoSession=(time() - $_SESSION['LAST_ACTIVITY']);

	    $html_result = '<div class="accordion-ui" id="accordion-basic2">
							<div class="card active">
								    <div class="card-header h6 dropdown-toggle" role="button" data-toggle="collapse" data-target="#target1" aria-expanded="false">
									   Info Server
									</div>
									<div id="target1" class="collapse show1" data-parent="#accordion-basic2">
												
										<style type="text/css">
										.devep_t {border-collapse: collapse;}
										.devep_e {background-color: #4080be; font-weight: bold; color: #000000; border: 1px solid #000000; font-size: 100%; vertical-align: baseline;}
										.devep_h {background-color: #4080be; font-weight: bold; color: #000000; border: 1px solid #000000; font-size: 100%; vertical-align: baseline;}
										.devep_v {background-color: #cccccc; color: #000000; border: 1px solid #000000; font-size: 100%; vertical-align: baseline;}
										</style>
										  <table class="t" border="0" cellpadding="3" width="100%" >
											<tr> 
											  <td colspan="2" width="100%" class="devep_h"><div align="center">SpyroInfo</div></td>
											</tr>
											<tr> 
											  <td width="50%" class="devep_e">Template</td>
											  <td width="50%" class="devep_e">Command</td>
											</tr>
											<tr> 
											  <td class="devep_v">' . $smarty->source->resource . '<br> Tiempo de Session :'.$timepoSession.'</td>
											  <td class="devep_v">' . $_REQUEST["action"] . '</td>
											</tr>
											<tr> 
											  <td class="devep_e">$_REQUEST</td>
											  <td class="devep_e">$_SESSION</td>
											</tr>
											<tr> 
											  <td class="devep_v"><pre>'. print_r($_REQUEST,true) . '</pre></td>
											  <td class="devep_v"><pre>'. print_r($_SESSION,true) . '</pre></td>
											</tr>
										  </table>
								    </div>
							</div>	
						</div>		  
		  ';    

	    print $html_result;
    }  
}

?>
