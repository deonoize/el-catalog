<?
/** @var array $user * */
/** @var array $categories * */
/** @var array $products * */
/** @var array $props * */
/** @var string $searchQuery * */
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Catalog</title>
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
              integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
              crossorigin="anonymous">
        <link rel="stylesheet" href="/css/catalog.css">
    </head>
    <body>
        <nav class="navbar-light bg-white shadow py-2">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-4 d-flex">
                        <a class="navbar-brand " href="/">El-Catalog</a>
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a id="dropdownMenu1"
                                   href="#"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false"
                                   class="nav-link dropdown-toggle">Категории</a>
                                <ul aria-labelledby="dropdownMenu1" class="dropdown-menu border-0 shadow">
                                    <?
                                    print_menu($categories, "");
                                    function print_menu($menu, $id) {
                                        foreach ($menu[$id] as $menuItem) {
                                            if (isset($menu[$menuItem['id']])) {
                                                ?>
                                                <li class="dropdown-submenu dropright">
                                                    <a id="dropdownMenu<?= $menuItem['id'] ?>"
                                                       href="#"
                                                       role="button"
                                                       data-toggle="dropdown"
                                                       aria-haspopup="true"
                                                       aria-expanded="false"
                                                       class="dropdown-item dropdown-toggle">
                                                        <?= $menuItem['name'] ?>
                                                    </a>
                                                    <ul aria-labelledby="dropdownMenu<?= $menuItem['id'] ?>"
                                                        class="dropdown-menu border-0 shadow">
                                                        <? print_menu($menu, $menuItem['id']) ?>
                                                    </ul>
                                                </li>
                                                <?
                                            } else {
                                                ?>
                                                <li><a href="/?category=<?= $menuItem['id'] ?>"
                                                       class="dropdown-item"><?= $menuItem['name'] ?></a></li>
                                                <?
                                            }
                                        }
                                    }

                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <form method="get" class="d-flex my-2 my-lg-0 w-100">
                            <input class="form-control mr-sm-2"
                                   type="search"
                                   placeholder="Search"
                                   name="query"
                                   aria-label="Search"
                                   value="<?= $searchQuery ?>">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-12 col-md-2 d-flex justify-content-end">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= $user['first_name'].' '.$user['last_name'] ?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu">
                                <a href="/logout" class="dropdown-item" type="button">Exit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container mt-3 ">
            <div class="row">
                <div class="col-12 col-md-3">
                    <form method="get">
                        <?
                        if (isset($_GET['category'])) {
                            ?>
                            <input type="hidden" name="category" value="<?= $_GET['category'] ?>">
                            <?
                        }
                        if (isset($_GET['query'])) {
                            ?>
                            <input type="hidden" name="query" value="<?= $_GET['query'] ?>">
                            <?
                        }
                        ?>
                        <div class="card shadow mb-3">
                            <div class="card-body">
                                <h5 class="card-title"
                                    data-toggle="collapse"
                                    data-target="#filterProp0">Сортировать</h5>
                                <div id="filterProp0" class="collapsing">

                                    <div class="custom-control custom-radio">
                                        <input type="radio"
                                               name="order"
                                               class="custom-control-input"
                                               id="filterPropCheckbox0_0"
                                               value=""
                                            <?= empty($_GET['order']) ? 'checked' : '' ?>>
                                        <label class="custom-control-label"
                                               for="filterPropCheckbox0_0">-</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio"
                                               name="order"
                                               class="custom-control-input"
                                               id="filterPropCheckbox0_1"
                                               value="name"
                                            <?= isset($_GET['order']) && $_GET['order'] === 'name' ? 'checked' : '' ?>>
                                        <label class="custom-control-label"
                                               for="filterPropCheckbox0_1">по наименованию</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                        foreach ($props as $propId => $prop) {
                            ?>
                            <div class="card shadow mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"
                                        data-toggle="collapse"
                                        data-target="#filterProp<?= $propId ?>"><?= $prop['name'] ?></h5>
                                    <div id="filterProp<?= $propId ?>"
                                         class="<?= (isset($_GET['list_props'][$propId]) || isset($_GET['num_props'][$propId])) ?: 'collapsing' ?>">
                                        <?
                                        if ($prop['type'] === 'list') {
                                            $values = array_unique($prop['values']);
                                            natcasesort($values);
                                            foreach ($values as $key => $value) {
                                                ?>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                           name="list_props[<?= $propId ?>][]"
                                                           class="custom-control-input"
                                                           id="filterPropCheckbox<?= $propId.'_'.$key ?>"
                                                           value="<?= $value ?>"
                                                        <?= in_array(
                                                            $value,
                                                            $_GET['list_props'][$propId] ?? []
                                                        ) ? 'checked' : '' ?>>
                                                    <label class="custom-control-label"
                                                           for="filterPropCheckbox<?= $propId.'_'.$key ?>"><?= $value ?></label>
                                                </div>
                                                <?
                                            }
                                            ?>
                                            <?
                                        }
                                        if ($prop['type'] === 'number') {
                                            $values = array_unique($prop['values']);
                                            asort($values);
                                            $min = (float) $values[0];
                                            $max = (float) $values[count($values) - 1];
                                            ?>
                                            <div class="row no-gutters">
                                                <div class="col">
                                                    <div class="form-group mb-0">
                                                        <input type="number"
                                                               class="form-control"
                                                               name="num_props[<?= $propId ?>][min]"
                                                               min="<?= $min ?>"
                                                               max="<?= $max ?>"
                                                               value="<?= $_GET['num_props'][$propId]['min'] ?? '' ?>"
                                                               placeholder="от <?= $min ?>">
                                                    </div>
                                                </div>
                                                <div class="col-1"></div>
                                                <div class="col">
                                                    <div class="form-group mb-0">
                                                        <input type="number"
                                                               class="form-control"
                                                               name="num_props[<?= $propId ?>][max]"
                                                               min="<?= $min ?>"
                                                               max="<?= $max ?>"
                                                               value="<?= $_GET['num_props'][$propId]['max'] ?? '' ?>"
                                                               placeholder="до <?= $max ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <?
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?
                        }
                        ?>
                        <div class="card shadow mb-3">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">Применить</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-9">
                    <?
                    if (isset($products) && count($products)) {
                        foreach ($products as $product) {
                            ?>
                            <div class="card shadow mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $product['name'] ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?= $product['category_name'] ?></h6>
                                    <a href="/product?id=<?= $product['id'] ?>" class="card-link">Подробнее</a>
                                </div>
                            </div>
                            <?
                        }
                    } else {
                        ?>
                        <div class="card shadow mb-3">
                            <div class="card-body">
                                <div class="alert alert-danger mb-0" role="alert">
                                    К сожалению, нет подходящих товаров
                                </div>
                            </div>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
                integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
                crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
                crossorigin="anonymous"></script>
        <script src="/js/catalog_script.js"></script>
    </body>
</html>
