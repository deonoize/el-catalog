<?

/** @var Doctrine\DBAL\Connection $conn * */

use Doctrine\DBAL\Query\QueryBuilder;

//Получаем категории
$query = $conn->executeQuery('SELECT * FROM category');
$categoriesFormDB = $query->fetchAllAssociative();
$categories = [];
foreach ($categoriesFormDB as $category) {
    $categories[$category['parent']][] = $category;
}

//Получаем товар
$productQueryBuilder = new QueryBuilder($conn);
$productQueryBuilder->select('p.*', 'ANY_VALUE(c.name) as category_name')->from('product', 'p')->leftJoin(
    'p',
    '`product-category`',
    'pc',
    'p.id=pc.id_product'
)->leftJoin('p', 'category', 'c', 'c.id=pc.id_category')->where('p.id = :id');
$productQueryBuilder->setParameter('id', $_GET['id']);

$query = $productQueryBuilder->execute();
$product = $query->fetchAssociative();
redirect('/', !$product);

//Получаем свойств из запроса для товаров
$propsQueryBuilder = new QueryBuilder($conn);
$propsQueryBuilder->select('pr.id', 'pr.name', 'pr.type', 'ppr.value')
                  ->from('`product-characteristic`', 'ppr')
                  ->leftJoin('ppr', 'characteristic', 'pr', 'pr.id=ppr.id_characteristic')
                  ->where('ppr.id_product = :id');
$propsQueryBuilder->setParameter('id', $_GET['id']);

$query = $propsQueryBuilder->execute();
$productProps = $query->fetchAllAssociative();

require __DIR__.'/../views/product.php';
