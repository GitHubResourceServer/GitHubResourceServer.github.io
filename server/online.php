<?php
// если вошел новый пользователь то заносим его в базу
if($_GET['data'] == 'add'){
    $file = file_get_contents('../data/online.json');
    $tasklist = json_decode($file, true);
    unset($file);
    $tasklist[] = array(
        'name' => $_GET['online'],
        'time' => time()
    );
    file_put_contents('../data/online.json', json_encode($tasklist));
    unset($tasklist);
    return false;
}
// если пользователь вышел то удаляем его с базы
if($_GET['data'] == 'del'){
    $file = file_get_contents('../data/online.json');
    $tasklist = json_decode($file, true);
    $user = urlencode($_GET['user']);
    foreach($tasklist as $key => $value){
        if(in_array($user, $value)){
            unset($tasklist[$key]);
        }
    }
    file_put_contents('../data/online.json', json_encode($tasklist));
    unset($tasklist);
    return false;
}
// обновляем время прибывания пользователя на сервере - в чате
if($_GET['data'] == 'update'){
    $oldname = trim(urldecode($_GET['user']));
    $newname = trim(time());
    $file = file_get_contents('../data/online.json');
    $taskList=json_decode($file,TRUE);
    foreach ( $taskList  as $key => $value){
        if (in_array( $oldname, $value)){
            $taskList[$key]  = array('name'=>$_GET['user'],'time'=>$newname);
        }
    }
    file_put_contents('../data/online.json',json_encode($taskList));
    unset($taskList);
}
// принудительная периодическая чистка пользователей которые давно не онлайн нужно ставить задачу на крон.
if($_GET['data'] == 'clear_offline'){
    $file = file_get_contents('../data/online.json');
    $result = json_decode($file, true);
    foreach($result as $k => $v){
        if($v['time'] < time()-15){
            $taskList = json_decode($file,TRUE);
            $user = urldecode($v['name']);
            foreach ( $taskList  as $key => $value){      
                if (in_array($user, $value)) {
                    unset($taskList[$key]);
                }
            }
            file_put_contents('../data/online.json', json_encode($taskList));
            unset($taskList);
            break;
        }
    }
}
// добавляем новое сообщение в базу сообщений data.txt
if($_GET['data'] == 'addmessage'){
    $file = '../data/data.txt';
    // вложенность блоков которые мы будем настраивать стилями дабы было красиво!
    $message = "<div class='message_wrapp'><div class='user_name'>".htmlspecialchars($_GET['user'])."</div><div class='message_text'>".htmlspecialchars($_GET['message'])."</div><div class='message_date'></div></div>";
    file_put_contents($file, $message, FILE_APPEND | LOCK_EX);
}
// очищаем все сообщения если в базе слишком много символов, зачем нам перегруженый сервер:)
if($_GET['data'] == 'clear_messages'){
    $file = '../data/data.txt';
    // стили благодаря которым будет виден наш неповторимый дизайн сообщений:)
    $message = "<link href=\"../server/style.css\" rel=\"stylesheet\" type=\"text/css\"><link href=\"../server/fonts/bloggersans.css\" rel=\"stylesheet\" type=\"text/css\">";
    file_put_contents($file, $message, LOCK_EX);
}
?>