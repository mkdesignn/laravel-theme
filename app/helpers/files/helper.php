<?php
use Illuminate\Console\Scheduling\Schedule;
use Spatie\Sitemap\SitemapGenerator;

function testy(){

    $schedule = new Schedule();
    $schedule->command('GenerateSiteMap')->everyMinute();
}

function filePath(){
    if( app()->environment() == "local" ){
        return public_path()."/uploads/";
    } else {
        return dirname(dirname(public_path())). '/public_html/uploads/';
    }
}

/**
 * @param $selected_tags
 * @param $tag_table
 * @param \App\Acme\Interfaces\IMenu $menu
 * @return mixed
 */
function tagMenu($selected_tags, $tag_table, \App\Acme\Interfaces\IMenu $menu){

    $tag_menu = $menu
        ->setTable($tag_table)
        ->setClassName("tags")
        ->render($selected_tags);
    return $tag_menu;
}

function breakPosition($content){
    return strpos($content, '<!-- pagebreak -->');
}

function ved_decode($ved)
{
    // Copyright 2013 Deed Poll Office Ltd, UK <https://deedpolloffice.com>
    // Licensed under Apache Licence v2.0 <http://apache.org/licenses/LICENSE-2.0>
    $keys = array('t' => 2, 'r' => 6, 's' => 7, 'i' => 1);
    $ret  = array();
    if (substr($ved, 0, 1) == '1') {
        preg_match_all('/([a-z]+):([0-9]+)/i', $ved, $matches, PREG_SET_ORDER);
        foreach ($matches as $m)
            $ret[isset($keys[$m[1]]) ? $keys[$m[1]] : $m[1]] = (int) $m[2];
        return $ret;
    }
    preg_match_all('/([\x80-\xff]*[\0-\x7f])([\x80-\xff]*[\0-\x7f])/',
        base64_decode(str_replace(array('_','-'), array('+','/'), substr($ved, 1))),
        $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        $key = $val = 0;
        foreach (str_split($m[1]) as $i => $c) $key += (ord($c) & 0x7f) << $i * 7;
        foreach (str_split($m[2]) as $i => $c) $val += (ord($c) & 0x7f) << $i * 7;
        $ret[$key >> 3] = $val;
    }
    return $ret;
}

function getSearchResult($keyword){

    // get the content from the google website
    $subject = file_get_contents("http://www.google.com/search?q=".urlencode($keyword));

    // get any things that start with h3 tag and end with h3 tag
    $href_pattern = '/<h3 class="r">(.*?)<\/h3>/s';
    preg_match_all($href_pattern, $subject, $matches);

    foreach ($matches[0] as $key => $match) {

        // get any charachter that souranded between the href attribute
        $replacment_pattern = '/href="([^"]+)"/';
        preg_match($replacment_pattern, $match, $found);

        // get the first part of the whole url and break it
        // by using the & charachter
        $found = explode("&", $found[1]);

        // replace the url?q= with nothing
        $found[0] = str_replace("/url?q=", "", $found[0]);

        // decode the url if it was encoded
        $found[0] = urldecode($found[0]);

        // replace the found into the match using replacment_pattern
        $replace_url = preg_replace($replacment_pattern, "href='".$found[0]."'", $match);
        settype($replace_url, "string");

        // replace the new url to the old one
        if( gettype($replace_url) != "boolean" )
            $matches[0][$key] = $replace_url;
        else
            unset($matches[0][$key]);

        if( json_encode($matches[0][$key]) == false )
            unset($matches[0][$key]);
    }

    return $matches[0];
}

function sendSms($cell_number, $text){

    date_default_timezone_set('Asia/Tehran');
    $client = new nusoap_client("http://www.5m5.ir/webservice/soap/smsService.php?wsdl");
    $parameters['username'] = "a";
    $parameters['password'] = "312312312";
    $parameters['sender_number'] = array("30007051777777");
    $parameters['receiver_number'] = array($cell_number);
    $parameters['note'] = array($text);
    $parameters['date'] = date("Y-m-d")."T".date("H:i:s");
    $client->call("send_sms",$parameters);

}

function generatePassPhrase($users){
    $str = "01234567899876543210";
    $password = "";
    $password = substr(str_shuffle($str), 0, 8);
    while( $users->wherePassword(Hash::make($password))->count() > 0  ){
        $password = substr(str_shuffle($str), 0, 8);
    }

    return $password;
}

function createRandomNumber($table, $field, $min = 111111, $max = 999999){
    $random_number = rand($min, $max);
    if( $table->where($field, $random_number)->first() != null ){
        while( $random_number !=  $table->where($field, $random_number)->first()->number){
            $random_number = rand($min, $max);
        }
    }
    return $random_number;
}

/**
 * @param string $name
 * @return string
 */
