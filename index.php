<?php
// Front proxy: si el servidor usa /var/www/html como DocumentRoot
// reenvía las peticiones al front controller dentro de /apis
require_once __DIR__ . '/apis/index.php';
