<?php
 
class Autoloader{
 
    public function __construct(){
        //LOADING COMPOSER
        if(file_exists(FCPATH . '/vendor/autoload.php')){
            include_once FCPATH . '/vendor/autoload.php';
        }
		
        //STANDARD AUTOLOADER
        spl_autoload_register(array($this, 'autoload'));
 
    }
 
    public function autoload($class){
        //PSR-0 autoloader
        $library_path = APPPATH . 'libraries/';
        $third_party_path = APPPATH . 'third_party/';

        $contracts_path = APPPATH. 'contracts/';
        $my_models_path = APPPATH. 'my_models/';
        $repositories_path = APPPATH. 'repositories/';
        $tools_path = APPPATH. 'tools/';
 
        //remove the first ns (\) since library_path already has it
 
        $class = ltrim($class, '\\');
        $file  = '';
        $namespace = '';
 
        if ($last_namespace_pos = strrpos($class, '\\')) {
 
            $namespace = substr($class, 0, $last_namespace_pos);
            $class = substr($class, $last_namespace_pos + 1);
            //replace all backslashes with DIRECTORY_SEPARATOR, it adds one more to the end
            $file = strtr($namespace, '\\', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
 
        }
        
        //replace all class names with (_) with DIRECTORY_SEPARATOR
        // $file .= strtr($class, '_', DIRECTORY_SEPARATOR); // ORIGINAL CODE
        $file = strtolower($class); // REPLACED CODE

        if(file_exists($library_path . $file . '.php')) {
            require_once($library_path . $file . '.php');
            return;
        }
        else if(file_exists($third_party_path . $file . '.php')) {
            require_once($third_party_path . $file . '.php');
            return;
        }
        else if(file_exists($contracts_path . $file . '.php')) {
            require_once($contracts_path . $file . '.php');
            return;
        }
        else if(file_exists($my_models_path . $file . '.php')) {
            require_once($my_models_path . $file . '.php');
            return;
        }
        else if(file_exists($repositories_path . $file . '.php')) {
            require_once($repositories_path . $file . '.php');
            return;
        }
        else if(file_exists($tools_path . $file . '.php')) {
            require_once($tools_path . $file . '.php');
            return;
        }
 
    }
 
}