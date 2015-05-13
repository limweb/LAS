<?php

/*====================================
USER NAME:          
FILE NAME:          test.php
 TAB SIZE:          2
SOFT TABS:          YES

====================================*/

require_once __DIR__.'/../database.php';
use Illuminate\Database\Capsule\Manager as Capsule;

$sv = new TestService();
$rs = $sv->test();
exit();

class TestService {

  public function __construct() {
    Capsule::enableQueryLog();
  }

  public function getAllTest() {
    $rs = Test::get();
    if($rs){
      return $rs;
    } else {
     return [];
   }
 }

 public function getTestByID() {
   $rs =    Test::find();
   if($rs){
    return $rs;
  } else {
    return -1;
  }
}

public function createTest() {
  $rs = Test::create();
  if($rs) {
    return $rs;
  } else {
    return -1;
  }
}

public function updateTest($item) {
  $rs = Test::find($item->id);
  if($rs) {
    $result = $rs->update();
    return $result;
  } else{
    return -1;
  }
}

public function deleteTest($id) {
  $rs = Test::find();
  if($rs){
    $result = $rs->destroy();
    return $result;
  } else {
    return -1;
  }
}

public function count() {
  $rs = Test::count();
  return $s;
}

public function getTest_paged($startIndex, $numItems) {
  $rs = Test::take($numItems)->skip($startIndex);
  return  $rs;
}

public function __destruct() {
  consolelog(Capsule::getQueryLog());
}




public  function searchs() {

    // {        "type":0,
    //          "paramars":{"product_code":"1","barcode":"34660" },
    //          "params":["11","22"],
    //          "params":"11",
    //          "cols":["product_code","barcode","name","category","typegroup"],
    //           "opr":"and",
    //          "order":"asc",
    //          "result_lenght":"10",
    //          "pageNo":"1" 
    // }
  if( $search == NULL )  return  '-1';

  if($search['result_lenght'] < 0 || $search['pageNo'] <= 0 ) return '-2';
  $skip = ( $search['pageNo'] -1 )  *  ['result_lenght']; 


  $rs =  ${1}::whereRaw('1= ?',[1]);


        //type == 1
  if($search['type'] == 1 ) {
    $rs->where( function($query) use ($search)  {
     foreach ( $search['paramars'] as $key => $value) {
      ($search['opr'] == 'or')  ? $query->orWhere($key,'like','%'.$value.'%') :  ->where($key,'like','%'..'%') ;
    }
  });

        // type == 0
  }else {
   if( $search['cols'] == NULL ) return '-3';
         // if(trim($search['params']) == NULL ) return '-4';
   $s = 'CONCAT_WS("",`'.implode("`,`",$search['cols']).'`)';
     // Parametor 
   if(!is_array($search['params'])) {
    $params = $search['params'];
    $character_mask = " \t\n\r\0\x0B";
    \  = trim\(, $character_mask);
    $params = \preg_replace('/\s+/', ' ', );
    $arparms = \explode(' ', );
  } else {
    $arparms  = $search['params'];
  }

  ->where(function() use ($search,$s,$arparms) {
   
   foreach ($arparms as $key => $value) {
    ($search['opr'] == 'or')  ? $query->whereRaw(.' like ?',['%'..'%'],'or') :  ->whereRaw($s.' like ?',['%'..'%'],'and') ;
  }
});

  foreach ( as $key => $value) {
    $rs->orderByRaw('locate(?,?) ASC',[$value,$s]); 
  }
  $rs->orderByRaw( '? ASC',[$s]);

         } // end if

         $result = new \stdClass();
         $result->count = $rs->count();
         if($search['result_lenght'] >0 ) ->take(['result_lenght'])->skip();
         $result->pageNo = ['pageNo'];
         ($search['result_lenght'] > 0 ) ?  $result->result_lenght = $search['result_lenght'] :$result->result_lenght = count($rs);
         $result->items = $rs->get();
         //  = DB::getQueryLog();
         // dd();
         $result->querys = $queries = DB::getQueryLog();
         return json_encode();


       }
       public function test() {
        echo "\n=================== Start Test ==========================\n";
        echo "\n";
        var_dump($this->getAllTest());
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n=================== End Test ==========================\n";
      }



    }

    ?>

