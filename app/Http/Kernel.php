protected $middlewareAliases = [
    // ... other middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
// app/Console/Kernel.php
protected $commands = [
    Commands\DownloadGameImages::class,
];