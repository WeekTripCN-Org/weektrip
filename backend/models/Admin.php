<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "admin".
 *
 * @property string $uid
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $mobile
 * @property string $reg_time
 * @property string $reg_ip
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property string $update_time
 * @property integer $status
 */
class Admin extends \common\models\Admin implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE  = 1;

    /**
     * @param int|string $uid
     * @return mixed
     * 根据UID获取账号信息
     */
    public static function findIdentity($uid)
    {
        return static::findOne(['uid' => $uid, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented');
    }

    /**
     * @param $username
     * @return mixed
     * 根据用户名获取账号信息
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param $token
     * @return null
     * 通过Token获取信息
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne(['password' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param $token
     * @return bool
     *
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->salt;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     * @return bool
     * 验证密码
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @param $password
     * 设置加密后的密码
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * 设置密码干扰码
     */
    public function generateAuthKey()
    {
        $this->salt = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password = Yii::$app->security->generateRandomString();
    }

    public function removePasswordResetToken()
    {
        $this->password = null;
    }
}
