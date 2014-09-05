<?php
class TimestampBehavior extends CActiveRecordBehavior {
    public function beforeSave($event) {
        if ($this -> owner -> isNewRecord) {
            $this -> owner -> created = time();
            $this -> owner -> modified = time();
        } else {
            $this -> owner -> modified = time();
        }
    }
}
