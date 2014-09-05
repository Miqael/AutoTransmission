<?php

class AdminController extends Controller
{
    public $u_id = null;
    public $req_action = null;

    public function init(){
        ini_set('display_errors',1);
        if(!isset(Yii::app()->session['admin']) && Yii::app()->urlManager->parseUrl(Yii::app()->request) != 'admin/login'
            && Yii::app()->urlManager->parseUrl(Yii::app()->request) != 'admin/error'){
            $this->redirect(Yii::app()->baseUrl.'/admin/login');
        }elseif(isset(Yii::app()->session['admin'])){
            $this->u_id = Yii::app()->session['admin'];
        }
        $url = explode('/',Yii::app()->urlManager->parseUrl(Yii::app()->request));
        if(isset($url[0]) && isset($url[1])){
            $this->req_action = $url[0].'/'.$url[1];
        }
    }

    public function ios_push($deviceToken,$message,$badge = null){
        $path =  realpath(Yii::app()->basePath . '/../pem');
        $passphrase = 'bala';
        // Put your alert message here:
//        $message = 'Test';
        ////////////////////////////////////////////////////////////////////////////////
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $path.'/Real.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default',
            'badge' => intval(1)
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        // Close the connection to the server

//        if (!$result)
//            echo 'Message not delivered  ---' . PHP_EOL;
//        else
//            echo 'Message successfully delivered ---'.$message. PHP_EOL;
//        echo '<br><br>';

        fclose($fp);
    }

    public function android_push($deviceToken,$message,$title){
        // Replace with real BROWSER API key from Google APIs
        $apiKey = "AIzaSyDQ4n6kSFJAGPLgOS6AM5nHTxqqOtVPL8o";

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids'  => $deviceToken,
            'data'              => array( "message" => $message, "title" => $title ),
        );

        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Execute post
        $result = curl_exec($ch);

//        var_dump($result);

        // Close connection
        curl_close($ch);
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
//        $this->
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    public function actionLogin() {
        $this->layout = 'login';
        $model=new LoginForm;
        if(isset($_POST['LoginForm'])){
            $data = $_POST['LoginForm'];
            $user = Yii::app()->db->createCommand()
                ->select('id')
                ->from('admins')
                ->where('username=:username
                    AND password=:password',
                    array(
                        ':username'        => $data['username'],
                        ':password'     => md5($data['password'])
                    )
                )
                ->queryRow();
            if($user){
                Yii::app()->session['admin'] = $user['id'];
                $this->redirect(Yii::app()->baseUrl.'/admin/index');
            }
        }
        $this->render('login',array('model'=>$model));
    }

    public function actionCoupons(){
        $view_data = array();
        $sql = "SELECT COUNT(*) AS c FROM reminders";
        $command = Yii::app()->db->createCommand($sql);
        $c = $command->queryRow();
        $criteria = new CDbCriteria();
        $count = $c['c'];
        $pages = new CPagination($count);

//        Yii::app()->request->requestUri;
        $perPage = isset($_GET['show']) ? intval($_GET['show']) : 10;
        $page = (isset($_GET['page']) && $_GET['page'] != "") ? intval($_GET['page']) : 1;
        $offset = ($page-1)*$perPage;

        $pages->pageSize=$perPage;
        $pages->applyLimit($criteria);


        $sql = "SELECT reminders.*,users.name , users.last_name FROM reminders
                LEFT JOIN users ON users.id = reminders.user_id
                ORDER BY id DESC LIMIT $perPage OFFSET $offset";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();

        $view_data = array(
            'coupons'        => $result,
            'count'         => $count,
            'pages'         => $pages
        );
        $this->render('coupons',$view_data);
    }

