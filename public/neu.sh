#!/bin/bash

style=$1
content=$2
output=$3
size=$4

export LD_LIBRARY_PATH=/usr/local/cuda-7.5/lib64:$LD_LIBRARY_PATH

echo "1"
cd neural
echo "2"

/home/slowpoked/torch/install/bin/th neural_style.lua -style_image $style -content_image $content -output_image $output -image_size $size -num_iterations 400 -backend cudnn -cudnn_autotune

echo "3"
