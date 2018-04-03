﻿<?php


$url_mass_url = array();
$url_mass_titles = array();
$url_mass_description = array();
$url_mass_img = array();
$url_mass_texts = array();
$all_count = array();
$all_count_2 = array();

for($j = 0; $j < 4; $j++) {
    $mainContent = file_get_contents($ParserPage);
    $contentTitle = $mainContent;

    $i = 0;
    $k = 0;
    while (true) {
        $contentTitle = $mainContent;
        switch($j) {

            case 0:
                if ($i == 0) {
                    $StartWord = "<pubDate>";
                    $EndWord = "</pubDate>";
                } else {
                    $StartWord = "<link>";
                    $EndWord = "</link>";
                }
                break;
            case 1:
                if ($i == 0) {
                    $StartWord = "<pubDate>";
                    $EndWord = "</pubDate>";
                } else {
                    $StartWord = "<title>";
                    $EndWord = "</title>";
                }
                break;
            case 2:
                $StartWord = "<description>";
                $EndWord = "</description>";
                break;
            case 3:
                if ($i == 0){
                    $StartWord = '<language>';
                    $EndWord = '</language>';
                } else {
                    $StartWord = '<enclosure url="';
                    $EndWord = '" ';
                }
                break;
        }
        $LengthWord = 0;
// Определяем позицию строки, до которой нужно все отрезать
        $pos = strpos($contentTitle, $StartWord);
        if ($pos === false) break;


//Отрезаем все, что идет до нужной нам позиции
        $contentTitle = substr($contentTitle, $pos);

        $pos_2 = strpos($contentTitle, $EndWord);
        $mainContent = substr($contentTitle, $pos_2);

        //echo '<br>'.$ParserPage.'<br>';

// Точно таким же образом находим позицию конечной строки
        $pos = strpos($contentTitle, $EndWord);

// Отрезаем нужное количество символов от нулевого
        $contentTitle = substr($contentTitle, $LengthWord, $pos);

//если в тексте встречается текст, который нам не нужен, вырезаем его
        //$contentTitle = str_replace('entry-content clearfix">', '', $contentTitle);
$contentTitle = str_replace('<link>','', $contentTitle);
        $contentTitle = str_replace('<title>','', $contentTitle);
        $contentTitle = str_replace('<description><![CDATA[','', $contentTitle);
        //$contentTitle = str_replace('Информационный портал Русь молодая','', $contentTitle);
        //$contentTitle = str_replace('впервые появилась','', $contentTitle);
        //$contentTitle = str_replace('Запись ','', $contentTitle);
        //$contentTitle = str_replace('  ','', $contentTitle);
        $contentTitle = str_replace('<enclosure url="','', $contentTitle);
//$contentTitle = str_replace('<title','', $contentTitle);
//$contentTitle = str_replace('<','', $contentTitle);
//$contentTitle = stripslashes($contentTitle);
//$contentTitle = htmlspecialchars($contentTitle);

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
                        $text_temp_2 = strip_tags(parser_page($temp_url, "article_body", "</div>"), '<p><img><frame><figure><figcaption><h1><h2><h3><strong><table><tbody><tr><td>');
                        $pos_text = strpos($text_temp_2, '<p>');
                        $text_temp_2 = substr($text_temp_2, $pos_text);
                        $text_temp_2 = str_replace('px', '', $text_temp_2);
                        $text_temp_2 = str_replace('style', '', $text_temp_2);
                        $text_temp_2 = str_replace('width', '', $text_temp_2);
                        $text_temp_2 = str_replace('height', '', $text_temp_2);
                        $text_temp_2 = str_replace('</figure>', '</figure></br>', $text_temp_2);
                        $text_temp_2 = str_replace('Читайте также:', '', $text_temp_2);
                        $text_temp_2 = str_replace('Читайте также', '', $text_temp_2);
                        $text_temp_2 = str_replace('FINANCE.', '', $text_temp_2);
                        $url_mass_texts[$k] = str_replace('TUT.BY', 'by-by.info', $text_temp_2);
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
                        $contentTitle = str_replace('TUT.BY', 'by-by.info',$contentTitle);
                        $url_mass_titles[$k] = $contentTitle;
                    }

                    break;
                case 2:
                    if ($all_count[$i] == 0) {
                        $k++;
                        $contentTitle = str_replace('&#x3C;','<',$contentTitle);
                        $contentTitle = str_replace('/&#x3E;','>',$contentTitle);
                        $contentTitle = str_replace('TUT.BY', 'by-by.info',$contentTitle);
                        $contentTitle = str_replace('FINANCE.', '',$contentTitle);
                        $url_mass_description[$k] = strip_tags($contentTitle, '<p>');
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


$url_ext = 'https://www.tut.by';


require_once("parser_insert_news.php");