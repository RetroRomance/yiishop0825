<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface {

    public $new_pwd;
    public $sure_pwd;
    public static function tableName()
    {
        return 'user';
    }
    public function rules(){
        return[
            [['username','password_hash','email'],'required'],//姓名不能为s空
            ['sure_pwd','compare','compareAttribute'=>'new_pwd'],
            [['new_pwd','sure_pwd'],'safe']
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'姓名',
            'password_hash'=>'密码',
            'email'=>'邮箱',
            'new_pwd'=>'新密码',
            'sure_pwd'=>'确认密码',

        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey()===$authKey;
    }
    public function getMenus(){
        $menuItems=[];
        //获取所有一级菜单
        $menus=Menu::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            //获取一级菜单的子菜单
            $children=Menu::find()->where(['parent_id'=>$menu->id])->all();
            $items=[];

            foreach ($children as $child){
                //判断用户是否有该菜单的权限
                if(\Yii::$app->user->can($child->url)){
                    $items[]=['label'=>$child->name,'url'=>[$child->url]];
                }
            }
            //没有子菜单的遗迹菜单不需要显示
            if($items){
                $menuItems[]=['label'=>$menu->name,'items'=>$items];
            }

        }
        return $menuItems;
    }
}