function uploadFolderPath($name = "")
{
    return dirname($_SERVER["SCRIPT_FILENAME"])."/uploads/".$name;
}


function checkNationalCode($code='')
{

    $numbers = str_split($code);
    $numbers = array_reverse($numbers);
    $sum_numbers = 0;
    foreach ($numbers as $key => $number) {
        if( $key != 0 ){
            $sum_numbers += $number * ($key+1);
        }
    }
    $sum_numbers = $sum_numbers % 11;

    if( $sum_numbers < 2 ){
        if( $sum_numbers == $numbers[0] )
            return true;
        else
            return false;
    }elseif( $sum_numbers >= 2 ){
        if( 11 - $sum_numbers  == $numbers[0] )
            return true;
        else
            return false;
    }
}


/**
 * @param string $date
 * @return array
 */
function shamsiDate($date = ""){
    $carbon = "";
    if($date != ""){
        $carbon = new Carbon\Carbon($date);
    }else{
        $carbon = new Carbon\Carbon;
    }
    $shamsi_date = gregorian_to_mds($carbon->year, $carbon->month, $carbon->day);
    $shamsi_date[1] = sprintf("%02d", $shamsi_date[1]);
    $shamsi_date[2] = sprintf("%02d", $shamsi_date[2]);

    $month_name = monthname($shamsi_date[1]);
    $day_name = mds_date("l",$carbon->timestamp);
    return $shamsi_date + ["month"=>$month_name, "day"=>$day_name, "hour"=>sprintf("%02d", $carbon->hour), "min"=>sprintf("%02d", $carbon->minute), "sec"=>sprintf("%02d", $carbon->second)];
}


/**
 * @param $parent
 * @param $table
 * @param bool $link
 * @return mixed
 */
function pageMenu($parent, $table , $link = false){

    static $get_posts_once = 0;
    static $arr = [];
    static $path = [0];
    static $container = "";
    static $once = true;
    static $count_child = 0;

    if($get_posts_once == 0){
        $all_pages = $table;
        foreach ($all_pages as $page) {
            $arr[$page->id] = $page->parent_id;
        }
    }
    $get_posts_once++;

    $children = array_keys($arr, $parent);
    if(count($arr) != 0){
        if( count($children) > 0)
        {
            $count_child ++;
            array_push($path, $children[0]);

            $childs = "";
            foreach ($children as $child) {
                $childs .= "<li count_child='".$count_child."' >".$child."<ul></ul></li>";
            }

            settype($parent, "string");
            if($parent != "0"){
                if( !stristr($container, $childs) )
                    $container = str_replace($parent."<ul>", $parent."<ul>".$childs, $container);
            }
            if($once)
                $container .= $childs;

            $once = false;
            pageMenu($children[0], $table);
        }else{
            array_pop($path);
            unset($arr[$parent]);
            $count_child--;
            pageMenu($path[count($path)-1], $table);
        }
    }
    $container = str_replace("<ul></ul>", "", $container);

    $test = preg_replace_callback('/\d+</', function($id) use ($link) {

        $posts = DB::table("categories")->find($id);
        $action = "";

        if( str_contains($posts->type, "product") )
            $action = "products-category";
        else
            $action = "news-category";

        if( $link == true )
            return "<a href='".route($action, $posts->id)."' id='".$posts->id."'>".$posts->slug."</a><";
        else
            return "<a href='#' id='".$posts->id."'>".$posts->slug."</a><";

    }, $container);

    return $test;
}

/**
 * @param $month
 * @return string
 */
function MMonthName($month){
    switch($month){
        case 'January':
            return "ژانویه";
            break;
        case 'February':
            return "فوریه";
            break;
        case 'March':
            return "مارس";
            break;
        case 'April':
            return "آوریل";
            break;
        case 'May':
            return "مه";
            break;
        case 'June':
            return "ژوئن";
            break;
        case 'July':
            return "ژوئیه";
            break;
        case 'August':
            return "اوت";
            break;
        case 'September':
            return "سپتامبر";
            break;
        case 'October':
            return "اکتبر";
            break;
        case 'November':
            return "نوامبر";
            break;
        case 'December':
            return "دسامبر";
            break;
    }
}

function successMessage($val){
    return ["msg"=>"اطلاعات شما با موفقیت ".$val." شدند"];
}

function msg($val){
    return ["msg"=>$val];
}
//
function rpHash($value){

    $hash = 5381;
    $value = strtoupper($value);
    for($i = 0; $i < strlen($value); $i++) {
        $hash = (($hash << 5) + $hash) + ord(substr($value, $i));
    }
    return $hash;
}

