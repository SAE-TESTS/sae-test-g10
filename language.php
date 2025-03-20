<?php
if(!isset($_SESSION)){
        session_start();
        }
 
if (isset($_POST['language'])) {
    $_SESSION["language"] = $_POST['language'];
    header("Location: index.php");

} 
    if (isset($_SESSION["language"])) {
    switch ($_SESSION["language"]) {
        case 'fr':
            require "languages/language_fr.php" ;
            break;

        case 'en':
            require "languages/language_en.php" ;
            break;

        case 'es':
            require "languages/language_es.php" ;
            break;

        case 'al':
            require "languages/language_al.php" ;
            break;
    
        case 'ru':
            require "languages/language_ru.php" ;
            break;
        case 'ch':
            require "languages/language_ch.php" ;
            break;
        
        default:
        require "languages/language_fr.php" ;
            break;
        }
    }else {
        require "languages/language_fr.php" ;
 
    }

?>