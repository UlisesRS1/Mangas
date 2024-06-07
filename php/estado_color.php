<?php

    function estado_color ($estado) {
        switch ($estado) {
            case "Finalizado":
                return '<p class="card-text text-danger p-0">'.$estado.'</p>';
            case "En publicación":
                return '<p class="card-text text-success p-0">'.$estado.'</p>';
            case "Cancelado":
                return '<p class="card-text text-warning p-0">'.$estado.'</p>';
            case "Pausado":
                return '<p class="card-text text-secondary p-0">'.$estado.'</p>';
            default:
                return '<p class="card-text text-secondary p-0">'.$estado.'</p>';          
        }
    }