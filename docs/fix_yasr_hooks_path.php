<?php
$path_to_file = 'docs/yasr_hooks.md';
$file_contents = file_get_contents($path_to_file);
$file_contents = str_replace('[./', '[../', $file_contents);
$file_contents = str_replace('](', '](../', $file_contents);
file_put_contents($path_to_file, $file_contents);