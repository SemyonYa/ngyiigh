<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "userng".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $role
 * @property string $jwt2
 */
class Userng extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userng';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'role'], 'required'],
            [['login'], 'string', 'max' => 20],
            [['role'], 'string', 'max' => 20],
            [['password', 'jwt2'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'role' => 'Role',
        ];
    }
}
