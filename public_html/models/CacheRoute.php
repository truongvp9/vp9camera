<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\models;


/**
 * ContactForm is the model behind the contact form.
 */
class CacheRoute extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'CacheRoute';
    }    
    //public $id;
    public $placeID_A;
    public $placeID_B;
    public $jsonArrayRoute;
    public $typeVehicle;
    public $verifyCode;

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }    
}
