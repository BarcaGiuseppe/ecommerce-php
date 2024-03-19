<?php
session_start();
//session_unset('products');à

// if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
//     echo "<pre>";
//     print_r($_SESSION['cart']);
//     echo "</pre>";
//     // var_dump($_SESSION);
// } else {
//     echo "La sessione è vuota o non è stata inizializzata.";
// }

class AppContext {
    private $cart = [];
    private $products = null;
    private $loading = false;
    private $error = "";

    public function __construct() {
        if (!isset($_SESSION['products'])) {
            $this->fetchProducts(); 
            $_SESSION['products'] = $this->getProducts();
        } else {
            $this->products = $_SESSION['products']; 
        }  
        
        if (isset($_SESSION['cart'])) {
            $this->cart = $_SESSION['cart'];
        }
    }

    public function getCart() {
        return $this->cart;
    }

    public function addToCart($idProduct) {
        $found = array_filter($this->cart, function($el) use ($idProduct) {
            return $el['id'] === $idProduct;
        });
        $qntProductAvailable = $this->getProductQuantity($idProduct);

        if (!empty($found)) {
            $newCart = array_map(function($el) use ($idProduct, $qntProductAvailable) {
                if ($el['id'] !== $idProduct || $qntProductAvailable == 0) {
                    return $el;
                }
                return ['id' => $el['id'], 'quantity' => $el['quantity'] + 1];
            }, $this->cart);
            $this->cart = $newCart;
            $_SESSION['cart'] = $this->cart;
        } else {
            $this->cart[] = ['id' => $idProduct, 'quantity' => 1];
            $_SESSION['cart'] = $this->cart;
        }
    }

    public function removeFromCart($idProduct) {
        $newCart = array_reduce($this->cart, function($acc, $el) use ($idProduct) {
            if ($el['id'] === $idProduct) {
                if ($el['quantity'] > 1) {
                    $acc[] = ['id' => $el['id'], 'quantity' => $el['quantity'] - 1];
                    return $acc;
                }
                return $acc;
            } else {
                $acc[] = $el;
                return $acc;
            }
        }, []);
        $this->cart = $newCart;
        $_SESSION['cart'] = $this->cart;
    }


    public function fetchProducts() {
        $this->loading = true;
        try {
            $response = file_get_contents("https://mockend.up.railway.app/api/products");
            $data = json_decode($response, true);
            $this->products = $data;
            $this->loading = false;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->loading = false;
        }
    }

    public function getProducts() {
        return $this->products;
    }

    public function getProductQuantity($idProduct) {
        $qntApi = 0;
        $qntCart = 0;

        foreach ($this->cart as $cartProduct) {
            if ($cartProduct['id'] == $idProduct) {
                $qntCart = $cartProduct['quantity'];
                break;
            }
        }
    
        foreach ($this->products as $product) {
            if ($product['id'] == $idProduct) {
                $qntApi = $product['qty'];
                break;
            }
        }

        return $qntApi - $qntCart;
    }
}


?>

