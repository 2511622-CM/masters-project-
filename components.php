<!--
Components.php
Created by Courtney Morrison 10-07-2026
Last edit: 26-07-2026
-->
<?php 
//Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Creating dropdowns for categories
function category_dropdown($conn) {
    $selected_category = isset($_GET['category']) ? $_GET['category'] : '';

    $result = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");

    echo "<li><a class='dropdown-item' href='home.php'>All Categories</a></li>";
    echo "<li><hr class='dropdown-divider'></li>";
    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];

        $url = "category.php?category=" . urlencode($category);
        echo "<li><a class='dropdown-item' href='" . $url . "'>" . htmlspecialchars($category) . "</a></li>";
    }
}
//Drop downs for sorting by price
function extra_dropdowns($conn) {
    $selected_category = isset($_GET['category']) ? $_GET['category'] : '';
    $selected_sort = isset($_GET['sort']) ? $_GET['sort'] : '';

    echo "<select name='sort' onchange='this.form.submit()'>";
    echo "<option value=''>Sort by Default</option>";

    $low_high_select = ($selected_sort == 'low_high') ? 'selected' : '';
    $high_low_select = ($selected_sort == 'high_low') ? 'selected' : '';

    echo "<option value='low_high' $low_high_select>Sort by Price - Low to High</option>";
    echo "<option value='high_low' $high_low_select>Sort by Price - High to Low</option>";
    echo "</select>";

    if (!empty($selected_category)) {
        echo "<input type='hidden' name='category' value='" . htmlspecialchars($selected_category) . "'>";
    }
}
//Creating cards using Bootstrap linked to the categories
function category_cards($conn) {
$image_map = [
    "Bicycles" => "bike.jpg",
    "Books and Music" => "books.jpg",
    "Clothes and Accessories" => "clothes.jpg",
    "Electronics and Technology"=> "tech.jpg",
    "Food"=> "food.jpg",
    "Health and Beauty" => "handb.jpg",
    "Home and Garden" => "house.jpg",
    "Office"=> "office.jpg",
    "Sports and Outdoors"   => "outdoors.jpg",
    "Pets" => "pets.jpg",
    "Toys" => "toys.jpg",
];
    $result= $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
    if ($result && $result->num_rows > 0) {
        echo "<div class='row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-4 mb-5'>";

        while ($row = $result->fetch_assoc()) {
            $category = $row['category'];
            $filename = isset($image_map[$category]) ? $image_map[$category] : 'default.jpg';
            $image_path = "Assets/" . $filename;
            $img_src = file_exists($image_path) ? $image_path :"https://placehold.co/100x100/EEE/31343C?font=pt-sans&text=No%20Image%20Available";
            ?>
            <!-- 1. Grid Column -->
            <div class="col">
                <!-- 2. Card Container -->
                <div class="card h-100">
                    <a href="category.php?category=<?php echo urlencode($category); ?>">
                        <img src="<?php echo $img_src; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($category); ?>" style="height: 120px; object-fit: cover;">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title m-0">
                            <a href="category.php?category=<?php echo urlencode($category); ?>" class="card-link text-decoration-none">
                                <?php echo htmlspecialchars($category); ?>
                            </a>
                        </h5>
                    </div>
                </div> <!-- Closes .card -->
            </div> <!-- Closes .col -->
            <?php
        } // End while loop

        echo "</div>"; 
    }
}
//Outputting the table using prepared statements
function table_output($conn) {
    $selected_category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $selected_sort = isset($_GET['sort']) ? trim($_GET['sort']) : '';

    if ($selected_sort === 'low_high') {
        $sort_order = "ORDER BY product_price ASC";
    } elseif ($selected_sort === 'high_low') {
        $sort_order = "ORDER BY product_price DESC";
    }else{
        $sort_order = "ORDER BY product_price ASC";
    }

    if ($selected_category !== '') {
        $stmt = $conn->prepare("SELECT product_name, category, product_price, product_sub_1 FROM products WHERE category LIKE ? ". $sort_order);
        $search_term = "%" . $selected_category . "%";
        $stmt->bind_param("s", $search_term);
        $stmt->execute();
        $result = $stmt->get_result();

    } else {

        $result = $conn->query("SELECT product_name, category, product_price, product_sub_1 FROM products $sort_order LIMIT 50");
        if ($result === false) {die("SQL Error" . $conn->error);}
    }
       if ($result && $result->num_rows > 0) {
            while($product = $result->fetch_assoc()) {

            //Calling image source
            $image_text = urlencode($product['product_name']);
            $image_src = "https://placehold.co/100x100/EEE/31343C?font=pt-sans&text=" . $image_text;

            //Creating URLs based on product name, replacing spaces with strings and making it all lowercase.
           $url = "products.php?category=" . urlencode($product['category']) . "&product_name=" . urlencode($product['product_name']);

            //Creating the table components to display
                echo "<tr>";
                echo "<td><img src='" . $image_src . "' alt='" . htmlspecialchars($product['product_name']) ."'></td>";
                echo "<td>" . htmlspecialchars($product['product_name']) . "</td>";
                echo "<td>" . htmlspecialchars($product['category']) . "</td>";
                echo "<td>" . htmlspecialchars($product['product_sub_1']) . "</td>";
                echo "<td>£" . htmlspecialchars($product['product_price']) . "</td>";
                echo "<td><a href='" . $url . "'><button>Learn more</button></a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Not found.</td></tr>";
        }
}