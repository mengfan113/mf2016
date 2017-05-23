<?php

header("content-type:text/html; charset=utf-8");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * 创建session表

  create  table  session(
  sess_id  varchar(40)   not null comment  'session_id',
  sess_content  text  comment  'session内容',
  last_write  int  not null default 0  comment '最后一次的记录时间',
  primary key(sess_id)
  )engine=myisam charset='utf8'  comment='session表';
  // */

/**
 * 从数据库读出对应$sess_id的数据
 * @param type $sess_id   id
 * @return string  返回的是字符串，是序列化的数据
 */
function sessionRead($sess_id) {
    $sql = "SELECT * FROM  session  WHERE sess_id='$sess_id'";

    $result = mysql_query($sql);

    $rec = mysql_fetch_assoc($result);
    if ($rec) {
   
        return $rec['sess_content'];
    } else {
        return '';
    }
}

/**
 * 执行写操作
 * @param type $sess_id  当执行写的操作  传入的ID
 * @param type $sess_content 当执行写的操作  传入的内容，都是序列化的
 * @return type  返回的是bool类型
 */
function sessionWrite($sess_id, $sess_content) {
    $sql = "replace into session values('$sess_id','$sess_content',unix_timestamp())";
  
    return mysql_query($sql);
}

/**
 * 执行的是删除的操作
 * @param type $sess_id  传入的ID
 * @return type  返回的是bool
 */
function sessionDelete($sess_id) {
    $sql = "DELETE  FROM  session  WHERE sess_id='$sess_id' ";
    
    return mysql_query($sql);
}

/**
 * 垃圾回收操作
 * @param type $maxlitetime  PHP的session机制将 最大有效期作为参数，传递过来！
 * @return type
 */
function sessionGC($maxlitetime) {
    $sql = "DELETE  FROM  session   WHERE  last_write < unix_timestamp()-$maxlitetime ";
    return mysql_query($sql);
}

/**
 * 用于初始化的工作
 */
function sessionBegin() {
     echo  '我是开始';
    $link = @mysql_connect('localhost', 'root', '123456')or die('连接失败');
    mysql_query('set names utf8');
    mysql_query('use lgt');
}

/**
 * 
 * @return boolean   这个函数基本没什么用
 */
function sessionEnd() {
      echo  '我是结束';
    return true;
}

/**
 * 告知session机制，在需要读写时，使用用户自定义的读写函数完成
 * Session_set_save_handler(
  开始函数，结束函数，读函数，写函数，删除函数，GC函数
  );
  用来将用户自定义的函数，设置成session存储相关的函数。

 */
session_set_save_handler('sessionBegin', 'sessionEnd', 'sessionRead', 'sessionWrite', 'sessionDelete', 'sessionGC');
/**
 * PHP配置项：session.save_handler
  PHP所使用的存储机制
  将以上配置改为 user： 表示用户自定义！
 */
ini_set('session.save_handler', 'user');
/**
 * 在 session_start()过程中，开启session机制过程中：有几率地执行 垃圾回收操作。一旦执行，就会删除所有的过期的垃圾数据区。
  默认的概率为1/1000。
  可以设置该几率：
 */
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '3');
session_start();
$_SESSION['name'] = 'liuff';
var_dump();
$_SESSION['age'] = 25;
echo '<pre>';
var_dump($_SESSION);
session_destroy();

