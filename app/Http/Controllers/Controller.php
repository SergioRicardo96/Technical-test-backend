<?php

namespace App\Http\Controllers;

use Exception;

class Controller
{
    public function view($route, $data = [])
    {
        // destructure array
        extract($data);

        $route = str_replace('.', '/', $route);
        $filePath = dirname(__DIR__, 3) . "/resources/views/{$route}.php";

        if(file_exists($filePath)){
            // Start output buffering.
            ob_start();
            try {
                include $filePath;
                // Get the output buffer content and clean it.
                $content = ob_get_clean();
            } catch (Exception $e) {
                // Clean the buffer if something goes wrong.
                ob_end_clean();
                return "Error loading the view: " . $e->getMessage();
            }

            return $content;
        }else{
            return "The file does not exist";
        }
    }

    public function redirect($url){
        header("Location: {$url}");
        exit;
    }

    protected function authorize($policyClass, $action, $user, $model)
    {
        if (!class_exists($policyClass)) {
            throw new Exception("Policy class $policyClass does not exist.");
        }
        
        $policyInstance = new $policyClass();
        
        if (!method_exists($policyInstance, $action)) {
            throw new Exception("Action $action not defined in policy.");
        }
        
        $authorized = call_user_func_array([$policyInstance, $action], [$user, $model]);

        if (!$authorized) {
            echo '403 Forbidden: You are not authorized to perform this action.';
            exit;
        }
        
        return true;
    }

}