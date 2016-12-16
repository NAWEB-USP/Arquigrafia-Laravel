<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    "accepted"         => "O campo :attribute deve ser aceito.",
    "active_url"       => "O campo :attribute não contém um URL válido.",
    "after"            => "O campo :attribute deverá conter uma data posterior a :date.",
    "alpha"            => "O campo :attribute deverá conter apenas letras.",
    "alpha_dash"       => "O campo :attribute deverá conter apenas letras, números e traços.",
    "alpha_num"        => "O campo :attribute deverá conter apenas letras e números .",
    "array"            => "O campo :attribute precisa ser um conjunto.",
    "before"           => "O campo :attribute deverá conter uma data anterior a :date.",
    "between"          => array(
        "numeric" => "O campo :attribute deverá ter um valor entre :min - :max.",
        "file"    => "O campo :attribute deverá ter um tamanho entre :min - :max kilobytes.",
        "string"  => "O campo :attribute deverá conter entre :min - :max caracteres.",
        "array"   => "O campo :attribute precisar ter entre :min - :max itens."
    ),
    "boolean"          => "O campo :attribute deverá ter o valor verdadeiro ou falso.",
    "confirmed"        => "A confirmação para o campo :attribute não coincide.",
    "date"             => "O campo :attribute não contém uma data válida.",
    "date_format"      => "A data indicada para o campo :attribute não respeita o formato :format.",
    "different"        => "Os campos :attribute e :other deverão conter valores diferentes.",
    "digits"           => "O campo :attribute deverá conter :digits dígitos.",
    "digits_between"   => "O campo :attribute deverá conter entre :min a :max dígitos.",
    "email"            => "O campo :attribute não contém um endereço de email válido.",
    "exists"           => "O valor selecionado para o campo :attribute é inválido.",
    "image"            => "O campo :attribute deverá conter uma imagem.",
    "in"               => "O campo :attribute não contém um valor válido.",
    "integer"          => "O campo :attribute deverá conter um número inteiro.",
    "ip"               => "O campo :attribute deverá conter um IP válido.",
    "max"              => array(
        "numeric" => "O campo :attribute não deverá conter um valor superior a :max.",
        "file"    => "O campo :attribute não deverá ter um tamanho superior a :max kilobytes.",
        "string"  => "O campo :attribute não deverá conter mais de :max caracteres.",
        "array"   => "O campo :attribute deve ter no máximo :max itens."
    ),
    "mimes"            => "O campo :attribute deverá conter um arquivo do tipo: :values.",
    "min"              => array(
        "numeric" => "O campo :attribute deverá ter um valor superior ou igual a :min.",
        "file"    => "O campo :attribute deverá ter no mínimo :min kilobytes.",
        "string"  => "O campo :attribute deverá conter no mínimo :min caracteres.",
        "array"   => "O campo :attribute deve ter no mínimo :min itens."
    ),
    "not_in"           => "O campo :attribute contém um valor inválido.",
    "numeric"          => "O campo :attribute deverá conter um valor numérico.",    
    "regex"            => "O campo :attribute só pode conter números, traços, underscore e letras minúsculas sem acentos, com no mínimo 6 e no máximo 10 caracteres.",
    "required"         => "É obrigatória a indicação de um valor para o campo :attribute.",
    "required_if"      => "É obrigatória a indicação de um valor para o campo :attribute quando o valor do campo :other é igual a :value.",
    "required_with"    => "É obrigatória a indicação de um valor para o campo :attribute quando :values está presente.",
    "required_with_all" => "É obrigatória a indicação de um valor para o campo :attribute quando um dos :values está presente.",
    "required_without" => "É obrigatória a indicação de um valor para o campo :attribute quanto :values não está presente.",
    "required_without_all" => "É obrigatória a indicação de um valor para o campo :attribute quando nenhum dos :values está presente.",
    "same"             => "Os campos :attribute e :other deverão conter valores iguais.",
    "size"             => array(
        "numeric" => "O campo :attribute deverá conter o valor :size.",
        "file"    => "O campo :attribute deverá ter o tamanho de :size kilobytes.",
        "string"  => "O campo :attribute deverá conter :size caracteres.",
        "array"   => "O campo :attribute deve ter :size itens."
    ),
    "timezone"         => "O campo :attribute deverá ter um fuso horário válido.",
    "unique"           => "O valor indicado para o campo :attribute já se encontra registrado.",
    "url"              => "O formato do URL indicado para o campo :attribute é inválido.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    /*
    'custom' => array(
        'attribute-name' => array(
            'rule-name' => 'custom-message',
        ),
    ), */
    'custom' => array(

        //To photo
        'photo_name' => array(
            'required' => 'É obrigatório inserir um valor para o título da imagem.',
        ),
        'photo_imageAuthor' => array(
            'required' => 'É obrigatório inserir um valor para o autor da imagem.',
        ),
        'tags' => array(
            'required' => 'É obrigatório inserir um valor para o campo tag.',
        ),
        'photo_country' => array(
            'required' => 'É obrigatório escolher um valor para o país.',
        ),
        'photo_state' => array(
            'required' => 'É obrigatório escolher um valor para o estado.',
        ),
        'photo_city' => array(
            'required' => 'É obrigatório inserir um valor para a cidade.',
        ),
        'photo_authorization_checkbox' => array(
            'required' => 'É obrigatório selecionar a permissão da imagem.',
        ),
        'photo' => array(
            'required' => 'É obrigatório escolher uma imagem com tamanho máximo de 10MB',
            'max' => 'O tamanho máximo da imagem é 10MB',
            'mimes' => 'Os tipos de arquivos permitidos são: jpeg,jpg,png,gif.'
        ),        
        'photo_workDate' => array(
            'date_format' => 'O valor inserido não é válido.',
        ),
        'photo_imageDate' => array(
            'date_format' => 'O valor inserido não  respeita o formato dd/mm/yyyy.',
        ), 
        'photo_image_date' => array(
            'date_format' => 'O valor inserido não  respeita o formato dd/mm/yyyy.',
        ), 
        'photo_imageDate' => array(
            'regex' => 'O valor inserido não  respeita o formato dd/mm/yyyy.',
        ),
        
       
        'image_date' => array(
            'date_format' => 'O valor inserido não  respeita o formato dd/mm/yyyy.',
        ),
        'image_date' => array(
            'regex' => 'O valor inserido não respeita o formato dd/mm/yyyy.',
        ),
        'title' => array(
            'required' => 'É obrigatório inserir um valor para o título do album.',
        ),
        //To create and edit usuário.
        'name' => array(
            'required' => 'É obrigatório inserir seu nome completo.',
        ),
        
        'login' => array(
            'required' => 'É obrigatório inserir um valor para o login.',
        ),
        'email' => array(
            'required' => 'É obrigatório inserir seu e-mail.',
        ),
            
        'email' => array(
            'email' => 'O formato do e-mail é incorreto.',
        ),    

        'terms' => array(
            'required' => 'É obrigatório selecionar os termos de compromisso.',
        ), 
        'birthday' => array(
            'date_format' => 'O valor inserido deve ser dd/mm/yy',
        ),
        //To create user.
        'password' => array(
            'required' => 'A confirmação para o campo senha não coincide.',
        ),
        'password' => array(
            'confirmed' => 'A confirmação para o campo senha não coincide.',
        ),
               
        'user_password' => array(
            'confirmed' => 'A confirmação para o campo nova senha não coincide.',
        ),
        'user_password' => array(
            'regex' => 'A senha deve conter números, traços, underscore e letras minúsculas sem acentos, com no mínimo 6 e no máximo 10 caracteres',
        ),
        'user_password' => array(
            'alpha_num' => 'A senha deverá conter apenas letras e números.',
        ),        

         'tagsArea' => array(
            'required' => 'É obrigatório inserir um valor para tags.',
        ),
        'tagsTypologyArea' => array(
            'required' => 'É obrigatório inserir um valor para tags de tipologia.',
        ),        
        'tagsElementsArea' => array(
            'required' => 'É obrigatório inserir um valor para tags de elementos.',
        ),
        'tagsMaterialArea' => array(
            'required' => 'É obrigatório inserir um valor para tags de materiais.',
        ),
        'support' => array(
            'required' => 'É obrigatório inserir um valor para o campo suporte.',
        ),
        'tombo' => array(
            'required' => 'É obrigatório inserir um valor para o campo tombo.',
        ),
        'subject' => array(
            'required' => 'É obrigatório inserir um valor para o campo assunto.',
        ),
        
        'characterization' => array(
            'required' => 'É obrigatório inserir um valor para o campo de caracterização.',
        ),
        'name_institution' => array(
            'required' => 'É obrigatório inserir o nome da instituição.',
        ),
        'phone' => array(
            'regex' => 'O telefone deve conter números, traços ou parênteses, com mínimo 8 dígitos.',
        ),
        'site' => array(
            'url' => 'O site deve ter o formato do URL.',
        ),
        'video_youtube' => array(
            'regex' => 'O link de Youtube não tem o formato adequado.',
        ),
        'video_vimeo' => array(
            'regex' => 'O link de Vimeo não tem o formato adequado.',
        ),
        
    ), 



    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => array(),

);
