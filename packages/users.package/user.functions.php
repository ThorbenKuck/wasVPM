<?php
namespace Users;

use Main\DBManager;
use PDO;

if (!defined ('HASH_MAX_LENGTH')) {
	define ('HASH_MAX_LENGTH', 50);
}

date_default_timezone_set ('Europe/Berlin');

/**
 * Gets the username of the logdin user from the database "Logins"
 *
 * @return NULL|string Returns the Username, if found, and NULL if any error accoured
 */
function get_username () {
	if (!\Main\is_logdin ())
		return "Unknown";
	if (empty ($_SESSION ["vpm_login_session"]))
		return null;

	$stmt = DBManager::access ()->get_prepared_statement (GET_USERNAME_FOR_HASH);
	$stmt->bindParam (1, \Main\get_identifier ());
	$stmt->execute ();
	if (!$stmt) {
		return false;
	} else {
		$result = $stmt->fetch (PDO::FETCH_ASSOC);
		return $result ["username"];
	}
}

/**
 * verifys wheter or not a login-attempt is correct or not.
 *
 * @param string $username
 *            The username of the user, trying to log in
 * @param string $password
 *            The password of the user, trying to log in
 * @return bool Returns true if successful and else false
 */
function verify_login (string $username, string $password) : bool {
	if (\Main\login_locked ()) {
		empty_hashes ();
		return false;
	}
	$stmt = DBManager::access ()->get_prepared_statement (GET_PASSHAS_FOR_USERNAME);
	$stmt->bindParam (1, $username);
	$stmt->execute ();
	if (!$stmt) {
		return false;
	} else {
		$erg = $stmt->fetch (PDO::FETCH_ASSOC);
	}
	return password_verify ($password, $erg ["passhash"]);
}

function verify_hash (string $hash, string $username) : bool {
	$stmt = DBManager::access ()->get_prepared_statement (GET_USERNAME_FOR_HASH);
	$stmt->bindParam (1, $hash);
	return $stmt->execute ();
}

function empty_hashes () {
	try {
		$pdo = new PDO(DBManager::get_connection_string (), DBManager::get_username (), DBManager::get_password ());
		$pdo->query (PURGE_HASHES_QUERY);
		\Main\return_new_error ("Die Anmeldung wurde ausgesetzt!" . "<br>" . "Bei Bedarf kontaktieren sie bitte einen Administrator!" . "<br>" . "Sollte dieses Problem weiterhing bestehen bleiben, kontaktieren sie bitte den Root-User");
	} catch ( \PDOException $e ) {
		echo $e->getMessage ();
		\Main\log ($e->getMessage ());
	} finally {
		$pdo = null;
	}
}

/**
 * Creates a random string of the minimum length $length and the maximum length HASH_MAX_LENGTH or 50
 *
 * @param int $length
 *            The startlength of a hash.
 * @return string|boolean Returns the generated String or false if anything has gone wrong.
 */
function create_random_hash ($length = 25) {
	if (!defined (HASH_MAX_LENGTH)) {
		$max_length = 50;
	} else {
		$max_length = HASH_MAX_LENGTH;
	}
	// Der String für alle möglichen Zeichen in dem random_hash
	$possibilities = "0123456789%abcdefghijklmn%opqrstuvwxyz%ABCDEFGHIJ%KLMNOPQRSTUVWXYZ&./\"";

	// länge des Strings der alle möglichkeiten beinhaltet
	$string_length = strlen ($possibilities) - 1;

	// Die Variable, die den entgültigen random-cache enthalten wird
	$erg = "";
	for ( $i = 0 ; $i < $length ; $i ++ ) {
		$erg .= $possibilities [rand (0, $string_length)];
	}

	if ($length >= $max_length) {
		// Die Maximale Länge wurde erreicht
		return str_replace ("%", "", $erg);
	} else if (strpos ($erg, "%") !== false) {
		// Der String enthält ein % zeichen
		return create_random_hash (++ $length);
	} else {
		// es wurde ein String gefunden, der kein % enthält
		return $erg;
	}
}

