<?php

namespace backend\models;

use Yii;
use common\core\BaseModel;

/**
 * Class LoginForm
 *
 * @package \backend\models
 */
class LoginForm extends BaseModel
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '账号或密码错误');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 : 0);
        } else {
            return false;
        }
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }
        return $this->_user;
    }
}