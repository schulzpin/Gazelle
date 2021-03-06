<?php

use Phinx\DB\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class Applicant extends AbstractMigration {
	public function up() {
		$this->table('thread_type', ['id' => false, 'primary_key' => 'ID'])
			->addColumn('ID', 'integer', ['limit' => 6, 'signed' => false, 'identity' => true])
			->addColumn('Name', 'string', ['limit' => 20])
			->addIndex(['Name'], ['unique' => true])
			->create();

		$this->table('thread', ['id' => false, 'primary_key' => 'ID'])
			->addColumn('ID', 'integer', ['limit' => 6, 'signed' => false, 'identity' => true])
			->addColumn('ThreadTypeID', 'integer', ['limit' => 6, 'signed' => false])
			->addColumn('Created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
			->addForeignKey('ThreadTypeID', 'thread_type', 'ID')
			->create();

		$this->table('thread_note', ['id' => false, 'primary_key' => 'ID'])
			->addColumn('ID', 'integer', ['limit' => 6, 'signed' => false, 'identity' => true])
			->addColumn('ThreadID', 'integer', ['limit' => 6, 'signed' => false])
			->addColumn('Created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
			->addColumn('UserID', 'integer', ['limit' => 10, 'signed' => false])
			->addColumn('Body', 'text', ['limit' => MysqlAdapter::TEXT_MEDIUM])
			->addColumn('Visibility', 'enum', ['values' => ['staff', 'public']])
			->addForeignKey('ThreadID', 'thread', 'ID')
			->addForeignKey('UserID', 'users_main', 'ID')
			->create();

		$this->table('applicant_role', ['id' => false, 'primary_key' => 'ID'])
			->addColumn('ID', 'integer', ['limit' => 4, 'signed' => false, 'identity' => true])
			->addColumn('Title', 'string', ['limit' => 40])
			->addColumn('Published', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 0])
			->addColumn('Description', 'text')
			->addColumn('UserID', 'integer', ['limit' => 10, 'signed' => false])
			->addColumn('Created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
			->addColumn('Modified', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
			->addForeignKey('UserID', 'users_main', 'ID')
			->create();

		$this->table('applicant', ['id' => false, 'primary_key' => 'ID'])
			->addColumn('ID', 'integer', ['limit' => 4, 'signed' => false, 'identity' => true])
			->addColumn('RoleID', 'integer', ['limit' => 4, 'signed' => false])
			->addColumn('UserID', 'integer', ['limit' => 10, 'signed' => false])
			->addColumn('ThreadID', 'integer', ['limit' => 6, 'signed' => false])
			->addColumn('Body', 'text')
			->addColumn('Created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
			->addColumn('Modified', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
			->addColumn('Resolved', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 0])
			->addForeignKey('RoleID', 'applicant_role', 'ID')
			->addForeignKey('ThreadID', 'thread', 'ID')
			->addForeignKey('UserID', 'users_main', 'ID')
			->create();

		$this->insert('thread_type', [
			['name' => 'staff-pm'],
			['name' => 'staff-role'],
			['name' => 'torrent-report']
		]);
	}

	public function down() {
		$this->dropTable('applicant');
		$this->dropTable('applicant_role');
		$this->dropTable('thread_note');
		$this->dropTable('thread');
		$this->dropTable('thread_type');
	}
}
