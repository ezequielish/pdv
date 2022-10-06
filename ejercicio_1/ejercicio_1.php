<?php

require '../ProduccionLeche.php';

$predeterminadas = [1, 4, 5, 6];
$peso_max_camion = 700;//kg

$vacas = [
    1 => ["peso_kg" => 360, 'produccion_dia' => 40],
    2 => ["peso_kg" => 250, 'produccion_dia' => 35],
    3 => ["peso_kg" => 400, 'produccion_dia' => 43],
    4 => ["peso_kg" => 180, 'produccion_dia' => 28],
    5 => ["peso_kg" => 50, 'produccion_dia' => 12],
    6 => ["peso_kg" => 90, 'produccion_dia' => 13],

];

$prod_leche = new ProduccionLeche($vacas, $peso_max_camion, $predeterminadas);
$prod_leche->iniciar();
