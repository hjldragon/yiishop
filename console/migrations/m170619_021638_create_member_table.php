<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170619_021638_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->notNull()->comment('用户名'),
            'auth_key'=>$this->string(32),
            'password_hash'=>$this->string(100)->comment('密文'),
            'email'=>$this->string(100)->comment('邮箱'),
            'tel'=>$this->char(11)->comment('电话'),
            'last_login_time'=>$this->integer()->comment('最后登陆时间'),
            'last_login_ip'=>$this->integer()->comment('最后登录ip'),
            'status'=>$this->integer()->comment('状态'),
            'create_time'=>$this->integer()->comment('添加时间'),
            'update_time'=>$this->integer()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
