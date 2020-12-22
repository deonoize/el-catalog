<?

/** @var Doctrine\DBAL\Connection $conn **/

if (isset($_POST['login']) && isset($_POST['password1']) && isset($_POST['password2'])) {
    $login = trim($_POST['login']);
    $password1 = trim($_POST['password1']);
    $password2 = trim($_POST['password2']);

    if (empty($login) || empty($password1) || empty($password1)) {
        $error = "Login or passwords is empty";
    } else {
        $query = $conn->executeQuery('SELECT * FROM user WHERE email=?', [$login]);
        $user = $query->fetchAssociative();
        if (!$user) {
            if ($password1 === $password2) {
                $passwordHash = password_hash($password1, PASSWORD_DEFAULT);
                $query = $conn->executeQuery('INSERT INTO user (email, password, first_name, last_name, phone_number, role) VALUES (?,?,"","","","")', [$login, $passwordHash]);
                $userId = $conn->lastInsertId();
                $_SESSION['user'] = $userId;
                $_SESSION['user_hash'] = password_hash($userId.getenv('APP_KEY'), PASSWORD_DEFAULT);
                header('Location:/');
                exit;
            } else {
                $error = "Passwords are not the same";
            }
        } else {
            $error = "This email already use";
        }
    }
}

require __DIR__.'/../views/registration.php';
