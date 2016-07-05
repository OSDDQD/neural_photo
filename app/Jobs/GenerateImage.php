<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Image;

class GenerateImage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $image;
    protected $options;
    protected $styles;
    protected $size;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
        $this->styles = $image->styles();
        $this->size = $image::SIZE;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $styleimage = '';

        foreach ($this->styles as $style) {
            if($style['id'] == $this->image->style) {
                $styleimage = $style['image'];
            }
        }

        $style = $styleimage;
        unset($styleimage);

        $colors = ($this->image->colors) ? '1' : '0';

        $filename = (new Image())->generateName();

        $content = $this->image->path . $this->image->name . $this->image->ext;
        $output = $this->image->path . $filename . $this->image->ext;

//        $path = getcwd();
//        chdir(public_path());

        $cmd = './neu.sh ' . $style . ' ' . $content . ' ' . $output . ' ' . $this->size . ' ' . $colors;

        // Set bash is default shell for exec
        putenv("SHELL=/bin/bash");
        $process = new Process($cmd, public_path(), null, null, null);

        try {
            $time_start = microtime(true);

            $process->run();

            $process->wait(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo 'OUT > '.$buffer;
                }
            });

            $time_end = microtime(true);
            $time = $time_end - $time_start;

            if($process->isSuccessful() && file_exists($output)) {
                print 'record';
                $this->image->rendered = $filename;
                $this->image->generate_time = $time;
                $this->image->is_done = true;
                $this->image->save();
            }

//            chdir($path);

        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }

    }
}
