<?php
include 'includes/header.php';
echo '<title>Vragenlijst - Stel je vraag</title>';  
echo '</head>';
include 'includes/navbar.php';
include 'utils.php';
$vragenlijst = new vragenLijst();  

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['vraag'], $_POST['naam'], $_POST['email'])) {
        $vraag = $_POST['vraag'];
        $naam = $_POST['naam'];
        $email = $_POST['email'];
        
        // klopt de input van de gebruiker
        if (empty($vraag) || empty($naam) || empty($email) ) {
            $error = 'Alle velden zijn verplicht.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Ongeldig e-mailadres.';
        } elseif (strlen($vraag) < 10) {
            $error = 'Uw vraag moet minimaal 10 karakters lang zijn.';
        } elseif (strlen($naam) < 2) {
            $error = 'Naam moet minimaal 2 karakters lang zijn.';
        } else {
            ;
            $result = $vragenlijst->nieuweVraag($vraag, $naam, $email);

            if ($result == 'successfully') {
                $success = true;
            } else {
                $error = 'Er is een fout opgetreden bij het opslaan.';
            }
        }
    } 
}

echo '<div id="wrapper">
<div class="container mt-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="text-center mb-4">
                <h1 class="display-5">
                    <i class="fas fa-edit text-primary me-2 float"></i>
                    Vragenlijst
                </h1>
                </div>';

if ($success) {
    echo '<div class="alert alert-success slide-in" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Bedankt!</strong> Uw vraag is succesvol toegevoegd.
          </div>';
}

if ($error) {
    echo '<div class="alert alert-danger slide-in" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Fout:</strong> ' . htmlspecialchars($error) . '
          </div>';
}

echo '<form method="POST" action="" id="questionForm" class="slide-in" style="animation-delay: 0.3s;">
    <div class="mb-3">
        <label for="vraag" class="form-label">
            <i class="fas fa-question-circle me-1"></i>Uw Vraag
        </label>
        <textarea class="form-control" id="vraag" name="vraag" rows="4" required 
                  placeholder="Stel hier uw vraag (minimaal 10 karakters)..."></textarea>
        <div class="invalid-feedback">Gelieve een vraag van minimaal 10 karakters in te voeren.</div>
        <div class="form-text"><span id="vraagCounter">0</span>/500 karakters</div>
    </div>
    <div class="mb-3">
        <label for="naam" class="form-label">
            <i class="fas fa-user me-1"></i>Naam
        </label>
        <input type="text" class="form-control" id="naam" name="naam" required 
               placeholder="Uw volledige naam">
        <div class="invalid-feedback">Gelieve uw naam in te voeren (minimaal 2 karakters).</div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="fas fa-envelope me-1"></i>E-mailadres
        </label>
        <input type="email" class="form-control" id="email" name="email" required 
               placeholder="uw.email@voorbeeld.com">
        <div class="invalid-feedback">Gelieve een geldig e-mailadres in te voeren.</div>
    </div>
  
    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg pulse">
            <i class="fas fa-paper-plane me-2"></i>Vraag Versturen
        </button>
        <div class="mt-3">
            <a href="gegevens.php" class="btn btn-outline-secondary">
                <i class="fas fa-list me-2"></i>Bekijk Alle Vragen
            </a>
        </div>
    </div>
</form>
        </div>
    </div>
</div>';

include 'includes/footer.php';
echo '</div>';

echo '<script>
if (!document.querySelector("link[href*=\"font-awesome\"]")) {
  const fontAwesome = document.createElement("link");
  fontAwesome.rel = "stylesheet";
  fontAwesome.href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";
  document.head.appendChild(fontAwesome);
}

document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("questionForm");
    const inputs = form.querySelectorAll("input, textarea");
    const vraagTextarea = document.getElementById("vraag");
    const vraagCounter = document.getElementById("vraagCounter");
    
    vraagTextarea.addEventListener("input", function() {
        const length = this.value.length;
        vraagCounter.textContent = length;
        
        if (length > 500) {
            vraagCounter.style.color = "#dc3545";
        } else if (length >= 450) {
            vraagCounter.style.color = "#fd7e14";
        } else {
            vraagCounter.style.color = "#6c757d";
        }
    });
    
    inputs.forEach(input => {
        input.addEventListener("blur", validateField);
        input.addEventListener("input", clearValidationOnInput);
    });
    
    function validateField(e) {
        const field = e.target;
        const value = field.value.trim();
        
        if (field.hasAttribute("required") && !value) {
            setFieldInvalid(field, "Dit veld is verplicht");
        } else if (field.type === "email" && value && !isValidEmail(value)) {
            setFieldInvalid(field, "Ongeldig e-mailadres");
        } else if (field.id === "vraag" && value && value.length < 10) {
            setFieldInvalid(field, "Vraag moet minimaal 10 karakters lang zijn");
        } else if (field.id === "naam" && value && value.length < 2) {
            setFieldInvalid(field, "Naam moet minimaal 2 karakters lang zijn");
        } else if (value) {
            setFieldValid(field);
        }
    }
    
    function clearValidationOnInput(e) {
        const field = e.target;
        if (field.value.trim()) {
            field.classList.remove("is-valid", "is-invalid");
        }
    }
    
    function setFieldValid(field) {
        field.classList.remove("is-invalid");
        field.classList.add("is-valid");
    }
    
    function setFieldInvalid(field, message) {
        field.classList.remove("is-valid");
        field.classList.add("is-invalid");
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains("invalid-feedback")) {
            feedback.textContent = message;
        }
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    form.addEventListener("submit", function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            validateField({target: input});
            if (input.classList.contains("is-invalid")) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first invalid field
            const firstInvalid = form.querySelector(".is-invalid");
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
                firstInvalid.focus();
            }
        }
    });
    
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active");
        }
    });
});
</script>';
?>

