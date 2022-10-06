<?php

require '../ProduccionLeche.php';

$predeterminadas = [2, 3, 4, 5, 6];
$peso_max_camion = 1000;//kg

$vacas = [
    1 => ["peso_kg" => 223, 'produccion_dia' => 30],
    2 => ["peso_kg" => 243, 'produccion_dia' => 34],
    3 => ["peso_kg" => 100, 'produccion_dia' => 28],
    4 => ["peso_kg" => 200, 'produccion_dia' => 45],
    5 => ["peso_kg" => 200, 'produccion_dia' => 31],
    6 => ["peso_kg" => 155, 'produccion_dia' => 50],
    7 => ["peso_kg" => 300, 'produccion_dia' => 29],
    8 => ["peso_kg" => 1, 'produccion_dia' => 1],

];

$prod_leche = new ProduccionLeche($vacas, $peso_max_camion, $predeterminadas);
$prod_leche->iniciar();
