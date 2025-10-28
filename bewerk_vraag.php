<?php
include 'includes/header.php';
echo '<title>Vragenlijst - Bewerken</title>';
echo '</head>';
include 'includes/navbar.php';
include 'utils.php';
require 'vendor/autoload.php';
$vragenlijst = new vragenLijst();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $vraag = trim($_POST['vraag'] ?? '');
    $code = trim($_POST['code'] ?? '');
    if ($id > 0 && $vraag !== '' && $code !== '') {
        if ($vragenlijst->updateVraagIfCodeMatches($id, $vraag, $code)) {
            header('Location: gegevens.php?updated=1');
            exit;
        } else {
            $error = 'Code onjuist of bijwerken mislukt.';
        }
    } else {
        $error = 'Ongeldige invoer of ontbrekende code.';
    }
}

// Load question
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['id'] ?? 0);
$vraag = $id ? $vragenlijst->getVraagById($id) : null;
?>
<div class="container mt-5 fade-in">
    <h1 class="mb-4"><i class="fas fa-edit text-primary me-2"></i>Vraag bewerken</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger slide-in"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!$vraag): ?>
        <div class="alert alert-warning">Vraag niet gevonden.</div>
        <a class="btn btn-secondary mt-2" href="gegevens.php">Terug</a>
    <?php else: ?>
        <form method="post" class="card p-4 slide-in" style="animation-delay: .1s;">
            <input type="hidden" name="id" value="<?= (int)$vraag['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Naam</label>
                <input class="form-control" value="<?= htmlspecialchars($vraag['naam']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" value="<?= htmlspecialchars($vraag['email']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Vraag</label>
                <textarea name="vraag" class="form-control" rows="4" required><?= htmlspecialchars($vraag['vraag']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Controlecode (verplicht)</label>
                <input name="code" class="form-control" placeholder="Voer uw controlecode in" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Opslaan
                </button>
                <a href="gegevens.php" class="btn btn-secondary">Annuleren</a>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
