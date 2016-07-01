<?php

namespace App\Models;

use App\Models\Utils\UploadableTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Image extends Eloquent
{
    use UploadableTrait;

    protected $table = 'image';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_done', 'type', 'size', 'path', 'name', 'ext', 'relative_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'integer'
    ];

    private $upload_dir = 'photo';

    const SIZE = '500';

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Image $entity) {
            $entity->deleteFile($entity, false);
        });
    }
    
    public function styles()
    {
        return [
            '1' => [
                'image' => resource_path('styles/1.jpg'),
                'title' => 'Style #1'
            ],
            '2' => [
                'image' => resource_path('styles/2.jpg'),
                'title' => 'Style #2'
            ],
            '3' => [
                'image' => resource_path('styles/3.jpg'),
                'title' => 'Style #3'
            ],
            '4' => [
                'image' => resource_path('styles/4.jpg'),
                'title' => 'Style #4'
            ],
            '5' => [
                'image' => resource_path('styles/5.jpg'),
                'title' => 'Style #5'
            ],
            '6' => [
                'image' => resource_path('styles/6.jpg'),
                'title' => 'Style #6'
            ],
            '7' => [
                'image' => resource_path('styles/7.jpg'),
                'title' => 'Style #7'
            ],
            '8' => [
                'image' => resource_path('styles/8.jpg'),
                'title' => 'Style #8'
            ],
            '9' => [
                'image' => resource_path('styles/9.jpg'),
                'title' => 'Style #9'
            ],
            '10' => [
                'image' => resource_path('styles/10.jpg'),
                'title' => 'Style #10'
            ],
            '11' => [
                'image' => resource_path('styles/11.jpg'),
                'title' => 'Style #11'
            ],
        ];
    }
}
