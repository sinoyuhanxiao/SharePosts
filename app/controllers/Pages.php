<?php

class Pages extends Controller {
    public function __construct() {

    }

    public function index() {
        if(isLoggedIn()) {
            redirect("posts");
        }
        $data = [
            'title' => 'SharePosts',
            'description' => 'Simple social network platform created by Erik Yu'
        ];

        $this->view('pages/index', $data);
    }

    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'This is the simple application built by PHP for sharing blog posts with other users'
        ];
        $this->view('pages/about', $data);
    }
}