<?php
$GLOBALS['THRIFT_ROOT'] = "/home/ganji/www/thrift-0.5.0";


require_once $GLOBALS['THRIFT_ROOT'].'/Thrift.php';

require_once $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php';
/** Include the socket layer */
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TSocketPool.php';
/** Include the socket layer */
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TFramedTransport.php';
/** Include the generated code */
require_once $GLOBALS['THRIFT_ROOT'].'/packages/conf_crawler/ExtractorService.php';

class call{

  protected function connection($config) {

    try{
      $this->socket = new TSocket($config['host'], $config['port']);
      $this->socket->setSendTimeout(1);
      //    $this->socket->setDebug(TRUE);

      $framedsocket = new TFramedTransport($this->socket, 1024, 1024);
      $this->transport = $framedsocket;

      //$protocol = new TBinaryProtocol($this->transport);
      $protocol = new TBinaryProtocolAccelerated($this->transport);
      $protocol_out = new TBinaryProtocol($this->transport);
      $this->extractorClient = new ExtractorServiceClient($protocol, $protocol_out);

      return $this->open();
    }
    catch(Exception $e){
      $msg  = "xapian socket fail ";
      $msg .= " xapian host:".$host." port:".$port;
      $msg .= " web host:".$_SERVER["SERVER_ADDR"]." name:".$_SERVER["SERVER_NAME"];
      $msg .= " Exception:".$e->getMessage();
      echo $msg;
      return false;
    }
  }
  public function open(){
    try{
      if (!$this->isOpen()) {
        $this->transport->open();
      }
      return true;
    }
    catch(Exception $e){
      $msg  = "xapian socket fail time".$this->socket_retry_time;
      $msg .= " xapian host:".$this->socket->getHost()." port:".$this->socket->getPort();
      $msg .= " web host:".$_SERVER["SERVER_ADDR"]." name:".$_SERVER["SERVER_NAME"];
      $msg .= " Exception:".$e->getMessage();
      echo $msg;
      if($this->socket_retry_time <= 2){
        $this->socket_retry_time++;
        usleep(floor(1000/2));
        $this->open();
      }
      return false;
    }
  }
  public function isOpen() {
    return $this->transport->isOpen();
  }

  public function load_template($tplName,$tplType=0)
  {
    $this->extractorClient->load_template($tplName,$tplType); 
    return true;

  }


  public function extract($tplName, $tplType, $depth, $body,$url)
  {
    $extract_item = new ExtractItem();
    $extract_item->url = $url;
    $extract_item->url_template = $tplName;
    $extract_item->depth = $depth;
    $extract_item->template_type = $tplType;
    $extract_item->body = $body;
    $matched_result_item = $this->extractorClient->extract_sync($extract_item);
    if($matched_result_item->is_ok == False)
    {
      echo 'Err:'.$matched_result_item->err_info;
    }
    //$res = array_merge($matched_result_item->self_result,$matched_result_item->sub_result_list);
    
    //print_r($matched_result_item);exit;
    foreach( $matched_result_item->self_result as $key => $value)
    {
        $res[] = $key.":".$value[0];
    }
    foreach($matched_result_item->sub_result_list as $item)
    {
      foreach($item as  $key => $value)
      {
        $res[] = $key.":".$value[0];
      }
    }
    return $res;

  }

  public function call_extract($tplName,$depth,$body,$url,$tplType=0)
  {

    $conf = array(
      'host' => '127.0.0.1',
      'port' => '61003',
    );

    $this->connection($conf);

    $this->load_template($tplName,$tplType);
    $str = $this->extract($tplName, $tplType, $depth, $body,$url);

    return $str;

  }


}

?>
