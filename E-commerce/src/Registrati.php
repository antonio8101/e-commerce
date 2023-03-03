<?php
require_once  "inc/config.inc.php";
require_once "inc/Helper.inc.php";
require_once  "mysqli/mysqli.php";
require_once  "inc/session.php";
require_once  "pdo/ConnectToPDO.php";

if (function_exists('startSession')) {
    startSession($_POST);
}

$invalidFields = getInvalidFields ($_POST);
$isOk           = ! ( count( $invalidFields ) > 0 );
$contactCreated = true;


if ( $isOk && isProcessingForm() ) {

  /*  if (function_exists('connetcDb')) {
        $db = connectPDO(HOST,DB, USER,PASSWORD);
        $contactCreated = createUserData($db, $_POST);
*/
       // if ($contactCreated = true) {
        // echo $_SERVER['HTTP_HOST'];

        if (function_exists('connectPDO')) {
            $contactCreated = createContactWithPDO($_POST);
        } elseif (function_exists('connectMySQLi')) {
            $contactCreated = createContactWithMySQLi($_POST);
        } else {
            $contactCreated = true;
}
        if (function_exists('endSession')) {
            endSession();
        }


/*
         $protocol = $_SERVER['HTTPS'] == 'off' ? 'http' : 'https';
         $serverHost = $_SERVER['HTTP_HOST'];
         $page = 'index.php';
         $self = $_SERVER['PHP_SELF'];
         $self1 = explode('/',$self);
         $self1 [count($self1)-1] = "{$page}";
         $final_self = implode('/',$self1);

         $path = "{$protocol}://{$serverHost}{$final_self}";
        header('Location: ' . $path);
        exit();
*/       }



/** VALIDATION  */
function getInvalidFields ( $post ): array {

    $invalidFields = [];

    foreach ($post as $key => $value) {
        if (! isValid($key,$value ) ) {
            $invalidFields  [ $key ] = $value;
        }

    }

    return $invalidFields;

}

function isValid (string $fieldName, string $value ): bool {

    switch ($fieldName ) {
        case ('name'):
            return validateString( $value );
        case ('surname'):
            return strlen( $value ) == 0 || validateString ( $value);
        case ('email'):
            return validateEmail($value);
        case ('phone'):
            return validateNumber ($value);
    }

    return true;

}

function validateString($value): bool {
    return strlen($value ) > 0 && preg_match("^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$^",$value);
}

function validateEmail( $value ): bool {
    return filter_var($value,FILTER_VALIDATE_EMAIL);
}

function validateNumber( $value ): bool {
    return validateNumber($value);
}


function getValue (string $fieldName ): ?string {

    if (array_key_exists($fieldName, $_POST)) {
        return $_POST[$fieldName];
    }

    return null;
}

function alertfInvalid (string $nameOfField, array $invalidFields): ?string {

    if (array_key_exists($nameOfField,$invalidFields) ) {
        return 'is-invalid';
    }

    return null;
}

function isProcessingForm (): bool {
    return $_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('x-form',$_POST);
}
/** DATABASE */

/**
 * Create contact with PDO
 *
 * @param array $params
 *
 * @return bool
 */
function createContactWithPDO( array $params ): bool
{

    $db = connectPDO(HOST, DB, USER, PASSWORD);

    if (!is_null($db)) {

        $contactCreated = createContact($db, $params);

        closePDO( $db );


       return $contactCreated;
    }
    return false;
}

/**
 * Create contact with Mysqli
 *
 * @param array $params
 *
 * @return bool
 */


function createContactWithMysqli (array $params): bool {

    $db = connectMySQLi(HOST,PORT,USER,PASSWORD,DB) ;

        if (! is_null($db ) ) {

            $contactCreated = mysqliCreateContact($db,$params);

            closeMySQLi($db);

            return $contactCreated;

        }

        return false;
}

?>


<?php if ( isProcessingForm() && $isOk && $contactCreated ): ?>