/**
 * Trys to log a user in.
 * Automaticly checks, if the login is veryfied or not.
 *
 * @param string $username
 *            The username of the user, who is trying to log in
 * @param string $password
 *            The password of the user, who is trying to log in
 * @param boolean $keep_logdin
 *            Boolean, if the user wants to keepd logdin
 * @return NULL|boolean Returns null, if anything goes wrong and a boolean, wether or not the login was successful
 */
function login_user ($username, $password, $keep_logdin = false) {
	if (login_locked ()) {
		return false;
	}
	if (is_logdin ()) {
		return true;
	}
	$ip = $_SERVER ["REMOTE_ADDR"];
	if (verify_login ($username, $password)) {

		$r_hash = create_random_hash ();
		// counter, der Prüft, dass die While-Schleife nicht endlos läuft
		$counter = 0;

		$stmt = DBManager::access ()->get_prepared_statement (GET_HASHES_FOR_USERNAME);
		$stmt->bindParam (1, $username);
		$stmt->execute ();
		if ($stmt && $stmt->rowCount () > 0) {
			\Main\log ("Zwangsabmeldung fuer \"" . $username . "\"! Vergessener logout?");
			\Main\return_new_warning ("Denken sie daran, sich ab zu melden, um ihren Account sicherer zu machen");
			$stmt = DBManager::access ()->get_prepared_statement (DELETE_HASH_FOR_USER);
			$stmt->bindParam (1, $username);
			$stmt->execute ();
		}


		// dann alle bereits belegten hashes eliminieren.
		$stmt = DBManager::access ()->get_prepared_statement ("SELECT `hash` FROM `Logins` WHERE `hash`=?");
		$stmt->bindParam (1, $r_hash);
		$stmt->execute ();
		while ( $stmt->rowCount () > 0 ) {
			$r_hash = create_random_hash ();
			$stmt->execute ();
		}


		$stmt = DBManager::access ()->get_prepared_statement (LOGIN_QUERY);
		$stmt->bindParam (1, $username);
		$stmt->bindParam (2, $r_hash);

		if (!$stmt->execute ()) {
			// Trit ein Fehler beim Eintragen des Nutzers in die Datenbank "logins" auf, so returne null
			\Main\log ("Ein Fehler trat auf, als der Nutzer: \"" . $username . "\" versucht wurde an zu melden");
			return null;
		} else {

			$stmt = DBManager::access ()->get_prepared_statement (GET_PERMISSION_FOR_USERNAME);
			$stmt->bindParam (1, $username);
			$stmt->execute ();
			\Main\login ($username, $stmt->fetch (PDO::FETCH_ASSOC)['permissions'], $r_hash);
			\Main\log ("Erfolgreiche angemeldung von Nutzer: \"" . $username . "\". IP: " . $ip);
			\Main\log ("Login-Hash fuer Nutzer \"" . $username . "\": " . $r_hash);

			return is_logdin ();
		}
	} else {
		\Main\log ("FEHLGESCHLAGENE angemeldung von Nutzer: \"" . $username . "\". IP: " . $ip);
		return false;
	}
}

/**
 * Logs the current user out
 *
 * @return bool Returns the success of the logout as a boolean
 */
function logout_user () : bool {
	if (!\Main\is_logdin ()) {
		return true;
	}
	$hash = \Main\get_identifier ();
	$username = \Main\get_username ();

	$stmt = DBManager::access ()->get_prepared_statement (DELETE_HASH_FOR_USER);
	$stmt->bindParam (1, $username);

	if ($stmt->execute ()) {
		\Main\logout ();
		\Main\log ("Der Nutzer " . $username . " hat sich erfolgreich vom System abgemeldet");
		return true;
	} else {
		\Main\log ("Beim abmelden des Nutzers " . $username . " gabe es einen Fehler ..");
		return false;
	}

}

/**
 * A function, wich checks whether or not the current user is logdin
 *
 * @return boolean True if logdin, false if logdout
 */
