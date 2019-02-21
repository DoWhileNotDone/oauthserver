<?php

use Phinx\Migration\AbstractMigration;

class UsersMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTables
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $users = $this->table("users");
        $users
            ->addColumn("name", "string", ["limit" => 255, "null" => false])
            ->addColumn("password", "string", ["limit" => 255, "null" => false])
            ->addColumn("email", "string", ["limit" => 255, "null" => false])
            ->addTimestamps()
            ->addIndex(["id"], ["unique" => true, "name" => "users_index"])
            ->addIndex(['email'], ['unique' => true]);
        $users->create();
    }
}
