<!--
Created by Courtney Morrison: 26-07-2026
Last edit 02-07-2026

Categories
-->
<?php
include ('configv3.php');
include ('components.php');

$category_name = isset($_GET['category']) ? trim($_GET['category']) : '';

if (empty($category_name)) {
    header("Location: home.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE LOWER(category) = LOWER(?)");
$stmt->bind_param("s", $category_name);
$stmt->execute();
$result = $stmt->get_result(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($category_name) . " - Pixel Pantry"; ?></title>
    <link rel="stylesheet" href="https://use.typekit.net/zpe4sjr.css">
    <link rel="stylesheet" href ="pixelpantry.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="PixelPantryFavicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
  <h1>Welcome to Pixel Pantry</h1>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb justify-content-center">

    <li class="breadcrumb-item">
        <a href="home.php">Home</a>
    </li>

    <li class="breadcrumb-item"> 
         <?php echo htmlspecialchars($category_name); ?>
    </li>
  </ol>
</nav>
</header>

<body>
 <main class="container my-4">
  <form method="GET" action="category.php">
      <?php extra_dropdowns($conn); ?>
  </form>

  <h2 class="mt-4 mb-3">Products in <?php echo htmlspecialchars($category_name); ?></h2>
<!--Outputtting the table of results-->
      <table class="table table-hover" border="1" cellpadding="8" cellspacing="0">
          <thead>
              <tr>
                  <th>Product Image</th>
                  <th>Product Name</th>
                  <th>Product Category</th>
                  <th>Product Type</th>
                  <th>Price</th>
                  <th>Buy</th>
              </tr>
          </thead>
          <tbody>
              <?php table_output($conn); ?>
          </tbody>
      </table>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

<footer class="footer">
    <p>Created in 2026 by Courtney Morrison</p>
</footer>
</html>

