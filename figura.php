<?php

$altura = 5;  // número máximo de # en la línea central

// ---------- PARTE SUPERIOR (incluye la línea central) ----------
for ($fila = 1; $fila <= $altura; $fila++) {

    for ($esp = 0; $esp < $altura - $fila; $esp++) {
        echo " ";
    }

    for ($col = 1; $col <= $fila; $col++) {
        echo "#";
        
        if ($col < $fila) {
            echo " ";
        }
    }

    echo "\n";
}

// ---------- PARTE INFERIOR (sin repetir línea central) ----------
for ($fila = $altura - 1; $fila >= 1; $fila--) {

    for ($esp = 0; $esp < $altura - $fila; $esp++) {
        echo " ";
    }

    for ($col = 1; $col <= $fila; $col++) {
        echo "#";
        if ($col < $fila) {
            echo " ";
        }
    }

    echo "\n";
}
