<?php function compress_image($source_url, $destination_url,$width=790,$height=950, $quality=70,$preview = null) {
    $info = getimagesize($source_url);
 
    if     ('image/jpeg' == $info['mime']) { $image = imagecreatefromjpeg($source_url); }
    elseif ('image/gif' == $info['mime'])  { $image = imagecreatefromgif($source_url); }
    elseif ('image/png' == $info['mime'])  { $image = imagecreatefrompng($source_url); }

    $x_ratio = $width / $info[0];
    $y_ratio = $height / $info[1];
    if ($info[0] <= $width && $info[1] <= $height) {
        $prop_width  = $info[0];
        $prop_height = $info[1];
    }
    elseif (($x_ratio * $info[1]) < $height) {
        $prop_height = ceil($x_ratio * $info[1]);
        $prop_width  = $width;
    } else {
        $prop_width  = ceil($y_ratio * $info[0]);
        $prop_height = $height;
    }

    $tmp_image = imagecreatetruecolor($prop_width,$prop_height);

    imagecopyresampled($tmp_image,$image,0,0,0,0,$prop_width, $prop_height,$info[0],$info[1]);
    imagejpeg($tmp_image, $destination_url, $quality);

    if (!empty($preview)) {
        $x_proportion = $preview[0]/$preview[1];
        $y_proportion = $preview[1]/$preview[0];

        if ($info[0] > $info[1]) {
            $prop_width  = $info[0];
            $prop_height = $info[0] * $y_proportion;
            if ($prop_height>$info[1]) {
                $prop_height = $info[1];
                $prop_width  = $info[1] * $x_proportion; 
            }
        } else {
            $prop_height = $info[1];
            $prop_width  = $info[1] * $x_proportion;
            if ($prop_width > $info[0]) {
                $prop_width  = $info[0];
                $prop_height = $info[0] * $y_proportion; // обрезаем от изображения непропорциональную часть
            }
        }

        $diff_width = ($info[0]-$prop_width)/2;
        $diff_height = ($info[1]-$prop_height)/2;
        $prop_img = imagecreatetruecolor($prop_width,$prop_height);
        imagecopyresampled($prop_img,$image,0,0,0,$diff_height,$prop_width, $prop_height,$info[0],$prop_height); //создаем изображение с обрезанными краями для соблюдения пропорции
        imagejpeg($prop_img, $preview[3], 100);

        $this->compress_image($preview[3], $preview[3],$preview[0],$preview[1],$preview[2]); // РЕКУРСИВНАЯ ССЫЛКА НА ФУНКЦИЮ - ОТРЕДАКТИРОВАЬ В СЛУЧАЕ ИЗМЕНЕНИЯ НАЗВАНИЯ
    }

    return $destination_url;
}