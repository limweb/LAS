<?php 
class Las {

    /*
    $myfile = fopen("example.las", "r") or die("Unable to open file!");
    $lasdata = fread($myfile,filesize("example.las"));
    fclose($myfile);
    $lines = explode(PHP_EOL, $lasdata);
    echo $lasdata;
    $las = new Las();
    $las->readdata($lines);
    $las->readfile('example1.las');
    echo  $las->__toString();
    echo  $las->export('test.las');
    echo "\n<br><br>";
    echo  'string=',$las->__toString();
    echo "\n<br><br>";
    echo  'json=',$las->toJson();
    echo "\n<br><br>";
    print_r($las->toArray());
    */

    private $var = 'VERS.           2.0  :CWLS LOG ASCII STANDARD - 2.0';
    private $warp = 'WRAP.           NO   :One line per depth step';
    private $session = [
                                  '~VERSION INFORMATION',
                                  '~WELL INFORMATION',
                                  '~CURVE INFORMATION',
                                  '~PARAMETER INFORMATION',
                                  '~OTHER INFORMATION',
                                  '~ASCII LOG DATA',
                                ];

    private   $las = [ 'V'=>[],'W'=>[],'C'=>[],'P'=>[],'O'=>[],'A'=>[] ];
    private $nullref = '-999.25000';
    protected $chk = '';

    public function __construct() { }

    public function readfile($file) {
      $myfile = fopen($file, "r") or die("Unable to open file!");
      $lasdata = fread($myfile,filesize($file));
      fclose($myfile);
      $lines = explode(PHP_EOL, $lasdata);
      $this->readdata($lines);
    }

    public  function readdata($lines) {
          $i=0;
          foreach ($lines as $line) {
              $line = $this->parsetCharmask($line);
              $this->chksession($line);
              // if($i >=30) {exit(); }
          }
    }

    protected function chksession($line) {
            $lines = str_split($line);
            if($lines[0] == '~' ) {
                    if($lines[1] == 'V') {
                        $this->chk = 'V';
                    } else if($lines[1] == 'W') {
                        $this->chk = 'W';
                    } else if($lines[1] == 'C') {
                        $this->chk = 'C';
                    } else if($lines[1] == 'P') {
                        $this->chk = 'P';
                    } else if($lines[1] == 'O') {
                        $this->chk = 'O';
                    } else if($lines[1] == 'A') {
                        $this->chk = 'A';
                    }
        } else {
            $this->readd($line);
        }
    }

    protected function  readd($line) {
        if($this->chk == 'V') {
            $this->las['V'][] = $this->parserVWCPO($line);
        } else if($this->chk == 'W') {
            $this->las['W'][] =$this->parserVWCPO($line);
        } else if($this->chk == 'C') {
            $this->las['C'][] =  $this->parserVWCPO($line);
        } else if($this->chk == 'P') {
            $this->las['P'][] =  $this->parserVWCPO($line);
        } else if($this->chk == 'O') {
            $this->las['O'][] = $this->parserVWCPO($line);
        } else if($this->chk == 'A') {
            if($this->parserData($line)){
                $this->las['A'][] =  $this->parserData($line);
            }
        } else  {

        }
    }

    private function  parserVWCPO($line) {
      if($line) {
        $line = $this->parsetCharmask(utf8_encode($line));
        if($line && $line[0] == '#') {
           return   [ 
                            'comment' => $line,
                       ];
        } else {
              list($str,$description) = explode(':', $line);
              list($mnem,$str)  = explode('.',$str,2);
              list($units,$data) = explode(' ',$str,2);
              // $data = trim($data);
              $length = strlen($data);
              if($this->width <= $length) {
                  $this->width = $length;
              }
                  $rs =  [
                              'mnem' => $mnem,
                              'units' =>$units,
                              'data' => $data,
                              'description' => $description,
                  ];
                  return  $rs;
              }
        }
    }

    private function parserData($line){
                    $line = preg_replace('/\s+/',' ',trim($line));
                    if($line) {
                            $arr =  explode(' ',$line);
                            // foreach ($arr as &$ar) {
                            //   if($ar == $this->nullref) {
                            //       $ar = NULL;
                            //   }
                            // }
                            return $arr;
                    }
    }

    private function parsetCharmask($line){
            $character_mask = " \t\n\r\0\x0B";
            $line = trim($line, $character_mask);
            $line = \preg_replace('/\s+/', ' ', $line);
            $line = $this->escapeJsonString($line);
            // echo 'chk=',$line,"\n";
            return $line;
    }

