<?php
$db = new PDO('sqlite:database/database.sqlite');
$tablesQuery = $db->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
$tables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);

$sql = "-- Dump de la base de datos SQLite a SQL\n\n";

foreach ($tables as $table) {
    $sql .= $table['sql'] . ";\n\n";
    
    $rows = $db->query("SELECT * FROM `" . $table['name'] . "`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $keys = array_keys($row);
        $values = array_values($row);
        $escapedValues = array_map(function($val) use ($db) {
            if (is_null($val)) return 'NULL';
            return $db->quote($val);
        }, $values);
        $sql .= "INSERT INTO `" . $table['name'] . "` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
    }
    $sql .= "\n";
}

file_put_contents('database_dump.sql', $sql);
echo "Dump creado exitosamente.\n";
