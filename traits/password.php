<?php
trait Password {
    private string $password;

    // --- Setter ---
    public function setPassword(string $password): void {
        if (strlen($password) < 4) {
            throw new Exception("Wachtwoord moet minstens 4 tekens bevatten.");
        }

        // Sterkere hash met cost 14 (zoals jij eerder wilde)
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 14]);
    }

    // --- Getter (optioneel, alleen als nodig) ---
    public function getPassword(): string {
        return $this->password;
    }

    // --- Verifiëren van wachtwoord ---
    public static function verifyPassword(string $password, string $hashedPassword): bool {
        return password_verify($password, $hashedPassword);
    }
}
