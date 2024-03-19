<?php
echo '
<style>
.nav-container {
  position: sticky;
  top: 0;
  z-index: 1000;
  display: flex;
  justify-content: space-around;
  align-items: flex-end;
  background-color: #0066cc;
  box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.3);
  margin: 0;
}

.nav-title {
  display: flex;
  gap: 10px;
  margin-left: none;
}

.nav-item {
  display: flex;
  gap: 10px;
  margin-left: auto;
}

.nav-item h3 {
  cursor: pointer;
  font-size: 1em;
  margin: 1em;
  padding: 0.25em 1em;
  color: white;
  text-decoration: none;
}
</style>

<div class="nav-container">
  <div class="nav-title">
    <h3 style="color: white;"><i class="fas fa-ship"></i> SJAs Shop</h3>
  </div>
  <div class="nav-item">
    <h3><a href="/ecommerce/" style="color: white; text-decoration: none;"><i class="fas fa-home"></i> Home</a></h3>
    <h3><a href="/ecommerce/cart.php" style="color: white; text-decoration: none;"><i class="fas fa-cart-shopping"></i> Cart</a></h3>
  </div>
</div>';
?>
