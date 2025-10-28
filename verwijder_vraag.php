<?php
require 'vendor/autoload.php';
include 'utils.php';

$vragenlijst = new vragenLijst();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $code = trim($_POST['code'] ?? '');
    if ($id > 0 && $code !== '' && $vragenlijst->verwijderVraagMetCode($id, $code)) {
        header('Location: gegevens.php?deleted=1');
        exit;
    }
    header('Location: gegevens.php?deleted=0');
    exit;
}

// Fallback simple form if accessed via GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>
<!doctype html>
<html lang="nl">
<head><meta charset="utf-8"><title>Verwijderen bevestigen</title></head>
<body>
    <form method="post" style="max-width:420px;margin:40px auto;font-family:sans-serif;">
        <h3>Verwijderen bevestigen</h3>
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <div style="margin:12px 0">
            <label>Controlecode</label>
            <input name="code" required class="form-control" style="width:100%;padding:8px">
        </div>
        <button type="submit" class="btn btn-danger">Definitief verwijderen</button>
        <a href="gegevens.php" class="btn btn-secondary">Annuleren</a>
    </form>
</body>
</html>