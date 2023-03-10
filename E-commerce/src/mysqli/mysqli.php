<?php

/**
 *  Create new entry in contacts table
 *
 * @param Mysqli $db
 * @param array $params
 *
 * @return bool
 */

function mysqliCreateContact (mysqli $db, array $params): bool {

    try {

            $statement = $db->prepare(
                "INSERT INTO contacts (name,surname,email,phone_number)
                       VALUES (?,?,?,?)");

        $name = array_key_exists('name', $params) ? $params['name'] : throw new Exception('Name is mandatory');
        $surname = array_key_exists('surname', $params) ? $params['surname'] : null ;
        $email = array_key_exists('email', $params) ? $params['email'] : throw new Exception('Email is mandatory');
        $phone_number = array_key_exists('phone', $params) ? $params['phone'] : throw new Exception('Phone is mandatory');
        $statement->execute( [
            $name,
            $surname,
            $email,
            $phone_number
        ]);

        return true;

    } catch (Exception $e) {

        addErrorLog($e->getMessage());
        addErrorLog(implode(',',$params) );

        return false;

    }

}

function deleteContact(mysqli $db, string $contactId,string $email): bool {

    try {
            $statement = $db->prepare("UPDATE contacts SET active = 0, email = ? WHERE id = ?");

            $statement->execute([
                $email,
                $contactId
            ]);

            return true;

    } catch (Exception $e) {

        addErrorToLog ($e->getMessage() );

        return false;

    }
}

/**
 * Gets new db connection
 *
 * @param string $host
 * @param string $port
 * @param string $user
 * @param string $password
 * @param string $db
 *
 * @return mysqli|null
 */

function connectMySQLi( string $host,string $port, string $user, string $password , string $db): ?mysqli {
    try {
            return new mysqli($host,$user,$password,$db,$port);
    } catch (Exception $exception) {

        var_dump($exception->getMessage() );
        var_dump(func_get_args());
       // addErrorToLog((string)func_get_args());

        return null;

    }
}


/**
 * Helps to close db connection
 *
 * @param mysqli $db
 *
 * @return void
 */
function closeMySQLi(mysqli $db ): void {
    $db->close();
}

function searchContacts( mysqli $db, ?string $text = null ): mysqli_result|bool {

    try {

        if ( ! hasData( $text ) ) {
            return getContacts( $db );
        }

        $query = $db->prepare( "
							SELECT * FROM contacts WHERE 
                           	active = 1 AND
                           	MATCH(name,surname,email) AGAINST (? IN BOOLEAN MODE);" );

        $queryParams = [ '*' . $text . '*' ];

        $query->execute( $queryParams );

        return $query->get_result();
    } catch ( Exception $exception ) {
        addErrorToLog( $exception->getMessage() );

        return false;
    }
}
/*
function getContacts(mysqli $db): mysqli_result|bool


    $query =  $db->query('SELECT * FROM contacts');

    return nu
    }
*/
function getContact( mysqli $db, string $contactId ): ?array
{

    try {

        $query = $db->prepare("SELECT * FROM contacts WHERE id = ?");

        $query->execute([$contactId]);

        $result = $query->get_result();

        return $result->fetch_assoc();

    } catch (Exception $exception) {

        addErrorToLog($exception->getMessage());

        return null;
    }
}










