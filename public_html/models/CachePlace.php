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
class CachePlace extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'CachePlace';
    }    
    //public $id;
    public $LatitudeMin;
    public $LongitudeMin;
    public $LatitudeMax;
    public $LongitudeMax;
    public $PlaceID;
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

