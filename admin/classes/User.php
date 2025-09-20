<?php  

require_once 'Database.php';
/**
 * Class for handling User-related operations.
 * Inherits CRUD methods from the Database class.
 */
class User extends Database {

    /**
     * Starts a new session if one isn't already active.
     */
    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function usernameExists($username) {
        $sql = "SELECT COUNT(*) as username_count FROM school_publication_users WHERE username = ?";
        $count = $this->executeQuerySingle($sql, [$username]);
        if ($count['username_count'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    

    public function registerUser($username, $email, $password, $is_admin = true) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO school_publication_users (username, email, password, is_admin) VALUES (?, ?, ?, ?)";
        try {
            $this->executeNonQuery($sql, [$username, $email, $hashed_password, (int)$is_admin]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function loginUser($email, $password) {
        $sql = "SELECT user_id, username, password, is_admin FROM school_publication_users WHERE email = ?";
        $user = $this->executeQuerySingle($sql, [$email]);

        if ($user && password_verify($password, $user['password'])) {
            $this->startSession();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = (bool)$user['is_admin'];
            return true;
        }
        return false;
    }


    public function isLoggedIn() {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }


    public function isAdmin() {
        $this->startSession();
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }

    /**
     * Logs out the current user.
     */
    public function logout() {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    public function getUsers($id = null) {
        if ($id) {
            $sql = "SELECT user_id, username, email, is_admin FROM school_publication_users WHERE user_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT user_id, username, email, is_admin FROM school_publication_users";
        return $this->executeQuery($sql);
    }

    public function updateUser($id, $username, $email, $is_admin) {
        $sql = "UPDATE school_publication_users SET username = ?, email = ?, is_admin = ? WHERE user_id = ?";
        return $this->executeNonQuery($sql, [$username, $email, (int)$is_admin, $id]);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM school_publication_users WHERE user_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}

?>