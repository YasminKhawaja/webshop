<?php
include_once(__DIR__ . "/classes/Admin.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (Admin::login($email, $password)) {
        header("Location: admin-dashboard.php");
        exit();
    } else {
        $error = "Ongeldige logingegevens.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - GlowCare</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <main class="main-login-wrapper">
        <section class="login-section">
            <h2>Admin Inloggen</h2>
            <?php if(isset($error)): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form class="login-form" method="POST">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Inloggen</button>
            </form>
        </section>
    </main>
</body>
</html>
