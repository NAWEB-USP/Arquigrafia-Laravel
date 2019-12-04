<?php
use lib\log\EventLogger;
use lib\utils\HelpTool;
use Carbon\Carbon;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookRequestException;
use modules\institutions\models\Institution as Institution;
use Illuminate\Database\Eloquent\Collection as Collection;
use modules\gamification\models\Gamified as Gamified;

class UsersController extends \BaseController {

  public function __construct()
  {
    $this->beforeFilter('auth',
      array('only' => ['follow', 'unfollow']));
  }

  public function index()
  {
    $users = User::all();

    return View::make('/users/index',['users' => $users]);
  }


  public function show($id)
  {
    // This page has a gamified variant, get the gamified variant
    $variationId = Gamified::getGamifiedVariationId();
    $isGamified = Gamified::isGamified($variationId);

    // Getting user info
    $user = User::whereid($id)->first();
    $institutionFollowed = $user->followingInstitution;
    $photos = $user->photos()->get()->reverse();
    // Starting suggestions variables as a empty array
    $acceptedSuggestions = [];
    $waitingSuggestions = [];
    $refusedSuggestions = [];
    // Also, userPoints and userWaitingPoints start value is 0
    $userPoints = 0;
    $userWaitingPoints = 0;
    // If you're logged in
    if (Auth::check()) {
      // Marking if you're following the user
      if (Auth::user()->following->contains($user->id)) $follow = false;
      else $follow = true;

      // If the current user is the user that we wanna show the profile
      if ($user->equal(Auth::user())) {
        // Getting the acceptedSuggestions for user
        $acceptedSuggestions = $user->suggestions()->where('accepted', '=', 1)->get();
        foreach ($acceptedSuggestions as $suggestion) {
          // Adding the suggestion numPoints to user points
          $userPoints += $suggestion->numPoints();
        }
        // Getting waiting points for user
        $waitingSuggestions = $user->suggestions()->where('accepted', '=', null)->get();
        foreach ($waitingSuggestions as $suggestion) {
          // Adding the suggestions numPoints to the waiting points
          $userWaitingPoints += $suggestion->numPoints();
        }
        // Getting refused suggestions
        $refusedSuggestions = $user->suggestions()->where('accepted', '=', 0)->get();
      }
    } else {
      $follow = true;
      $followInstitution = true;
    }

    $albums = $user->userAlbums;

    EventLogger::printEventLogs(null, "select_user", ["target_userId" => $id], "Web");

    return View::make('/users/show',['user' => $user, 'photos' => $photos, 'follow' => $follow,
      'evaluatedPhotos' => Photo::getEvaluatedPhotosByUser($user),
      'lastDateUpdatePhoto' => Photo::getLastUpdatePhotoByUser($id),
      'lastDateUploadPhoto' => Photo::getLastUploadPhotoByUser($id),
      'albums' => $albums,
      'institutionFollowed' => $institutionFollowed,
      'userPoints' => $userPoints,
      'acceptedSuggestions' => $acceptedSuggestions,
      'userWaitingPoints' => $userWaitingPoints,
      'waitingSuggestions' => $waitingSuggestions,
      'refusedSuggestions' => $refusedSuggestions,
      'gamified' => $isGamified,
      'variationId' => $variationId
      ]);
  }

  // show create account form
  public function account()
  {
    if (Auth::check()) return Redirect::to('/home');
    return View::make('/modal/account');
  }


