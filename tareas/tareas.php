<?php

require __DIR__ . '/../ColorLog.php';

$fp = fopen(__DIR__ . "/tareas.txt", "r");

$index = 1;
$titulo_index = 1;

$titulo_total_tareas = "";
$titulo_tiempo_tareas = "";
$titulo_dependencia_tareas = "";

$total_tareas = 0;
$tareatiempo = [];
$dependenciatareas = [];
$lista_de_tareas = [3, 1, 4];

while (!feof($fp)) {
    $linea = fgets($fp);

    $pattern = '/[0-9]/';
    $patternTitulos = '/#/';
    preg_match($pattern, $linea, $matches);
    if (count($matches) > 0) {
        if ($index == 1) {
            $total_tareas = (int) $linea;
        }
        if ($index > 1 && $index <= 7) {

            $tt_expl = explode(",", $linea);

            $tt = [
                "tarea" => (int) $tt_expl[0],
                "tiempo" => (int) $tt_expl[1],
            ];

            array_push($tareatiempo, $tt);
        }
        if ($index > 7) {
            array_push($dependenciatareas, $linea);
        }

        $index++;

    }

    preg_match($patternTitulos, $linea, $matches_t);
    if (count($matches_t) > 0) {
        switch ($titulo_index) {
            case 1:
                $titulo_total_tareas = (string) $linea;
                break;

            case 2:
                $titulo_tiempo_tareas = (string) $linea;
                break;

            case 3:
                $titulo_dependencia_tareas = (string) $linea;
                break;

            default:
                # code...
                break;
        }

        $titulo_index++;
    }

}

fclose($fp);
echo ($titulo_total_tareas);
echo $total_tareas . "\n";

echo ($titulo_tiempo_tareas);
foreach ($tareatiempo as $key => $value) {
    echo $value['tarea'] . "," . $value['tiempo'] . "\n";
}

echo ($titulo_dependencia_tareas);
foreach ($dependenciatareas as $key => $value) {
    echo $value;
}
echo "\n";
echo "\n";

class ProcesarTarea
{
    private $tarea_tiempo = [];
    private $dependencia_tareas = [];
    private $lista_de_tareas = [];
    private $dependencia_tareas_result = [];
    private $total_tiempo = [];

    public function __construct($tarea_tiempo, $dependencia_tareas, $lista_de_tareas)
    {
        $this->tarea_tiempo = $tarea_tiempo;
        $this->dependencia_tareas = $dependencia_tareas;
        $this->lista_de_tareas = $lista_de_tareas;
        $this->iniciar();
    }

    public function dep_array(array $dependenciatareas)
    {

        $d = [];
        $dep = [];
        foreach ($dependenciatareas as $key => $value) {
            $_dep = explode(",", $value);
            $dep[(int) $_dep[0]] = $_dep;
        }
        return $dep;
    }

    public function fntareatiempo(int $t, array $tareatiempo)
    {
        foreach ($tareatiempo as $key => $value) {
            if ((int) substr($value['tarea'], 0, 1) == $t) {
                return $value['tiempo'];
            }
        }
    }
    public function fndependencias(int $tarea, array $dependenciatareas)
    {

        $dependencias_tarea = [];
        foreach ($dependenciatareas as $key => $value) {
            if ((int) $value[0] == $tarea) {
                $dep_r = array_reverse($value);
                $dep_r_nuevo = [];

                foreach ($dep_r as $key => $value) {
                    $dep_r_nuevo[(int) $value] = (int) $value;
                }
                $dependencias_tarea = $dep_r_nuevo;
            }
        }

        return $dependencias_tarea;

    }

    public function manejar_dependencias($tarea, $lista_dependencias, $tarea_dependencias, $dep_o = [])
    {
        $v_dependencias = $tarea_dependencias;

        foreach ($tarea_dependencias as $key => $value) {

            if (array_key_exists((int) $value, $lista_dependencias) && (int) $value != $tarea && !in_array($value, $dep_o)) {
                $f = array_splice($v_dependencias, $key, 1, $lista_dependencias[(int) $value]); // se añaden las tareas a las que depende
                array_push($dep_o, $f[0]);

            }
        }

        if ($v_dependencias === $tarea_dependencias) {
            $this->dependencia_tareas_result[$tarea] = $v_dependencias;
        } else {
            $this->manejar_dependencias($tarea, $lista_dependencias, $v_dependencias, $dep_o);
        }

    }

