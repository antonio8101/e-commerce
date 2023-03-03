<?php

require_once "inc/config.inc.php";
require_once "inc/helper.inc.php";
require_once "templates.php";
require_once "mysqli/mysqli.php";
require_once "mysqli/mysqli.php";
require_once "mysqli/query.php";
require_once "inc/session.php";



$db          = connectMySQLi( HOST, PORT, USER, PASSWORD, DB );
$queryResult = getContacts($db);
$searchKey   = array_key_exists( 'search', $_GET ) ? $_GET['search'] : null;
$queryResult = searchContacts( $db, $searchKey );

?>



    <div class="create-btn">
        <a href="Registrati.php"><i class="bi bi-plus"></i></a>
    </div>
    <div class="card-container ">
        <div class="create-form card m-3">
            <div class="card-body">
                <h5 class="card-title">Lista contatti:</h5>
                <input class="form-control mb-2" type="text" id="searchText" oninput="processChange(this)"
                       placeholder="Scrivi il Nome, Cognome o Email...">
                <div class="list-group">
                    <!-- SINGLE CONTACT-->
                    <?php while($row = $queryResult ->fetch_assoc()): ?>
                    <?php var_dump($row) ?>
                    <a href="#" class="d-flex list-group-item list-group-item-action"  aria-current="true">
                        <div class="profile-pic me-2">
                        </div>
                        <div>
                            <div class="w-100 mb-1">
                                <h5 class="m-0"><?= $row['name']?> <?= $row['surname']?> </h5>
                            </div>
                            <p class="m-0"><i class="bi bi-telephone"></i><?= $row['phone_number']?> </p>
                            <small class="m-0"><i class="bi bi-envelope"></i><?= $row['email']?> </small>
                        </div>
                    </a>
                    <?php endwhile;  ?>










                    <?php /* $counter = 0; ?>
                    <?php if ($queryResult): ?>
                        <?php while ( $row = $queryResult->fetch_assoc() ): ?>
                            <a href="view.php?contactId=<?= $row['id']?>" class="d-flex list-group-item list-group-item-action" aria-current="true">
                                <div>
                                    <div class="w-100 mb-1">
                                        <h5 class="m-0"><?= $row['Nome'] ?> <?= $row['Cognome'] ?></h5>
                                    </div>
                                    <p class="m-0"><i class="bi bi-telephone"></i><?= $row['phone_number'] ?></p>
                                    <small class="m-0 "><i class="bi bi-envelope"></i> <?= $row['email'] ?></small>
                                </div>
                            </a>
                        -->
                            <?php $counter++; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>

                    <?php if (!$queryResult || $counter === 0): ?>
                        <div class="d-flex list-group-item justify-content-center align-items-center">
                            No data
                        </div>
                    <?php endif; */?>


                    <script>
                        window.addEventListener('load', function () {
                            const url = new URL(window.location.href);
                            const searchParam = url.search.replace('?search=', '');

                            if (searchParam.length > 0) {
                                const elm = document.getElementById('searchText');

                                if (searchParam.length > 0) {

                                    elm.focus();
                                    elm.value = searchParam;
                                }
                            }
                        })

                        function refreshList(elm) {
                            location.href = location.origin + location.pathname + '?' + 'search=' + elm.value;
                        }

                        function debounce(func, timeout = 10) {
                            let timer;
                            return (...args) => {
                                clearTimeout(timer);
                                timer = setTimeout(() => {
                                    func.apply(this, args);
                                }, timeout);
                            };
                        }

                        const processChange = debounce((elm) => refreshList(elm));
                    </script>
                </div>
            </div>
        </div>
    </div>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pre e-commerce</title>
    <link href="
https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css
" rel="stylesheet">