  // register of user with email
  public function store()
  {
    // put input into flash session for form repopulation
    Input::flash();
    $input = Input::all();

    // validate data
    $rules = array(
        'name' => 'required',
        'login' => 'required|regex:/^[a-z0-9-_]{3,20}$/|unique:users',
        'password' => 'required|min:6|confirmed',
        'email' => 'required|email|unique:users',
        'terms' => 'required'
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return Redirect::to('/users/account')->withErrors($messages);
    } else {

       $name = $input["name"];
       $email =$input["email"];
       $login =$input["login"];
       $verify_code = str_random(30);
      //create user with a verify code
      $user = User::create([
      'name' => $name,
      'email' => $email,
      'password' => Hash::make($input["password"]),
      'login' => $login,
      'verify_code' => $verify_code
      ]);

      EventLogger::printEventLogs(null, "new_account", ["origin" => "Arquigrafia"], "Web");

        //send email to user created
       Mail::send('emails.users.verify', array('name' => $name, 'email' => $email, 'login' => $login ,'verifyCode' => $verify_code),
          function($msg) use($email) {
            $msg->to($email)
                ->subject('[Arquigrafia]- Cadastro de Usuário');
        });

        return Redirect::to("/users/register");

    }
  }

  public function emailRegister(){

    $msgType = "sendEmail";
    return View::make('/modal/register')->with(['msgType'=>$msgType]);


  }

  public function verify($verify_code){

    if(!empty($verify_code)){
      $newUser= User::userVerifyCode($verify_code);
    }

    if (!$newUser){
            //error
            return Redirect::to('users/verify');
      }else{
          //update data of new user registered
          $newUser->active = 'yes';
          $newUser->verify_code = null;
          $newUser->save();

          return Redirect::to('/users/login')->with('msgRegister', "<strong>Conta ativada com sucesso!.</strong>");

      }
  }

  public function verifyError(){

      $msgType = "verify";
      return View::make('/modal/register')->with(['msgType'=>$msgType]);
  }


  public function forgetForm()
  {
    $message = false;
    $existEmail = true;
    return View::make('/modal/forget')->with(['message'=>$message, 'existEmail'=>$existEmail]);
  }

