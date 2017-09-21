<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'user_id',
        'code',
    ];
    //
    public function transfer() {
        return $this->hasMany('App\Models\PartnerTransfer');
    }
    //увеличивает сумму заработынных денег на заданное значение
    public function accrue($value) {
        if($value > 0) {
            $this->make_money = $this->make_money + $value;
        }
        $this->save();
    }
    //увеличивает сумму заработынных денег на заданное значение
    public function withdraw($value) {
        if($value > 0) {
            $this->spent_money = $this->spent_money + $value;
        }
        $this->save();
    }
    public function user() {
        return $this->belongsTo('App\User');
    }
    public function referrals() {
        return $this->hasMany('App\Models\Referral');
    }

    public function users() {
        return User::where('status', 1)->get();
    }
    static public function generateSaleCode() {
        return substr(md5(time()), 0, 6);
    }
    public function getRemainMoneyAttribute() {
        return $this->make_money - $this->spent_money;
    }
    public function getReferralDiscountPercentAttribute() {
        return Setting::getVar('referral_discount_percent');
    }
}
