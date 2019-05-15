<?php
    if(isset($_GET['url']) && trim($_GET['url']) != "")
    {
        header("Location: ".$_GET['url']);
        exit();
    }

    header("Location: index.html");
    exit();
?>