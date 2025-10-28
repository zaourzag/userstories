<?php
include 'includes/header.php';
echo '<title>Vragenlijst - Backup & Restore</title>';
echo '</head>';
include 'includes/navbar.php';
include 'utils.php';
require 'vendor/autoload.php';

$vragenlijst = new vragenLijst();
$msg = null;
$type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['restore'])) {
        if (!empty($_FILES['backup_file']['tmp_name'])) {
            $tmp = $_FILES['backup_file']['tmp_name'];
            $mode = ($_POST['mode'] ?? 'append') === 'replace' ? 'replace' : 'append';
            $count = $vragenlijst->restoreVragenFromJson($tmp, $mode);
            $msg = "Herstel voltooid. $count records toegevoegd.";
            $type = 'success';
        } else {
            $msg = 'Geen bestand gekozen.';
            $type = 'warning';
        }
    }
}
?>
<div class="container mt-5">
    <h1 class="mb-4"><i class="fas fa-database text-primary me-2"></i>Backup en Herstel</h1>

    <?php if ($msg): ?>
        <div class="alert alert-<?= htmlspecialchars($type) ?> fade-in"><?= $msg ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4 slide-in">
                <h5 class="mb-3"><i class="fas fa-download me-2"></i>Maak backup (JSON)</h5>
                <p class="text-muted mb-3">De backup wordt direct gedownload en niet op de server opgeslagen. De controlecodes worden niet opgenomen.</p>
                <a href="export_backup.php" id="btnBackup" class="btn btn-primary">
                    <i class="fas fa-file-export me-1"></i>Backup downloaden
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 slide-in" style="animation-delay:.1s;">
                <h5 class="mb-3"><i class="fas fa-upload me-2"></i>Herstel vanuit backup</h5>
                <form lang="nl" method="post" enctype="multipart/form-data">
                    <div class="mb-3" lang="nl">
                        <!-- Custom file chooser in Dutch (browser default "Choose file"/"No file chosen" cannot be changed) -->
                        <label for="upload" class="btn btn-outline-secondary me-2" role="button">Bestand kiezen</label>
                        <span id="upload-name" class="text-muted">Geen bestand gekozen</span>
                        <input type="file" lang="nl" name="backup_file" class="form-control d-none" id="upload" accept=".json" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Modus</label>
                        <select name="mode" class="form-select">
                            <option value="append">Toevoegen aan bestaande data</option>
                            <option value="replace">Vervangen (leeg tabel en importeer)</option>
                        </select>
                    </div>
                    <button type="submit" name="restore" class="btn btn-warning">
                        <i class="fas fa-file-import me-1"></i>Herstellen
                    </button>
                </form>

                <script>
                // Update visible filename text in Dutch
                // (function () {
                //   const input = document.getElementById('upload');
                //   const nameEl = document.getElementById('upload-name');
                //   if (!input || !nameEl) return;
                //   input.addEventListener('change', function () {
                //     const fileName = (input.files && input.files.length) ? input.files[0].name : 'Geen bestand gekozen';
                //     nameEl.textContent = fileName;
                //   });
                // })();
                </script>
                <small class="text-muted d-block mt-2">Ontbreekt een controlecode in de backup, dan wordt er een nieuwe code gegenereerd.</small>
            </div>
        </div>
    </div>
</div>
<script>
// Button loading animation when starting download
document.addEventListener("DOMContentLoaded", function () {
  const btn = document.getElementById("btnBackup");
  if (btn) {
    btn.addEventListener("click", function () {
      btn.classList.add("loading");
      setTimeout(() => btn.classList.remove("loading"), 1800);
    });
  }

  // Auto-dismiss restore alerts
  document.querySelectorAll(".alert").forEach(alert => {
    setTimeout(() => {
      alert.classList.add("fade-out");
      alert.addEventListener("animationend", () => alert.remove(), { once: true });
    }, 2500);
  });
});
</script>
<?php include 'includes/footer.php'; ?>