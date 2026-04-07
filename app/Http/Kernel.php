protected $routeMiddleware = [
    // ... middleware lainnya
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];