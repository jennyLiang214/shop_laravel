<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Model
 */
class BasicConfig extends Model
{
    /**
     * 网站配置表
     *
     * @var string
     */
    protected $table = 'data_basic_config';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $fillable = ['site_name', 'site_describe', 'telephone', 'logo', 'level_set','record_number', 'address', 'copyright'];

}
