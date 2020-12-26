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

//Создаем запрос для получения товаров
$productsQueryBuilder = new QueryBuilder($conn);
$productsQueryBuilder->select('p.*', 'ANY_VALUE(c.name) as category_name')->from('product', 'p')->leftJoin(
    'p',
    '`product-category`',
    'pc',
    'p.id=pc.id_product'
)->leftJoin('p', 'category', 'c', 'c.id=pc.id_category')->rightJoin(
    'p',
    '`product-characteristic`',
    'ppr',
    'p.id=ppr.id_product'
)->leftJoin('p', 'characteristic', 'pr', 'pr.id=ppr.id_characteristic');

if (isset($_GET['category'])) {
    $productsQueryBuilder->andWhere('c.id=:cat_id');
    $productsQueryBuilder->setParameter('cat_id', $_GET['category']);
}

//Получаем свойств из запроса для товаров
$propsQueryBuilder = clone $productsQueryBuilder;
$propsQueryBuilder->select('pr.id', 'pr.name', 'pr.type', 'ppr.value');

$query = $propsQueryBuilder->execute();
$propsFromDB = $query->fetchAllAssociative();
$props = [];
foreach ($propsFromDB as $prop) {
    $props[$prop['id']]['name'] = $prop['name'];
    $props[$prop['id']]['type'] = $prop['type'];
    $props[$prop['id']]['values'][] = $prop['value'];
}

//Получаем товары
if (isset($_GET['query'])) {
    $searchQuery = trim($_GET['query']);
    $productsQueryBuilder = $productsQueryBuilder->andWhere('p.name LIKE :query');
    $productsQueryBuilder->setParameter('query', '%'.$searchQuery.'%');
}
$propsWhereExpr = [];
$propsWhereParams = [];
if (isset($_GET['list_props'])) {
    $propsQuery = $_GET['list_props'];
    foreach ($propsQuery as $propId => $prop) {
        $propWhereExpr = [];
        foreach ($prop as $key => $value) {
            $propWhereExpr[] = $productsQueryBuilder->expr()->and(
                $productsQueryBuilder->expr()->eq('pr.id', ':prop'.$propId),
                $productsQueryBuilder->expr()->eq('ppr.value', ':prop_value'.$propId.'_'.$key)
            );
            $propsWhereParams['prop'.$propId] = $propId;
            $propsWhereParams['prop_value'.$propId.'_'.$key] = $value;
        }
        $propsWhereExpr[] = $productsQueryBuilder->expr()->or(...$propWhereExpr);
    }
}
if (isset($_GET['num_props'])) {
    $propsQuery = $_GET['num_props'];
    foreach ($propsQuery as $propId => $prop) {
        if ($prop['min'] == "" && $prop['max'] == "") {
            continue;
        }
        if ($prop['min'] > $prop['max']) {
            $buf = $prop['max'];
            $_GET['num_props'][$propId]['max'] = $prop['max'] = $prop['min'];
            $_GET['num_props'][$propId]['min'] = $prop['min'] = $buf;
        }
        $numPropWhereExpr = [
            $productsQueryBuilder->expr()->eq('pr.id', ':prop'.$propId),
        ];
        if ($prop['min'] != "") {
            $productsQueryBuilder->expr()->comparison('ppr.value', '>=', ':prop_min'.$propId);
            $propsWhereParams['prop_min'.$propId] = $prop['min'];
        }
        if ($prop['max'] != "") {
            $productsQueryBuilder->expr()->comparison('ppr.value', '<=', ':prop_max'.$propId);
            $propsWhereParams['prop_max'.$propId] = $prop['max'];
        }
        $propsWhereExpr[] = $productsQueryBuilder->expr()->or(
            $productsQueryBuilder->expr()->and(...$numPropWhereExpr)
        );
        $propsWhereParams['prop'.$propId] = $propId;
    }
}
if (count($propsWhereExpr) > 0) {
    $productsQueryBuilder->andWhere($productsQueryBuilder->expr()->or(...$propsWhereExpr));
    $productsQueryBuilder->setParameters(array_merge($productsQueryBuilder->getParameters(), $propsWhereParams));
    $productsQueryBuilder->having('COUNT(ppr.id_characteristic) >= :count_props');
    $productsQueryBuilder->setParameter('count_props', count($propsWhereExpr));
}
if (isset($_GET['order']) && in_array($_GET['order'], ['name'])) {
    $productsQueryBuilder->orderBy('p.'.$_GET['order']);
}
$productsQueryBuilder->andWhere('p.id IS NOT NULL');
$query = $productsQueryBuilder->groupBy('p.id')->execute();
$products = $query->fetchAllAssociative();

require __DIR__.'/../views/catalog.php';
