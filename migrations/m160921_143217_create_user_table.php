<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m160921_143217_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->char(100),
            'password_hash' => $this->string(),
            'password_reset_token' => $this->string(),
            'auth_key' => $this->string(),
            'status' => $this->integer(3),
            'type' => $this->integer(3),
            'created_at' => $this->timestamp(),
        ]);

        $this->insert('user', [
            'id' => 1,
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'type' => 10,
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'status' => 10,
            'created_at' => time(),
        ]);
        $this->insert('user', [
            'id' => 2,
            'username' => 'operator1',
            'password_hash' => Yii::$app->security->generatePasswordHash('111'),
            'type' => 0,
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'status' => 10,
            'created_at' => time(),
        ]);
        $this->insert('user', [
            'id' => 3,
            'username' => 'operator2',
            'password_hash' => Yii::$app->security->generatePasswordHash('222'),
            'type' => 0,
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'status' => 10,
            'created_at' => time(),
        ]);
        $this->insert('user', [
            'id' => 4,
            'username' => 'operator3',
            'password_hash' => Yii::$app->security->generatePasswordHash('333'),
            'type' => 0,
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'status' => 10,
            'created_at' => time(),
        ]);
        $this->insert('user', [
            'id' => 5,
            'username' => 'operator4',
            'password_hash' => Yii::$app->security->generatePasswordHash('444'),
            'type' => 0,
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'status' => 10,
            'created_at' => time(),
        ]);
        $this->insert('user', [
            'id' => 6,
            'username' => 'operator5',
            'password_hash' => Yii::$app->security->generatePasswordHash('555'),
            'type' => 0,
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'status' => 10,
            'created_at' => time(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
