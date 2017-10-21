<?php
/**
 *
 * User: codeMonkey QQ:631872807
 * Date: 2015/1/18
 * Time: 0:01
 */

  class CRUD{


      public static   $table_fool ="mon_fool";





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