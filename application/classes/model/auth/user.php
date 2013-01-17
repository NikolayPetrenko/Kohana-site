<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Default auth user
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Model_Auth_User extends ORM {

	/**
	 * A user has many tokens and roles
	 *
	 * @var array Relationhips
	 */
	protected $_has_many = array(
		'user_tokens' 	=> array('model' => 'user_token'),
		'roles'       	=> array('model' => 'role', 'through' => 'roles_users'),
		'pets'	  	=> array('model' => 'pet'),
                'friends'       => array(
                                          'model'       => 'user',
                                          'through'     => 'friendships',
                                          'foreign_key' => 'friend_id', 
                                          'far_key'     => 'user_id'
                                        ),
                'feeds'         => array('model' => 'feed'),
                'messages'      => array(
                                         'model'       => 'user_message',
                                         'foreign_key' => 'addressee_id', 
                                         'far_key'     => 'user_id'
                                        )
            
//                'visits'        => array(
//                                          'model'        => 'location', 
//                                          'through'      => 'location_checkins',
//                                          'foreign_key'  => 'user_id',
//                                          'far_key'      => 'location_id'
//                                         ),
  	);
        


          
         /**
	 * Rules for the user model. Because the password is _always_ a hash
	 * when it's set,you need to run an additional not_empty rule in your controller
	 * to make sure you didn't hash an empty string. The password rules
	 * should be enforced outside the model or with a model helper method.
	 *
	 * @return array Rules
	 */
	public function rules()
	{
		return array(
			'firstname' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
				// array(array($this, 'unique'), array('username', ':value')),
			),
			'lastname' => array(
				array('max_length', array(':value', 100)),
				// array(array($this, 'unique'), array('username', ':value')),
			),
			'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
			'termofuse' => array(
				array('not_empty')
			),
			'dob' => array(
				array('date')
			)
		);
	}

	/**
	 * Filters to run when data is set in this model. The password filter
	 * automatically hashes the password when it's set in the model.
	 *
	 * @return array Filters
	 */
	public function filters()
	{
		return array(
			'password' => array(
				array(array(Auth::instance(), 'hash'))
			)
		);
	}

	/**
	 * Labels for fields in this model
	 *
	 * @return array Labels
	 */
	public function labels()
	{
		return array(
			'firstname'        => 'First Name',
			'lastname'         => 'Last Name',
			'email'            => 'email address',
			'password'         => 'password',
		);
	}

	/**
	 * Complete the login for a user by incrementing the logins and saving login timestamp
	 *
	 * @return void
	 */
	public function complete_login()
	{
		if ($this->_loaded)
		{
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = time();

			// Save the user
			$this->update();
		}
	}

	/**
	 * Tests if a unique key value exists in the database.
	 *
	 * @param   mixed    the value to test
	 * @param   string   field name
	 * @return  boolean
	 */
	public function unique_key_exists($value, $field = NULL)
	{
		if ($field === NULL)
		{
			// Automatically determine field by looking at the value
			$field = $this->unique_key($value);
		}

		return (bool) DB::select(array('COUNT("*")', 'total_count'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->where($this->_primary_key, '!=', $this->pk())
			->execute($this->_db)
			->get('total_count');
	}

	/**
	 * Allows a model use both email and username as unique identifiers for login
	 *
	 * @param   string  unique value
	 * @return  string  field name
	 */
	public function unique_key($value)
	{
		return Valid::email($value) ? 'email' : 'username';
	}

	/**
	 * Password validation for plain passwords.
	 *
	 * @param array $values
	 * @return Validation
	 */
	public static function get_password_validation($values)
	{
		return Validation::factory($values)
			->rule('password', 'min_length', array(':value', 6))
			->rule('password_confirm', 'matches', array(':validation', ':field', 'password'));
	}

	/**
	 * Create a new user
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('user')->create_user($_POST, array(
	 *	'username',
	 *	'password',
	 *	'email',
	 * );
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function create_user($values, $expected)
	{
		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values)
			->rule('password', 'not_empty');
		return $this->values($values, $expected)->create($extra_validation);
	}

	public function createFacebookUser($value, $expected)
	{
		$password = Text::random('alnum');
		$data  = array(
			'firstname' 		=> $value['firstname'],
			'lastname'		=> $value['lastname'],
			'email'			=> $value['email'],
                        'facebook_id'     	=> $value['facebook_id'],
                        'facebook_token'     	=> $value['facebook_token'],
                        'facebook_expire_date' 	=> @$value['facebook_expire_date'],
			'password'		=> $password,
			'password_confirm'	=> $password
		);

		if(isset($value['termofuse']) || isset($_POST['json'])) {
			$data['termofuse']	= 1;
		}
		array_push($expected, 'password');

		try {
			$this->last_login 	= time();
			$this->logins		= 1;
			$this->create_user($data, $expected);
			$this->add('roles', ORM::factory('role')->where('name', '=', 'login')->find());

			$newUser = ORM::factory('user')->where('id', '=', $this->id)->find();

			//send message to user with generated password
			Mailer::factory('user')->send_setpassword(array(
                                                                                  'user'	=> array(
                                                                                  "name" 	=> $value['firstname'],
                                                                                  'email'	=> $value['email'],
                                                                                  'password'	=> $password
                                                                          )
                                                                  ));
			Session::instance()->regenerate();
			Session::instance()->set('auth_user', $newUser);
			// //generate token for user
			Helper_iAuth::instance()->createSession();
			return true;
		}
		catch (ORM_Validation_Exception $e) {
			// Helper_Main::print_flex($e->errors(''));
			foreach ($e->errors('') as $key => $value) {
				Helper_Output::addErrors(Helper_Output::getErrorCode($value));
			}
			return false;
		}
	}

	public function sendInterview()
	{
		$hash = md5(time().$this->id);
		$this->set('hash_code', $hash);
		$this->update(); 

		$link = URL::base() . 'users/forgot?hash='.$hash;
		Mailer::factory('user')->send_forgotpasswordfirst(array(
			'user'	=> array(
				"name" 		=> $this->firstname,
				'email'		=> $this->email,
				'link'		=> $link
			)
		));
	}

	public function recoveryPassword()
	{
		$password = Text::random('alnum');
		try {
			$this->update_user(array('hash_code'=> '', 'password' => $password, 'password_confirm' => $password), array('password', 'hash_code'));
			Mailer::factory('user')->send_forgotpasswordsecond(array(
				'user'	=> array(
					"name" 		=> $this->firstname,
					'email'		=> $this->email,
					'password'	=> $password
				)
			));
			return true;
		}
		catch(ORM_Validation_Exception $e) {
			var_dump($e->errors(''));
			return false;
		}

	}

	public function createTwitterUser($values, $expected)
	{
		$validation = Validation::factory($values);
		$rules = $this->rules();

		if(!isset($_POST['json'])) {
			$validation->rules('termofuse', $rules['termofuse']);
		}

		foreach ($values as $key => $value) {
			if(isset($rules[$key])) {
				$validation->rules($key, $rules[$key]);
			}
		}
		$validation->labels($this->labels());

		foreach ($expected as $key => $value) {
			if(!array_key_exists($value, $values)) {
				switch ($value) {
					case 'firstname':
						Helper_Output::addErrors(Helper_Output::getErrorCode('First Name must not be empty'));
						break;
					case 'email':
						Helper_Output::addErrors(Helper_Output::getErrorCode('email address must not be empty'));
						break;
					case 'twitter_id':
						Helper_Output::addErrors(Helper_Output::getErrorCode('No Twitter ID'));
						break;
				}
			}
		}

		if(count(Helper_Output::getErrors()) > 0) {
			return false;
		}
		
		if(!$validation->check()) {
			$errors = $validation->errors('user');
			foreach ($errors as $key => $value) {
				Helper_Output::addErrors(Helper_Output::getErrorCode($value));
			}
			return false;
		} else {
			//if all good no errors
			$password = Text::random('alnum');
			$data  = array(
					'firstname' 		=> $values['firstname'],
					'lastname'		=> $values['lastname'],
					'email'			=> $values['email'],
                                        'twitter_token'		=> $values['twitter_token'],
                                        'twitter_secret'	=> $values['twitter_secret'],
					'password'		=> $password,
					'password_confirm'	=> $password,
					'twitter_id'		=> $values['twitter_id']
				);

			if(isset($value['termofuse']) || isset($_POST['json'])) {
				$data['termofuse']	= 1;
			}

			try {
					$this->last_login 	= time();
					$this->logins		= 1;
					$this->create_user($data, array('firstname', 'lastname','password', 'email', 'twitter_id', 'termofuse', 'twitter_token', 'twitter_secret'));
					$this->add('roles', ORM::factory('role')->where('name', '=', 'login')->find());

					$newUser = ORM::factory('user')->where('id', '=', $this->id)->find();

					//send message to user with generated password
					Mailer::factory('user')->send_setpassword(array(
																	'user'	=> array(
																		"name" 		=> $values['firstname'],
																		'email'		=> $values['email'],
																		'password'	=> $password
																	)
																));
					Session::instance()->regenerate();
					Session::instance()->set('auth_user', $newUser);
					//generate token for user
					Helper_iAuth::instance()->createSession();
					return true;
	    		}
	    		catch (ORM_Validation_Exception $e) {
	    			//we should not be there never.
	    			die("Error with logic please check model/auth/user.php createTwitterUser method");
	    			return false;
	    		}

		}
		

		
	}

	/**
	 * Update an existing user
	 *
	 * [!!] We make the assumption that if a user does not supply a password, that they do not wish to update their password.
	 *
	 * Example usage:
	 * ~~~
	 * $user = ORM::factory('user')
	 *	->where('username', '=', 'kiall')
	 *	->find()
	 *	->update_user($_POST, array(
	 *		'username',
	 *		'password',
	 *		'email',
	 *	);
	 * ~~~
	 *
	 * @param array $values
	 * @param array $expected
	 * @throws ORM_Validation_Exception
	 */
	public function update_user($values, $expected = NULL)
	{
		if (empty($values['password']))
		{
			unset($values['password'], $values['password_confirm']);
		}
                
                if(isset($values['avatar']) && $values['avatar']){
                    @copy(Kohana::$config->load('config')->get('temp.upload').$values['avatar'], Kohana::$config->load('config')->get('user.avatars').$values['avatar']);
                    @unlink(Kohana::$config->load('config')->get('temp.upload').$values['avatar']);
                }
                
                $user = ORM::factory('user', Request::initial()->post('id'));
                if($user->avatar){
                    if(isset($values['avatar']) && ($values['avatar'] == '' || $values['avatar'] != $user->avatar ) ){
                      @unlink(Kohana::$config->load('config')->get('user.avatars').$user->avatar);
                    }
                }
                

		// Validation for passwords
		$extra_validation = Model_User::get_password_validation($values);

		return $this->values($values, $expected)->update($extra_validation);
	}


	public function login($login, $password) 
	{
		// Auth::instance()->login($_POST['email'], $_POST['password']);
		$user = $this->where('email', '=', $_POST['email'])->find();
		if($user->id && $user->status == 0) {
			Helper_Output::addErrors('1003');
			Helper_Output::keepErrors();
		} else {
			$status = Auth::instance()->login($_POST['email'], $_POST['password']);
                        
			if($status) {
				// Helper_iAuth::instance()->createSession();
				return true;
			} else {
				Helper_Output::addErrors('1002');
				Helper_Output::keepErrors();
				return false;
			}
		}
	}
        
        public static function getFriendsDeviceTokensInRadius($user_id, $latitude, $longitude,  $radius = 20)
        {
           $string = "distance(user_locations.point, PointFromText('POINT($latitude $longitude)')) * 100";
           $res = DB::select('users.device_token')
                    ->select(array(DB::expr($string), 'distance'))
                    ->from('users')
                    ->join('friendships', 'RIGHT')->on('friendships.friend_id', '=', 'users.id')
                    ->join('user_locations', 'RIGHT')->on('user_locations.user_id', '=', 'users.id')
                    ->having('distance', '<', $radius)
                    ->where('users.device_token', '!=', null)
                    ->where('friendships.user_id', '=', $user_id)
                    ->where('friendships.accepted', '=', 1)
                    ->as_object()->execute()->as_array();
          $resArray = array();
          foreach ($res as $item){
            $resArray[] = $item->device_token;
          }
          return $resArray;
        }
        
} // End Auth User Model