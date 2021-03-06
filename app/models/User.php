<?php

 class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Register User 
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        // Bind Values
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':password', $data['password']);

        // Execute to check if execution went ok
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE :email = email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        // verify password
        // password unhashing
        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Find user by email  
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE :email = email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get user id by email
    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE :email = email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        return $row;
    }

    //Get user by id
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    // show user profile

    // Update user info when editing personal profile
    public function updateUsers($data) {
        $this->db->query('UPDATE users SET name = :name, email = :email WHERE id = :id');
        // Bind Values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':name', $data['name']);

        // Execute to check if execution went ok
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
 }