<?php
// serve.php

$host = '127.0.0.1';
$port = '8888';
$docRoot = __DIR__; // or set it manually e.g., '/path/to/project/public'

echo "Starting server at http://$host:$port\n";
echo "Press Ctrl+C to stop...\n";

// Run PHP built-in server
exec("php -S $host:$port -t $docRoot");
