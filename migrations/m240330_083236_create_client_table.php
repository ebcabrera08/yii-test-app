<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m240330_083236_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string(),
            'cpf'           => $this->string()->notNull()->unique(),
            'picture'       => $this->string() ,
            'sex'      	    => $this->string() ,  
            'created_at'    => $this->integer(),  
            'created_by'    => $this->integer(),  
            'address_id'    => $this->integer(), 
        ]);
        // create index for column `created_by`
        $this->createIndex(
        'idx-client-created_by',
        'client',
        'created_by'
        );
        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-client-created_by',
            'client',
            'created_by',
            'user',
            'id',
            'CASCADE'
        );
         // create index for column `created_by`
         $this->createIndex(
            'idx-client-address_id',
            'client',
            'address_id'
        );
         // add foreign key for table `address`
         $this->addForeignKey(
            'fk-post-address_id',
            'client',
            'address_id',
            'address',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-client-created_by','{{%client}}');
        $this->dropIndex('idx-client-created_by', '{{%client}}'); 
        $this->dropForeignKey('fk-post-address_id','{{%client}}');
        $this->dropIndex('idx-client-address_id', '{{%client}}'); 
        $this->dropTable('{{%client}}');
    }
}
