<?php
include 'includes/header.php';
echo '<title>Vragenlijst - Gegevens</title>';
echo '</head>';
include 'includes/navbar.php';
include 'utils.php';
$vragenlijst = new vragenLijst();  

echo '<div id="wrapper">
<div class="container mt-5 fade-in">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-5">
                <i class="fas fa-database text-primary me-2"></i>
                Alle Gestelde Vragen
            </h1>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" id="searchInput" placeholder="Zoek in vragen...">
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <a href="vragenlijst.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nieuwe Vraag Stellen
            </a>
        </div>
    </div>
    
    <div class="row" id="vraagcontainer">';

;


    echo '<div class="col-12">';
    echo '<div id="Vragen">';
    
    $vragenlijst->Vragen();
    
    echo '</div>';
    echo '</div>';


echo '</div>
</div>';

include 'includes/footer.php';
echo '</div>';

echo '<script>
// Add Font Awesome for icons
if (!document.querySelector("link[href*=\"font-awesome\"]")) {
  const fontAwesome = document.createElement("link");
  fontAwesome.rel = "stylesheet";
  fontAwesome.href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";
  document.head.appendChild(fontAwesome);
}

document.addEventListener("DOMContentLoaded", function() {
    // Search functionality
    const searchInput = document.getElementById("searchInput");
    const questionItems = document.querySelectorAll(".question-item");
    
    if (searchInput && questionItems.length > 0) {
        searchInput.addEventListener("input", function() {
            const searchTerm = this.value.toLowerCase();
            
            questionItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = "block";
                    item.classList.add("fade-in");
                } else {
                    item.style.display = "none";
                }
            });
        });
    }
    
    // Add active navigation highlighting
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        if (link.getAttribute("href") === currentPage) {
            link.classList.add("active");
        }
    });
    
    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("fade-in");
            }
        });
    }, observerOptions);
    
    document.querySelectorAll(".card").forEach(card => {
        observer.observe(card);
    });
});
</script>';
?>