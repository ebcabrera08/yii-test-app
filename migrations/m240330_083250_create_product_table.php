<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m240330_083250_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'price'       => $this->money()->notNull(),
            'picture'     => $this->string(),   
            'created_at'  => $this->integer(), 
            'client_id'   => $this->integer(),              
            'created_by'  => $this->integer(), 
        ]);

         // create index for column `created_by`
         $this->createIndex(
            'idx-product-created_by',
            'product',
            'created_by'
        );
        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-product-created_by',
            'product',
            'created_by',
            'user',
            'id',
            'CASCADE'
        );

         // create index for column `created_by`
         $this->createIndex(
            'idx-product-client_id',
            'product',
            'client_id'
        );
         // add foreign key for table `clients`
         $this->addForeignKey(
            'fk-product-client_id',
            'product',
            'client_id',
            'client',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-product-client_id','{{%product}}');
        $this->dropIndex('idx-product-client_id', '{{%product}}');  
        $this->dropForeignKey('fk-product-created_by','{{%product}}');
        $this->dropIndex('idx-product-created_by', '{{%product}}'); 
        $this->dropTable('{{%product}}');
    }
}
