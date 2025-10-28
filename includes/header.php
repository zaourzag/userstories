<?php
echo '
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
      .fade-in { animation: fadeIn .5s ease-out both; }
      .slide-in { animation: slideIn .5s ease-out both; }
      .fade-out { animation: fadeOut .4s ease-in forwards; }
      .pulse { animation: pulse 1.2s ease-in-out infinite; }

      .highlight-success { animation: flashSuccess 1.2s ease-out 1; }
      .highlight-danger { animation: flashDanger 1.2s ease-out 1; }

      .btn.loading { position: relative; pointer-events: none; opacity: .85; }
      .btn.loading::after {
        content: "";
        position: absolute; right: 12px; top: 50%;
        width: 16px; height: 16px; margin-top: -8px;
        border: 2px solid rgba(255,255,255,.6);
        border-left-color: transparent; border-radius: 50%;
        animation: spin .8s linear infinite;
      }

      @keyframes fadeIn { from {opacity:0; transform: translateY(6px)} to {opacity:1; transform:none} }
      @keyframes slideIn { from {opacity:0; transform: translateY(-8px)} to {opacity:1; transform:none} }
      @keyframes fadeOut { to {opacity:0; transform: translateY(-6px)} }
      @keyframes pulse { 0%,100% { transform: scale(1) } 50% { transform: scale(1.03) } }
      @keyframes spin { to { transform: rotate(360deg) } }

      @keyframes flashSuccess {
        0% { box-shadow: 0 0 0 0 rgba(25,135,84,.0) }
        30% { box-shadow: 0 0 0 8px rgba(25,135,84,.18) }
        100% { box-shadow: 0 0 0 0 rgba(25,135,84,.0) }
      }
      @keyframes flashDanger {
        0% { box-shadow: 0 0 0 0 rgba(220,53,69,.0) }
        30% { box-shadow: 0 0 0 8px rgba(220,53,69,.18) }
        100% { box-shadow: 0 0 0 0 rgba(220,53,69,.0) }
      }

      @media (prefers-reduced-motion: reduce) {
        .fade-in, .slide-in, .fade-out, .pulse,
        .highlight-success, .highlight-danger, .btn.loading::after {
          animation: none !important;
        }
      }
    </style>
';
?>