<?php

spl_autoload_register(function ($className): bool{
    
    $checkNamespace = explode('\\', $className);

    // If className contains a namespace, convert first letter to lowercase
    if(array_key_exists(1, $checkNamespace)){
        $className = lcfirst($className);
    }
    
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // If autoload find the class's file, require the file, else do nothing
    if (file_exists('src'. DIRECTORY_SEPARATOR . $className .'.php')) {
        require_once 'src'. DIRECTORY_SEPARATOR . $className .'.php';
        return true;
    }
    return false;
});