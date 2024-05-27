<?php 

include "INCLUDES/menu.php";

?>


<body>
    <script src="JS/app.js"></script>
    <?php 
    
   if (isset($_GET["action"])) {
        $action_name = $_GET["action"];
        $action_name = str_replace(".", "", $action_name);
        $file = "PHP/" . $action_name . ".php";
        if (file_exists($file)) {
            include $file;
        }else{
            header("Location: " . get_base_url());
        }
    }elseif (isset($_GET["page"])) {
        $page_name = $_GET["page"];
        $page_name = str_replace(".", "", $page_name);
        $file = "PAGES/" . $page_name . ".php";
        if (file_exists($file)) {
            include $file;
        }else{
            header("Location: " . get_base_url());
        }
    }else{
        include "PAGES/list.php";
    }
    
    ?>
    <script src="https://kit.fontawesome.com/f1ce168fe5.js" crossorigin="anonymous"></script>
</body>