  public function forget(){
    $input = Input::all();
    $email = $input["email"];
    $rules = array('email' => 'required|email');

    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return Redirect::to('/users/forget')->withErrors($messages);
    }else{

      $user = User::userInformationObtain($email);

      if(!empty($user)){
        $randomPassword = strtolower(Str::quickRandom(8));
        $user->password = Hash::make($randomPassword);
        $user->oldAccount = 0; // usuário já tem senha nova
        $user->touch(); // touch já salva

        Mail::send('emails.users.reset-password', array('user' => $user,'email' => $email,'randomPassword' => $randomPassword),
         function($message) use($email) {
               $message->to($email)
               //->replyTo($email)
               ->subject('[Arquigrafia] - Esqueci minha senha');
         });
        $message = true;
        $existEmail = true;
      } else{
        $existEmail = false;
        $message = null;
      }
      return View::make('/modal/forget')->with(['message'=>$message,'email'=>$email, 'existEmail'=>$existEmail]);
    }


  }



  // formulário de login
  public function loginForm()
  {
    if (Auth::check())
        return Redirect::to('/home');

    session_start();
    // $fb_config = Config::get('facebook');
    // FacebookSession::setDefaultApplication($fb_config["id"], $fb_config["secret"]);
    // $helper = new FacebookRedirectLoginHelper(url('/users/login/fb/callback'));
    // $fburl = $helper->getLoginUrl(array(
    //       'scope' => 'email',
    // ));

    $fburl = "";
    $institutions = Institution::institutionsList();

    if (!Session::has('filter.login') && !Session::has('login.message')) //nao foi acionado pelo filtro, retornar para pagina anterior
         Session::put('url.previous', URL::previous());

    return View::make('/modal/login')->with(['fburl' => $fburl,'institutions' => $institutions]);
  }

   // validacao do login
  public function login()
  {
    $input = Input::all();
    $user = User::userInformation($input["login"]);
    if (isset($user)) {
      $integration_message = $this->integrateAccounts($user->email);
    }
    if ($user != null && $user->oldAccount == 1)
    {
      if ( User::checkOldAccount($user, $input["password"]) )
      {
        $user->updateAccount($input['password']);
      } else {
        Session::put('login.message', 'Usuário e/ou senha inválidos, tente novamente.');
        return Redirect::to('/users/login')->withInput();
      }
    }
    if (isset($user)) {
      if ($user->active == 'no' && (Auth::validate(array('login' => $user->login, 'password' => $input["password"])) == true)) {
        Session::put('login.message', 'Finalize seu cadastro acessando o link enviado ao seu e-mail.');
            return Redirect::to('/users/login')->withInput();
      }
      if ( Auth::attempt(array('login' => $user->login, 'password' => $input["password"],'active' => 'yes')) == true ||
          Auth::attempt(array('email' => $input["login"], 'password' => $input["password"],'active' => 'yes')) == true  ) {
        if ( Session::has('filter.login') ) //acionado pelo login
        {
          Session::forget('filter.login');
          EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');
          if (isset($integration_message)) {
            return Redirect::to('/home')->with('msgWelcome', $integration_message);
          }
          return Redirect::intended('/home');
        }
        if ( Session::has('url.previous') )
        {
          $url = Session::pull('url.previous');

          if (!empty($url)) {
            EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');
            //Redirect when user forget password
            if($url == URL::to('users/forget')){
              return Redirect::to('/home');
            }elseif(!empty($input["firstTime"])){
                return Redirect::to('/home')->with('msgWelcome', "Bem-vind@ ".ucfirst($user->name).".");

            }else{
              if($url == URL::to("/")."/" || strpos($url, '/landing') !== false)
                  return Redirect::to('/home');
              else
                  return Redirect::to($url);
            }

          }


          EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');
          if (isset($integration_message)) {
            return Redirect::to('/home')->with('msgWelcome', $integration_message);
          }
          return Redirect::to('/home');
        }
        EventLogger::printEventLogs(null, 'login', ["origin" => "Arquigrafia"], 'Web');
        if (isset($integration_message)) {
          return Redirect::to('/home')->with('msgWelcome', $integration_message);
        }
        return Redirect::to('/home');
      } else {
  			Session::put('login.message', 'Usuário e/ou senha inválidos, tente novamente.');
        return Redirect::to('/users/login')->withInput();
      }
    } else {
      Session::put('login.message', 'Usuário e/ou senha inválidos, tente novamente.');
      return Redirect::to('/users/login')->withInput();
    }
  }

  // formulário de login
  public function logout()
  {
    if (Auth::check()) {
      EventLogger::printEventLogs(null, 'logout', null, 'Web');

      // Before logging out, we're going to save the gamified state
      $variationId = Gamified::getGamifiedVariationId();
      // Logging out
      Auth::logout();
      Session::flush();
      // Saving variationId on Session again
      Gamified::saveGamifiedVariationId($variationId);

      return Redirect::to('/home');
    }
    return Redirect::to('/home');
  }

  // facebook login NÃO ESTA SENDO USADO
  public function facebook()
  {
    $fb_config = Config::get('facebook');
    $facebook = new Facebook( $fb_config );

    $params = array(
        'redirect_uri' => url('/users/login/fb/callback'),
        'scope' => 'email',
    );
    return Redirect::to($facebook->getLoginUrl($params));
  }

    // facebook login callback
    public function callback()
   {
    session_start();

    $fb_config = Config::get('facebook');

    FacebookSession::setDefaultApplication($fb_config["id"], $fb_config["secret"]);

    $helper = new FacebookRedirectLoginHelper(url('/users/login/fb/callback'));

    try {
      $session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $ex) {
      // When Facebook returns an error
      dd($ex);
    } catch(\Exception $ex) {
      // When validation fails or other local issues
      dd($ex);
    }
    if ($session) {
      // Logged in
      $request = new FacebookRequest($session, 'GET', '/me');
      $response = $request->execute();
      $fbuser = $response->getGraphObject();
      $fbid = $fbuser->getProperty('id');
      $fbmail = $fbuser->getProperty('email');

      $integration_message = $this->integrateAccounts($fbmail);
      //usuarios antigos tem campo id_facebook null, mas existe login = $fbid;
      $user = User::where('id_facebook', '=', $fbid)->orWhere('login', '=', $fbid)->first();

      if (!is_null($user)) {
        // loga usuário existente
        Auth::loginUsingId($user->id);
        if(is_null($user->id_facebook)) {
          $user->id_facebook = $fbid;
          $user->save();
        }
        // pega avatar
        $request = new FacebookRequest(
          $session,
          'GET',
          '/me/picture',
          array (
            'redirect' => false,
            'height' => '200',
            'type' => 'normal',
            'width' => '200',
          )
        );
        $response = $request->execute();
        $pic = $response->getGraphObject();
        $image = Image::make($pic->getProperty('url'))->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
        if ($user->photo != '/arquigrafia-avatars/'.$user->id.'.jpg') {
          $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
          $user->save();
        }

        EventLogger::printEventLogs(null, "login", ["origin" => "Facebook"], "Web");
        if (isset($integration_message)) {
          return Redirect::to('/home')->with('msgWelcome', $integration_message);
        }
        return Redirect::to('/home')->with('msgWelcome', "Bem-vindo {$user->name}!");

      } else {
        $query = User::where('email', '=', $fbmail)->first();
        if (!is_null($query)) {
          $query->id_facebook = $fbid;
          $query->save();
          Auth::loginUsingId($query->id);
          EventLogger::printEventLogs(null, "login", ["origin" => "Facebook"], "Web");
          if (isset($integration_message)) {
            return Redirect::to('/home')->with('msgWelcome', $integration_message);
          }
          return Redirect::to('/home')->with('msgWelcome', "Bem-vindo {$query->name}!");
        }
        else {
        $user = new User;
        $user->name = $fbuser->getProperty('name');
        $user->login = $fbuser->getProperty('id');
        $user->email = $fbuser->getProperty('email');
        $user->password = 'facebook';
        $user->id_facebook = $fbuser->getProperty('id');
        $user->save();
        Auth::loginUsingId($user->id);

        // pega avatar
        $request = new FacebookRequest(
          $session,
          'GET',
          '/me/picture',
          array (
            'redirect' => false,
            'height' => '200',
            'type' => 'normal',
            'width' => '200',
          )
        );
        $response = $request->execute();
        $pic = $response->getGraphObject();
        $image = Image::make($pic->getProperty('url'))->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
        $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
        $user->save();

        EventLogger::printEventLogs(null, "new_account", ["origin" => "Facebook"], "Web");

        return Redirect::to('/home')->with('message', 'Sua conta foi criada com sucesso!');
      }
      }

    }
  }

  public function getFacebookPicture() {
    if (Auth::check()) {
      $user = Auth::user();
      if ($user->id_facebook != null) {
        $image = file_get_contents('https://graph.facebook.com/'.$user->id_facebook.'/picture?type=large');
        $file_name = public_path().'/arquigrafia-avatars/'.$user->id.'.jpg';
        $file = fopen($file_name, 'w+');
        fputs($file, $image);
        fclose($file);
        $user->photo = '/arquigrafia-avatars/'.$user->id.'.jpg';
        $user->save();

      }
    }
    return $user->photo;
  }

  public function follow($user_id)
  {
    $logged_user = Auth::user();

    if ($logged_user == null) //futuramente, adicionar filtro de login
       return Redirect::to('/home');

    $following = $logged_user->following;



    if ($user_id != $logged_user->id && !$following->contains($user_id)) {
      //Envio da Notificação

      Event::fire('user.followed', array($logged_user->id, (int)$user_id));

      $logged_user->following()->attach($user_id);

      EventLogger::printEventLogs(null, 'follow', ['target_userId' => $user_id], 'Web');
    }

    return Redirect::to(URL::previous()); // redirecionar para friends
  }

  public function unfollow($user_id)
  {
    $logged_user = Auth::user();

    if ($logged_user == null) //futuramente, adicionar filtro de login
      return Redirect::to('/home');

    $following = $logged_user->following;


    if ($user_id != $logged_user->id && $following->contains($user_id)) {
      $logged_user->following()->detach($user_id);

      EventLogger::printEventLogs(null, 'unfollow', ['target_userId' => $user_id], 'Web');
    }

    return Redirect::to(URL::previous()); // redirecionar para friends
  }

  // AVATAR
  public function profile($id)
  {
    $path = public_path().'/arquigrafia-avatars/'.$id.'_view.jpg';
    if( File::exists($path) ) {
      header("Cache-Control: public");
      header("Content-Disposition: inline; filename=\"".$id . '_view.jpg'."\"");
      header("Content-Type: image/jpg");
      header("Content-Transfer-Encoding: binary");
      readfile($path);
      exit;
    }
    return $path;
  }

