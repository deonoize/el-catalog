<?
require_once __DIR__.'/main.php';

$path = substr(explode('?', $_SERVER['REQUEST_URI'])[0], 1);
switch ($path) {
    case 'login':
        redirect('/', isset($user) && $user);
        require __DIR__.'/pages/login.php';
        break;
    case 'registration':
        redirect('/', isset($user) && $user);
        require __DIR__.'/pages/registration.php';
        break;
    case 'logout':
        unset($_SESSION['user']);
        unset($_SESSION['user_hash']);
        redirect('/login');
        break;
    case 'product':
        redirect('/login', !(isset($user) && $user));
        redirect('/', !isset($_GET['id']));
        require __DIR__.'/pages/product.php';
        break;
    case '':
        redirect('/login', !(isset($user) && $user));
        require __DIR__.'/pages/catalog.php';
        break;
    default:
        break;
}

function redirect($url, $condition = true) {
    if ($condition) {
        header('Location: '.$url);
        exit;
    }
}
