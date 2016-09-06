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
        'is_done',
        'type',
        'size',
        'path',
        'name',
        'ext',
        'relative_path',
        'style',
        'generate_time',
        'rendered',
        'colors'
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
        'is_done' => 'boolean',
        'colors' => 'boolean',
        'style' => 'integer'
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
            [
                'id' => '1',
                'image' => resource_path('styles/1.jpg'),
                'thumb' => url('static/styles/1.jpg'),
                'title' => 'Style #1'
            ],
            [
                'id' => '2',
                'image' => resource_path('styles/2.jpg'),
                'thumb' => url('static/styles/2.jpg'),
                'title' => 'Style #2'
            ],
            [
                'id' => '3',
                'image' => resource_path('styles/3.jpg'),
                'thumb' => url('static/styles/3.jpg'),
                'title' => 'Style #3'
            ],
            [
                'id' => '4',
                'image' => resource_path('styles/4.jpg'),
                'thumb' => url('static/styles/4.jpg'),
                'title' => 'Style #4'
            ],
            [
                'id' => '5',
                'image' => resource_path('styles/5.jpg'),
                'thumb' => url('static/styles/5.jpg'),
                'title' => 'Style #5'
            ],
            [
                'id' => '6',
                'image' => resource_path('styles/6.jpg'),
                'thumb' => url('static/styles/6.jpg'),
                'title' => 'Style #6'
            ],
            [
                'id' => '7',
                'image' => resource_path('styles/7.jpg'),
                'thumb' => url('static/styles/7.jpg'),
                'title' => 'Style #7'
            ],
            [
                'id' => '8',
                'image' => resource_path('styles/8.jpg'),
                'thumb' => url('static/styles/8.jpg'),
                'title' => 'Style #8'
            ],
            [
                'id' => '9',
                'image' => resource_path('styles/9.jpg'),
                'thumb' => url('static/styles/9.jpg'),
                'title' => 'Style #9'
            ],
            [
                'id' => '10',
                'image' => resource_path('styles/10.jpg'),
                'thumb' => url('static/styles/10.jpg'),
                'title' => 'Style #10'
            ],
            [
                'id' => '11',
                'image' => resource_path('styles/11.jpg'),
                'thumb' => url('static/styles/11.jpg'),
                'title' => 'Style #11'
            ],
            [
                'id' => '12',
                'image' => resource_path('styles/12.jpg'),
                'thumb' => url('static/styles/12.jpg'),
                'title' => 'Style #12'
            ],
            [
                'id' => '13',
                'image' => resource_path('styles/13.jpg'),
                'thumb' => url('static/styles/13.jpg'),
                'title' => 'Style #13'
            ],
            [
                'id' => '14',
                'image' => resource_path('styles/14.jpg'),
                'thumb' => url('static/styles/14.jpg'),
                'title' => 'Style #14'
            ],
            [
                'id' => '15',
                'image' => resource_path('styles/15.jpg'),
                'thumb' => url('static/styles/15.jpg'),
                'title' => 'Style #15'
            ],
            [
                'id' => '16',
                'image' => resource_path('styles/16.jpg'),
                'thumb' => url('static/styles/16.jpg'),
                'title' => 'Style #16'
            ],
            [
                'id' => '17',
                'image' => resource_path('styles/17.jpg'),
                'thumb' => url('static/styles/17.jpg'),
                'title' => 'Style #17'
            ],
            [
                'id' => '18',
                'image' => resource_path('styles/18.jpg'),
                'thumb' => url('static/styles/18.jpg'),
                'title' => 'Style #18'
            ],
            [
                'id' => '19',
                'image' => resource_path('styles/19.jpg'),
                'thumb' => url('static/styles/19.jpg'),
                'title' => 'Style #19'
            ],
            [
                'id' => '20',
                'image' => resource_path('styles/20.jpg'),
                'thumb' => url('static/styles/20.jpg'),
                'title' => 'Style #20'
            ],
            [
                'id' => '21',
                'image' => resource_path('styles/21.jpg'),
                'thumb' => url('static/styles/21.jpg'),
                'title' => 'Style #21'
            ],
            [
                'id' => '22',
                'image' => resource_path('styles/22.jpg'),
                'thumb' => url('static/styles/22.jpg'),
                'title' => 'Style #22'
            ],
        ];
    }
}
