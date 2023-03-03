<?php

function getContacts(mysqli $db , ?array $params = null): mysqli_result|bool {

try {

    $query = $db->query('SELECT * FROM contacts');

    return $query;
    } catch (Exception $exception) {
    var_dump($exception->getMessage());

    return false;
    }
}