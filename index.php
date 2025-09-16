<?php
include 'includes/header.php';
echo '<title>Vragenlijst - Home</title>';
echo '</head>';
include 'includes/navbar.php';
include 'utils.php';
$vragenlijst = new vragenLijst();  

echo '<div id="wrapper">
  <main class="container mt-5 flex-grow-1">
    <div class="row">
      <div class="col-12 text-center fade-in">        <h1 class="mb-4 display-4">
          <span class="float">💭</span> Welkom bij de Vragenlijst
        </h1>
        <p class="lead mb-5">Deel uw vragen en ontdek wat anderen vragen. Een platform voor kennis delen.</p>
        <div class="bounce-in" style="animation-delay: 0.5s;">
          <i class="fas fa-chevron-down text-primary" style="font-size: 2rem; animation: floating 2s ease-in-out infinite;"></i>
        </div>
      </div>
    </div>
    
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card slide-in" style="animation-delay: 0.2s;">
          <div class="card-body text-center">            <div class="mb-3">
              <i class="fas fa-question-circle text-primary float" style="font-size: 3rem;"></i>
            </div>
            <h5 class="card-title">Stel een Vraag</h5>
            <p class="card-text">Heeft u een vraag? Stel deze aan onze community en krijg antwoorden van experts.</p>
            <a href="vragenlijst.php" class="btn btn-primary pulse">
              <i class="fas fa-plus me-2"></i>Start Vragenlijst
            </a>
          </div>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card slide-in" style="animation-delay: 0.4s;">
          <div class="card-body text-center">            <div class="mb-3">
              <i class="fas fa-database text-success float" style="font-size: 3rem; animation-delay: 1s;"></i>
            </div>
            <h5 class="card-title">Bekijk Gegevens</h5>
            <p class="card-text">Ontdek alle gestelde vragen en antwoorden van andere gebruikers in onze database.</p>
            <a href="gegevens.php" class="btn btn-outline-primary">
              <i class="fas fa-eye me-2"></i>Bekijk Gegevens
            </a>
          </div>
        </div>
      </div>
    </div>
    
   
  </main>';

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
    const scrollIndicator = document.createElement("div");
    scrollIndicator.className = "scroll-indicator";
    document.body.prepend(scrollIndicator);
    
    window.addEventListener("scroll", function() {
        const scrollPercent = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
        scrollIndicator.style.transform = `scaleX(${scrollPercent / 100})`;
    });
    
    const backToTop = document.createElement("button");
    backToTop.className = "back-to-top";
    backToTop.innerHTML = "<i class=\"fas fa-arrow-up\"></i>";
    backToTop.setAttribute("aria-label", "Back to top");
    document.body.appendChild(backToTop);
    
    backToTop.addEventListener("click", function() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
    
    window.addEventListener("scroll", function() {
        if (window.scrollY > 300) {
            backToTop.classList.add("show");
        } else {
            backToTop.classList.remove("show");
        }
    });
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("fade-in");
                
                const children = entry.target.querySelectorAll(".card, .slide-in");
                children.forEach((child, index) => {
                    setTimeout(() => {
                        child.style.animationDelay = `${index * 0.1}s`;
                        child.classList.add("fade-in");
                    }, index * 100);
                });
            }
        });
    }, observerOptions);
    
    document.querySelectorAll(".card, .fade-in, .slide-in").forEach(element => {
        observer.observe(element);
    });
    
    document.querySelectorAll("a[href^=\"#\"]").forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                const offsetTop = target.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: "smooth"
                });
            }
        });
    });
    
    const currentPage = window.location.pathname.split("/").pop() || "index.php";
    document.querySelectorAll(".navbar-nav .nav-link").forEach(link => {
        const href = link.getAttribute("href");
        if (href === currentPage || (currentPage === "" && href === "index.php")) {
            link.classList.add("active");
        }
    });
    
    document.querySelectorAll(".card").forEach(card => {
        card.addEventListener("mouseenter", function() {
            this.style.transform = "translateY(-10px) scale(1.02)";
        });
        
        card.addEventListener("mouseleave", function() {
            this.style.transform = "translateY(0) scale(1)";
        });
    });
    
    if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
        document.documentElement.style.setProperty("--animation-duration", "0.3s");
    }
});
</script>';
?>