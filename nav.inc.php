<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?><nav class="navbar">
  <a href="home.php" class="logo">GlowCare</a>

 <form class="search-form" action="search.php" method="GET">
  <input
    type="search"
    name="q"
    placeholder="Waar ben je naar op zoek?"
    aria-label="Zoeken"
    required
  />
  <button type="submit" aria-label="Zoeken">Zoek</button>
</form>


  <div class="nav-actions">
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="logout.php" class="login-btn">Uitloggen</a>
      <a href="winkelwagen.php" class="cart-btn" aria-label="Winkelwagen">🛒</a>
      <a href="account.php">Account</a>
    <?php else: ?>
      <a href="login.php" class="login-btn">Inloggen</a>
      <a href="winkelwagen.php" class="cart-btn" aria-label="Winkelwagen">🛒</a>
    <?php endif; ?>
  </div>
</nav>
