<?php
function delAllPhoto($data){
    foreach ($data as $val)
        {
            unlink(WORK_PATH.$val);
        }
}
//tableName是表二的，selectName是表一的，fieldvalue是表二的，fieldname是表一的,selectValue是表一的
//自动建立一对多类型的下拉框
function bulidSelect($tableName,$selectName,$fieldValue,$fieldName,$selectValue)
{
   $res=M($tableName)->select();
   $str='<select name="'.$selectName.'"><option value="">-请选择-</option>';
   foreach ($res as $key =>$val)
   {
       if($selectValue && $selectValue == $val[$fieldValue])
       {
           $str1="selected='selected'";
       }
       else
       {
           $str1="";
       }
       $str.='<option '.$str1.' value="'.$val[$fieldValue].'">'.$val[$fieldName].'</option>';
   }
   $str.='</select>';
   echo $str;
}
function filterXSS($string){
    //相对index.php入口文件，引入HTMLPurifier.auto.php核心文件
    require_once './Public/htmlpurifier/library/HTMLPurifier.auto.php';
    // 生成配置对象
    $cfg = HTMLPurifier_Config::createDefault();
    // 以下就是配置：
    $cfg -> set('Core.Encoding', 'UTF-8');
    // 设置允许使用的HTML标签
    $cfg -> set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,br,p[style],span[style],img[width|height|alt|src]');
    // 设置允许出现的CSS样式属性
    $cfg -> set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
    // 设置a标签上是否允许使用target="_blank"
    $cfg -> set('HTML.TargetBlank', TRUE);
    // 使用配置生成过滤用的对象
    $obj = new HTMLPurifier($cfg);
    // 过滤字符串
    return $obj -> purify($string);
}
function showImg($url,$width=0){
    $str='';
    if($width>0)
    {
        $str="<img src='".$url."' width=".$width."px />";
    }
    else
    {
        $str="<img src='".$url."' />";
    }
    echo $str;
}
