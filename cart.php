<?php
//session_start();

require_once 'AppContext.php';

//use AppContext;

$appContext = new AppContext();

$products = $appContext->getProducts();
$cart = $appContext->getCart();

$totalItems = count($products);
$isEmpty = empty($cart);

function getProductById($products, $productId) {
    //print_r($productId . "   -   ");
     if ($products !== null && !empty($products)) {
        foreach ($products as $product) {
            if ($product['id'] == $productId) {
                return $product;
            }
        }
    }
    //print_r('ciao');
    return null;
}

function calculateTotal($cart, $products) {
    return array_reduce($cart, function($total, $cartItem) use ($products) {
        $product = getProductById($products, $cartItem['id']);
        if ($product) {
            return $total + $product['price'] * $cartItem['quantity'];
        }
        return $total;
    }, 0);
}

function handleQuantityChange($event, $id, $qnt, $addToCart, $removeFromCart) {
    $newQuantity = (int)$event;
    if ($newQuantity > $qnt) {
        $addToCart($id);
    } else {
        $removeFromCart($id);
    }
}

function displayCartItem($cartItem, $index, $products, $addToCart, $removeFromCart) {
    $product = getProductById($products, $cartItem['id']);
    //print_r($product);
    if (!$product) return null;
    $product = $product;
    return '
        <div class="cart-item" key="' . $index . '">
            <div class="product-info">
                <img class="product-image" src="' . $product['thumbnail'] . '" alt="' . $product['title'] . '">
                <div>
                    <h3 class="product-title">' . $product['title'] . '</h3>
                    <p class="product-price">' . $product['price'] . '€</p>
                </div>
            </div>
            <div class="quantity-wrapper">
                <form method="post" action="index.php">
                    <input type="hidden" name="cart_item_id" value="<?php echo $product["id"]; ?>">
                    <button class="remove-button" type="submit">Remove</i></button>
                </form> 
            </div>
        </div>';
}

?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <div id="cart-page-wrapper">
        <h1>Your Cart</h1>
        <div id="cart-container">
            <?php foreach ($cart as $index => $cartItem): ?>
                <?php echo displayCartItem($cartItem, $index, $products, 'addToCart', 'removeFromCart'); ?>
            <?php endforeach; ?>
            <div id="total-wrapper">
                <p id="total-label">Total:</p>
                <span id="total-amount"><?php echo calculateTotal($cart, $products); ?>€</span>
            </div>
            <button id="buy-button" <?php echo $isEmpty ? 'disabled' : ''; ?>>
                <?php if ($isEmpty): ?>
                    Buy Now
                <?php else: ?>
                    <a href="/success" style="color: white; text-decoration: none;">Buy Now</a>
                <?php endif; ?>
            </button>
        </div>
    </div>
    <script>
        function handleQuantityChange(value, id, qnt, addToCart, removeFromCart) {
            var newQuantity = parseInt(value);
            if (newQuantity > qnt) {
                addToCart(id);
            } else {
                removeFromCart(id);
            }
        }
    </script>
</body>
</html>
