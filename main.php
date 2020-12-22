<?
require_once __DIR__.'/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Symfony\Component\Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

session_start();

$dotenv = new Dotenv(true);
$dotenv->load(__DIR__.'/.env');

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$connectionParams = [
    'dbname'   => getenv('DB_DATABASE'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'driver'   => 'pdo_mysql',
];

$conn = DriverManager::getConnection($connectionParams);

if (isset($_SESSION['user']) && isset($_SESSION['user_hash'])) {
    if (password_verify($_SESSION['user'].getenv('APP_KEY'), $_SESSION['user_hash'])) {
        $userId = $_SESSION['user'];
        $query = $conn->executeQuery('SELECT * FROM user WHERE id=?', [$userId]);
        $user = $query->fetchAssociative();
    }
}
