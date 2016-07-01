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
        'is_done', 'type', 'size', 'path', 'name', 'ext'
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

    const STYLES = [
        '1' => '/var/app/neural/style/12.jpg',
        '2' => '2.jpg',
        '3' => '3.jpg'
    ];

    const SIZE = '500';

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Image $entity) {
            $entity->deleteFile($entity, false);
        });
    }
}
