<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Indiesoft\LaravelUploads\LaravelUploads;

use Date;

class Article extends Model
{
    use SoftDeletes, LaravelUploads;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'sysname',
        'date',
        'name',
        'descr',
        'text',

        'title',
        'description',
        'keywords',

        'status'
    ];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg',
            'preview' => '360x239',
            'full' => '1155x755',
            'middle' => '755x755',
            'small' => '385x385',
        ],
    ];



    public function categories() {
        return $this
            ->belongsToMany('App\Models\Category',
                'article_category',
                'article_id',
                'category_id')
            ->withTimestamps();
    }

    public function tags() {
        return $this->belongsToMany('App\Models\Tag',
                'article_tag',
                'article_id',
                'tag_id')
            ->withTimestamps();
    }



    public function getShortBodyAttribute() { return mb_substr($this->descr, 0, 125).'...'; }

    //public function dateFormat() {
    //    return (new Date($this->date))->format('F').'<div class="day-year">'.(new Date($this->date))->format('d | Y').'</div>';
    //}

    public function dateLocale() {
        return (new Date($this->date))->format('d F Y');
    }

    public function datePicker() {
        return (new Date($this->date))->format('d.m.Y');
    }
    public function scopePublished($query) { $query->where('status', 1); }
    public function scopeRecent($query) { $query->orderBy('date', 'desc'); }
    public function scopeBySysname($query, $sysname) { $query->where('sysname', $sysname); }

}