/**
 * Show the form for editing the specified resource.
 *
 * @return Response
 */
  public function edit($id) {
    if (Session::has('institutionId') ) {
      return Redirect::to('/home');
    }

    $user = User::find($id);

    $logged_user = Auth::User();
    if ($logged_user == null) {
      return Redirect::action('PagesController@home');
    }
    elseif ($logged_user->id == $user->id) {
      return View::make('users.edit')->with( ['user' => $user] );
    }
    return Redirect::action('PagesController@home');
  }

  public function update($id) {
    $user = User::find($id);

    Input::flash();
    $input = Input::only('name', 'login', 'email', 'scholarity', 'lastName', 'site', 'birthday', 'country', 'state', 'city',
      'photo', 'gender', 'institution', 'occupation', 'visibleBirthday', 'visibleEmail','old_password','user_password','user_password_confirmation');

    $rules = array(
        'name' => 'required',
        'login' => 'required',
        'email' => 'required|email',
        'user_password' => 'min:6|regex:/^[a-z0-9-@_]{6,10}$/|confirmed',
        'birthday' => 'date_format:"d/m/Y"',
        'photo' => 'max:2048|mimes:jpeg,jpg,png,gif'
    );
    if ($input['email'] !== $user->email)
      $rules['email'] = 'required|email|unique:users';

    if ($input['login'] !== $user->login)
      $rules['login'] = 'required|unique:users';

    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
      $messages = $validator->messages();
      return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
    } else {
      $user->name = $input['name'];
      $user->login = $input['login'];
      $user->email = $input['email'];
      $user->scholarity = $input['scholarity'];
      $user->lastName = $input['lastName'];
      $user->site = $input['site'];
      if ( !empty($input["birthday"]) )
        $user->birthday = $input["birthday"];
      $user->country = $input['country'];
      $user->state = $input['state'];
      $user->city = $input['city'];
      $user->gender = $input['gender'];
      $user->visibleBirthday = $input['visibleBirthday'];
      $user->visibleEmail = $input['visibleEmail'];

      Log::info("check=".Hash::check($input["old_password"], $user->password)."autenticar =".Auth::attempt(array('login' => $user->login,'password' => $input["old_password"])));

      if(Hash::check($input["old_password"], $user->password)){

            if(!empty($input['user_password']) || trim($input['user_password']) != ""){
                $user->password = Hash::make($input["user_password"]);
            }else{
                  $messages = array('user_password'=>array('Inserir uma senha válida com mínimo 6 caracteres'));
                  return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
            }
       } else if(!empty($input['old_password']) || trim($input['old_password']) != ""){
            $messages = array('old_password'=>array('Antiga senha incorreta'));
            return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
       } else if(!empty($input['user_password']) || trim($input['user_password']) != "" ){
            $messages = array('old_password'=>array('Precisa inserir a senha antiga'));
            return Redirect::to('/users/' . $id . '/edit')->withErrors($messages);
       }


      if ($input["institution"] != null or $input["occupation"] != null) {
        $occupation = Occupation::firstOrCreate(['user_id'=>$user->id]);
        $occupation->institution = $input["institution"];
        $occupation->occupation = $input["occupation"];
        $occupation->save();
      }


      if (Input::hasFile('photo') and Input::file('photo')->isValid())  {
        $file = Input::file('photo');
        $ext = $file->getClientOriginalExtension();

        $user->photo = "/arquigrafia-avatars/".$user->id.".jpg";
        //$user->save();
        $image = Image::make(Input::file('photo'))->encode('jpg', 80);
        $image->save(public_path().'/arquigrafia-avatars/'.$user->id.'.jpg');
        $file->move(public_path().'/arquigrafia-avatars', $user->id."_original.".strtolower($ext));
      }
      $user->touch();
      $user->save();

      return Redirect::to("/users/{$user->id}")->with('message', '<strong>Edição de perfil do usuário</strong><br>Dados alterados com sucesso');

    }
  }

  public function stoaLogin() {

    $account = Input::get('stoa_account');
    $password = Input::get('password');
    $stoa_user = $this->getStoaAccount($account, $password, 'login');
    if (!$stoa_user->ok) {
      $stoa_user = $this->getStoaAccount($account, $password, 'usp_id');
      if (!$stoa_user->ok) {
        return Response::json(false);
      }
    }
    $user = User::stoa($stoa_user);
    Auth::loginUsingId($user->id);

    EventLogger::printEventLogs(null, "login", ["origin" => "Stoa"], "Web");
    return Response::json(true);
  }

  public function institutionalLogin() {
    Log::info("Login Institution");
    $login = Input::get('login');
    $institutionId = Input::get('institution');
    $password = Input::get('password');
    Log::info("Retrieved params login=".$login.", institution=".$institutionId);
    $booleanExist = User::userBelongInstitution($login,$institutionId);
    Log::info("Result belong institution -> booleanExist=".$booleanExist);

    if ((Auth::attempt(array('login' => $login, 'password' => $password)) == true ||
        Auth::attempt(array('email' => $login, 'password' => $password,'active' => 'yes')) == true) &&
        $booleanExist == true){
        $displayedInstitutionName = null;
        $institution = Institution::find($institutionId);
        $displayedInstitutionName = HelpTool::formattingLongText($institution->name, $institution->acronym, 25);
        Log::info("Valid access, redirect");
        Session::put('institutionId', $institutionId);
        Session::put('displayInstitution', $displayedInstitutionName);
        return Redirect::to('/home');
    } else {
      Log::info("Invalid access, return message");
      return Response::json(false);
    }
  }

  private function getStoaAccount($account, $password, $account_type) {
    $ch = curl_init();
    $this->setCurlOptions($ch, $account, $password, $account_type);
    $response = curl_exec($ch);
    curl_close ($ch);
    return json_decode($response);
  }

  private function setCurlOptions($ch, $account, $password, $account_type) {
    curl_setopt($ch, CURLOPT_URL,"https://social.stoa.usp.br/plugin/stoa/authenticate/");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
      http_build_query([
          $account_type => $account,
          'password' => $password,
          'fields' => 'full'
        ])
    );
  }

  private function integrateAccounts($email) {
    /* Verifica quantas contas com o mesmo e-mail existem */
    $all_acc = User::where('email','=',$email)->get();
    /* Se existir somente uma, não há o que integrar */
    if (count($all_acc) <= 1) {
      return;
    }
    /* Pega cada conta separadamente */
    $arq_acc =  User::whereRaw('(email = ?) and (id_stoa is NULL or id_stoa != login) and (id_facebook is NULL or id_facebook != login)', array($email))->first();
    $fb_acc = User::whereRaw('(email = ?) and (id_facebook = login)', array($email))->first();
    $stoa_acc = User::whereRaw('(email = ?) and (id_stoa = login)', array($email))->first();
    /* Existe uma conta Arquigrafia? */
    if (!is_null($arq_acc)) {
      /* Essa conta já tem uma foto? */
      if ($arq_acc->photo == "/arquigrafia-avatars/" . $arq_acc->id . ".jpg") {
        $has_photo = true;
      }
      /* Existe uma conta Facebook? */
      if (!is_null($fb_acc)) {
        $fb_boolean = true;
        /* Associa as contas */
        DB::table('users')->where('id', '=', $arq_acc->id)->update(array('id_facebook' => $fb_acc->id));
        /* Se a conta Arquigrafia não possuir foto e a conta Facebook possuir foto, pega essa foto */
        if (!isset($has_photo)) {
          if ($fb_acc->photo == "/arquigrafia-avatars/" . $fb_acc->id . ".jpg") {
            $old_filename = public_path() . $fb_acc->photo;
            $new_filename = public_path() . "/arquigrafia-avatars/" . $arq_acc->id . ".jpg";
            if (File::exists($old_filename) && File::move($old_filename, $new_filename)) {
              $arq_acc->photo = "/arquigrafia-avatars/" . $arq_acc->id . ".jpg";
              $has_photo = true;
            }
          }
        }
        /* Importa Photos, Comments, Evaluations, follows e followers, se existirem */
        $this->getAttributesFromTo($fb_acc, $arq_acc);
      }
      /* Retorna uma mensagem dizendo quais contas foram integradas */
      $result = "Sua(s) conta(s): ";
      if (isset($fb_boolean)) {
        $result = $result . "Facebook";
      }
      if (isset($fb_boolean) && isset($stoa_boolean)) {
        $result = $result . " e ";
      }
      if (isset($stoa_boolean)) {
        $result = $result . "Stoa";
      }
      $result = $result . " foi(ram) integrada(s) à sua conta Arquigrafia";
      /* Exclui as contas paralelas do banco */
      if (isset($fb_boolean)) {
        DB::table('users')->where('id', '=', $fb_acc->id)->delete();
      }
      if (isset($stoa_boolean)) {
        DB::table('users')->where('id', '=', $stoa_acc->id)->delete();
      }
      return $result;
    }
  }

  private function getAttributesFromTo($accountFrom, $accountTo) {
    DB::table('friendship')->where('following_id', '=', $accountFrom->id)->update(array('following_id' => $accountTo->id));
    DB::table('friendship')->where('followed_id', '=', $accountFrom->id)->update(array('followed_id' => $accountTo->id));
    DB::table('photos')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('binomial_evaluation')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('comments')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('albums')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('notifications')->where('sender_id', '=', $accountFrom->id)->update(array('sender_id' => $accountTo->id));
    DB::table('notification_user')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('likes')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('occupations')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    // DB::table('scores')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('users_roles')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('user_badges')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('employees')->where('user_id', '=', $accountFrom->id)->update(array('user_id' => $accountTo->id));
    DB::table('friendship_institution')->where('following_user_id', '=', $accountFrom->id)
      ->update(array('following_user_id' => $accountTo->id));
  }

   public function usersList()
  {
        if (Auth::check()) {
            echo "Criando o arquivo Json com a lista de usuarios";

            $pathFile = public_path('data')."/users.json";
            $usersAll = User::userDataToJson();
            $users = $usersAll->toJson();

            //criando todo o arquivo json dos usuarios
            File::put($pathFile, $users);
        }else{
          dd("Precisa estar logado");

        }
  }

  public function updateUsersListJson($user){
      $pathFile = public_path('data')."/users.json";

      if (File::exists($pathFile)){
                  Log::info("Adicionando texto no arquivo json de usuario");

                  $search = ']';
                  $replace = ',{"id":'.$user->id.', "name": "'.$user->name.'"}]';
                  file_put_contents($pathFile, str_replace($search, $replace, file_get_contents($pathFile)));
      }
  }


}
