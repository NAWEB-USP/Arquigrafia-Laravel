<?php

use lib\utils\ActionUser;
use lib\date\Date;
use modules\institutions\models\Institution as Institution;
use modules\collaborative\models\Comment as Comment;
use modules\collaborative\models\Like as Like;
use modules\evaluations\models\Binomial as Binomial;
use modules\evaluations\models\Evaluation as Evaluation;
use modules\collaborative\models\Tag as Tag;

class PagesController extends BaseController {

/*
|--------------------------------------------------------------------------
| Default Page Controller
|--------------------------------------------------------------------------
 */

    protected $date;

    public function __construct(Date $date = null)
    {
        $this->date = $date ?: new Date;
        
    }


    public function home()
    { 
        if(Session::has('last_search'))
            Session::forget('last_search');

        if(Session::has('last_advanced_search'))
            Session::forget('last_advanced_search');

        $photos = Photo::orderByRaw("RAND()")->take(150)->get();

        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $user_or_visitor = "user";
        }else { 
            $user_or_visitor = "visitor";
            session_start();
            $user_id = session_id();
        } 
        if(Session::has('institutionId')){
            $institution = Institution::find(Session::get('institutionId'));
        }else{
            $institution = null;
        }
        $source_page = Request::header('referer');
        ActionUser::printHomePage($user_id, $source_page, $user_or_visitor);
        
