@echo off
REM Ejecuta esta tarea cada minuto
schtasks /Create /TN XAMPP /TR "curl http://localhost/GeneracionXml/applications/launcher/index.php?action=CmdGenXML" > "C:/xampp/htdocs/GeneracionXml/applications/log.txt" /SC MINUTE /MO 1

