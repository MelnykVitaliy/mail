<?php
class BankAccount {
    private $balance;

    public function __construct($initialBalance) {
        $this->balance = $initialBalance;
    }

    public function deposit($amount) {
        // Внесення грошей на рахунок
        $this->balance += $amount;
        echo "Внесено: $amount грн. Загальний баланс: {$this->balance} грн.<br>";
    }

    public function withdraw($amount) {
        // Зняття грошей з рахунку
        if ($amount > $this->balance) {
            throw new Exception("Недостатньо грошей на рахунку");
        }

        $this->balance -= $amount;
        echo "Знято: $amount грн. Загальний баланс: {$this->balance} грн.<br>";
    }
}

// Використання класу BankAccount
$account = new BankAccount(1000);

try {
    $account->withdraw(500);  // Знімання 500 грн.
    $account->withdraw(800);  // Знімання 800 грн. (спричинить виняток)
    $account->deposit(200);   // Внесення 200 грн. (цей рядок не виконається через виняток)
} catch (Exception $e) {
    echo "Помилка: " . $e->getMessage() . "<br>";
} finally {
    echo "Операції завершено.<br>";
}
