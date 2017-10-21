<?php
/**
 *
 * User: codeMonkey QQ:631872807
 * Date: 2015/1/18
 * Time: 0:01
 */

  class CRUD{


      public static   $table_zjp ="mon_zjp";

    public static   $table_zjp_prize ="mon_zjp_prize";

    public static   $table_zjp_user ="mon_zjp_user";

      public static   $table_zjp_record ="mon_zjp_record";


      public static $DIALOG_MSG='好慷宅洁士居家保洁都是4小时标准服务时间。
宅洁士居家保洁4小时-139元即可"焕"个新家。
好慷专用的日式保洁工具组，应用多项专利技术，实现保洁服务0死角#幸运抽到保洁券的您，要抓紧使用哦!
七色保洁布从概念到落地，首创是我们好慷哦#针对服务的安全性，好慷的家政服务全程都购买了保险哦。';

      public static $SUCC_MSG='我靠，哈哈，哥中奖了!
duang，duang，中奖了。
尼玛，走狗屎运，中奖了。
人帅，就是中奖!';

      public static $FAIL_MSG="从来没有中过。。。呜呜。
奖品都去哪了啊。。。。。
啥时候能走个狗屎运啊。";


      public static   function findById($table,$id){
          return pdo_fetch("select * from ".tablename($table)." where id=:id",array(':id'=>$id)) ;
      }


      public static function findUnique($table,$params=array()){


          if(!empty($params)){
              $where=" where ";
              $index=0;
              foreach($params as $key =>$value){

                  $where.=substr($key,1)."=".$key." ";

                  if($index<count($params)-1){
                      $where.=" and ";
                  }
                  $index++;

              }
          }

          return pdo_fetch("select * from ".tablename($table).$where,$params);

      }
      public static function  findList($table,$params=array()){



          if(!empty($params)){
              $where=" where ";
              $index=0;
              foreach($params as $key =>$value){

                  $where.=substr($key,1)."=".$key." ";

                  if($index<count($params)-1){
                      $where.=" and ";
                  }
                  $index++;

              }
          }

          return pdo_fetchall("select * from ".tablename($table).$where,$params);
      }
      
      
       public static function  findListEx($table,$fileds,$params=array()){

			

          if(!empty($params)){
              $where=" where ";
              $index=0;
              foreach($params as $key =>$value){

                  $where.=substr($key,1)."=".$key." ";

                  if($index<count($params)-1){
                      $where.=" and ";
                  }
                  $index++;

              }
          }

          return pdo_fetchall("select ".$fileds." from ".tablename($table).$where,$params);
      }
      

      public  static  function  create($table,$data=array()){
         return  pdo_insert($table,$data);

      }

      public  static  function  update($table,$data = array(), $params = array()){
          return pdo_update($table,$data,$params);

      }

      public  static  function  updateById($table,$data = array(),$id){
          return pdo_update($table,$data,array('id'=>$id));

      }



      public  static  function  deleteByid($table,$id){
          return   pdo_delete($table,array('id'=>$id));
      }

      public  static  function  delete($table,$params = array()){
          return   pdo_delete($table,$params);
      }




  }