        return View::make('index', ['photos' => $photos, 'institution' => $institution ]);
    }

    public function panel()
    {
        $photos = Photo::orderByRaw("RAND()")->take(150)->get();
        return View::make('api.panel', ['photos' => $photos]);
    }
    
    
    public function searchBinomial($binomial_id, $option, $value = null) {
        $bin = Binomial::find($binomial_id);
        $bi_opt = $option == 1 ? $bin->firstOption : $bin->secondOption;
        $photos = Evaluation::getPhotosByBinomial($bin, $option, $value);
        $value = $option == 1 ? 100 - $value : $value;
        return View::make('/search',
            [
                'tags' => [], 'photos' => $photos, 'query' => '',
                'city' => '', 'dateFilter' => [], 'binomial_option' => $bi_opt,
                'value' => $value
            ]);
    }

    

    public function search()
    {   
        if ( Input::has('bin') ) {
            return $this->searchBinomial(
                Input::get('bin'), Input::get('opt'), Input::get('val')
            );
        }
        $pageVisited = 0;    //null; 
        $needle = trim(Input::get("q"));
        $txtcity = Input::get("city"); 
        $type = Input::get("t"); 
        $dateFilter = null;
        $date = Input::get("d"); 
        $authorFilter = null;

        $url= null;
        $maxPage = 0;
        $photosTotal = 0;        
        $photosPages = null;
        $photosAll = 0;
        $pageRetrieved = 1;
        $haveSession = 0;
        $pageLinked = 0;
        
      
        if ( Input::has('type') ) {
                $authorFilter= Input::get('type');
        }

        if ($needle != "") {        
            if (!Input::has('pg') ){  
               Session::forget('CurrPage');
            }
            $tags = null;
            $allAuthors =  null;
            $tags = Tag::approximateTagSearch($needle);
            $allAuthors = Author::authorSearch($needle);                                                 
            if ($txtcity != "") {                  
                $photos = Photo::streetAndCitySearch($needle,$txtcity); 
            }elseif ((DateTime::createFromFormat('Y-m-d', $needle) !== FALSE || DateTime::createFromFormat('Y-m-d H:i:s', $needle) !== FALSE )&& !empty($type)) {
                $photos = Photo::dateSearch($needle,$type);
            }elseif (DateTime::createFromFormat('Y', $needle) !== FALSE) {
                $photos = Photo::yearSearch($needle,$dateFilter,$date);      
            } else {         
                $idUserList = User::userPhotosSearch($needle);
                $photos = Photo::searchPhoto($needle, $idUserList);                              
            }           
            // se houver uma tag exatamente como a busca, pegar todas as fotos dessa tag e juntar no painel
            $photos = Tag::tagSearch($needle, $photos);
            
            if($authorFilter != null){             
                $photos = Author::photoAuthorSearch($needle,$photos); 
            }else{
                $photos = Author::approxPhotoAuthorSearch($needle,$photos);                  
            }  

            $query = Institution::where(function($query) use($needle) {
                    $query->where('name', 'LIKE', '%'. $needle .'%');                      
                    $query->orWhere('acronym', '=',  $needle);
                });
            $institutions =  $query->get(); 
            
            foreach ($institutions as $institution) { 
                $byInstitution = $institution->photos;
                $photos = $photos->merge($byInstitution);
            }  
           
            $photosAll = $photos->count();

            if (Auth::check()) {
                $user_id = Auth::user()->id;
                $user_or_visitor = "user";
            }else { 
                $user_or_visitor = "visitor";
                session_start();
                $user_id = session_id();
            }
            $source_page = Request::header('referer');
            ActionUser::printSearch($user_id, $source_page, $needle, $user_or_visitor);
            
            if(Session::has('CurrPage') && Session::get('CurrPage')!= 1){ 
                $pageRetrieved = Session::get('CurrPage');  
                $haveSession = 1;   
            }
    
            if($photos->count() != 0){           
                $photosPages = Photo::paginatePhotosSearch($photos); 
                $photosTotal = $photosPages->getTotal();
                $maxPage = $photosPages->getLastPage();
                
                $url = URL::to('/search'. '/paginate/other/photos/');                 

                Session::put('last_search',
                ['tags' => $tags, 'photos' => $photos, 'query'=>$needle, 'city'=>$txtcity,
                'dateFilter'=>$dateFilter, 'authors' => $allAuthors,
                'url' => $url,'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
                'photosAll' => $photosAll, 'pageVisited'=> $pageVisited, 'typeSearch'=> 'simples' ]); 

                if(Input::has('pg') && $haveSession != 0 ) { 
                    $pageVisited = 1; 
                    Session::forget('CurrPage');
                }
            }else{                
                Session::forget('last_search');
                Session::forget('CurrPage');
                Session::forget('paginationSession');
            }            

            return View::make('/search',['tags' => $tags, 'photos' => $photosPages, 
                'query'=>$needle, 'city'=>$txtcity,'dateFilter'=>$dateFilter,
                'authors' => $allAuthors ,'needle' => $needle,'url' => $url,
                'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
                'photosAll' => $photosAll,'pageVisited'=> $pageVisited ]);
        }else {
            if(Session::has('last_search') && Input::has('pg')) { 
                return View::make('/search', Session::get('last_search'));
            }else{// busca vazia
                return View::make('/search',['tags' => [], 'photos' => [], 'query' => "", 'city'=>"",
                    'dateFilter'=>[], 'authors' =>[], 
                    'url'=>null,'photosTotal'=> 1,'maxPage' => 1, 'page' => 1, 'photosAll' => 0 , 'pageVisited'=> $pageVisited ]);
            }
        }
     
    }  

    private static function searchTags($t) {
        $query = Tag::where('name','=', $t);    
        $tagList = $query->get();    
        return $tagList->lists('id');
    }

    public function advancedSearch()
    { 
        $pageVisited = 0;
        $fields = Input::only( array(
            'name', 'description', 'city', 'state', 'country', 
            'imageAuthor', 'dataCriacao', 'dataUpload', 'workdate', 'district',
            'street', 'allowCommercialUses', 'allowModifications', 'institution_id'//,'workAuthor_area', $tags
        ));
        $fields = array_filter(array_map('trim', $fields));

        $url= null;
        $maxPage = 0;
        $photosTotal = 0;        
        $photosPages = null;
        $pageRetrieved = 1;
        $haveSession = 0;
        $pageLinked = 0;

        if(Session::has('CurrPage') && Session::get('CurrPage')!= 1){ 
             $pageRetrieved = Session::get('CurrPage');  
            $haveSession = 1;   
        }else{
            Session::put('CurrPage',1);
        }

        $institutions = Institution::institutionsList();

        if( count($fields) == 0 ) { // busca vazia                    
            if(Session::has('last_advanced_search')){ 
                return View::make('/advanced-search', Session::get('last_advanced_search'));
              
            }else {  // busca vazia
              
                return View::make('/advanced-search',
                    ['tags' => [], 'photos' => [], 'query' => "", 'binomials' => Binomial::all(),'authorsArea' => [],
                    'url'=> null,'photosTotal'=> 1,'maxPage' => 1, 'page' => $pageRetrieved,
                    'photosAll' => 0 , 'pageVisited'=> $pageVisited,'typeSearch'=> 'advance', 'institutions' => $institutions  ]);
            }
        }
        $binomials = array();
        if ( Input::has('binomial_check') ) {
            foreach (Binomial::all() as $binomial) {
                if ( Input::has('value-' . $binomial->id) ) {
                    $binomials[$binomial->id] = Input::get('value-' . $binomial->id);
                }
            }
        }
       
        //Adding search by tags
        //$tags = str_replace(array('\'', '"', '[', ']'), '', $fields['tags']);
        $tags = str_replace(array('\'', '"', '[', ']'), '', Input::get('tags'));
        $tags = Tag::transform($tags);

        //$authorsArea = str_replace(array('","'), '";"', $fields['workAuthor_area']);
        $authorsArea = str_replace(array('","'), '";"', Input::get('workAuthor_area'));    
        $authorsArea = str_replace(array('\'', '"', '[', ']'), '', $authorsArea);
        $authorsArea = Author::transform($authorsArea);

        $photos = Photo::search(array_except($fields, 'tags'), $tags, $binomials,$authorsArea);
         

        if( ($count_photos = $photos->count()) == 0 ) {
            $message = 'A busca não retornou resultados.';
         }elseif ( $count_photos == 1 ) {
            $message = 'Verifique abaixo a ' . $count_photos . ' imagem encontrada.';
         }else {
            $message = 'Verifique abaixo as ' . $count_photos . ' imagens encontradas.';
         }
        $tags = $tags == '' ? [] : $tags;

        /*if(Session::has('CurrPage') && Session::get('CurrPage')!= 1){ 
           echo $pageRetrieved = Session::get('CurrPage');  
            $haveSession = 1;   
        }else{
            Session::put('CurrPage',1);
        } */

        $photos = $photos->count() == 0 ? [] : $photos;
        //echo "=PGG=".Input::has('pg');
        if(Input::has('pg') && $haveSession != 0 ) { 
             $pageVisited = 1; 
             Session::forget('CurrPage');
        }
        
        $photosPages = Photo::paginatePhotosSearchAdvance($photos); 
        if($photosPages != null){
            $photosTotal = $photosPages->getTotal();
            $maxPage = $photosPages->getLastPage();
        } 
        $url = URL::to('/search/more'. '/paginate/other/photos/');

        Session::put('last_advanced_search', ['tags' => $tags, 'photos' => $photos,
            'binomials' => Binomial::all(), 'authorsArea' => $authorsArea, 'message' => $message,
            'url' => $url,'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
            'pageVisited'=> $pageVisited, 'typeSearch'=> 'advance', 'institutions' => $institutions
            ]);
       
        if(Session::has('last_advanced_search')){ 
            $lastSearchAdvance = Session::get('last_advanced_search');       
            $typeSearch = $lastSearchAdvance['typeSearch']; 
            $currentPage = $lastSearchAdvance['page'];               
        }
        

        return View::make('/advanced-search',
            ['tags' => $tags, 'photos' => $photosPages, //'photos' => $photos,
            'binomials' => Binomial::all(), 'authorsArea' => $authorsArea, 'message' => $message,
            'url' => $url,'photosTotal' => $photosTotal , 'maxPage' => $maxPage, 'page' => $pageRetrieved,
            'pageVisited'=> $pageVisited,'typeSearch'=> 'advance', 'institutions' => $institutions ]); 
    }

    public function paginatePhotosResult() {
        
        if(Session::has('last_search')){
            Log::info("si session fotos");
            $lastSearch = Session::get('last_search');
            $photos = $lastSearch['photos'];
        }
        $query = Input::has('q') ? Input::get('q') : ''; 
        
        $pagination = Photo::paginateAllPhotosSearch($photos,$query);       
        return $this->paginationResponseSearch($pagination, 'add');
    }

    public function paginatePhotosResultAdvance() {
       if(Session::has('last_advanced_search')){
            $lastSearchAdvance = Session::get('last_advanced_search');
            $photos = $lastSearchAdvance['photos'];
        }
            
        $query = Input::has('q') ? Input::get('q') : ''; 
        
        $pagination = Photo::paginateAllPhotosSearchAdvance($photos,$query);
        return $this->paginationResponseSearch($pagination, 'add');
    }

    private function paginationResponseSearch($photos, $type) {
        Log::info("paginateRsp");
        $count = $photos->getTotal();
        $perPage = $photos->getPerPage();
        $page = $photos->getCurrentPage();
       
        if(Session::has('CurrPage')){
           Log::info("pageGetWithSession".$page);
        }

        $fromPage = $photos->getFrom();        
        
        $toPage = $photos->getTo();
        
        Log::info("Response>> CurrPage=".$page." FromPage=".$fromPage." ToPage=".$toPage." count=".$count." perPage=".$perPage);
        
        Session::put('FromPage', $fromPage); 
        Session::put('ToPage', $toPage);      
        Session::put('CurrPage', $page); 
       
        $response = [];
        $response['content'] = View::make('photos.includes.searchResult_include')
            ->with(['photos' => $photos, 'page' => $page, 'type' => $type])
            ->render();
        $response['maxPage'] = $photos->getLastPage();
        $response['empty'] = ($photos->count() == 0);
        $response['count'] = $count;
       

        return Response::json($response);
    }

    
}
