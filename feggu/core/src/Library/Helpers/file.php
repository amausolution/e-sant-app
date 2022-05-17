<?php
/**
 * File function process image
 */
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Function upload image
 */
if (!function_exists('au_image_upload') && !in_array('au_image_upload', config('helper_except', []))) {
    function au_image_upload($fileContent, $disk = 'public', $path = null, $name = null, $options = ['unique_name' => true, 'thumb' => false, 'watermark' => false])
    {
        $pathFile = null;
        try {
            $fileName = false;
            if ($name) {
                $fileName = $name . '.' . $fileContent->getClientOriginalExtension();
            } elseif (empty($options['unique_name'])) {
                $fileName = $fileContent->getClientOriginalName();
            }

            //Save as file
            if ($fileName) {
                $pathFile = Storage::disk($disk)->putFileAs(($path ?? ''), $fileContent, $fileName);
            }
            //Save file id unique
            else {
                $pathFile = Storage::disk($disk)->putFile(($path ?? ''), $fileContent);
            }
        } catch (\Throwable $e) {
            au_report($e->getMessage());
            return null;
        }

        if ($pathFile && $disk == 'public') {
            //generate thumb
            if (!empty($options['thumb']) && au_config('upload_image_thumb_status')) {
                au_image_generate_thumb($pathFile, $widthThumb = 250, $heightThumb = null, $disk);
            }

            //insert watermark
            if (!empty($options['watermark']) && au_config('upload_watermark_status')) {
                au_image_insert_watermark($pathFile);
            }
        }
        if ($disk == 'public') {
            $url =  'storage/' . $pathFile;
        } else {
            $url =  Storage::disk($disk)->url($pathFile);
        }

        return  [
            'fileName' => $fileName,
            'pathFile' => $pathFile,
            'url' => $url,
        ];
    }
}
/**
 * Function upload file
 */
if (!function_exists('au_file_upload') && !in_array('au_file_upload', config('helper_except', []))) {
    function au_file_upload($fileContent, $disk = 'public', $path = null, $name = null)
    {
        $pathFile = null;
        try {
            $fileName = false;
            if ($name) {
                $fileName = $name . '.' . $fileContent->getClientOriginalExtension();
            } else {
                $fileName = $fileContent->getClientOriginalName();
            }

            //Save as file
            if ($fileName) {
                $pathFile = Storage::disk($disk)->putFileAs(($path ?? ''), $fileContent, $fileName);
            }
            //Save file id unique
            else {
                $pathFile = Storage::disk($disk)->putFile(($path ?? ''), $fileContent);
            }
        } catch (\Throwable $e) {
            return null;
        }
        if ($disk == 'public') {
            $url =  'storage/' . $pathFile;
        } else {
            $url =  Storage::disk($disk)->url($pathFile);
        }
        return  [
            'fileName' => $fileName,
            'pathFile' => $pathFile,
            'url' => $url,
        ];
    }
}
/**
 * Remove file
 *
 * @param   [string]  $disk
 * @param   [string]  $path
 * @param   [string]  $prefix  will remove
 *
 */
if (!function_exists('au_remove_file') && !in_array('au_remove_file', config('helper_except', []))) {
    function au_remove_file($pathFile, $disk = null)
    {
        if ($disk) {
            return Storage::disk($disk)->delete($pathFile);
        } else {
            return Storage::delete($pathFile);
        }
    }
}

/**
 * Function insert watermark
 */
if (!function_exists('au_image_insert_watermark') && !in_array('au_image_insert_watermark', config('helper_except', []))) {
    function au_image_insert_watermark($pathFile, $pathWatermark = null)
    {
        if (!$pathWatermark) {
            $pathWatermark = au_config('upload_watermark_path');
        }
        if (empty($pathWatermark)) {
            return false;
        }
        $pathReal = config('filesystems.disks.public.root') . '/' . $pathFile;
        Image::make($pathReal)
            ->insert(public_path($pathWatermark), 'bottom-right', 10, 10)
            ->save($pathReal);
        return true;
    }
}
/**
 * Function generate thumb
 */
if (!function_exists('au_image_generate_thumb') && !in_array('au_image_generate_thumb', config('helper_except', []))) {
    function au_image_generate_thumb($pathFile, $widthThumb = null, $heightThumb = null, $disk = 'public')
    {
        $widthThumb = $widthThumb ?? au_config('upload_image_thumb_width');
        if (!Storage::disk($disk)->has('tmp')) {
            Storage::disk($disk)->makeDirectory('tmp');
        }

        $pathReal = config('filesystems.disks.public.root') . '/' . $pathFile;
        $image_thumb = Image::make($pathReal);
        $image_thumb->resize($widthThumb, $heightThumb, function ($constraint) {
            $constraint->aspectRatio();
        });
        $tmp = '/tmp/' . time() . rand(10, 100);

        $image_thumb->save(config('filesystems.disks.public.root') . $tmp);
        if (Storage::disk($disk)->exists('/thumb/' . $pathFile)) {
            Storage::disk($disk)->delete('/thumb/' . $pathFile);
        }
        Storage::disk($disk)->move($tmp, '/thumb/' . $pathFile);
    }
}
/**
 * Function rener image
 */
