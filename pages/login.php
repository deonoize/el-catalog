<?

/** @var Doctrine\DBAL\Connection $conn **/

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "Login or password is empty";
    } else {
        $query = $conn->executeQuery('SELECT * FROM user WHERE email=?', [$login]);
        $user = $query->fetchAssociative();
        if (isset($user) && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['id'];
            $_SESSION['user_hash'] = password_hash($user['id'].getenv('APP_KEY'), PASSWORD_DEFAULT);
            header('Location:/');
            exit;
        } else {
            $error = "Incorrect login or password";
        }
    }
}

require __DIR__.'/../views/login.php';
