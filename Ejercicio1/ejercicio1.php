<?php
    $text = '"El próximo día 21/05/2019 se celebrará desde las 14:30 a las 15 horas el congreso de …"';

    function regexHours($text){
        $regexHours = '/(\d+(?:[\:])\d+)|(\s\d+\s)/m';

        if(preg_match_all($regexHours, $text, $matchesHours)){
            return $matchesHours;
        }
        return false;
    }

    function regexDate($text){
        $regexDate = '/(\d+(?:[\/])\d+(?:[\/]\d+))|(\d+(?:[\-])\d+(?:[\-]\d+))/m';

        if(preg_match_all($regexDate, $text, $matchesDate)){
            return $matchesDate;
        }
        return false;
    }

    function parseMinutes($matchesHours){
        $hoursArr = array();
        if($matchesHours){
            foreach($matchesHours[0] as $hour){
                $parseHour = explode(':', $hour);
                if(count($parseHour) <= 1){
                    $hour = trim($hour) . ':00'; 
                }
                $hoursArr[] = $hour;
            }
        }
        return $hoursArr;
    }

    function getHour($matchesDate, $matchesHours){
        $formats = ['j/m/Y H:i', 'j-m-Y H:i'];
        $formatSelected = '';
        foreach($formats as $format){
            $dateFirst = DateTime::createFromFormat($format, $matchesDate[0][0] . '' . $matchesHours[0]);
            $dateSecond = DateTime::createFromFormat($format, $matchesDate[0][0] . '' . $matchesHours[1]);
            
            if ($dateFirst !== false && $dateSecond !== false) {
                $formatSelected = $format;
                break;
            }
        }

        echo $dateFirst->format($formatSelected) . "\n";
        echo $dateSecond->format($formatSelected) . "\n";
    }

    $hours = regexHours($text);
    $date = regexDate($text);
    if($hours){
        $hoursArr = parseMinutes($hours);
    }else{
        echo "No se pudieron leer las horas";
    }
    if($date && $hoursArr){
        getHour($date, $hoursArr);
    }else{
        echo "No se pudieron leer las horas y la fecha";
    }
