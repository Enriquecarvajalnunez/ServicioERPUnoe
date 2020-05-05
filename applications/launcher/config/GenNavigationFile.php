<html>
<head>
<head>
       <title>Save Navigation Configuration File</title>
</head>
<body>
<h2>Save Navigation Configuration File</h2>
<hr>

<?php

//Esto es para independizar librerias del include_path de PHP
//Se afecta el Application.class y el Serializer.php
global $saveconfiguration;
$saveconfiguration = "S";

require_once "config.inc.php";
require_once "Serializer.class.php";

$Navigation_config = array(
    'default_action' => 'DefaultCommand', 
    'error_view' => 'CmdDefaultError',
    'commands' => array(

    	//Comando por defecto de la aplicacion sino se invoca el action en el REQUEST
        'DefaultCommand' => array(
            'class' => 'DefaultCommand',
            'validated' => 'false',
            'views' => array(
                'success' => array(
                    'view' => 'Form_Wellcome.tpl',
                    'redirect' => 0
                )
            )
        ),
        'CmdDefaultError' => array(
            'class' => 'CmdDefaultError',
            'validated' => 'false',
            'desc' => 'Cargar Forma Error',
            'views' => array(
                'success' => array(
                    'view' => 'Form_Error.tpl',
                    'redirect' => 0
                )
            )
        ),
        'CmdGenXML' => array(
            'class' => 'CmdGenXML',
            'validated' => 'false',
            'desc' => 'Cargar Forma XML',
            'views' => array(
                'success' => array(
                    'view' => 'MotosXML.tpl',
                    'redirect' => 0
                )
            )
        )

	) // Fin arreglo de comandos	

); //Fin Arreglo Navigation_config

echo "<pre>";
print_r($Navigation_config);
echo "</pre>";

$result = Serializer::save($Navigation_config, 'web.conf.data');

if (@PEAR::isError($result)) {
    echo "Error creating configuration file";
}

?>
</body>
</html>