<?php    if ($_SERVER['REQUEST_METHOD'] == 'post') {
    var_dump($_POST);
    }
?>


    <div class="card-container">
        <div class="create-form card m-3">
            <div class="card-body">
                <h5 class="card-title  centered">Contatto inserito</h5>
                <div class="icon-container">
                    <i class="bi bi-check-circle.fill centered"></i>
                </div>
            </div>
        </div>
    </div>

    <form class="centered"><a href="<?= $_SERVER['PHP_SELF'] ?>">Inserisci un nuovo contatto</a></form>
     <div class="centered"><a href="list.php">Lista Contatti</a></div>

<?php else: ?>

    <div class="card-container">
        <div class="create-form card m-3">
            <div class="card-body">

                        <?php if (! $isOk || ! $contactCreated ): ?>
                <div class="alert alert-danger" role="alert">
                    <p>Inserimento fallito!</p>
                    <p class="error-log"><?=getLog()?></p>
                </div>

                            <?php  endif; ?>

            </div>
        </div>
    </div>


<html lang="en"  xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pre e-commerce</title>
    <link href="
https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css
" rel="stylesheet">
    <style>
        div.container{
            margin-top: 120px;
            max-height: 400px;
            max-width: 400px;
        }
    </style>
</head>
<body>
<div class="container">
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">


        <div class="col-md-12">
            <label for=Nome" class="form-label">Nome</label>
            <input type="text"
                   class="form-control <?= alertfInvalid('Nome', $invalidFields)?>"
                   value="<?= getValue('Nome',$invalidFields) ?>" name="Nome"
                   oninput="removeInvalid(this)"
                   id="InputName" placeholder="Mario" required>
        </div>

        <div class="col-md-12">
            <label for="Cognome" class="form-label">Cognome</label>
            <input type="text"
                   class="form-control <?= alertfInvalid('Cognome', $invalidFields)?>"
                   value="<?= getValue('Cognome',$invalidFields) ?>" name="Cognome"
                   oninput="removeInvalid(this)"
                   id="InputSurname" placeholder="Rossi" required>
        </div>


            <div class="row g-3" method="post">
                <input type="hidden" name="x-form" value="contacts">
        <div class="col-md-12">
            <label for="email" class="form-label">Email</label>
            <input type="email"
                   class="form-control <?= alertfInvalid('email',$invalidFields) ?>"
                   value="<?= getValue('email') ?>" name="email"
                    oninput="removeInvalid(this)"
                    id="email" placeholder="MarioRossi@esempio.it" required>
        </div>

       <div class="col-md-12">
           <label for="password" class="form-label">Password</label>
           <input type="password"
                  class="form-control <?= alertfInvalid('password', $invalidFields)?>"
                  value="<?= getValue('password',$invalidFields) ?>" name="password"
                  oninput="removeInvalid(this)"
                  id="password" placeholder="Password" required>
       </div>

       <div class="col-md-12">
           <label for="indirizzo" class="form-label">Indirizzo</label>
           <input type="text"
                  class="form-control <?= alertfInvalid('indirizzo', $invalidFields)?>"
                  value="<?= getValue('indirizzo',$invalidFields) ?>" name="indirizzo"
                  oninput="removeInvalid(this)"
                  id="InputAddress" placeholder="Via delle quali 23 " >
       </div>

       <div class="col-md-12"
        <label for="inputCity" class="form-label">Città</label>
        <input type="text"
               class="form-control <?= alertfInvalid('città', $invalidFields)?>"
               value="<?= getValue('città',$invalidFields) ?>" name="città"
               oninput="removeInvalid(this)"
               id="InputAddress" placeholder="Roma">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary" value="crea">Registrati</button>
        </div>

    </form>
    </form>


</div>
<script>

    function removeInvalid(elm) {
        elm.classList.remove('is-Invalid');
    }

</script>

<script src="
https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js
"></script>
</body>
</html>

<?php endif ?>


<?php unset($_SESSION['errors']); ?>
