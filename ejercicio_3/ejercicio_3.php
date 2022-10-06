<?php

require '../ProduccionLeche.php';

$predeterminadas = [1, 2, 3, 4, 5, 6, 8, 9];
$peso_max_camion = 2000; //kg

$vacas = [
    1 => ["peso_kg" => 340, 'produccion_dia' => 45],
    2 => ["peso_kg" => 355, 'produccion_dia' => 50],
    3 => ["peso_kg" => 223, 'produccion_dia' => 34],
    4 => ["peso_kg" => 243, 'produccion_dia' => 39],
    5 => ["peso_kg" => 130, 'produccion_dia' => 29],
    6 => ["peso_kg" => 240, 'produccion_dia' => 40],
    7 => ["peso_kg" => 260, 'produccion_dia' => 30],
    8 => ["peso_kg" => 155, 'produccion_dia' => 52],
    9 => ["peso_kg" => 302, 'produccion_dia' => 31],
    10 => ["peso_kg" => 130, 'produccion_dia' => 1],
];

$prod_leche = new ProduccionLeche($vacas, $peso_max_camion, $predeterminadas);
$prod_leche->iniciar();
