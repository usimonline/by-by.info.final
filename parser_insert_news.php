<?php

$razdel = 'news_latest';

//echo $total_parse;

//print_r($all_count);
//print_r($all_count_2);
//print_r($url_mass_url);
print_r($url_mass_titles);
//print_r($url_mass_description);
//print_r($url_mass_img);
//print_r($url_mass_texts);


for($k = 0; $k < $total_parse; $k++) {

$datetime = date("Y-m-d H:i:s",strtotime("+".(($k+1+$hours)*30)." minutes"));
   // echo $datetime.'<br>         ';
$id = time()+($k);
   // echo $id.'<br>         ';
$datetime_mass_1 = explode(' ', $datetime);
    //echo $datetime_mass_1.'<br>         ';
$datetime_mass_2 = explode('-', $datetime_mass_1[0]);
    //echo $datetime_mass_2.'<br>         ';
$year = $datetime_mass_2[0].'-2';
   // echo $year.'<br>         ';
$month = $datetime_mass_2[1];
  //  echo $month.'<br>         ';
$day = $datetime_mass_2[2];
   // echo $day.'<br>         ';
$comments = 0;
$teme = $url_mass_titles[$k];
    $teme = transform_words($teme);


$url = '/news/'.$year.'/'.$month.'/'.$day.'/'.$id.'/';
$url = $url.translate_into_english($teme).'/';
  //  echo $url.'<br>         ';
$description = $url_mass_description[$k];
    $description = transform_words($description);

$text = $url_mass_texts[$k];
$text = transform_img($text, $url);
    $text = transform_words($text);

$keys = $keys_temp;

$url_frame = '';

$temp_time_ogr = '2017-01-25 20:12:53';
$select_rand = "SELECT COUNT(*) FROM $Name_database.$table WHERE `datetime` > '$temp_time_ogr' ";
$res_rand = mysqli_query($link, $select_rand);
$row_rand = mysqli_fetch_row($res_rand);
$all_count_temp_1 = $row_rand[0] - 70; // всего записей по выборке

$nomer_zap = rand(0,$all_count_temp_1);
   // echo $nomer_zap.'<br>         ';

$select_rand = "SELECT * FROM $Name_database.$table WHERE `datetime` > '$temp_time_ogr' LIMIT $nomer_zap, 1";

//$select_rand = "SELECT * FROM $Name_database.$table WHERE `datetime` > '$temp_time_ogr' ORDER BY RAND() LIMIT 1";
$res_rand = mysqli_query($link, $select_rand);
$row_rand = mysqli_fetch_array($res_rand);

$url_int = $row_rand['url'];
  //  echo $url_int.'<br>         ';
$teme_int = $row_rand['teme'];
   // echo $teme_int.'<br>         ';



$insert = "INSERT INTO $Name_database.$table (`id`, `datetime`, `teme`, `description`, `razdel`, `url`, `comments`, `text`, `keys`, `url_ext`, `url_frame`, `url_int`, `teme_int`)
VALUES ($id,'$datetime','$teme','$description','$razdel','$url',$comments,'$text','$keys','$url_ext','$url_frame','$url_int','$teme_int')";


$result = mysqli_query($link, $insert);
if ($result = 'true') {
//echo "Информация занесена в базу данных";
} else {
echo "Информация не занесена в базу данных";
}

$url_mass = explode('/', $url);
if (!empty($url_mass[6])) $chpu_url = "/" . $url_mass[6];
else $url_mass[6] = '';
$url_pic = "pictures/" . $url_mass[2] . "/" . $url_mass[3] . "/" . $url_mass[4] . "/" . $url_mass[5] . $chpu_url;

@mkdir("pictures/" . $url_mass[2], 0755);
@mkdir("pictures/" . $url_mass[2] . "/" . $url_mass[3], 0755);
@mkdir("pictures/" . $url_mass[2] . "/" . $url_mass[3] . "/" . $url_mass[4], 0755);
@mkdir("pictures/" . $url_mass[2] . "/" . $url_mass[3] . "/" . $url_mass[4] . "/" . $id, 0755);
@mkdir($url_pic, 0755);

$url_pic_site = str_replace(' ', '', $url_mass_img[$k]);
$url_pic_site = trim($url_pic_site);
$url_pic = "./pictures/" . $url_mass[2] . "/" . $url_mass[3] . "/" . $url_mass[4] . "/" . $url_mass[5] . $chpu_url;

$url_pic_temp = $url_pic;

$url_pic = $url_pic . '/img_1.jpg';

file_put_contents($url_pic, file_get_contents($url_pic_site));

$filename_sq = $url_pic_temp."/";
$filename1_sq = $filename_sq.'img_1.jpg';
//echo $filename1;
$filename2_sq = $filename_sq.'img_1_2.jpg';
//echo $filename2;
$image_smoll_sq =  imagecreatefromjpeg($filename1_sq);
imagejpeg($image_smoll_sq, $filename2_sq, 10);


}
    $hours = $hours + $total_parse;
    ?>
    <form method="POST" enctype="multipart/form-data" action="<?php echo $main_name; ?>/<?php echo $name_parse; ?>">
        <input type="text" name="hours" value="<?php echo $hours; ?>"><br>
        <input style="width:200px; height:50px; border: 1px solid #cccccc;" type="submit" value="Отправить парсинг"/>
    </form>