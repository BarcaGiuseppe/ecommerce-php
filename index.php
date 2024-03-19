<?php

require_once 'AppContext.php';

$appContext = new AppContext();
// if (!isset($_SESSION['cart'])) {
//     $_SESSION['cart'] = $appContext->getCart();
// }

// if (!isset($_SESSION['products'])) {
//     $_SESSION['products'] = $appContext->getProducts();
// }

$products = $appContext->getProducts();
$cart = $appContext->getCart();
$totalItems = count($products);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $idProductToAdd = $_POST['product_id'];

    $appContext->addToCart($idProductToAdd);

    //$_SESSION['cart'] = $appContext->getCart();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
    //echo "Prodotto aggiunto al carrello con ID: " . $idProductToAdd;
}
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div id="homeWrapper">
            <?php foreach ($products as $item): ?>
                <div id="card">
                    <img alt="image" src=<?php echo $item['image']; ?>></img>
                    <div id="itemWrapper">
                        <h3 id="title"><?php echo $item['title']; ?></h3>
                    </div>
                    <p id="description"><?php echo $item['description']; ?></p>
                    <div id="itemWrapper">
                        <h6 id="price">N. <?php echo $appContext->getProductQuantity($item['id']); ?></h6>
                        <h5 id="price"><?php echo $item['price']; ?>€</h5>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button id="addToCartButton" type="submit"><i class="fas fa-shopping-cart"></i></button>
                        </form>     
                    </div>
                </div>
            <?php endforeach; ?>
    </div>
</body>
</html>
