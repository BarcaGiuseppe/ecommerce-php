<?php
require_once 'AppContext.php';

$appContext = new AppContext();

$products = $appContext->getProducts();
$cart = $appContext->getCart();
$totalItems = count($products);
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div id="homeWrapper">
        <ul>
            <?php foreach ($products as $item): ?>
                <div id="card">
                    <img alt="image" src=<?php echo $item['image']; ?>></img>
                    <div id="itemWrapper">
                        <h3 id="title"><?php echo $item['title']; ?></h3>
                    </div>
                    <p id="description"><?php echo $item['description']; ?></p>
                    <div id="itemWrapper">
                        <h5 id="price"><?php echo $item['price']; ?>€</h5>
                        <button onClick="' . $appContext->addToCart($product['id']) . '"'></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
