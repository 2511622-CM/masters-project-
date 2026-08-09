<!--
Created by Courtney Morrison: 20-07-2026
Last edit 26-07-2026

Products
-->
<?php
include ('configv3.php');
include ('components.php');

$category_name = trim($_GET['category'] ?? '');
$product_name  = trim($_GET['product_name'] ?? '');

$stmt = $conn->prepare("SELECT * FROM products WHERE LOWER(product_name) = LOWER(?) AND LOWER(category) = LOWER(?)");
//Catch the error before a crash
if ($stmt === false) {
    die("Database statement failed" . $conn->error);
}

$stmt->bind_param("ss", $product_name, $category_name);
$stmt->execute();

$result = $stmt->get_result();
$productssssss = $result->fetch_assoc();

$image_text = urlencode($product['product_name']);
$image_src = "https://placehold.co/300x300/EEE/31343C?font=pt-sans&text=" . $image_text;

if (!$product) {
    header("HTTP/1.0 404 not found");
    echo "<h1> 404: Product not found. Please try again.</h1>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['product_name']) . " - Pixel Pantry"; ?></title>
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
  <h1>Welcome to Pixel Pantry update test</h1>
  <!-- Creating breaddcrumbs-->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">

      <li class="breadcrumb-item">
          <a href="home.php">Home</a>
      </li>

      <li class="breadcrumb-item"> 
          <a href="category.php?category=<?php echo urlencode($product['category']); ?>">
          <?php echo htmlspecialchars($product['category']); ?>
          </a>
      </li>
      <li class="breadcrumb-item">
          <?php echo htmlspecialchars($product['product_sub_1'] ?? ''); ?>
      </li> 
    </ol>
  </nav>
</header>

<body>
  <!-- Creating product card-->
  <div class="padding">
    <div class="card w-25">
      <img src="<?php echo $image_src; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
      <div class="card-body">
        <h2 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h2>
        <p class="card-text"><?php echo htmlspecialchars($product['product_desc']); ?></p>
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></li>
        <li class="list-group-item"><strong>Price:</strong> £<?php echo number_format($product['product_price'], 2); ?></li>
        <li class="list-group-item"><button class="button_design" onclick="alert('Added to basket!')"> Add to Basket</button></li>
      </ul>
      <div class="card-body">
        <a href="category.php?category=<?php echo urlencode($product['category']); ?>" class="card-link">
          <- Back to <?php echo htmlspecialchars($product['category']); ?>
        </a>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

<footer class="footer">
    <p>Created in 2026 by Courtney Morrison</p>
</footer>
</html>

