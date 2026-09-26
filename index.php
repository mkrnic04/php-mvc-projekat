<?php 

include "./config/conn.php";
require_once "./config/common.php";
require_once(BASE_DIR . "models/functions.php");


require_once "views/fixed/header.php";

$page = get("page");

//za logove
logAccess($page ? $page : DEFAULT_PAGE);

if(!$page) {
    require_once("views/pages/" . DEFAULT_PAGE . ".php");
} else {
    $page = strtolower($page);

    if(!file_exists("views/pages/" . $page . ".php")) {
        require_once("views/pages/404.php");
    } else {
        require_once("views/pages/$page.php");
    }
}



require_once "views/fixed/footer.php";

