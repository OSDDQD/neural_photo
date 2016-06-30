<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use App\Models\Image;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ProcessBuilder;

class TestController extends Controller
{
    const APP = '/var/app/neural/neural_style.lua';

    protected $image;
    protected $options;
    protected $styles;
    protected $size;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->image = Image::find(3);
        $this->styles = (new Image)::STYLES;
        $this->size = (new Image)::SIZE;
    }

    /**
     * Store new Image file.
     *
     * @return \Illuminate\Http\Response
     */
    public function exec()
    {
        $style = $this->styles['1'];
        $colors = '0';


        $builder = new ProcessBuilder();
        $builder->setPrefix('th ' . $this::APP);

        $content = $this->image->path . $this->image->name . $this->image->ext;
        $output = $this->image->path . $this->image->name . '_rendered' . $this->image->ext;

        $cmd = $builder
            ->setArguments([
                '-backend cudnn',
                '-cudnn_autotune',
                '-style_image ' . $style,
                '-content_image ' . $content,
                '-output_image ' . $output,
                '-num_iterations 400',
                '-gpu 0',
                '-save_iter 0',
                '-original_colors ' . $colors,
                '-image_size ' . $this->size
            ])
            ->getProcess()
            ->getCommandLine();

        $process = new Process($cmd);

        try {

            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo 'OUT > '.$buffer;
                }
            });

            while ($process->isRunning()) {
                // waiting for process to finish
            }

            if ($process->isSuccessful()) {
                $this->image->is_done = 1;
                $this->image->save();
            }

        } catch (ProcessFailedException $e) {
            throw new \Exception ($e->getMessage());
        }

    }
}