<?php

class RestController extends Controller{

    public function init(){
        ini_set('display_errors', 1);
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            header('HTTP/1.1 404 Not Found');
            echo "<h1>404 Not Found</h1>";
            exit;
        }
        header('Content-type: application/json');
    }

    function missingParam($param){
        $response['status'] = 'nok';
        $response['errors'][] = array('code'=>'MISSING_PARAM_OR_EMPTY','param_name'=>$param);
        echo json_encode($response);die;
    }

    function badFormat($param){
        $response['status'] = 'nok';
        $response['errors'][] = array('code'=>'BAD_FORMAT','param_name'=>$param);
        echo json_encode($response);die;
    }

    public function actionRegister(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        if(!isset($_POST['name'])){
            $this->missingParam('name');
        }elseif(strlen(trim(($_POST['name']))) == 0){
            $this->missingParam('name');
        }

        if(!isset($_POST['last_name'])){
            $this->missingParam('last_name');
        }elseif(strlen(trim(($_POST['last_name']))) == 0){
            $this->missingParam('last_name');
        }

        if(!isset($_POST['email'])){
            $this->missingParam('email');
        }elseif(strlen(trim(($_POST['email']))) == 0){
            $this->missingParam('email');
        }elseif(!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)){
            $this->badFormat('email');
        }

        if(!isset($_POST['phone_number'])){
            $this->missingParam('phone_number');
        }elseif(strlen(trim(($_POST['phone_number']))) == 0){
            $this->missingParam('phone_number');
        }
        $response['data']['token'] = md5(microtime());
        $sql = "INSERT INTO users SET
                        name = :name ,
                        last_name = :last_name ,
                        email = :email ,
                        phone = :phone ,
                        token = :token
                        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":name",      $_POST['name'], PDO::PARAM_STR);
        $command->bindParam(":last_name", $_POST['last_name'], PDO::PARAM_STR);
        $command->bindParam(":phone",     $_POST['phone_number'], PDO::PARAM_STR);
        $command->bindParam(":email",     $_POST['email'], PDO::PARAM_STR);
        $command->bindParam(":token",     $response['data']['token'], PDO::PARAM_STR);
        $command->execute();

        echo json_encode($response);die;
    }

    public function actionRegisterDevice(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        if(!isset($_POST['token'])){
            $this->missingParam('token');
        }elseif(strlen(trim(($_POST['token']))) == 0){
            $this->missingParam('token');
        }
        $user_id = $this->checkToken($_POST['token']);

        /*
         * type = 1 ios
         * type = 0 android
         */
        if(!isset($_POST['type'])){
            $this->missingParam('type');
        }elseif(strlen(trim(($_POST['type']))) == 0){
            $this->missingParam('type');
        }

        if(!isset($_POST['device_id'])){
            $this->missingParam('device_id');
        }elseif(strlen(trim(($_POST['device_id']))) == 0){
            $this->missingParam('device_id');
        }

        $sql = "DELETE FROM user_devices WHERE `type` = :type AND `device_id` = :device_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":type",     $_POST['type'], PDO::PARAM_STR);
        $command->bindParam(":device_id",$_POST['device_id'], PDO::PARAM_STR);
        $command->execute();

        $sql = "INSERT INTO user_devices SET
                        `type` = :type ,
                        `user_id` = :user_id ,
                        `device_id` = :device_id
                        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":type",     $_POST['type'], PDO::PARAM_STR);
        $command->bindParam(":user_id",  $user_id, PDO::PARAM_STR);
        $command->bindParam(":device_id",$_POST['device_id'], PDO::PARAM_STR);
        $command->execute();

        echo json_encode($response);
    }

    public function actionAddCars(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        if(!isset($_POST['token'])){
            $this->missingParam('token');
        }elseif(strlen(trim(($_POST['token']))) == 0){
            $this->missingParam('token');
        }
        $user_id = $this->checkToken($_POST['token']);
        if(!isset($_POST['year'])){
            $this->missingParam('year');
        }elseif(strlen(trim(($_POST['year']))) == 0){
            $this->missingParam('year');
        }elseif(!is_numeric($_POST['year'])){
            $this->badFormat('email');
        }
        if(!isset($_POST['make'])){
            $this->missingParam('make');
        }elseif(strlen(trim(($_POST['make']))) == 0){
            $this->missingParam('make');
        }
        if(!isset($_POST['model'])){
            $this->missingParam('model');
        }elseif(strlen(trim(($_POST['model']))) == 0){
            $this->missingParam('model');
        }
        if(!isset($_POST['lease_return_date'])){
            $this->missingParam('lease_return_date');
        }elseif(strlen(trim(($_POST['lease_return_date']))) == 0){
            $this->missingParam('lease_return_date');
        }elseif(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['lease_return_date'])){
            $this->badFormat('lease_return_date');
        }
        if(!isset($_POST['referred_by'])){
            $this->missingParam('referred_by');
        }elseif(strlen(trim(($_POST['referred_by']))) == 0){
            $this->missingParam('referred_by');
        }
        //files
        if( (!isset($_FILES['image_1']))||($_FILES['image_1']['error']) ){
            $this->missingParam('image_1');
        }
        if( (!isset($_FILES['image_2']))||($_FILES['image_2']['error']) ){
            $this->missingParam('image_2');
        }
        if( (!isset($_FILES['image_3']))||($_FILES['image_3']['error']) ){
            $this->missingParam('image_3');
        }

        $path =  realpath(Yii::app()->basePath . '/../images/cars');
        $img_name = md5(microtime());

        $image_1 = $img_name.'_1.'. end(explode('.', $_FILES['image_1']['name']));
        $image_2 = $img_name.'_2.'. end(explode('.', $_FILES['image_2']['name']));
        $image_3 = $img_name.'_3.'. end(explode('.', $_FILES['image_3']['name']));

        move_uploaded_file($_FILES['image_1']['tmp_name'], $path.'/'. $image_1);
        move_uploaded_file($_FILES['image_2']['tmp_name'], $path.'/'. $image_2);
        move_uploaded_file($_FILES['image_3']['tmp_name'], $path.'/'. $image_3);

        $sql = "INSERT INTO cars SET
                        `user_id` = :user_id,
                        `year` = :year ,
                        `make` = :make ,
                        `model` = :model,
                        `lease_return_date` = :lease_return_date ,
                        `referred_by` = :referred_by ,
                        `image_1` = :image_1 ,
                        `image_2` = :image_2 ,
                        `image_3` = :image_3
                        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":year",             $_POST['year'], PDO::PARAM_STR);
        $command->bindParam(":user_id",          $user_id, PDO::PARAM_STR);
        $command->bindParam(":make",             $_POST['make'], PDO::PARAM_STR);
        $command->bindParam(":model",            $_POST['model'], PDO::PARAM_STR);
        $command->bindParam(":lease_return_date",$_POST['lease_return_date'], PDO::PARAM_STR);
        $command->bindParam(":referred_by",      $_POST['referred_by'], PDO::PARAM_STR);
        $command->bindParam(":image_1",          $image_1, PDO::PARAM_STR);
        $command->bindParam(":image_2",          $image_2, PDO::PARAM_STR);
        $command->bindParam(":image_3",          $image_3, PDO::PARAM_STR);
        $command->execute();

        // TODO all data mail to referrer
        echo json_encode($response);die;
    }

    public function actionGetCars(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        if(!isset($_POST['token'])){
            $this->missingParam('token');
        }elseif(strlen(trim(($_POST['token']))) == 0){
            $this->missingParam('token');
        }
        $user_id = $this->checkToken($_POST['token']);

        $cars = Yii::app()->db->createCommand()
            ->select('cars.id,year,make,model,lease_return_date,referred_by,user_id,referrers.name as referred_name,
            CONCAT("'.Yii::app()->request->hostInfo .Yii::app()->baseUrl .'/images/cars/",image_1) as image_1,
            CONCAT("'.Yii::app()->request->hostInfo .Yii::app()->baseUrl .'/images/cars/",image_2) as image_2,
            CONCAT("'.Yii::app()->request->hostInfo .Yii::app()->baseUrl .'/images/cars/",image_3) as image_3
            ')
            ->from('cars')
            ->leftJoin('referrers','referrers.id = cars.referred_by')
            ->queryAll();
        $response['data']['cars'] = $cars;
        echo json_encode($response);die;
    }

    public function actionGetReferred(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        if(!isset($_POST['token'])){
            $this->missingParam('token');
        }elseif(strlen(trim(($_POST['token']))) == 0){
            $this->missingParam('token');
        }
        $user_id = $this->checkToken($_POST['token']);

        $cars = Yii::app()->db->createCommand()
            ->select('*')
            ->from('referrers')
            ->queryAll();
        $response['data']['referrers'] = $cars;
        echo json_encode($response);die;
    }

    public function actionGetQuotes(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        if(!isset($_POST['token'])){
            $this->missingParam('token');
        }elseif(strlen(trim(($_POST['token']))) == 0){
            $this->missingParam('token');
        }
        $user_id = $this->checkToken($_POST['token']);
        if(!isset($_POST['description'])){
            $this->missingParam('description');
        }elseif(strlen(trim(($_POST['description']))) == 0){
            $this->missingParam('description');
        }
        //files
        if( (!isset($_FILES['image_1']))||($_FILES['image_1']['error']) ){
            $this->missingParam('image_1');
        }
        if( (!isset($_FILES['image_2']))||($_FILES['image_2']['error']) ){
            $this->missingParam('image_2');
        }
        if( (!isset($_FILES['image_3']))||($_FILES['image_3']['error']) ){
            $this->missingParam('image_3');
        }

        $path =  realpath(Yii::app()->basePath . '/../images/quotes');
        $img_name = md5(microtime());

        $image_1 = $img_name.'_1.'. end(explode('.', $_FILES['image_1']['name']));
        $image_2 = $img_name.'_2.'. end(explode('.', $_FILES['image_2']['name']));
        $image_3 = $img_name.'_3.'. end(explode('.', $_FILES['image_3']['name']));

        move_uploaded_file($_FILES['image_1']['tmp_name'], $path.'/'. $image_1);
        move_uploaded_file($_FILES['image_2']['tmp_name'], $path.'/'. $image_2);
        move_uploaded_file($_FILES['image_3']['tmp_name'], $path.'/'. $image_3);

        $sql = "INSERT INTO quotes SET
                        `user_id` = :user_id,
                        `description` = :description ,
                        `image_1` = :image_1 ,
                        `image_2` = :image_2 ,
                        `image_3` = :image_3
                        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":description",$_POST['description'], PDO::PARAM_STR);
        $command->bindParam(":user_id",    $user_id, PDO::PARAM_STR);
        $command->bindParam(":image_1",    $image_1, PDO::PARAM_STR);
        $command->bindParam(":image_2",    $image_2, PDO::PARAM_STR);
        $command->bindParam(":image_3",    $image_3, PDO::PARAM_STR);
        $command->execute();

        echo json_encode($response);die;
    }

    public function actionGetReminders(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        if(!isset($_POST['token'])){
            $this->missingParam('token');
        }elseif(strlen(trim(($_POST['token']))) == 0){
            $this->missingParam('token');
        }
        $user_id = $this->checkToken($_POST['token']);
        $where = '1=1';
        if(isset($_POST['id'])){
            $where = ' id = '.$_POST['id'];
        }
        $reminders = Yii::app()->db->createCommand()
            ->select('id,text,
            CONCAT("'.Yii::app()->request->hostInfo .Yii::app()->baseUrl .'/images/reminders/",image) as image
            ')
            ->from('reminders')
            ->where($where)
            ->queryAll();
        $response['data']['reminders'] = $reminders;
        echo json_encode($response);die;
    }

    public function actionGetProjects(){
        $response['status'] = 'ok';
        $response['errors'] = array();

        $reminders = Yii::app()->db->createCommand()
            ->select('CONCAT("'.Yii::app()->request->hostInfo .Yii::app()->baseUrl .'/images/projects/",image) as image')
            ->from('projects')
            ->queryAll();
        $response['data']['projects'] = $reminders;
        echo json_encode($response);die;
    }

    function checkToken($token){
        $usr = Yii::app()->db->createCommand()
            ->select('id')
            ->from('users')
            ->where('token = :token',array(':token'=>$token))
            ->queryRow();
        if($usr){
            return $usr['id'];
        }else{
            $response['status'] = 'nok';
            $response['errors'][] = array('code'=>'BAD_TOKEN');
            echo json_encode($response);die;
        }
    }

}