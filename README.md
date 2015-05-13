# LAS 

    // Read from data
    $myfile = fopen("example.las", "r") or die("Unable to open file!");
    $lasdata = fread($myfile,filesize("example.las"));
    fclose($myfile);
    $las = new Las();
    $las->readdata($lasdata);

    // read file
    $las->readfile('example1.las');
    
    
    //output 
    echo  $las->__toString();
    echo  $las->export();
    echo  $las->__toString();
    echo  $las->toJson();
    $las->toArray();
    $las->output($filename,$para);
    $para =   I standard input output   D download   F savelocal file   S String
    
