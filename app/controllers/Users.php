<?php

class Users extends Controller{
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function register() {
        // Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form

            // Sanitize Post Data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

             // init data
             $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '', 
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check if email already existed in the database
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate Name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated

                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if ($this->userModel->register($data)) {
                    flash('register_success', 'Register Succeeded, you can log in.');
                    redirect('users/login'); //after registration redirect to the login page
                } else {
                    die('Something went wrong');
                }

            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }


        } else {
            // init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // load view
            $this->view('users/register', $data);
        }

    }

    public function login() {
        // Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form

            // Sanitize Post Data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

             // init data
             $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            // Validate Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Check for user/email
            if (!$this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'No User found'; // user not found
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }

        } else {
            // init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // load view
            $this->view('users/login', $data);
        }

    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }

    public function isLoggedIn(){
        if(isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function profile() {
        $this->view('users/profile');
    }

    public function editProfilePage() {
        $data = [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'name_err' => '', 
            'email_err' => ''
        ];

        $this->view('users/editProfilePage', $data);
    }

    public function editProfile() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form

            // Sanitize Post Data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // init data
            $data = [
                'id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'name_err' => '', 
                'email_err' => ''
            ];

            // Validate Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Since your user email has already existed in the database because it is your email, this case should be excluded.
                $emailExists = $this->userModel->findUserByEmail($data['email']);
                $nameNotMatch = $this->userModel->getUserByEmail($data['email']);
                $nameNotMatch = $nameNotMatch->id != $_SESSION['user_id'];
                // Check if email already existed in the database and the email is not your own email (since this is for editing, email can remain the same)
                if ($emailExists && $nameNotMatch) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate Name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err'])) {
                // Validated
                if($this->userModel->updateUsers($data)) {
                    $this->updateUserSession($data);
                    flash('user_message', 'User Profile Updated');
                    redirect('users/profile');
                } else {
                    die("Oops, Something went wrong");
                }
            } else {
                // Load view with errors
                $this->view('users/editProfilePage', $data);
            }
        
        } else {
            // init data
            $data = [
                'id' => '',
                'name' => '',
                'email' => '',
                'name_err' => '', 
                'email_err' => ''
            ];

            // load view
            die('Something went wrong');
        }
    }

    // coordinate with update user profile
    public function updateUserSession($data) {
        $_SESSION['user_name'] = $data['name'];
        $_SESSION['user_email'] = $data['email'];
    }

}