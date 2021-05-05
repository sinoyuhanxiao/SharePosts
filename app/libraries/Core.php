<?php

// App Core Class
// Creates URL & Loads core controller
// URL FORMAT - /controller/method/params

class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'Index';
    protected $params = [];

    public function __construct() {
        // print_r($this->getUrl());

        $url = $this->getUrl(); 
        // print_r($url);
        // echo '<br>';       
        if(file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->currentController = $url[0];
            //unset 0 Index
            unset($url[0]); // array 0 element disappear but 1 and 2 still exist
        }

        require_once '../app/controllers/' . $this->currentController . '.php';
 
        $this->currentController = new $this->currentController;

        // check for the second part of the url
        if(isset($url[1])) {
            //check if the method exists in the controller
            if(method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}