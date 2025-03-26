<?php
$password = "Admin123!";

// Simule la ligne de ton UserController
$hashed = password_hash($password, PASSWORD_BCRYPT);

echo "Mot de passe : " . $password . "<br>";
echo "Hash généré : " . $hashed . "<br>";

// Test immédiat
if (password_verify("Admin123!", $hashed)) {
    echo "<strong>✅ Vérification réussie</strong>";
} else {
    echo "<strong>❌ Vérification échouée</strong>";
}
