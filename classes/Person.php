<?php
require_once(__DIR__ . '/../traits/Password.php');

abstract class Person {
    use Password;

    // === PROPERTIES ===
    protected string $firstName;
    protected string $lastName;
    protected string $email;

    // === SETTERS ===
    public function setFirstName(string $firstName): void {
        if (empty($firstName) || strlen($firstName) < 2) {
            throw new Exception("Voornaam moet minstens 2 tekens bevatten.");
        }
        $this->firstName = htmlspecialchars(ucfirst(trim($firstName)));
    }

    public function setLastName(string $lastName): void {
        if (empty($lastName) || strlen($lastName) < 2) {
            throw new Exception("Achternaam moet minstens 2 tekens bevatten.");
        }
        $this->lastName = htmlspecialchars(ucfirst(trim($lastName)));
    }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres.");
        }
        $this->email = htmlspecialchars(strtolower(trim($email)));
    }

    // === GETTERS ===
    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getEmail(): string {
        return $this->email;
    }

    // === ABSTRACT METHODS ===
    abstract public function save(): bool;  // wordt overschreven in User/Admin
}
