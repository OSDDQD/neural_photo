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
    public function __construct(Image $image, array $options)
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
        $style = $this->styles[$this->image->style]['image'];
        $colors = ($this->image->colors) ? '1' : '0';

        $filename = (new Image())->generateName();

        $content = $this->image->path . $this->image->name . $this->image->ext;
        $output = $this->image->path . $filename . $this->image->ext;

        $path = getcwd();
        chdir(public_path());

        $cmd = './neu.sh ' . $style . ' ' . $content . ' ' . $output . ' ' . $this->size . ' ' . $colors;

        putenv("SHELL=/bin/bash");
        $process = new Process($cmd);

        try {
            $time_start = microtime(true);

            $process->start(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo 'OUT > '.$buffer;
                }
            });

//            while ($process->isRunning()) {
//                // waiting for process to finish
//            }

            $time_end = microtime(true);
            $time = $time_end - $time_start;

            if($process->isSuccessful() && file_exists($output)) {
                $this->image->rendered = $filename;
                $this->image->generate_time = $time;
                $this->image->is_done = true;
                $this->image->save();
            }


            echo $process->getOutput();

            chdir($path);

        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }

    }
}
