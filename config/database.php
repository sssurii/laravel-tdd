<?php
$url      = parse_url(env("DATABASE_URL"));
$host     = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = substr($url["path"], 1);

$redis_url      = parse_url(env("REDIS_URL"));
$redis_host     = $redis_url["host"];
$redis_port     = $redis_url["port"];
$redis_password = $redis_url["pass"]=="null"?null:$redis_url["pass"];

return [

    'fetch' => PDO::FETCH_CLASS,
    'default' => env('DB_CONNECTION', 'pgsql'),
    'connections' => [
        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],
    ],
    'migrations' => 'migrations',
        'redis' => [
            'client' => 'predis',
            'default' => [
                'host'     => env('REDIS_HOST', $redis_host),
                'password' => env('REDIS_PASSWORD', $redis_password),
                'port'     => env('REDIS_PORT', $redis_port),
                'database' => 0,
                ],

        ],
];

?>
