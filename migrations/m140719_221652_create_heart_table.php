<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\rbac\DbManager;

class m140719_221652_create_heart_table extends Migration
{
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }
	
	public function safeUp()
    {
		$tableOptions = null;	
		switch (Yii::$app->db->driverName) {
            case 'mysql':
                $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
                break;
            default:
                throw new RuntimeException('Your database is not supported!');
        }
		$this->createTable('{{%user}}', [
            'id'            => Schema::TYPE_PK,
            'username'      => Schema::TYPE_STRING . '(25) NOT NULL',
            'email'         => Schema::TYPE_STRING . '(255) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . '(60) NOT NULL',
            'auth_key'      => Schema::TYPE_STRING . '(32) NOT NULL',

            // confirmation
            'confirmation_token'   => Schema::TYPE_STRING . '(32)',
            'confirmation_sent_at' => Schema::TYPE_INTEGER,
            'confirmed_at'         => Schema::TYPE_INTEGER,
            'unconfirmed_email'    => Schema::TYPE_STRING . '(255)',

            // recovery
            'recovery_token'   => Schema::TYPE_STRING . '(32)',
            'recovery_sent_at' => Schema::TYPE_INTEGER,

            // block
            'blocked_at' => Schema::TYPE_INTEGER,

            // RBAC
            'role' => Schema::TYPE_STRING . '(255)',

            // trackable
            'registered_from' => Schema::TYPE_INTEGER,
            'logged_in_from'  => Schema::TYPE_INTEGER,
            'logged_in_at'    => Schema::TYPE_INTEGER,

            // timestamps
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createIndex('user_unique_username', '{{%user}}', 'username', true);
        $this->createIndex('user_unique_email', '{{%user}}', 'email', true);
        $this->createIndex('user_confirmation', '{{%user}}', 'id, confirmation_token', true);
        $this->createIndex('user_recovery', '{{%user}}', 'id, recovery_token', true);

        $this->createTable('{{%profile}}', [
            'user_id'        => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'name'           => Schema::TYPE_STRING . '(255)',
            'public_email'   => Schema::TYPE_STRING . '(255)',
            'gravatar_email' => Schema::TYPE_STRING . '(255)',
            'gravatar_id'    => Schema::TYPE_STRING . '(32)',
            'location'       => Schema::TYPE_STRING . '(255)',
            'website'        => Schema::TYPE_STRING . '(255)',
            'bio'            => Schema::TYPE_TEXT
        ], $tableOptions);

        $this->addForeignKey('fk_user_profile', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
		
		$this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',

            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
		
		$this->createTable('{{%account}}', [
            'id' => Schema::TYPE_PK,
			'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'provider' => Schema::TYPE_STRING . '(255) NOT NULL',
            'client_id' => Schema::TYPE_STRING . '(255) NOT NULL',
            'properties' => Schema::TYPE_TEXT ,
            
        ], $tableOptions);
		
		$this->createIndex('account_unique_user_id', '{{%account}}', 'user_id', true);
		$this->addForeignKey('fk_user_account', '{{%user}}', 'user_id', '{{%profile}}', 'id', 'CASCADE', 'RESTRICT');
		
		$authManager = $this->getAuthManager();

        $this->createTable($authManager->ruleTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
            'child' => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'user_id' => Schema::TYPE_STRING . '(64) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
		
		$this->createTable('{{%menu}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'parent' => Schema::TYPE_INTEGER. ' NULL',
            'route' => Schema::TYPE_STRING . '(256)',
            'order' => Schema::TYPE_INTEGER,
            'data' => Schema::TYPE_TEXT,
            'FOREIGN KEY (parent) REFERENCES {{%menu}}(id) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);
		
    }

    public function safeDown()
    {
        //echo "m140719_221652_create_heart_table cannot be reverted.\n";

        //return false;
		$this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
		$this->dropTable('{{%account}}');
		$authManager = $this->getAuthManager();

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
		$this->dropTable('{{%menu}}');
    }
}
