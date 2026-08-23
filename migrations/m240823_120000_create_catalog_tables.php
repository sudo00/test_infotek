<?php

use yii\db\Migration;

class m240823_120000_create_catalog_tables extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->notNull()->unique(),
            'cover_image' => $this->string(512),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx-book-year', '{{%book}}', 'year');

        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk-book_author', '{{%book_author}}', ['book_id', 'author_id']);
        $this->addForeignKey(
            'fk-book_author-book',
            '{{%book_author}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE',
            'CASCADE',
        );
        $this->addForeignKey(
            'fk-book_author-author',
            '{{%book_author}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx-subscription-author_phone', '{{%subscription}}', ['author_id', 'phone'], true);
        $this->addForeignKey(
            'fk-subscription-author',
            '{{%subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $time = time();
        $this->insert('{{%user}}', [
            'username' => 'user',
            'password_hash' => '$2y$10$ppK8jqHlHnj8RmZNHxEHn.4hkKPYuDghKGAHjZDP7XE0yngoHyZfG',
            'auth_key' => 'demo-auth-key-0001',
            'created_at' => $time,
            'updated_at' => $time,
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%subscription}}');
        $this->dropTable('{{%book_author}}');
        $this->dropTable('{{%book}}');
        $this->dropTable('{{%author}}');
        $this->dropTable('{{%user}}');
    }
}
