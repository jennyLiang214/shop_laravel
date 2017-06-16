<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class FriendLink extends Model
{
    /**
     * 关联'data_friend_link'表
     *
     * @var string
     */
    protected $table = 'data_friend_link';

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'delete_at'];

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'url', 'image'];
}