if (!function_exists('au_image_render') && !in_array('au_image_render', config('helper_except', []))) {
    function au_image_render($path, $width = null, $height = null, $alt = null, $title = null, $urlDefault = null, $options = '')
    {
        $image = au_image_get_path($path, $urlDefault);
        $style = '';
        $style .= ($width) ? ' width:' . $width . ';' : '';
        $style .= ($height) ? ' height:' . $height . ';' : '';
        return '<img  alt="' . $alt . '" title="' . $title . '" ' . (($options) ?? '') . ' src="' . au_file($image) . '"   ' . ($style ? 'style="' . $style . '"' : '') . '   >';
    }
}
/*
Return path image
 */
if (!function_exists('au_image_get_path') && !in_array('au_image_get_path', config('helper_except', []))) {
    function au_image_get_path($path, $urlDefault = null)
    {
        $image = $urlDefault ?? 'images/no-image.jpg';
        if ($path) {
            if (file_exists(public_path($path)) || filter_var(str_replace(' ', '%20', $path), FILTER_VALIDATE_URL)) {
                $image = $path;
            } else {
                $image = $image;
            }
        }
        return $image;
    }
}
/*
Function get path thumb of image if saved in storage
 */
if (!function_exists('au_image_get_path_thumb') && !in_array('au_image_get_path_thumb', config('helper_except', []))) {
    function au_image_get_path_thumb($pathFile)
    {
        if (strpos($pathFile, "/storage/") === 0) {
            $arrPath = explode('/', $pathFile);
            $fileName = end($arrPath);
            $pathThumb = substr($pathFile, 0, -strlen($fileName)) . 'thumbs/' . $fileName;
            if (file_exists(public_path($pathThumb))) {
                return $pathThumb;
            } else {
                return au_image_get_path($pathFile);
            }
        } else {
            return au_image_get_path($pathFile);
        }
    }
}



if (!function_exists('au_zip') && !in_array('au_zip', config('helper_except', []))) {
    /*
    Zip file or folder
     */
    function au_zip(string $pathToSource, string $pathSaveTo)
    {
        if (extension_loaded('zip')) {
            if (file_exists($pathToSource)) {
                $zip = new \ZipArchive();
                if ($zip->open($pathSaveTo, \ZIPARCHIVE::CREATE)) {
                    $pathToSource = str_replace('\\', '/', realpath($pathToSource));
                    if (is_dir($pathToSource)) {
                        $iterator = new \RecursiveDirectoryIterator($pathToSource);
                        // skip dot files while iterating
                        $iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
                        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {
                            $file = str_replace('\\', '/', realpath($file));
                            if (is_dir($file)) {
                                $zip->addEmptyDir(str_replace($pathToSource . '/', '', $file . '/'));
                            } elseif (is_file($file)) {
                                $zip->addFromString(str_replace($pathToSource . '/', '', $file), file_get_contents($file));
                            }
                        }
                    } elseif (is_file($pathToSource)) {
                        $zip->addFromString(basename($pathToSource), file_get_contents($pathToSource));
                    }
                }
                return $zip->close();
            }
        }
        return false;
    }
}


if (!function_exists('au_unzip') && !in_array('au_unzip', config('helper_except', []))) {
    /**
     * Unzip file to folder
     *
     * @return  [type]  [return description]
     */
    function au_unzip(string $pathToSource, string $pathSaveTo)
    {
        $zip = new \ZipArchive();
        if ($zip->open(str_replace("//", "/", $pathToSource)) === true) {
            $zip->extractTo($pathSaveTo);
            return $zip->close();
        }
        return false;
    }
}


/**
 * Process path file
 */
if (!function_exists('au_file') && !in_array('au_file', config('helper_except', []))) {
    function au_file(string $pathFile = null, bool $security = null):string
    {
        return asset($pathFile, $security);
    }
}

if (!function_exists('au_path_download_render') && !in_array('au_path_download_render', config('helper_except', []))) {
    /*
    Render path download
     */
    function au_path_download_render(string $string):string
    {
        if (filter_var($string, FILTER_VALIDATE_URL)) {
            return $string;
        } else {
            return \Storage::disk('path_download')->url($string);
        }
    }
}
