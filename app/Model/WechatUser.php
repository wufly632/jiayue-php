<?php


namespace App\Model;


class WechatUser extends Model
{
    protected $table = 'wechat_users';
    protected $guarded = ['id'];

    protected function user()
    {
        return $this->belongsTo(User::class);
    }
}
