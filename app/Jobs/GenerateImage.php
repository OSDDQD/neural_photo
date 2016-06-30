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
    const APP = '/var/app/neural/neural_style.lua';
    use InteractsWithQueue, SerializesModels;

    protected $image;
    protected $options;
    protected $styles;

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

        $builder = new ProcessBuilder();
        $builder->setPrefix('th ' . $this::APP);

        $content = $this->image->path . $this->image->name . $this->image->ext;
        $output = $this->image->path . $this->image->name . '_rendered' . $this->image->ext;

        $builder
            ->setArguments([
                '-backend cudnn',
                '-cudnn_autotune',
                '-style_image ' . $style,
                '-content_image ' . $content,
                '-output_image ' . $output,
                '-num_iterations 400',
                '-gpu 0',
                '-save_iter 0'
            ])
            ->getProcess();

        try {
            $builder->mustRun();

            if ($builder->isSuccessful()) {
                $this->image->is_done = 1;
                $this->image->save();
            }

        } catch (ProcessFailedException $e) {
            return $e->getMessage();
        }

    }
}
