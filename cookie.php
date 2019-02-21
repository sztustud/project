<?php
 $pass = md5('123'); //пароль для доступа

 function get_param ($name,$number=0) { 
  //имя параметра $name и пометка $number, числовой ли он
  if (isset($_REQUEST[$name])) { 
   //Массив $_REQUEST = $_POST+$_GET+$_COOKIE !
   $name = trim(htmlspecialchars($_REQUEST[$name])); //делать для всех типов параметров
   //дальше числа и строки обрабатываем по-разному
   return ($number ? doubleval($name) : @mysql_real_escape_string($name));
  }
  else return ($number ? 0 : ""); 
 }

 if (!empty($_COOKIE['auth'])) $trypass = $_COOKIE['auth']; //есть сохранённый куки? прочитать
 else $trypass = md5(get_param('trypass')); //иначе попытаться получить пароль от юзера

 $go = get_param('go'); //некий параметр, обозначающий "действие" скрипта
 $access = false; //флажок "доступ к авторизованной части"
 if ($trypass==$pass) { //получен верный пароль
  $access = true; 
  setcookie('auth',$pass,time()+3600); //ставим кукиз на час
       //в файле cookie хранится только зашифрованный пароль
 }
 if ($go=='logout') { //выбрано действие "выход"
  setcookie('auth','',time()-3600); //сбрасываем время действия кукиза
  $access = false; 
 }  
 if ($access) { //находимся в авторизованном режиме
  setcookie('auth',$pass,time()+3600); //продляем кукиз на час от текущего момента
  //здесь выбор других действий в зависимости от значения $go
  echo '<p>Контент для авторизованного пользователя;
   <a href="cookie.php">обновить</a>;
   <a href="cookie.php?go=logout">выход</a></p>';
 }
 else {
  echo '<p>Вы не авторизованы; 
        <a href="cookie.php">обновить</a>;
        <a href="cookie.php?trypass=123">автовход</a></p>';
            //на самом деле пароль будет получен из формы
 }
?>