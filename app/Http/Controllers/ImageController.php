<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use App\Models\Image;
use App\Jobs\GenerateImage;
use Illuminate\Contracts\Queue;

class ImageController extends Controller
{
    protected $styles = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->styles = (new Image)->styles();
    }

    /**
     * Store new Image file.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json([
                'message' => "image_file_is_required",
                'status_code' => IlluminateResponse::HTTP_BAD_REQUEST,
            ], IlluminateResponse::HTTP_BAD_REQUEST);
        }

        if (!$request->exists('style')
            || empty($request->get('style'))
            || !$this->searchStyle($request->get('style'))) {
            return response()->json([
                'message' => "attribute_style_is_required",
                'status_code' => IlluminateResponse::HTTP_BAD_REQUEST,
            ], IlluminateResponse::HTTP_BAD_REQUEST);
        }

        $image = new Image();

        try {
            $file = $image->uploadFile($request->file('image'));

            $options = [
                'colors' => ($request->exists('colors')) ? true : false,
                'style' => $request->get('style')
            ];

            $source = $image->create(array_merge($file, $options));

            if($source) {
                // add task to queue
                $this->dispatch(new GenerateImage($source));

                return response()->json([
                    'url' => route('image.show', [
                        'id' => $source->id
                    ])
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $image = Image::findOrFail($id);

        if($image) {
            if($image->is_done) {
                return response()->json([
                    'status' => 'is_ready',
                    'file' => url($image->relative_path.$image->rendered.$image->ext)
                ]);
            }

            return response()->json([
                'status' => 'in_queue'
            ]);
        }
    }

    public function styles()
    {
        return response()->json($this->styles);
    }

    public function searchStyle($id)
    {
        foreach($this->styles as $style) {
            if($style['id'] == $id) {
                return true;
            }

            return false;
        }
    }
}