    public function ejecutarTarea(int $tarea, int $tiempo)
    {

        $text_color = ColorLog::textColor($tarea, "s");
        $text_color_tiempo = ColorLog::textColor($tiempo . " segundos", "w");
        echo "Ejecutanto tarea " . $text_color . " se resolverá en " . $text_color_tiempo . " \n";
        sleep($tiempo);

        return $tiempo;

    }

    public function resultados()
    {
        foreach ($this->total_tiempo as $key => $value) {

            echo "La tarea " . $key . " se ejecuto en " . $value . "\n";

        }
    }
    public function iniciar()
    {

        $dep_arr = $this->dep_array($this->dependencia_tareas);

        foreach ($this->lista_de_tareas as $key => $tarea) {
            $this->total_tiempo[$tarea] = 0;
            $dep = $this->fndependencias($tarea, $dep_arr);

            if ($dep) {
                $this->manejar_dependencias($tarea, $dep_arr, $dep_arr[$tarea]);
            } else {
                $tiempo_t = $this->fntareatiempo($tarea, $this->tarea_tiempo);
                $resultado_p = $this->ejecutarTarea($tarea, $tiempo_t);
                $this->total_tiempo[$tarea] = $resultado_p;

            }

            if (array_key_exists($tarea, $this->dependencia_tareas_result)) {

                $arr_r = array_reverse($this->dependencia_tareas_result[$tarea]);

                foreach ($arr_r as $key_t => $t) {

                    if ((int) $t != $tarea) {
                        if (in_array((int) $t, $this->lista_de_tareas)) { // Se ejecuts en paralelo

                            $tiempo_p = $this->fntareatiempo((int) $t, $this->tarea_tiempo);
                            $total_p = $this->total_tiempo[$tarea];

                            if ($tiempo_p > $total_p) {
                                $t_t_p = ($tiempo_p - $total_p);
                                $resultado_p = $this->ejecutarTarea((int) $t, $t_t_p);
                                $this->total_tiempo[$tarea] = $this->total_tiempo[$tarea] + $resultado_p;
                            } else {

                                $resultado_p_m = $this->ejecutarTarea((int) $t, $tiempo_p);
                                $total_p_m = $this->total_tiempo[$tarea];
                                $this->total_tiempo[$tarea] = $total_p_m + $resultado_p_m;

                            }

                        } elseif (in_array((int) $t, $dep_arr[$tarea])) { // no tiene dependencias

                            $tiempo_o = $this->fntareatiempo((int) $t, $this->tarea_tiempo);
                            $resultado_o = $this->ejecutarTarea((int) $t, $tiempo_o);
                            $total_o = $this->total_tiempo[$tarea];
                            $this->total_tiempo[$tarea] = $total_o + $resultado_o;

                        } else { // son dependencias

                            $tiempo_d = $this->fntareatiempo((int) $t, $this->tarea_tiempo);
                            $total_d = $this->total_tiempo[$tarea];

                            if ($tiempo_d > $total_d) {

                                $t_d = ($tiempo_d - $total_d);
                                $resultado_d = $this->ejecutarTarea((int) $t, $t_d);
                                $this->total_tiempo[$tarea] = $this->total_tiempo[$tarea] + $resultado_d;

                            } else {

                                $resultado_d_m = $this->ejecutarTarea((int) $t, $tiempo_d);
                                $this->total_tiempo[$tarea] = $this->total_tiempo[$tarea] + $resultado_d_m;
                            }

                        }
                    } else {

                        $tiempo = $this->fntareatiempo((int) $t, $this->tarea_tiempo);
                        $resultado = $this->ejecutarTarea((int) $t, $tiempo);
                        $this->total_tiempo[$tarea] = $this->total_tiempo[$tarea] + $resultado;
                    }
                }
            }
        }
    }

}

$proceso = new ProcesarTarea($tareatiempo, $dependenciatareas, $lista_de_tareas);

$proceso->resultados();
