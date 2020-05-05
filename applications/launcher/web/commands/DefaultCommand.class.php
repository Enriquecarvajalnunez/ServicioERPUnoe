<?php


require_once "WebRequest.class.php";
//require_once "Response.class.php";
//class DefaultCommand extends Response{

class DefaultCommand {
    function execute() {

        //$this ->setHttpHeaders(200); //OK
        return "success";
    }
}

?>

