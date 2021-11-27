<?php

return [
    'local' => [
        'credenciar' => 'http://localhost:8080/subadquirente/api/publico/credenciar',
        'pagar' => 'http://localhost:8080/subadquirente/api/publico/pagar?credencial={credencial}',
        'retorno' => 'http://localhost:8080/subadquirente/api/retornos/put',
        'liquidacao' => 'http://localhost:8080/subadquirente/api/publico/protectedliquidarretorno',
    ] 
];
