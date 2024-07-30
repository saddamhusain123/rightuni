<?php
class DbSessionHandler implements SessionHandlerInterface {
    private $conn;
    private $table;

    public function __construct($conn, $table = 'sessions') {
        $this->conn = $conn;
        $this->table = $table;
    }

    public function open($savePath, $sessionName) {
        // No action necessary since using a database connection
        return true;
    }

    public function close() {
        // Close the database connection
        return $this->conn->close();
    }

    public function read($id) {
        $sql = "SELECT data FROM $this->table WHERE session_id = ? LIMIT 1";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('s', $id);
            if ($stmt->execute()) {
                $stmt->bind_result($data);
                if ($stmt->fetch()) {
                    return $data;
                }
            }
            $stmt->close();
        }
        return '';
    }

    public function write($register_id, $data) {
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
        $gmail = isset($_SESSION['email']) ? $_SESSION['email'] : '';
        $status = 'active';
        
        $sql = "REPLACE INTO $this->table (session_id, register_id, username, gmail, status, data, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('sissss', $register_id, $username, $gmail, $status, $data);
            return $stmt->execute();
        }
        return false;
    }

    public function destroy($id) {
        $sql = "DELETE FROM $this->table WHERE session_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('s', $id);
            return $stmt->execute();
        }
        return false;
    }

    public function gc($maxlifetime) {
        $sql = "DELETE FROM $this->table WHERE created_at < DATE_SUB(NOW(), INTERVAL ? SECOND)";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('i', $maxlifetime);
            return $stmt->execute();
        }
        return false;
    }
}
?>
