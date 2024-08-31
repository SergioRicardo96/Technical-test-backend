<!DOCTYPE html>
<html lang="<?= getCurrentLang(); ?>" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/public/css/app.css" rel="stylesheet">
    <title><?= config('app', 'name'); ?></title>
</head>
<body>
<body class="d-flex h-100 text-center text-white bg-dark">
    <div class="cover-container d-flex w-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0"><?= config('app', 'name'); ?></h3>
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    <a class="nav-link <?= isCurrentUrl('') ? 'active' : ''; ?>" href="/"><?= trans('app', 'home'); ?></a>
                    @guest
                        <a class="nav-link <?= isCurrentUrl('/login') ? 'active' : ''; ?>" href="/login"><?= trans('app', 'login'); ?></a>
                        <a class="nav-link <?= isCurrentUrl('/register') ? 'active' : ''; ?>" href="/register"><?= trans('app', 'register'); ?></a>
                    @endguest
                    @auth
                        <a class="nav-link <?= isCurrentUrl('/admin/tasks') ? 'active' : ''; ?>" href="/admin/tasks"><?= trans('app', 'tasks'); ?></a>
                        <form id="logout-form" action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="nav-link ms-3">Logout</button>
                        </form>
                    @endauth
                </nav>
            </div>
        </header>
    
        <main class="px-3">
            @yield('content')
        </main>
    
        <footer class="mt-auto text-white-50">
            <p>© 2024 - Sergio Nangusé</p>
        </footer>
    </div>
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>