    public function actionAdd_project(){
        $sql = "INSERT INTO projects SET
                        image = :image
                        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":image", $_GET['pic'], PDO::PARAM_STR);
        $command->execute();
        echo json_encode(Yii::app()->db->lastInsertID);
        die;
    }

    public function actionReferrer(){
        $view_data = array();
        $sql = "SELECT COUNT(*) AS c FROM referrers";
        $command = Yii::app()->db->createCommand($sql);
        $c = $command->queryRow();
        $criteria = new CDbCriteria();
        $count = $c['c'];
        $pages = new CPagination($count);

//        Yii::app()->request->requestUri;
        $perPage = isset($_GET['show']) ? intval($_GET['show']) : 10;
        $page = (isset($_GET['page']) && $_GET['page'] != "") ? intval($_GET['page']) : 1;
        $offset = ($page-1)*$perPage;

        $pages->pageSize=$perPage;
        $pages->applyLimit($criteria);


        $sql = "SELECT referrers.* FROM referrers
                ORDER BY id DESC LIMIT $perPage OFFSET $offset";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();

        $view_data = array(
            'result'        => $result,
            'count'         => $count,
            'pages'         => $pages
        );
        $this->render('referrer',$view_data);
    }

    public function actionAdd_referrer(){
        $view_data = array();
        $ref_id = null;
        if(isset($_GET['ref_id'])){
            $ref_id = $_GET['ref_id'];
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $sql = "INSERT INTO ";
            $where = '';
            if($ref_id){
                $sql = "UPDATE ";
                $where = " WHERE id = :ref_id ";
            }
            $sql .= " referrers SET
                        name = :name,
                        phone = :phone,
                        email = :email
                        ".$where;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
            $command->bindParam(":phone",$_POST['phone'], PDO::PARAM_STR);
            $command->bindParam(":email",$_POST['email'], PDO::PARAM_STR);
            if($ref_id){
                $command->bindParam(":ref_id",$ref_id, PDO::PARAM_STR);
            }
            $command->execute();
            $this->redirect(Yii::app()->baseURl.'/admin/referrer');
        }
        if($ref_id){
            $view_data['data'] = Yii::app()->db->createCommand()
                ->select('*')
                ->from('referrers')
                ->where('id=:ref_id',
                    array(
                        ':ref_id'=> $ref_id
                    )
                )
                ->queryRow();
        }
        $this->render('add_referrer',$view_data);
    }

    public function actionAdd_coupon(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $image = null;
            if(isset($_POST['real_image'])){
                $image = $_POST['real_image'];
            }
            $sql = "INSERT INTO reminders SET
                        image = :image,
                        text = :text,
                        user_id = :user_id
                        ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":text",   $_POST['text'], PDO::PARAM_STR);
            $command->bindParam(":user_id",$_POST['user_id'], PDO::PARAM_STR);
            $command->bindParam(":image",  $image, PDO::PARAM_STR);
            $command->execute();
            //selecting type for push ( one user or all users)
            // message for push
            $where = '';
            if($_POST['user_id'] != 0){
                $where = ' AND user_id = '.$_POST['user_id'];
            }
            $message['reminder_id'] = Yii::app()->db->lastInsertID;
            $message['msg'] = 'New Reminder !';
            $message['type'] = 'reminder';
            // android push
            $sql = "SELECT device_id FROM user_devices WHERE type = 0 ".$where;
            $command = Yii::app()->db->createCommand($sql);
            $devices = $command->queryAll();
            if($devices){
                $send = array();
                foreach($devices as $dev){
                    $send[] = $dev['device_id'];
                }
                $this->android_push($send,json_encode($message),'Hot Deal');
            }
            // ios push
            $sql = "SELECT device_id FROM user_devices WHERE type = 1 ".$where;
            $command = Yii::app()->db->createCommand($sql);
            $devices = $command->queryAll();
            if($devices){
                foreach($devices as $dev){
                    $this->ios_push($dev['device_id'],json_encode($message));
                }
            }
        }
        $this->render('add_coupon');
    }

    public function actionResend_coupon(){
        $where = '';
        $data =  Yii::app()->db->createCommand()
            ->select('*')
            ->from('reminders')
            ->where('id=:pr_id',
                array(
                    ':pr_id'        => $_GET['reminder_id']
                )
            )
            ->queryRow();
        if($data['user_id'] != 0){
            $where = ' AND user_id = '.$data['user_id'];
        }
        $message['reminder_id'] = $_GET['reminder_id'];
        $message['msg'] = 'New Reminder !';
        $message['type'] = 'reminder';
        // android push
        $sql = "SELECT device_id FROM user_devices WHERE type = 0 ".$where;
        $command = Yii::app()->db->createCommand($sql);
        $devices = $command->queryAll();
        if($devices){
            $send = array();
            foreach($devices as $dev){
                $send[] = $dev['device_id'];
            }
            $this->android_push($send,json_encode($message),'Hot Deal');
        }
        // ios push
        $sql = "SELECT device_id FROM user_devices WHERE type = 1 ".$where;
        $command = Yii::app()->db->createCommand($sql);
        $devices = $command->queryAll();
        if($devices){
            foreach($devices as $dev){
                $this->ios_push($dev['device_id'],json_encode($message));
            }
        }
        $this->redirect(Yii::app()->baseUrl.'/admin/coupons');
    }

    public function actionUsers(){
        $view_data = array();
        $sql = "SELECT COUNT(*) AS c FROM users";
        $command = Yii::app()->db->createCommand($sql);
        $c = $command->queryRow();
        $criteria = new CDbCriteria();
        $count = $c['c'];
        $pages = new CPagination($count);

//        Yii::app()->request->requestUri;
        $perPage = isset($_GET['show']) ? intval($_GET['show']) : 10;
        $page = (isset($_GET['page']) && $_GET['page'] != "") ? intval($_GET['page']) : 1;
        $offset = ($page-1)*$perPage;

        $pages->pageSize=$perPage;
        $pages->applyLimit($criteria);


        $sql = "SELECT users.* FROM users
                ORDER BY id DESC LIMIT $perPage OFFSET $offset";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();

        $view_data = array(
            'result'        => $result,
            'count'         => $count,
            'pages'         => $pages
        );
        $this->render('users',$view_data);
    }

    public function actionCars(){
        $view_data = array();
        $sql = "SELECT COUNT(*) AS c FROM cars";
        $command = Yii::app()->db->createCommand($sql);
        $c = $command->queryRow();
        $criteria = new CDbCriteria();
        $count = $c['c'];
        $pages = new CPagination($count);

//        Yii::app()->request->requestUri;
        $perPage = isset($_GET['show']) ? intval($_GET['show']) : 10;
        $page = (isset($_GET['page']) && $_GET['page'] != "") ? intval($_GET['page']) : 1;
        $offset = ($page-1)*$perPage;

        $pages->pageSize=$perPage;
        $pages->applyLimit($criteria);


        $sql = "SELECT
                    cars.* ,
                    CONCAT(referrers.name,' / ',referrers.phone,' / ',referrers.email) as ref ,
                    CONCAT(users.name,' / ',users.last_name,' / ',users.email) as user
                FROM cars
                LEFT JOIN referrers ON referrers.id = cars.referred_by
                LEFT JOIN users ON users.id = cars.user_id
                ORDER BY cars.id DESC LIMIT $perPage OFFSET $offset";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();

        $view_data = array(
            'result'        => $result,
            'count'         => $count,
            'pages'         => $pages
        );
        $this->render('cars',$view_data);
    }

    public function actionFender_bender(){
        $view_data = array();
        $sql = "SELECT COUNT(*) AS c FROM quotes";
        $command = Yii::app()->db->createCommand($sql);
        $c = $command->queryRow();
        $criteria = new CDbCriteria();
        $count = $c['c'];
        $pages = new CPagination($count);

//        Yii::app()->request->requestUri;
        $perPage = isset($_GET['show']) ? intval($_GET['show']) : 10;
        $page = (isset($_GET['page']) && $_GET['page'] != "") ? intval($_GET['page']) : 1;
        $offset = ($page-1)*$perPage;

        $pages->pageSize=$perPage;
        $pages->applyLimit($criteria);


        $sql = "SELECT
                    quotes.* ,
                    CONCAT(users.name,' / ',users.last_name,' / ',users.email) as user
                FROM quotes
                LEFT JOIN users ON users.id = quotes.user_id
                ORDER BY quotes.id DESC LIMIT $perPage OFFSET $offset";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();

        $view_data = array(
            'result'        => $result,
            'count'         => $count,
            'pages'         => $pages
        );
        $this->render('fender_bender',$view_data);
    }

    public function actionCurrent_projects(){
        $view_data = array();
        $sql = "SELECT COUNT(*) AS c FROM projects";
        $command = Yii::app()->db->createCommand($sql);
        $c = $command->queryRow();
        $criteria = new CDbCriteria();
        $count = $c['c'];
        $pages = new CPagination($count);

//        Yii::app()->request->requestUri;
        $perPage = isset($_GET['show']) ? intval($_GET['show']) : 18;
        $page = (isset($_GET['page']) && $_GET['page'] != "") ? intval($_GET['page']) : 1;
        $offset = ($page-1)*$perPage;

        $pages->pageSize=$perPage;
        $pages->applyLimit($criteria);


        $sql = "SELECT projects.* FROM projects
                ORDER BY id DESC LIMIT $perPage OFFSET $offset";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();

        $view_data = array(
            'result'        => $result,
            'count'         => $count,
            'pages'         => $pages
        );
        $this->render('current_projects',$view_data);
    }

    public function actionLogout(){
        unset(Yii::app()->session['admin']);
        $this->redirect(Yii::app()->baseUrl);
    }

    public function actionUploader(){
        $path =  realpath(Yii::app()->basePath . '/../images/reminders');
        $FileUploader = new FileUploader();
        if (isset($_GET['qqfile'])) {
            $imgName = $_GET['qqfile'];
        } elseif (isset($_FILES['qqfile'])) {
            $imgName = $_FILES['qqfile']['name'];
        }
//            var_dump($_REQUEST);die;
        $explode = explode('.', $imgName);
        $ext = end($explode);
        $name = md5(microtime()) . '.' . $ext;
        if($ext){
            // if (!is_dir(WWW_ROOT . 'system' . DS . 'bulletinPic' . DS.$this->u_id)){
            // mkdir(WWW_ROOT . 'system' . DS . 'bulletinPic' . DS.$this->u_id,true);}
            $test = $FileUploader->upload($path.'/');
//                var_dump($test);die;
            $response['fileName'] = $name;
//                var_dump($response);die;
            $response['success'] = true;
            @rename($path. DIRECTORY_SEPARATOR .$imgName , $path.DIRECTORY_SEPARATOR.$name);
            $img = array();
            $img[$name] = $name;
            list($width, $height) = getimagesize( $path.DIRECTORY_SEPARATOR.$name);
            if($width > $height){
                $resolution = $height/$width;
                $width = 300;
                $height = round($width*$resolution);
            }else{
                $resolution = $width/$height;
                $height = 300;
                $width = round($height*$resolution);
            }
            $response['name'] = $imgName;
            $response['width'] = $width;
            $response['height'] = $height;
            $echo = json_encode($response);
            echo $echo;
            die;
        }else{
            $response['success'] = false;
            $echo = json_encode($response);
            echo $echo;
            die;
        }
    }

    public function actionUpload_projects(){
        $path =  realpath(Yii::app()->basePath . '/../images/projects');
        $FileUploader = new FileUploader();
        if (isset($_GET['qqfile'])) {
            $imgName = $_GET['qqfile'];
        } elseif (isset($_FILES['qqfile'])) {
            $imgName = $_FILES['qqfile']['name'];
        }
//            var_dump($_REQUEST);die;
        $explode = explode('.', $imgName);
        $ext = end($explode);
        $name = md5(microtime()) . '.' . $ext;
        if($ext){
            // if (!is_dir(WWW_ROOT . 'system' . DS . 'bulletinPic' . DS.$this->u_id)){
            // mkdir(WWW_ROOT . 'system' . DS . 'bulletinPic' . DS.$this->u_id,true);}
            $test = $FileUploader->upload($path.'/');
//                var_dump($test);die;
            $response['fileName'] = $name;
//                var_dump($response);die;
            $response['success'] = true;
            @rename($path. DIRECTORY_SEPARATOR .$imgName , $path.DIRECTORY_SEPARATOR.$name);
            $img = array();
            $img[$name] = $name;
            list($width, $height) = getimagesize( $path.DIRECTORY_SEPARATOR.$name);
            if($width > $height){
                $resolution = $height/$width;
                $width = 300;
                $height = round($width*$resolution);
            }else{
                $resolution = $width/$height;
                $height = 300;
                $width = round($height*$resolution);
            }
            $response['name'] = $imgName;
            $response['width'] = $width;
            $response['height'] = $height;
            $echo = json_encode($response);
            echo $echo;
            die;
        }else{
            $response['success'] = false;
            $echo = json_encode($response);
            echo $echo;
            die;
        }
    }

    public function actionRemove_photo(){
        $path =  realpath(Yii::app()->basePath . '/../images/reminders');
        $image = $_GET['pic'];
        unlink($path.DIRECTORY_SEPARATOR.$image);
        die;
    }

    public function actionRemove_projects(){
        $path =  realpath(Yii::app()->basePath . '/../images/projects');
        $image = $_GET['pic'];
        unlink($path.DIRECTORY_SEPARATOR.$image);
        die;
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError(){
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionDelete_From(){
        $id = $_GET['id'];
        $table = $_GET['table'];
        if($table == 'projects'){
            $data =  Yii::app()->db->createCommand()
                ->select('*')
                ->from('projects')
                ->where('id=:pr_id',
                    array(
                        ':pr_id'        => $id
                    )
                )
                ->queryRow();
            $path =  realpath(Yii::app()->basePath . '/../images/projects');
            unlink($path.DIRECTORY_SEPARATOR.$data['image']);
        }
        $sql = "DELETE FROM `$table` WHERE id = :id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":id", $id, PDO::PARAM_STR);
        $command->execute();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }


    public function actionChangePassword(){
        $view_data = array();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($_POST['pass'] == $_POST['c_pass']){
                $sql = "UPDATE admins SET password = :pass WHERE id = :id";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":id",   $this->u_id, PDO::PARAM_STR);
                $command->bindParam(":pass", md5($_POST['pass']), PDO::PARAM_STR);
                $command->execute();
                Yii::app()->user->setFlash('success','Password successfully changed');
            }else{
                Yii::app()->user->setFlash('danger','Passwords mismatches');
            }
        }
        $this->render('change_password',$view_data);
    }

    public function actionGetUsers(){
        $users =  Yii::app()->db->createCommand()
            ->select('users.id,users.name,users.last_name,users.email')
            ->from('users')
            ->queryAll();
        echo json_encode($users);die;
    }
}