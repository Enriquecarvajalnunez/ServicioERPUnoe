@echo off
REM Ejecuta esta tarea cada minuto
schtask /Create /TN XAMPP /TR "C:/xampp/php/php.exe C:/xampp/htdocs/GeneracionXml/applications/applications/launcher/?action=CmdGenXML" /SC MINUTE /MO 1