function is_logdin () : bool {
	if (login_locked ()) {
		empty_hashes ();
		return false;
	}

	if (\Main\is_logdin ()) {
		$hash = \Main\get_identifier ();
		$username = \Main\get_username ();

		$stmt = DBManager::access ()->get_prepared_statement (GET_HASHES_FOR_USERNAME);
		$stmt->bindParam (1, $username);
		if ($stmt->execute ()) {
			$hash2 = $stmt->fetch (PDO::FETCH_ASSOC)['hash'];
			\Main\debug_input ("system_identifier: " . $hash);
			\Main\debug_input ("user_identifier: " . $hash2);
			if ($hash !== $hash2) {
				\Main\logout ();
				return false;
			}
			return true;
		} else {
			\Main\log ("Fehler bei der Abfrage des Hashses für den Nutzer: " . $username . " mit dem Hash: " . $hash);
		}
	} else {
		return false;
	}
}

/**
 * Easy function to check if the current user ist administrator
 *
 * @return boolean Wheter or not the current user is Administrator
 */
function is_admin () {
	$permission = get_user_permissions ();

	if ($permission === false)
		return false;

	return ($permission > 1500 && $permission <= 4500);
}

/**
 * Easy function to check if the current user ist root
 *
 * @return boolean Wheter or not the current user is root
 */
function is_root () {
	$permission = get_user_permissions ();

	if ($permission === false)
		return false;

	return ($permission == 9999);
}

/**
 * A function to get the permissions of the current logd in user as an integer
 *
 * @return boolean|integer Returns the permissions as an integer or else false
 */
function get_user_permissions () {
	if (empty ($_SESSION ["vpm_login_session"]))
		return false;
	$sql = "
				SELECT `Permissions` FROM `Users`
				WHERE `UserName`=(
					SELECT `Username` FROM `Logins`
					WHERE `Hash`='" . $_SESSION ["vpm_login_session"] . "')";
	$link = mysqli_connect (MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
	if (!check_connection_to_database ($link))
		die ("Es gab einen Fehler mit der Datenbank!");
	$result = execute_sql_statement ($sql, $link);
	if (is_bool ($result))
		return false;
	else
		return $result ["Permissions"];
	// get username from logins where hash = $_SESSION['login']
	// return users.rechte
}

// #################Later###################

function create_new_user ($username, $password, $email, $permissions) {
	if (!is_logdin () || (!is_admin () || !is_root ()))
		die (return_new_warning ("Sie haben keine Berechtigung, dies zu tun!"));

	$hash = password_hash ($password, PASSWORD_BCRYPT);
	unset ($password);

	// is_logdin()
	// ####################################
	// is_admin()
	// allow to create $permissions > 500 && $permissions <= 1500;
	// ####################################
	// is_root()
	// allow to create $permissions > 500 && $permissions <= 4500;
	// ####################################
	// if not admin or root return error
	// ####################################
	// else
	// ####################################
	// write user to db
}

function delete_user ($username) {
	if (!is_logdin () || (!is_admin () || !is_root ()))
		die ("Sie haben keine Berechtigung, dies zu tun!");
	if ($username = "root")
		die ("How DARE you, trie to delete the root?!");
	// is_logdin()
	// ####################################
	// is_admin()
	// allow to create $permissions > 500 && $permissions <= 1500;
	// ####################################
	// is_root()
	// allow to create $permissions > 500 && $permissions <= 4500;
	// ####################################
	// if not admin or root return error
	// ####################################
	// else
	// ####################################
	// delete user
}

function change_permissions ($username) {

	// is_logdin()
	// ####################################
	// is_admin()
	// allow to change persons with 1500 >= right >=500
	// to 1500 >= right >= 500
	// ####################################
	// is_root()
	// allow to change persons with 4500 >= right >=500
	// to 4500 >= right >= 500
	// ####################################
	// else return noooooooooooooooooooo5
}

function login_locked () {
	// try to find a MLockLogin in db "Manual"
	return false;
}

function lock_login () {
}

function unlock_login () {
}

?>