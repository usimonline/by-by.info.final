﻿<?php


$url_mass_url = array();
$url_mass_titles = array();
$url_mass_description = array();
$url_mass_img = array();
$url_mass_texts = array();
$all_count = array();
$all_count_2 = array();

$mainContent_2 = file_get_contents($ParserPage);

for($j = 0; $j < 4; $j++) {
    $mainContent = $mainContent_2;
    $contentTitle = $mainContent;

    $i = 0;
    $k = 0;
    while (true) {
        $contentTitle = $mainContent;
        switch($j) {
            case 0:
                $StartWord = "<link>";
                $EndWord = "</link>";
                break;
            case 1:
                $StartWord = "<title>";
                $EndWord = "</title>";
                break;
            case 2:
                $StartWord = "<description>";
                $EndWord = "</description>";
                break;
            case 3:
                if ($i == 0){
                    $StartWord = '<copyright>';
                    $EndWord = '</copyright>';
                } else {
                    $StartWord = '<enclosure url="';
                    $EndWord = '" ';
                }

                break;

        }
        $LengthWord = 0;
// Определяем позицию строки, до которой нужно все отрезать
        $contentTitle_2 = $contentTitle;
        $pos = strpos($contentTitle, $StartWord);
        if ($pos === false) break;
//Отрезаем все, что идет до нужной нам позиции
        $contentTitle = substr($contentTitle, $pos);

        if ($j == 3) {
            if ($i ==0 ) {
                $StartWord_2 = '<copyright>';
                $EndWord_2 = '</copyright>';
            }
            else {
                $StartWord_2 = '<item>';
                $EndWord_2 = '</item>';
            }
            $pos_t = strpos($contentTitle_2, $StartWord_2);
            if ($pos_t === false) break;

//Отрезаем все, что идет до нужной нам позиции
            $contentTitle_2 = substr($contentTitle_2, $pos_t);
            $pos_2_t = strpos($contentTitle_2, $EndWord_2);
            $mainContent = substr($contentTitle_2, $pos_2_t);
        } else {
            $pos_2 = strpos($contentTitle, $EndWord);
            $mainContent = substr($contentTitle, $pos_2);
        }

        //echo '<br>'.$ParserPage.'<br>';

// Точно таким же образом находим позицию конечной строки
        $pos = strpos($contentTitle, $EndWord);

// Отрезаем нужное количество символов от нулевого
        $contentTitle = substr($contentTitle, $LengthWord, $pos);

//если в тексте встречается текст, который нам не нужен, вырезаем его

        $contentTitle = str_replace('<link>','', $contentTitle);
        $contentTitle = str_replace('<title>','', $contentTitle);
        $contentTitle = str_replace('<description><![CDATA[','', $contentTitle);

        $contentTitle = str_replace('<enclosure url="','', $contentTitle);
        $contentTitle = str_replace('<item>','', $contentTitle);


// выводим спарсенный текст.
        //echo $contentTitle;
        if($i != 0) {
            switch ($j) {

                case 0:
                    $url_temp_5 = str_replace(' ', '', trim($contentTitle));

                    $select = "SELECT COUNT(*) FROM $Name_database.$table_link WHERE `url` = '$url_temp_5'";
                    $res = mysqli_query($link, $select);
                    $row = mysqli_fetch_row($res);
                    $all_count[$i] = $row[0]; // всего записей по выборке

                    if ($all_count[$i] == 0) {
                        $id = time() + ($i);
                        $insert = "INSERT INTO $Name_database.$table_link (`id`, `url`) VALUES ($id,'$url_temp_5')";
                        $result = mysqli_query($link, $insert);
                        if ($result = 'true'){
                            //echo "Информация занесена в базу данных";
                        }else{
                            echo "Информация не занесена в базу данных";
                        }

                        $k++;
                        $url_mass_url[$k] = $url_temp_5;
                        $temp_url = $url_mass_url[$k];
                        // Определяем позицию строки <p>, до которой нужно все отрезать
                        $text_temp_2 = otbor_parse($temp_url, "©", "b-banner");
                        $text_temp_2 = str_replace('©','', $text_temp_2);
                        $pos_feed_1 = strpos($text_temp_2, '<div class="b-inject');
                        $contentTitle_feed_1 = substr($text_temp_2, 0, $pos_feed_1);
                        $pos_feed_2 = strpos($text_temp_2, '<div class="b-inject');
                        $contentTitle_feed_2 = substr($text_temp_2, $pos_feed_2);
                        $pos_feed_2 = strpos($contentTitle_feed_2, '</div></div></p>');
                        $contentTitle_feed_2 = substr($contentTitle_feed_2, $pos_feed_2);
                        $text_temp_2 = $contentTitle_feed_1.'</p><br>'.$contentTitle_feed_2;


                        $text_temp_2 = strip_tags($text_temp_2, '<p><img><frame><figure><figcaption><h1><h2><h3><strong><table><tbody><tr><td>');
                        $pos_text = strpos($text_temp_2, '<p>');
                        $text_temp_2 = substr($text_temp_2, $pos_text);
                        $text_temp_2 = str_replace('px', '', $text_temp_2);
                        $text_temp_2 = str_replace('style', '', $text_temp_2);

                        //$text_temp_2 = strip_tags(parser_page($temp_url, "©", "social-likes-pane"),'<p><img>');
                        //$pos_text = strpos($text_temp_2, '<p>');
                        //$text_temp_2 = substr($text_temp_2, $pos_text);
                        //$text_temp_2 = str_replace('Sputnik','by-by.info', $text_temp_2);

                        $text_temp_2 = str_replace('width', '', $text_temp_2);
                        $text_temp_2 = str_replace('height', '', $text_temp_2);
                        $text_temp_2 = str_replace('</figure>', '</figure></br>', $text_temp_2);
                        $text_temp_2 = str_replace('Читайте также:', '', $text_temp_2);
                        $text_temp_2 = str_replace('Читайте также', '', $text_temp_2);
                        $text_temp_2 = str_replace('FINANCE.', '', $text_temp_2);
                        $url_mass_texts[$k] = str_replace('Sputnik','by-by.info', $text_temp_2);
                        //$url_mass_img[$i] = parser_page($contentTitle, "featured-image", "class=");
                    }
                    break;
                case 1:


                    //$select = "SELECT COUNT(*) FROM $Name_database.$table WHERE `teme` = '$contentTitle'";
                    //$res = mysqli_query($link, $select);
                    // $row = mysqli_fetch_row($res);
                    //$all_count[$i] = $row[0]; // всего записей по выборке
                    //echo $all_count;

                    if ($all_count[$i] == 0) {
                        $k++;
                        $contentTitle = str_replace('Sputnik','by-by.info',$contentTitle);
                        $url_mass_titles[$k] = $contentTitle;
                    }

                    break;
                case 2:
                    if ($all_count[$i] == 0) {
                        $k++;
                        //$contentTitle = str_replace('&#x3C;','<',$contentTitle);
                        //$contentTitle = str_replace('/&#x3E;','>',$contentTitle);
                        $contentTitle = str_replace('Sputnik','by-by.info',$contentTitle);
                        //$contentTitle = str_replace('FINANCE.', '',$contentTitle);
                        $url_mass_description[$k] = strip_tags($contentTitle, '<p>');
                        $url_mass_texts[$k] = str_replace('<p>'.$url_mass_description[$k].'</p>','',$url_mass_texts[$k]);
                    }
                    break;
                case 3:
                    if ($all_count[$i] == 0) {
                        $k++;
                        $url_mass_img[$k] = $contentTitle;
                    }
                    break;

            }
        } else {
            $url_mass_url[0] = '';
            $url_mass_texts[0] = '';
            $url_mass_img[0] = '';
            $url_mass_titles[0] = '';
            $url_mass_description[0] = '';
        }
        $i++;
        if ($k == 6 ) break;

    }
}


$url_ext = 'https://sputnik.by';


require_once("parser_insert_news.php");