<?php

namespace controllers;

use library\database;

use function library\session_remove;
use function library\session_set;

class authentication implements controller {
	private $database;

	public function __construct(database $database = null) {
		$this->database = $database;
	}

	public function register(string $username, string $password) : void {
		if (!$this->database) {
			LOG_CRITICAL('Database is set to null');
		}

		$salt = bin2hex(openssl_random_pseudo_bytes(11));
		if ($this->database->execute(
			'insert into users (username, password, salt) values (:username, :password, :salt)', 
			['username' => $username, 'password' => hash('sha256', $salt . $password), 'salt' => $salt]
		)) {
			redirect('/');
		}
	}

	public function login(string $username, string $password) : void {
		if (!$this->database) {
			LOG_CRITICAL('Database is set to null');
			redirect('/');
		}

		$user = $this->find_user($username);
		if ($user && $user->password === hash('sha256', $user->salt . $password)) {
			session_set(CONFIG('SESSION_AUTH'), $user->id);
			redirect('/');
		}
	}

	private function find_user($username) : ?object {
		$sql = 'select * from users where username = :username';
		$user = $this->database->fetch($sql, ['username' => $username]);
		return $user ?? null;
	}

	public function logout() : void {
		session_remove(CONFIG('SESSION_AUTH'));
		redirect('/');
	}

}