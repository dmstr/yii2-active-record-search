<?php

use yii\db\Migration;
use yii\db\Schema;

class m220114_133837_init_search_tables extends Migration
{

    const SEARCH_TABLE = '{{%dmstr_search}}';
    const GROUP_TABLE = '{{%dmstr_search_group}}';
    const GROUP_TRANS_TABLE = '{{%dmstr_search_group_translation}}';

    public function up()
    {

        $this->createTable(self::GROUP_TABLE, [
            'id'         => $this->primaryKey(11),
            'ref_name'   => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()'),
        ], ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null));
        $this->createIndex('ref_name', self::GROUP_TABLE, ['ref_name']);

        $this->createTable(self::GROUP_TRANS_TABLE, [
            'id'          => $this->primaryKey(11),
            'group_id'       => $this->integer(11)->notNull(),
            'language' => $this->string(7)->notNull(),
            'status'       => $this->boolean()->notNull()->defaultValue(1),
            'name'    => $this->string(255)->notNull(),
            'rank'    => $this->string(45)->null(),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()'),
        ], ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null));

        $this->createIndex('group_id', self::GROUP_TRANS_TABLE, ['group_id']);
        $this->createIndex('lang_group', self::GROUP_TRANS_TABLE, ['language', 'group_id']);
        $this->createIndex('lang_status_rank', self::GROUP_TRANS_TABLE, ['language', 'status', 'rank']);
        $this->addForeignKey('fk_dmstr_search_group_trans_fk1', self::GROUP_TRANS_TABLE,'group_id',self::GROUP_TABLE,'id', 'CASCADE', 'CASCADE');

        $this->createTable(self::SEARCH_TABLE, [
            'id'          => $this->primaryKey(11),
            'group'   => $this->string(255)->notNull(),
            'model_class' => $this->string(255)->notNull(),
            'route'       => $this->string(255)->notNull(),
            'model_id'    => $this->string(255)->notNull(),
            'language'    => $this->string(255)->notNull(),
            'url_params'  => $this->string(255)->notNull(),
            'link_text'   => $this->string(255)->notNull(),
            'search_text' => $this->text()->notNull(),
        ], ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null));

        $this->createIndex('model_class', self::SEARCH_TABLE, ['model_class']);
        $this->createIndex('model_id', self::SEARCH_TABLE, ['model_id']);
        $this->createIndex('language', self::SEARCH_TABLE, ['language']);
        $this->createIndex('search_fe_combined_index', self::SEARCH_TABLE, ['language', 'group', 'model_id']);
        $this->addForeignKey('fk_dmstr_search_fk1', self::SEARCH_TABLE,'group',self::GROUP_TABLE,'ref_name', 'NO ACTION', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable(self::SEARCH_TABLE);
    }

}

?>
