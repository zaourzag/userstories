<?php
include 'config.php';
Class vragenLijst {
   private $db;
    private $username;
    private $password;  
    private $host;
    private $database;
function __construct() {
    global $dbuser, $dbpass, $dbhost, $dbname;
    $this->username = $dbuser;
    $this->password = $dbpass;
    $this->host = $dbhost;
    $this->database = $dbname;
    $this->db = new mysqli($this->host, $this->username, $this->password, $this->database);
    if ($this->db->connect_error) {
        die("Connection failed: " . $this->db->connect_error);
    }

}
public function Vragen() {
    $sql = "SELECT * FROM vragen ORDER BY id DESC";
    $result = $this->db->query($sql);
    if ($result->num_rows > 0) {
        $questionCount = 0;
        while($row = $result->fetch_assoc()) {
            $questionCount++;
            echo '<div class="card data-card mb-4 slide-in question-item" style="animation-delay: ' . ($questionCount * 0.1) . 's;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-user-circle text-primary me-2"></i>
                            ' . htmlspecialchars($row['naam']) . '
                        </h6>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Vraag #' . $row['id'] . '
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-question-circle me-1"></i>Vraag:
                                </h6>
                                <p class="mb-3">' . htmlspecialchars($row['vraag']) . '</p>
                            </div>
                            <div class="col-md-4">
                                
                              
                            </div>
                        </div>
                    </div>
                  </div>';
        }
    } else {
        echo '<div class="text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle fa-2x mb-3 text-primary"></i>
                    <h4>Nog geen vragen</h4>
                    <p>Er zijn nog geen vragen gesteld. Wees de eerste!</p>
                    <a href="vragenlijst.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Stel de eerste vraag
                    </a>
                </div>
              </div>';
    }
}
public function getConnection() {
    return $this->db;
}
public function bewerkVraag($id, $nieuweVraag,$code) {

    $stmt = $this->db->prepare("UPDATE vragen SET vraag = ?, code = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nieuweVraag, $code, $id);
    if ($stmt->execute()) {
        echo "Question updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
public function nieuweVraag($vraag, $naam, $email) {

    $stmt = $this->db->prepare("INSERT INTO vragen (vraag, naam, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $vraag, $naam, $email);
    if ($stmt->execute()) {
       return "successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
function __destruct() {
    $this->db->close();
}
}