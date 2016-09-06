#!/bin/bash

style=$1
content=$2
output=$3
size=$4
colors=$5
torch='/home/ubuntu/torch/install/bin/th'
appdir='/var/app/neural'

. /home/ubuntu/torch/install/bin/torch-activate
export LD_LIBRARY_PATH=/usr/local/cuda-7.5/lib64:$LD_LIBRARY_PATH

echo "Go to app dir"
cd $appdir

echo "Init generate image with file: $content"

/home/ubuntu/torch/install/bin/th neural_style.lua -style_image $style -content_image $content -output_image $output -image_size $size -num_iterations 400 -backend cudnn -cudnn_autotune -save_iter 0 -print_iter 0

echo "Generate finished: $output"