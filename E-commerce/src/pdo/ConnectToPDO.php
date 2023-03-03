<?php

$host = 'localhost' ;
$dbName = 'e_commerce_db';
$user = "root";
$password = '2802';
$option = [];

function connectPDO($host,$dbName,$user,$password, ) : ?PDO {
    try {
        return new PDO("mysql:host={$host};dbname={$dbName}",
            $user,
            $password,
            []);
    } catch (Exception $e) {

        var_dump($e -> getMessage());

        return null;

    }
}
function closePDO(PDO $db ): void {
    $db = null ;
}
?>

<?php

/**
 * Create new UserData // create contact
 *
 * @param PDO $db
 * @param array $_params
 * @return bool
 */
function createContact(PDO $db, array $_params): bool { //createContact

    try {

        $command = "INSERT INTO table_u<sersdata (Email, Password, Indirizzo, Città)
                    VALUES (?,?,?,?)";

        $statement = $db->prepare($command);

        $email = array_key_exists('email',$_params) ? $_params['email'] : throw new Exception('email is mandatory');
        $password = array_key_exists('password',$_params) ? $_params['password'] : throw new Exception('password is mandatory');
        $indirizzo = array_key_exists('indirizzo', $_params) ? $_params['indirizzo'] : null;
        $città = array_key_exists('città', $_params) ? $_params['città'] : null;

        $statement->execute([
            $email,
            $password,
            $indirizzo,
            $città,
        ]);

        return true;

    } catch (Exception $e) {

      //  var_dump($e->getMessage());
        var_dump($_params);

        return false;

    }

}
