<?php

use yii\db\Migration;

/**
 * Class m240507_123754_ensure_unique_index_for_group_ref_name
 */
class m240507_123754_ensure_unique_index_for_group_ref_name extends Migration
{
    const SEARCH_TABLE = '{{%dmstr_search}}';
    const GROUP_TABLE = '{{%dmstr_search_group}}';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        // Ensure unique key exist when the previous migration was already executed
        $this->dropForeignKey('fk_dmstr_search_fk1', self::SEARCH_TABLE);
        $this->dropIndex('ref_name', self::GROUP_TABLE);
        $this->createIndex('ref_name', self::GROUP_TABLE, ['ref_name'], true);
        $this->addForeignKey('fk_dmstr_search_fk1', self::SEARCH_TABLE,'group',self::GROUP_TABLE,'ref_name', 'NO ACTION', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m240507_123754_ensure_unique_index_for_group_ref_name cannot be reverted.\n";
        return false;
    }
}
