<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Indiesoft\LaravelUploads\LaravelUploads;

class Banner extends Model
{
    use SoftDeletes, LaravelUploads;

    protected $fillable = [
        'type',

        'url',
        'blank',
        'status'
    ];

    static public $types = [
        'main'    => 'На главной',
        'left'    => 'Слева',
        'content' => 'В тексте'
    ];

    public function getTypeAttribute($value) {
        return self::$types[$value];
    }

    protected $uploads = [
        'img' => [
            'extensions' => 'jpeg,jpg,png',
            'preview'    => [
                'by'      => 'type',
                'left'    => '100x100',
                'content' => '560x479',
                'main'    => '100x52',
            ],
            'lg' => [
                'by' => 'type',
                'left'    => '240x240',
                'content' => '560x479',
                'main'    => '1920x683',
            ],
            'sm' => [
                'by' => 'type',
                'left'    => '240x240',
                'content' => '560x479',
                'main'    => '839x459',
            ],
        ]
    ];
}
