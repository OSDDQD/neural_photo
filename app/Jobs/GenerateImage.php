<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ProcessBuilder;
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
        $this->options = $options;
        $this->styles = $image::STYLES;
        $this->size = $image::SIZE;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->attempts() > 3) {
            return false;
        }

        $style = $this->styles[$this->options['style']];
        $colors = ($this->options['colors']) ? '1' : '0';


        $builder = new ProcessBuilder();
        $builder->setPrefix('th ' . $this::APP);

        $content = $this->image->path . $this->image->name . $this->image->ext;
        $output = $this->image->path . $this->image->name . '_rendered' . $this->image->ext;

        $cmd = './neu.sh ' . $style . ' ' . $content . ' ' . $output . ' ' . $this->size . ' ' . $colors;

        putenv("SHELL=/bin/bash");
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
