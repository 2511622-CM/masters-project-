<!--
Created by Courtney Morrison: 02-07-2026
Last edit 02-08-2026

Landing Page
-->
<?php
include ('configv3.php');
include ('components.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://use.typekit.net/zpe4sjr.css">
    <link rel="stylesheet" href ="pixelpantry.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="PixelPantryFavicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pixel Pantry</title>
</head>
<header>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="home.php"><img src="PixelPantryMainLogo.png" alt="PixelPantryLogo" class="logo_mini"/></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
          <a class="nav-link" href="#">Your Account</a>
          <a class="nav-link" href="#">Login</a>
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
              <ul class="dropdown-menu">
                <?php category_dropdown($conn); ?>
              </ul>
          </li>
        </div>
      </div>
    </div>
  </nav>
</header>

<body>
  <h2 class="h2">Efficient Online Shopping testing 123</h2>

  <!--Card Layout -->
  <main class="container my-4">
    <section class="mb-5">
      <h2 class="mb-3"> Browse Categories</h2>
      <?php category_cards($conn); ?>
    </section>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

<footer class="footer">
    <p>Created in 2026 by Courtney Morrison</p>
</footer>
</html>

