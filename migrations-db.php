<?php
use Doctrine\DBAL\DriverManager;

return DriverManager::getConnection([
    'dbname' => 'Atelier',
    'user' => 'root',
    'password' => 'root',
    'host' => 'mariadb',
    'driver' => 'pdo_mysql',
]);