    private function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    // return $result;
      return $value;
   }

    private function jsonRemoveUnicodeSequences($struct) {
       return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
    }

    private $width = 10;
    private function parsetforExport($data=null) {
             if($data) {
                    $f= '';
                   if(isset($data['comment']) && $data['comment'] != '') {
                      $f .= $data['comment']."\n";
                      // $f .= $this->jsonRemoveUnicodeSequences($data['comment'])."\n";
                    } else {
                      // $f  .=  $this->jsonRemoveUnicodeSequences($data['mnem']).'.'.$this->jsonRemoveUnicodeSequences($data['units']).'   '. str_pad($this->jsonRemoveUnicodeSequences($data['data']), $width, " ", STR_PAD_LEFT) .':'.$this->jsonRemoveUnicodeSequences($data['description'])."\n";
                      $f  .=  $data['mnem'].'.'.$data['units'].'   '. str_pad($data['data'], $this->width, " ", STR_PAD_LEFT) .':'.$data['description']."\n";
                   }
                  // echo 'export=',$f;
                  return $f;
              }
    }

    public function  __toString() {  
      // return  $this->toJson();
      // var_dump($this->las);
      return  $this->toJson();
    }

  public function toJson($options = 0)
  {
     // return  json_encode( $this->toArray(),$options) ;
     // return json_encode( $this->toArray(),JSON_FORCE_OBJECT);
    return $this->jsonRemoveUnicodeSequences($this->toArray());
  }

    public  function toArray() {
      return $this->las;
    }

   public function Output($name='',$dest=''){
              // echo  'width=',$this->width;
              // $myfile = fopen($filepath, "w") or die("Unable to open file!");
              // // $lasdata = fread($myfile,filesize("example.las"));
              // fwrite($myfile, $f);
              // fclose($myfile);
              // echo 'sueecssed.';
              switch($dest) {
                     case 'I':
                                  header('content-type:text/html;charset=utf-8');
                                  header('Content-Length: '.strlen( $this->export()));
                                  header('Content-disposition: inline; filename="'.$name.'"');
                                  header('Cache-Control: public, must-revalidate, max-age=0'); 
                                  header('Pragma: public');
                                  header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); 
                                  header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
                                  echo  $this->export();
                                  break;
                     case 'D':
                    //Download file
                                header('Content-Description: File Transfer');
                                if (headers_sent())
                                  $this->Error('Some data has already been output to browser, can\'t send PDF file');
                                header('Content-Transfer-Encoding: binary');
                                header('Cache-Control: public, must-revalidate, max-age=0');
                                header('Pragma: public');
                                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
                                header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
                                header('Content-Type: application/force-download');
                                header('Content-Type: application/octet-stream', false);
                                header('Content-Type: application/download', false);
                                header('Content-Type: application/pdf', false);
                                if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) OR empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
                                  // don't use length if server using compression
                                  header('Content-Length: '.strlen( $this->export()));
                                }
                                header('Content-disposition: attachment; filename="'.$name.'"');
                                echo  $this->export();
                                break;
                     case 'F':
                              //Save to local file
                              $f=fopen($name,'wb');
                              if(!$f) $this->Error('Unable to create output file: '.$name);
                              fwrite($f, $this->export(),strlen( $this->export()));
                              fclose($f);
                              break;
                               case 'S':
                              //Return as a string
                              return $this->export();
                     default:
                            $this->Error('Incorrect output destination: '.$dest);
                  }
   }

   public  function  export() {
        $f = '';
        foreach ($this->session as $s) {
            $f .=  $s."\n";
            if($s == '~VERSION INFORMATION' ) {
                    foreach ($this->las['V'] as $data) {
                      $f .= $this->parsetforExport($data);
                    }
            } else if($s == '~WELL INFORMATION' ) {
                    foreach ($this->las['W'] as $data) {
                      $f .= $this->parsetforExport($data);
                    }
            } else if($s == '~CURVE INFORMATION' ) {
                    foreach ($this->las['C'] as $data) {
                        $f .= $this->parsetforExport($data);
                    }
            } else if($s == '~PARAMETER INFORMATION' ) {
                    foreach ($this->las['P'] as $data) {
                      $f .=  $this->parsetforExport($data);
                    }
            } else if($s == '~OTHER INFORMATION' ) {
                  foreach ($this->las['O'] as $data) {
                      $f .= implode(' ',$data)."\n";
                  }
            } else if($s == '~ASCII LOG DATA' ) {
                  foreach ($this->las['A'] as $data) {
                      $f .= implode(' ',$data)."\n";
                  }
            }
        }
        return   $f;
   }

}
