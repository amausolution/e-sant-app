<?php

use App\Partner\Models\SettingPartner;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Feggu\Core\Partner\Models\CategoryAnalysis;
use Feggu\Core\Partner\Models\DepartmentPartner;
use Feggu\Core\Partner\Models\FegguConsultation;
use Feggu\Core\Partner\Models\FegguPartner;
use Feggu\Core\Partner\Partner;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Jenssegers\Agent\Agent;

function getCurrency(
    $value,
    $locale = 'fr_FR',
    $style = NumberFormatter::CURRENCY,
    $precision = 0,
    $groupingUsed = true
    )
{
    $currencyCode = getPartner()->currency ;
    $formatter = new NumberFormatter($locale, $style);
    $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);
    $formatter->setAttribute(NumberFormatter::GROUPING_USED, $groupingUsed);
    if ($style === NumberFormatter::CURRENCY) {
        $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currencyCode);
    }

    return $formatter->format($value);
}

function getCurrencySymbol($locale = 'fr_FR')
{

    $currencyCode = getPartner()->currency;
    $formatter = new \NumberFormatter($locale . '@currency=' . $currencyCode, \NumberFormatter::CURRENCY);
    return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
}

function showAge($date) {
    $age_format =    Carbon::parse($date)->diff(Carbon::now())->format('%y,%m,%d');
    $array = explode(',',$age_format);
   // $year = $array[0]>0?$array[0].' '.__('year').' ':'';
    $day = '';
    if ($array[2]>0){
        if ($array[2]===1){
            $day = $array[2].' '.__('day').' ';
        }else{
            $day = $array[2].' '.__('days').' ';
        }
    }
    $month = '';
    if ($array[1]>0){
        if ($array[1]===1){
            $month = $array[1].' '.__('month').' ';
        }else{
            $month = $array[1].' '.__('months').' ';
        }
    }
    $year = '';
    if ($array[0]>0){
        if ($array[0]===1){
            $year = $array[0].' '.__('year').' ';
        }else{
            $year = $array[0].' '.__('years').' ';
        }
    }
    return $year.$month.$day;
}
function getDosageText(array $data){
     if (!is_array($data)){
         return null;
     }
     $result = '';
   $arr= array_flatten($data);
     foreach ($arr as $k => $value){
         if (!is_null($value)){
             $result .= ' ' . __(dosageText()[$value]);
         }
     }
     return $result;
}
function showTime()
{
    $lang = session()->get('locale')??'fr';
    $tz = config('app.timezone');
    \Carbon\Carbon::setlocale($lang);
    return \Carbon\Carbon::parse(now($tz))->translatedFormat('H:i');
}

function dosageText(){
    return [
      1=>'Noon' ,
      2=>'Afternoon',
      3=>'Evening' ,
    ];
}
function formatting($phone){

    // Pass phone number in preg_match function
    if(preg_match(
        '/^\+[0-9]([0-9]{3})([0-9]{3})([0-9]{4})$/',
        $phone, $value)) {

        // Store value in format variable
        $format = $value[1] . '-' .
            $value[2] . '-' . $value[3];
    }
    else {

        // If given number is invalid
        return "Invalid phone number <br>";
    }

    // Print the given format
    return $format;
}
function generateTicket($department){
    $ticket = 0;
    $partner = session('partnerId');
    $consultation = FegguConsultation::where('hospital_id',$partner)->where('department_id',$department)->whereDate('created_at', '=', date('Y-m-d'))->count();
    if ($consultation >0){
        $ticket = $consultation+1;
    }else{
        $ticket = 1;
    }
    return $ticket;
}

function generateDocNumber($f,$l,$id)
{
    return 'FSN-'.$id.strtoupper(substr($f, 0, 2).substr($l, 0, 2));
}

