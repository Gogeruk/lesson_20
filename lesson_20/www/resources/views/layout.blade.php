<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <title>Test 2</title>
    </head>
    <body style="background-color:#FFFDD0;">
        <h1 class="text-center mb-3">10. NO FRONTEND required, only API (incl. DB)</h1>
        <h1 class="text-center mb-3">YOU FUCKING IDIOT!</h1>
        <h1 class="text-center mb-3">WTF IS THIS?</h1>
        <h1 class="text-center mb-3">WHY DID YOU MAKE IT?</h1>
        <div class="m-2 bg-muted">
            <div class="mb-12 d-flex justify-content-center">
                <div class="border border-warning mb-12">
                    <h5 class="text-center">Login</h5>
                    <div class="border border-warning mb-12">
                        <div class="text-center mb-4">
                            <button onclick="location.href='{{ route('login') }}'" type="submit" class="btn btn-sm btn-warning m-1" name="button">LOGIN</button>
                            <button onclick="location.href='{{ route('create-user') }}'" type="submit" class="btn btn-sm btn-warning m-1" name="button">NEW USER</button>
                        </div>
                        <div class="text-center mb-4">
                            <button onclick="location.href='{{ route('login') }}'" type="submit" class="btn btn-sm btn-warning m-1" name="button">VERIFY USER</button>
                            <button onclick="location.href='{{ route('create-user') }}'" type="submit" class="btn btn-sm btn-warning m-1" name="button">DELETE USERS</button>
                        </div>
                        <div class="text-center mb-4">
                            <button onclick="location.href='{{ route('login') }}'" type="submit" class="btn btn-sm btn-warning m-1" name="button">EDIT USER(S)?????</button>
                            <button onclick="location.href='{{ route('create-user') }}'" type="submit" class="btn btn-sm btn-warning m-1" name="button">LIST USERS</button>
                        </div>
                    </div>
                    <h5 class="text-center">Navigation</h5>
                    <div class="border border-warning mb-12">
                        <div class="text-center mb-4">
                            <button onclick="location.href='{{ route('home') }}'" type="submit" class="btn btn-sm btn-warning m-1" name="button">HOME</button>
                            <button onclick="location.href=''" type="submit" class="btn btn-sm btn-warning m-1" name="button">DO STUFF</button>
                        </div>
                        <div class="text-center mb-4">
                            <button onclick="location.href=''" type="submit" class="btn btn-sm btn-warning m-1" name="button">CREATE PROJECT</button>
                            <button onclick="location.href=''" type="submit" class="btn btn-sm btn-warning m-1" name="button">CREATE LABEL</button>
                        </div>
                    </div>
                </div>
                @yield('home-display')
                @yield('create-user-display')
                @yield('login-display')
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    </body>
</html>
