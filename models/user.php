<?php

namespace models;

use library\database;

class user extends model {

    public function __construct(database $database) {
        parent::__construct($database);
        $this->table = 'users';
    }

    public function access_user_data(int $user_access_id, array $columns = ['*']) : ?object {
        $select_str = $this->sql_columns($columns) . ', users_access.expiry';
        $sql = "select $select_str from {$this->table} join users_access on {$this->table}.id = users_access.user_id where users_access.id = :user_access_id";
		return $this->database->fetch($sql, ['user_access_id' => $user_access_id]) ?? null;
    }
}