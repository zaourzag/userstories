<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
Class vragenLijst {
   private $db;
    private $database;
    private $username;
    private $password;
    private $host;
function __construct() {
    $this->username = $_ENV['DBUSER'];
    $this->password = $_ENV['DBPASS'];
    $this->host = $_ENV['DBHOST'];
    $this->database = $_ENV['DBNAME'];
    $this->db = new mysqli($this->host, $this->username, $this->password, $this->database);
    if ($this->db->connect_error) {
        die("Connection failed: " . $this->db->connect_error);
    }

}
public function Vragen() {
    $sql = "SELECT * FROM vragen ORDER BY id ASC";
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
                            <div class="col-md-4 d-flex justify-content-md-end align-items-start gap-2 mt-2 mt-md-0">
                                <a class="btn btn-sm btn-outline-primary" href="bewerk_vraag.php?id=' . (int)$row['id'] . '">
                                    <i class="fas fa-edit me-1"></i>Bewerken
                                </a>
                                <a class="btn btn-sm btn-outline-danger delete-link" href="verwijder_vraag.php?id=' . (int)$row['id'] . '" onclick="return confirm(\'Weet je zeker dat je deze vraag wilt verwijderen?\');">
                                    <i class="fas fa-trash me-1"></i>Verwijderen
                                </a>
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
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
public function nieuweVraag($vraag, $naam, $email, $code) {

    $stmt = $this->db->prepare("INSERT INTO vragen (vraag, naam, email, code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $vraag, $naam, $email, $code);
    if ($stmt->execute()) {
       return "successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
public function getVraagById(int $id): ?array {
    $stmt = $this->db->prepare("SELECT * FROM vragen WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

public function verwijderVraag(int $id): bool {
    $stmt = $this->db->prepare("DELETE FROM vragen WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

// Export helper: return array without 'code' for backup download
public function getVragenForBackupNoCode(): array {
    $rows = [];
    $res = $this->db->query("SELECT id, vraag, naam, email FROM vragen ORDER BY id ASC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        $res->free();
    }
    return $rows;
}

// Optional: keep the old file-based backup if still used elsewhere (but better use export_backup.php)
// public function backupVragen(...) { ... }

// Restore: accept backups without 'code' (generate a new one if missing)
public function restoreVragenFromJson(string $filePath, string $mode = 'append'): int {
    if (!is_file($filePath)) return 0;
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);
    if (!is_array($data)) return 0;

    if ($mode === 'replace') {
        $this->db->query("TRUNCATE TABLE vragen");
    }

    $stmt = $this->db->prepare("INSERT INTO vragen (vraag, naam, email, code) VALUES (?, ?, ?, ?)");
    $count = 0;
    foreach ($data as $row) {
        $vraag = isset($row['vraag']) ? (string)$row['vraag'] : '';
        $naam  = isset($row['naam']) ? (string)$row['naam'] : '';
        $email = isset($row['email']) ? (string)$row['email'] : '';
        $code  = isset($row['code']) && $row['code'] !== '' ? (string)$row['code'] : $this->generateRandomCode(8);
        $stmt->bind_param("ssss", $vraag, $naam, $email, $code);
        if ($stmt->execute()) $count++;
    }
    $stmt->close();
    return $count;
}

// Validate/update/delete with code (owner-only)
public function validateVraagCode(int $id, string $code): bool {
    $stmt = $this->db->prepare("SELECT 1 FROM vragen WHERE id = ? AND code = ?");
    $stmt->bind_param("is", $id, $code);
    $stmt->execute();
    $stmt->store_result();
    $ok = $stmt->num_rows === 1;
    $stmt->close();
    return $ok;
}
public function updateVraagIfCodeMatches(int $id, string $nieuweVraag, string $code): bool {
    $stmt = $this->db->prepare("UPDATE vragen SET vraag = ? WHERE id = ? AND code = ?");
    $stmt->bind_param("sis", $nieuweVraag, $id, $code);
    $stmt->execute();
    $rows = $stmt->affected_rows;
    $stmt->close();
    return $rows > 0;
}
public function verwijderVraagMetCode(int $id, string $code): bool {
    $stmt = $this->db->prepare("DELETE FROM vragen WHERE id = ? AND code = ?");
    $stmt->bind_param("is", $id, $code);
    $stmt->execute();
    $rows = $stmt->affected_rows;
    $stmt->close();
    return $rows > 0;
}

// Helper to generate codes when restoring
private function generateRandomCode(int $len = 8): string {
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789_-';
    $max = strlen($alphabet) - 1;
    $out = '';
    for ($i = 0; $i < $len; $i++) {
        $out .= $alphabet[random_int(0, $max)];
    }
    return $out;
}
function __destruct() {
    $this->db->close();
}
}