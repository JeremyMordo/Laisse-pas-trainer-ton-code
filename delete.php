<?php

if (isset($_GET['delete']) && $_GET['delete'] <> ''){
    if (file_exists("uploads/".$_GET['delete'])){
        unlink("uploads/".$_GET['delete']);
        header('location:./upload.php');    }
}