function errors($errors){

    if($errors->count() > 0){
        echo "<div style='direction:rtl;font-family:IranSans' class='alert alert-danger red danger-errors'";
        echo "<p style='margin-right:10px;'>";
        foreach ($errors->all() as $error) {
            echo "<span style='vertical-align: text-bottom;'>•</span> &nbsp; &nbsp;".$error;
            echo "<br/>";
        }
        echo "</p>";
        echo "</div>";
    }
}

function message($msg){
    echo "<div style='text-align:right;font-family: IranSans;direction:rtl;' class='alert alert-success green'>".Session::get('msg')."</div>";
}

function resolveExtensions($file_name){
    if( $file_name != "" ){
        $file_name = explode(".", $file_name);
        return $file_name[count($file_name)-1];
    }else{
        return "";
    }
}

function easyDate($date){

    $shamsi_date = shamsiDate($date);
    return $shamsi_date[0]."/".$shamsi_date[1]."/".$shamsi_date[2];
}

function easyDateTime($date){
    $shamsi_date = shamsiDate($date);
    $date = explode(" ", $date);
    return $shamsi_date["day"]." ".$shamsi_date[2]." ".$shamsi_date["month"]." ".$shamsi_date[0];
}

function avatar( $email_address ,$style = "", $firstname = null , $surname = null , $size = '50')
{
    $hash = md5( strtolower( trim( $email_address ) ) );
    $url = 'http://www.gravatar.com/avatar/'.$hash.'?s=' . $size;
    if ( $firstname && $surname ){
        $alt_text = $firstname . ' ' . $surname;
    } else {
        $alt_text = 'Gravatar for ' . $email_address;
    }

    return '<img style="'.$style.'" class="avatar" src="'.$url.'" alt="'.$email_address.'"/>';
}

/**
 * @param $image_address
 * @param $save_image
 */
function saveThumbnail($image_address, $save_image){

    $save_image = explode(".", $save_image);
    $filename = $image_address;

    // Get new dimensions
    list($source_width, $source_height) = getimagesize($filename);

    //      - 768px by 1024px (http://goo.gl/nY45z)
    if( $source_width > 768 && $source_height < 1024 ){
        $large_thumbnail_percent = 0.8;
        $large_thumbnail_width = $source_width * $large_thumbnail_percent;
        $large_thumbnail_height = $source_height * $large_thumbnail_percent;
        createImage($large_thumbnail_width, $large_thumbnail_height,
            $filename, $save_image[0]."_large.".$save_image[1] , $source_width, $source_height);
    }

    //      - 375px by 500px (http://goo.gl/nY45z)
    if( $source_width > 375 && $source_height > 500 ){
        $big_thumbnail_percent = 0.6;
        $big_thumbnail_width = $source_width * $big_thumbnail_percent;
        $big_thumbnail_height = $source_height * $big_thumbnail_percent;
        createImage($big_thumbnail_width, $big_thumbnail_height,
            $filename, $save_image[0]."_big.".$save_image[1] , $source_width, $source_height);
    }


    //      - 180px by 240px (http://goo.gl/nY45z)
    if( $source_width > 180 && $source_height > 240 ){
        $small_thumbnail_percent = 0.4;
        $small_thumbnail_width = $source_width * $small_thumbnail_percent;
        $small_thumbnail_height = $source_height * $small_thumbnail_percent;
        createImage($small_thumbnail_width, $small_thumbnail_height,
            $filename, $save_image[0]."_small.".$save_image[1] , $source_width, $source_height);
    }

    //      - 75px by 100px (http://goo.gl/F03do)
    if( $source_width > 180 && $source_height > 100 ){
        $extra_small_thumbnail = 0.2;
        $extra_small_thumbnail_width = $source_width * $extra_small_thumbnail;
        $extra_small_thumbnail_height = $source_height * $extra_small_thumbnail;
        createImage($extra_small_thumbnail_width, $extra_small_thumbnail_height,
            $filename, $save_image[0]."_extra_small.".$save_image[1] , $source_width, $source_height);
    }
}

function createImage($new_width, $new_height, $image_address, $save_image
    , $source_width, $source_height){

    $image_p = imagecreatetruecolor($new_width, $new_height);
    if( exif_imagetype($image_address) == 2 ) {

        // Resample
        $image = imagecreatefromjpeg($image_address);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height);

        // Output
        imagejpeg($image_p, $save_image, 100);

    } elseif ( exif_imagetype($image_address) == 3 ){

        $targetImage = imagecreatetruecolor( 270, $new_height );
        imagealphablending( $targetImage, false );
        imagesavealpha( $targetImage, true );

        $image = imagecreatefrompng($image_address);
        imagealphablending($image, true);

        imagecopyresampled($targetImage, $image, 0, 0, 0, 0, 270, $new_height, $source_width, $source_height);
        // Output
        imagepng($targetImage, $save_image, 9);

    }
}