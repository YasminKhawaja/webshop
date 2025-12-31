<?php
trait PasswordTrait {

    // Hasht een wachtwoord veilig met cost 14
    public function hashPassword(string $password): string {
        if (strlen($password) < 4) {
            throw new Exception("Wachtwoord moet minstens 4 tekens bevatten.");
        }
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 14]);
    }

    // Controleert of een ingegeven wachtwoord overeenkomt
    public function verifyPassword(string $input, string $hashed): bool {
        return password_verify($input, $hashed);
    }
}
