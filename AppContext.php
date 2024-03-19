<?php

class AppContext {
    private $cart = [];
    private $paid = false;
    private $products = null;
    private $loading = false;
    private $error = "";

    public function __construct() {
        $this->fetchProducts();    
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
            //$this->getProductQuantity($idProduct, true);
        } else {
            $this->cart[] = ['id' => $idProduct, 'quantity' => 1];
            //$this->getProductQuantity($idProduct, true);
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
        //$this->getProductQuantity($idProduct, false);
    }

    public function pay() {
        $this->paid = true;
        $this->cart = [];
    }

    public function done() {
        $this->paid = false;
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

    private function getProductQuantity($idProduct) {
        $productHome = null;
        $qntApi = 0;
        $qntCart = 0;

        foreach ($this->cart as $product) {
            if ($product['id'] == $idProduct) {
                $qntCart = $product['quantity'];
                break;
            }
        }

        foreach ($this->products as $product) {
            if ($product['id'] == $idProduct) {
                $qntApi = $product['quantity'];
                break;
            }
        }

        return $qntApi - $qntCart;
    }
}