function getWaiting($department){
    $wait = 0;
    $partner = session('partnerId');
    $consultation = FegguConsultation::where('hospital_id',$partner)->where('department_id',$department)->where('status',0)->whereDate('created_at', '=', date('Y-m-d'))->count();
    if ($consultation >0){
        $wait = $consultation-1;
    }else{
        $wait = 0;
    }
    return $wait;
}
function statusConsultation()
{
    return [
        0=> '<span class="btn btn-white btn-sm btn-rounded"><i class="fa fa-dot-circle-o text-info mr-1"></i>'.__('Waiting').'</span>',
        1=>'<span class="btn btn-white btn-sm btn-rounded"><i class="fa fa-dot-circle-o text-success mr-1"></i>'.__('View').'</span>',
        2=>'<span class="btn btn-white btn-sm btn-rounded"><i class="fa fa-dot-circle-o text-warning mr-1"></i>'.__('Consulting').'</span>',
        3=>'<span class="btn btn-white btn-sm btn-rounded"><i class="fa fa-dot-circle-o text-danger mr-1"></i>'.__('Missing').'</span>',
    ];
}
function gender()
{
    return [
      0 =>__('Female'),
      1 =>__('Male')
    ];
}
function genders()
{
    return [
          ['id' => 0,'title' =>__('Female')],
          ['id' =>1 ,'title'=>__('Male')]
    ];
}
function typeHospitalisation()
{
    return [
        0=>__('Monitoring'),
        1=>__('Hospitalisation')
    ];
}

function typePiece()
{
    return [
        1=>__('CIN'),
        2=>__('PASSPORT')
    ];
}
function typePayment()
{
    return [
        'cash'=>__('Cash'),
        'insurer'=>__('Insurer'),
        'cmu'=>__('CMU')
    ];
}
function getAnalysis()
{
  return  CategoryAnalysis::all();
}
function getPartner()
{
    $idP = session('partnerId')??1;
    if (!$idP){
        return false;
    }
    return FegguPartner::find($idP);
}


function getAgentUser()
{
    return new Agent();
}
function getClassroom()
{
    return [
        'A'=> 'Cabinet',
        'B'=> 'Simple',
        'C'=> 'Public',
    ];
}
function getTypeBed()
{
    return [
        'A'=> 'Type A',
        'B'=> 'Type B',
        'C'=> 'Type C',
    ];
}
function getDepartment(){
    return \Feggu\Core\Partner\Models\DepartmentPartner::where('partner_id',session('partnerId'))->get();
}
function getDay(){
    $array = [];
    $count = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
    for($i=1;$i<$count+1; $i++){
        $jd=gregoriantojd(date('m'),$i,date('Y'));
        // echo jddayofweek($jd,2) ;
        $array []=jddayofweek($jd,1).' '.$i;
    }

     return $array;
}
function getDayNumber(){
    $array = [];
    $count = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
    for($i=1;$i<$count+1; $i++){
        $jd=gregoriantojd(date('m'),$i,date('Y'));
        // echo jddayofweek($jd,2) ;
        $array []=jddayofweek($jd,0);
    }

    return $array;
}

function getDoctors()
{
    return \Feggu\Core\Partner\Models\PartnerUser::where('group',1)->where('partner_id',session('partnerId'))->get();
}

function arrayMonth()
{
    return [
         1  => __('January'),
         2  => __('February'),
         3  => __('March'),
         4  => __('April'),
         5  => __('May'),
         6  => __('June'),
         7  => __('July'),
         8  => __('August'),
         9  => __('September'),
         10 => __('October'),
         11 => __('November'),
         12 => __('December'),
    ];
}
function getYear()
{
    return range(date('Y'),date('Y')+3);
}

function CheckArray($array){
    $test=false;
    foreach ($array as $a =>$b){
        if (is_array($b)){
            return true;
        }
    }
    return $test;
}

