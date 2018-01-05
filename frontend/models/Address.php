<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $username
 * @property string $cmbProvince
 * @property string $cmbCity
 * @property string $cmbArea
 * @property string $address
 * @property string $tel
 * @property string $member_id
 * @property string $sort
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'cmbProvince', 'cmbCity', 'cmbArea'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            [['member_id'], 'integer'],
            [['sort'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '收货人',
            'cmbProvince' => '省',
            'cmbCity' => '市',
            'cmbArea' => '县',
            'address' => '具体地址',
            'tel' => '电话',
            'member_id' => '用户id',
            'sort' => '排序',
        ];
    }
}
