<?php namespace Main;

use PDO;

class DBManager {

	private static $instance;
	private $pdo;

	private function __construct() {
		$this->pdo = $this->create_pdo();
	}

	public static function access() : DBManager {
		if(self::$instance == null) {
			self::$instance = new DBManager();
		}
		return self::$instance;
	}

	public function __destruct () {
		$this->disconnect();
	}

	public function force_reconnect() {
		$this->disconnect();
		$this->pdo = $this->create_pdo();
	}

	private function create_pdo() : PDO {
		return new PDO(self::get_connection_string(), self::get_username(), self::get_password());
	}

	private function disconnect() {
		$this->pdo = null;
	}

	public static function create_initial_structure() : bool {
		// TODO
	}

	public function get_prepared_statement(string $query) : \PDOStatement {
		return $this->pdo->prepare($query);
	}

	public static function get_connection_string() {
		self::try_loading_config();
		return DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_DATABASE;
	}

	public static function get_username() {
		self::try_loading_config();
		return DB_USER;
	}

	public static function get_password() {
		self::try_loading_config();
		return DB_PASSWORD;
	}

	private static function try_loading_config() {
		if(!defined('DB_TYPE')) {
			require dirname(__FILE__) . "/connection.php";
		}
	}
}

?>