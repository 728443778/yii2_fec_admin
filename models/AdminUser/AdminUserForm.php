<?php
namespace fecadmin\models\AdminUser;
use fecadmin\models\AdminUser;
class AdminUserForm extends AdminUser {
	private $_admin_user;
	public function rules()
    {
       
		
		$parent_rules  = parent::rules();
		$current_rules = [
			['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'validateUsername'],
            ['username', 'string', 'min' => 2, 'max' => 20],
			
			['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
			['email', 'validateEmail'],
			
			['role', 'required'],
			['person', 'required'],
			
        //    ['email', 'unique', 'targetClass' => '\fecadmin\models\AdminUser', 'message' => 'This email address has already been taken.'],

		//	['password', 'required'],
         //   ['password', 'string', 'min' => 6],
			['password', 'validatePasswordFormat'],
		
		];
		
		
		return array_merge($parent_rules,$current_rules) ;
		
		
    }
	
	
	
	
	public function validateUsername($attribute, $params){
		//$user = User::findByUsername($this->username)
		if($this->id){
			$one = AdminUser::find()->where(" id != ".$this->id." AND username = '".$this->username."' ")
						->one();
			if($one['id']){
				$this->addError($attribute,"this username is exist!");
			}
		}else{
			$one = AdminUser::find()->where(" username = '".$this->username."' ")
						->one();
			if($one['id']){
				$this->addError($attribute,"this username is exist!");
			}
		}
	}
	
	
	public function validateEmail($attribute, $params){
		//$user = User::findByUsername($this->username)
		if($this->id){
			$one = AdminUser::find()->where(" id != ".$this->id." AND email = '".$this->email."' ")
						->one();
			if($one['id']){
				$this->addError($attribute,"this email is exist!");
			}
		}else{
			$one = AdminUser::find()->where(" email = '".$this->email."' ")
						->one();
			if($one['id']){
				$this->addError($attribute,"this email is exist!");
			}
		}
	}
	
	public function validatePasswordFormat($attribute, $params){
		if($this->id){
			if($this->password && strlen($this->password) <= 6){
				$this->addError($attribute,"password must >=6");
			}
		}else{
			if($this->password && strlen($this->password) >= 6){
				
			}else{
				$this->addError($attribute,"password must >=6");
			}	
		}
	}
	
	
	public function setPassword($password)
    {
		if($this->password){
			$this->password_hash = \Yii::$app->security->generatePasswordHash($password);
			$this->password = '';
		}
    }
	
	# 重写保存方法
	public function save($runValidation = true, $attributeNames = NULL){
		
		if($this->id){
			$this->updated_at_datetime = date("Y-m-d H:i:s");
		}else{
			$this->created_at_datetime = date("Y-m-d H:i:s");
			$this->updated_at_datetime = date("Y-m-d H:i:s");
		}
		# 如果auth_key为空，则重置
		if(!$this->auth_key){
			$this->generateAuthKey();
		}
		# 如果access_token为空，则重置
		if(!$this->access_token){
			$this->generateAccessToken();
		}
		# 设置password
		$this->setPassword($this->password);
		parent::save($runValidation,$attributeNames);
	}
}