function showDepart($id)
{
    return DepartmentPartner::where('id',$id)->first()->department ?? null;
}
if (!function_exists('au_setting')) {
    /**
     * Get value config from table au_config
     * Default value is only used if the config key does not exist (including null values)
     *
     * @param   [string|array|null]  $key      [$key description]
     * @param   [int|null]  $partnerId  [$partnerId description]
     * @param   [string|null]  $default  [$default description]
     *
     * @return  [type]            [return description]
     */
    function au_setting($key = null, $partnerId = null)
    {
        $partnerId = session('partnerId') ? config('app.partnerId') : $partnerId;
        //Update config
//        if (is_array($key)) {
//            if (count($key) == 1) {
//                foreach ($key as $k => $v) {
//                    return SettingPartner::where('partner_id', $partnerId)
//                        ->where('key', $k)
//                        ->update(['value' => $v]);
//                }
//            } else {
//                return false;
//            }
//        }
        //End update

        $allConfig = SettingPartner::where('partner_id',$partnerId)->first()->toArray();

        if ($key === null) {
            return $allConfig;
        }
        return array_key_exists($key, $allConfig) ?? $allConfig[$key];
    }
}
function getStatusAppoint (){
    return  [
        0=> __('New'),
        1=> __('Done'),
        2=> __('Canceled')
    ];
}
function addDico($dico,$key,$value){
    $items = file_get_contents(resource_path('dico/'.$dico.'.json'));
    $reqKey = trim(\Str::slug($key));
    if (!array_key_exists($reqKey, json_decode($items, true))) {
        $newArr[$reqKey] = trim($value);
        $itemsDico = json_decode($items, true);
        $result = array_merge($itemsDico, $newArr);
        file_put_contents(resource_path('dico/'.$dico.'.json'), json_encode($result));
    }
}
function getDicoHealth()
{
    $items = file_get_contents(resource_path('dico/health.json'));
    $items = json_decode($items,true);
    return array_flatten((array)$items);
}

function translate()
{
    $locale = App::getLocale();
    $phpTranslations = [];
    $jsonTranslations = [];
/*     Cache::rememberForever("translations_$locale", function () use ($locale) {
        if (File::exists(resource_path("lang/$locale"))) {
            $phpTranslations = collect(File::allFiles(resource_path("lang/$locale")))
                ->filter(function ($file) {
                    return $file->getExtension() === "php";
                })->flatMap(function ($file) {
                    return Arr::dot(File::getRequire($file->getRealPath()));
                })->toArray();
        }

        if (File::exists(resource_path("lang/$locale.json"))) {
            $jsonTranslations = json_decode(File::get(resource_path("lang/$locale.json")), true);
        }
    });*/
    if (File::exists(resource_path("lang/$locale"))) {
        $phpTranslations = collect(File::allFiles(resource_path("lang/$locale")))
            ->filter(function ($file) {
                return $file->getExtension() === "php";
            })->flatMap(function ($file) {
                return Arr::dot(File::getRequire($file->getRealPath()));
            })->toArray();
    }

    if (File::exists(resource_path("lang/$locale.json"))) {
        $jsonTranslations = json_decode(File::get(resource_path("lang/$locale.json")), true);
    }
    return array_merge($phpTranslations, $jsonTranslations);
}

function checkIsExp($date):bool
{
    $date1 = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
    $date2 = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime("+5 day")));
    return $date1->gt($date2);
}

if (!function_exists('au_token')) {
    /*
    Create random token
     */
    function au_token(int $length = 32)
    {
        $token = Str::random($length);
        return $token;
    }
}

if (!function_exists('generateId')) {
    /**
     * Generate ID
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    function generateId($type = null)
    {
        switch ($type) {
            case 'shop_store':
                return 'S-'.au_token(5).'-'.au_token(5);
                break;
            case 'shop_order':
                return 'O-'.au_token(5).'-'.au_token(5);
                break;

            default:
                return au_uuid();
                break;
        }
    }
}
if (!function_exists('au_uuid') ) {
    /**
     * Generate UUID
     *
     * @param   [string]  $name
     * @param   [array]  $param
     *
     * @return  [type]         [return description]
     */
    function au_uuid()
    {
        return (string)\Illuminate\Support\Str::orderedUuid();
    }
}
function employeeDepartments()
{
    return \Partner::user()->areDepartments??[];
}
function statusPatient(){
    return [
        0=>'waiting',
        1=>'view',
        2=>'consulting',
        3=>'missing'
    ];

}




