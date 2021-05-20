<?php

/*
    Este ejercicio me lo he leido al menos 10 veces y no lo entiendo bien, aún así haré lo que entendí y lo comentaré.
*/
$table1 = array(
    array(
        'id' => 2,
        'nombre' => "pepe",
        'DNI' => "7777777x",
        'saldo' => 25,
        'fecha' => "25/05/1998"
    )
);

$table2 = array(
    array(
        'id' => 2,
        'nombre' => "pepe",
        'DNI' => "7777777x",
        'saldo' => 40,
        'fecha' => "05/05/1998"
    )
);

/**
 * Recorremos los arrays y ponemos la id como indice para poder obtenerlo mas facilmente
 */
function parserArrTable($arrTable){
    $outputTable = array();
    foreach($arrTable as $fields){
        $outputTable[$fields['id']] = $fields; 
    }

    return $outputTable;
}

/**
 * Aqui es lo que yo entendí, entiendo que hay dos arrays uno con la tabla1 y otro con la 
 * tabla2(supuse que podían venir mas arrays dentro del mismo de cada tabla).
 * 
 * Si la variable "last_run" del script es menor que la que hay en la tabla1, entonces la tabla1 mete su valor a 
 * la tabla2. La tabla2 solo recibe y no tienen ningun comportamiento sobre la tabla1.
 */
function changeSaldo($arrTable, &$tableToChange){
    $last_run = "24/04/1998";
    $dateLastRun = DateTime::createFromFormat('j/m/Y', $last_run);
    foreach($arrTable as $id => $table){
        $dateTable = DateTime::createFromFormat('j/m/Y', $table['fecha']);
        if($dateTable > $dateLastRun){
            $tableToChange[$id]['saldo'] = $table['saldo'];
        }
    }
}
$table1 = parserArrTable($table1);
$table2 = parserArrTable($table2);
changeSaldo($table1, $table2);
echo print_r($table1);
echo print_r($table2);