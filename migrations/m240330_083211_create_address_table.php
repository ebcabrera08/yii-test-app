<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%address}}`.
 */
class m240330_083211_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%address}}', [
            'id'              => $this->primaryKey(),
            'zip'             => $this->string(),
            'public_place'    => $this->string()->notNull(),
            'number'          => $this->string()->notNull(),
            'complement'      => $this->string(),  
            'city'            => $this->string()->notNull() , 
            'state'           => $this->string()->notNull() , 
            'created_at'      => $this->integer(),  
            'created_by'      => $this->integer(),      
        ]);
          // create index for column `created_by`
          $this->createIndex(
            'idx-address-created_by',
            'address',
            'created_by'
        );
        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-address-created_by',
            'address',
            'created_by',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-address-created_by','{{%address}}');
        $this->dropIndex('idx-address-created_by', '{{%address}}');  
        $this->dropTable('{{%address}}');
    }
}
