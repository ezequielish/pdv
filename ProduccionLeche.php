<?php

require __DIR__ . '/ColorLog.php';

class ProduccionLeche
{

    protected $vacas_seleccionadas = [];
    protected $peso_limite_camion = 0;
    protected $vacas = [];
    protected $predeterminado = [];

    public function __construct(array $vacas = [], int $camion_peso_kg_max = 0, array $predeterminado = [])
    {
        $this->peso_limite_camion = $camion_peso_kg_max;
        $this->vacas = $vacas;
        $this->predeterminado = $predeterminado;
    }

    public function iniciar()
    {

        try {
            echo "\n";
            echo ColorLog::textColor("Peso máximo del camión " . $this->peso_limite_camion . "Kg", "w") . " \n \n";

            $this->listarVacas($this->vacas);
            $this->manejarSeleccion();
            $this->manejarResultado($this->vacas);
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "\n";
        }
    }

    protected function manejarError(string $textError)
    {
        $error = ColorLog::textColor($textError, "e");
        throw new Exception($error);
    }

    protected function manejarResultado(array $vacas)
    {
        $peso_total = [];
        $lts_total = [];

        foreach ($this->vacas_seleccionadas as $vaca) {

            if (array_key_exists($vaca, $vacas)) {
                array_push($peso_total, $vacas[$vaca]['peso_kg']);
                array_push($lts_total, $vacas[$vaca]['produccion_dia']);
            } else {

                $error = "La vaca nº " . $vaca . " no existe";
                $this->manejarError($error);
            }
        }

        if (array_sum($peso_total) > $this->peso_limite_camion) {
            $error = "El peso seleccionado es de: " . array_sum($peso_total) . " y excede el peso del camión";
            $this->manejarError($error);
        }
        echo (ColorLog::textColor('peso total de vacas en kg', 's') . " = " . array_sum($peso_total));
        echo "\n";
        echo (ColorLog::textColor('leche total en litros', 's') . " = " . array_sum($lts_total));
        echo "\n";
    }

    protected function manejarSeleccion()
    {
        echo "\n";
        echo ColorLog::textColor("[*]Selección predeterminada: " . implode(",", $this->predeterminado), "i");
        echo "\n";
        $txt = ColorLog::textColor('Seleccionar vacas colocando el número separando por coma (,)', 'i');
        echo "\n";
        echo $txt;
        $prompt = readline();

        $vacas_seleccionadas = explode(",", $prompt);

        if (count($vacas_seleccionadas) == 1 && empty($vacas_seleccionadas[0])) {
            $vacas_seleccionadas = $this->predeterminado;
        }

        $this->vacas_seleccionadas = array_unique($vacas_seleccionadas);

    }

    public function listarVacas(array $vacas)
    {

        echo "Lista de vacas disponibles" . "\n \n";
        foreach ($vacas as $key => $vaca) {
            echo "- Vaca nro " . $key . "  peso = " . $vaca['peso_kg'] . " kg -" . " producción de leche al día = " . $vaca['produccion_dia'] . " lts \n";
            echo "\n";

        }
    }

}
