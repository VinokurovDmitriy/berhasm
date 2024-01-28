<?php

namespace shop\entities\User;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\Shop\Orders;
use shop\entities\Shop\Product\Review;
use yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $phone
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $email
 * @property string $auth_key
 * @property string $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Network[] $networks
 * @property \shop\entities\User\UserAddresses[] $addresses
 * @property \shop\entities\User\UserProfile $userProfile
 * @property Orders[] $orders
 * @property Orders[] $activeOrders
 * @property Review[] $reviews
 */
class User extends ActiveRecord implements IdentityInterface
{
    use InitiateTrait;

    const STATUS_BLOCKED = 0;
    const STATUS_PENDING = 10;
    const STATUS_ACTIVE = 20;

    const ROLE_CUSTOMER = 'customer';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMIN = 'admin';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['networks']
            ]
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'status' => 'Статус',
            'role' => 'Роль',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
        ];
    }

    /* @var $username string
     * @var $email string
     * @var $password string
     * @var $role string
     * @return self
     */
    public static function signup($username, $email, $password, $role)
    {
        $user = new static();
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = self::STATUS_PENDING;
        $user->generateEmailConfirmToken();
        $user->role = $role;
        $user->save();
        $profile = new UserProfile();
        $profile->user_id = $user->id;
        $profile->username = $username;
        $profile->save();
        $address = new UserAddresses();
        $address->user_id = $user->id;
        $address->save();
        return $user;
    }

    public function edit($username, $phone, $email, $address = null)
    {
        $this->userProfile->username = $username;
        $this->userProfile->phone = $phone;
        $this->userProfile->save();
        $this->email = $email;
        if ($address) {
            $this->addresses[0]->value = $address;
            $this->addresses[0]->save();
        }
        $this->save();
        return true;
    }

    public static function signupByNetwork($network, $identity): self
    {
        $user = new User();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->networks = [Network::create($network, $identity)];
        return $user;
    }

    public function attachNetwork($network, $identity): void
    {
        $networks = $this->networks;
        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    public function confirmSignup(): void
    {
        if (!$this->isPending()) {
            throw new \DomainException('Учетная запись уже активирована');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->removeEmailConfirmToken();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()->where(['id' => $id])->andWhere(['!=', 'status', self::STATUS_BLOCKED])->limit(1)->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }


    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    /**
     * @inheritdoc
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    public function getAddresses()
    {
        return $this->hasMany(UserAddresses::class, ['user_id' => 'id']);
    }

    public function getReviews()
    {
        return $this->hasMany(Review::class, ['user_id' => 'id']);
    }

    public function getReviewsProvider()
    {
        return new ActiveDataProvider([
            'query' => Review::find()->having(['user_id' => $this->id])
        ]);
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id']);
    }

    public function getActiveOrders()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id'])->having(['user_status' => 1]);
    }

    public function getOrdersProvider()
    {
        return new ActiveDataProvider([
            'query' => Orders::find()->having(['user_id' => $this->id])
        ]);
    }

    public function getPublicIdentity()
    {
        if ($this->userProfile->username) {
            return $this->userProfile->username;
        }
        return $this->email;
    }

    public function getUsername()
    {
        return $this->userProfile->username;
    }
	

    public function getPhone()
    {
        return $this->userProfile->phone;
    }

    public static function isUserAdmin($email)
    {
        if (static::findOne(['email' => $email, 'role' => self::ROLE_ADMIN])) {
            return true;
        } else {
            return false;
        }
    }

    public static function isUserManager($email)
    {
        if (static::findOne(['email' => $email, 'role' => self::ROLE_MANAGER])) {
            return true;
        } else {
            return false;
        }
    }

    public static function isUserCustomer($email)
    {
        if (static::findOne(['email' => $email, 'role' => self::ROLE_CUSTOMER])) {
            return true;
        } else {
            return false;
        }
    }

    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Сброс пароля уже запрошен.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Сброс пароля не был запрошен.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::class, ['user_id' => 'id']);
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isBlocked()
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function notBlocked()
    {
        return $this->status !== self::STATUS_BLOCKED;
    }

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager()
    {
        return $this->role === self::ROLE_MANAGER;
    }
}
