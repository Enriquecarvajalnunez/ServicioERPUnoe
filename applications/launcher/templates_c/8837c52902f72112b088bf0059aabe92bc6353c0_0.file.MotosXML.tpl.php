<?php
/* Smarty version 3.1.34-dev-7, created on 2020-05-01 20:46:50
  from 'C:\xampp\htdocs\GeneracionXml\applications\launcher\web\html\MotosXML.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.34-dev-7',
  'unifunc' => 'content_5eac6e9a9bede1_19697994',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8837c52902f72112b088bf0059aabe92bc6353c0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\GeneracionXml\\applications\\launcher\\web\\html\\MotosXML.tpl',
      1 => 1588358667,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5eac6e9a9bede1_19697994 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\GeneracionXml\\applications\\launcher\\web\\plugins\\function.consult_table_XML.php','function'=>'smarty_function_consult_table_XML',),));
echo smarty_function_consult_table_XML(array('table_name'=>"vw_cebes_erp",'ruta'=>"xmls",'file_name'=>"vw_cebes_erp.xml"),$_smarty_tpl);
}
}
