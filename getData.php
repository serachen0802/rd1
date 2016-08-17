<?php
header("content-type: text/html; charset=utf-8");
require_once("connect.php");

// 關掉瀏覽器，PHP腳本也可以繼續執行\
ignore_user_abort();
set_time_limit(0);
$interval=60;
do{

// 建立CURL連線
    $search = curl_init();

    // 設定擷取的URL網址
    $header = ["Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                "Accept-Encoding:gzip, deflate, sdch",
                "Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4",
                "Connection:keep-alive",
                "Cookie:PHPSESSID=l97onqma8469lsc7h20ltr0v01",
                "Host:www.228365365.com",
                "If-Modified-Since:Wed, 17 Aug 2016 08:54:44 GMT",
                "Referer:http://www.228365365.com/app/member/FT_browse/index.php?rtype=r&uid=test00&langx=zh-cn&mtype=3&showtype=future&league_id=&hot_game=",
                "Upgrade-Insecure-Requests:1",
                "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36
            "];
    $url = "http://www.228365365.com/app/member/FT_browse/body_var.php?uid=test00&rtype=r&langx=zh-cn&mtype=3&delay=&league_id=";

    curl_setopt($search, CURLOPT_URL, $url);
    curl_setopt($search, CURLOPT_HEADER, 0);
    curl_setopt($search, CURLOPT_RETURNTRANSFER, 1);

    // 設置HTTP字段的数组
    curl_setopt($search, CURLOPT_HTTPHEADER, $header);

    // 執行
    $result = curl_exec($search);

    // 關閉CURL連線
    curl_close($search);
    // echo htmlspecialchars($result);
    preg_match_all("/parent.GameFT(.+)/", $result, $data);

    for($i = 0; $i < count($data[0]); $i++){
        $r = explode(",",$data[0][$i]);
        $r = str_replace("<font color=red>Running Ball</font>","",$r);
        $r = str_replace("<br>", "", $r);
        $r = str_replace("parent.","$",$r);
        $r = str_replace('new Array',"Array",$r);
        $r = str_replace('$GameFT['.$i.']=Array(',"",$r);
        $r = str_replace("'","",$r);

        $id = $r[0];
        $League = $r[2];//聯賽
        $time = $r[1];
        $event = $r[5].''.$r[6];//賽事
        $OverallWin = $r[16]." ".$r[16]." ".$r[17];// 全場獨贏
        $OverallHandicap = $r[9].''.$r[10];// 全場讓球
        $OverallSize =$r[11].$r[13].''.$r[12].$r[14];// 全場大小
        $Mono =$r[18].$r[20].''.$r[19].$r[21];// 單雙
        $HalfWin = $r[31].''.$r[32].''.$r[33];// 半場獨贏
        $HalfHandicap = $r[25].''.$r[26];// 半場讓球
        $HalfSize = $r[29].''.$r[30];// 半場大小

        $insert = $db->prepare("REPLACE INTO `Football`" .
                "(`id`, `League`, `time`, `event`, `OverallWin`, `OverallHandicap`, `OverallSize`, `Mono`,
                `HalfWin`, `HalfHandicap`, `HalfSize`)" ."VALUES (:id, :League, :time, :event, :OverallWin,
                :OverallHandicap, :OverallSize, :Mono, :HalfWin, :HalfHandicap, :HalfSize)");
        $insert->bindParam(':id', $id);
        $insert->bindParam(':League', $League);
        $insert->bindParam(':time', $time);
        $insert->bindParam(':event', $event);
        $insert->bindParam(':OverallWin', $OverallWin);
        $insert->bindParam(':OverallHandicap', $OverallHandicap);
        $insert->bindParam(':OverallSize', $OverallSize);
        $insert->bindParam(':Mono', $Mono);
        $insert->bindParam(':HalfWin', $HalfWin);
        $insert->bindParam(':HalfHandicap', $HalfHandicap);
        $insert->bindParam(':HalfSize', $HalfSize);
        $insert->execute();
}

sleep($interval);
}while(true);
