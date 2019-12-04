<?php
namespace modules\collaborative\controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use modules\collaborative\models\Tag;
use modules\institutions\models\Institution as Institution;
use Photo;
use DB;
use Illuminate\Support\Facades\Validator;

class TagsController extends \BaseController {

	public function indexAll()
	{
		$tags = Tag::all();
		return $tags;
	}

	public function index()
	{
        //checks if the user is an admin. If not, puts them back in the home page
        $user = \Auth::user();
        if ($user->admin == False) 
        {
            return \Redirect::to('/home');
        }

        //gets the 'acervo' tags to display them in order
        $tags_acervo = Tag::where('type', 'Acervo')->orderBy('name')->get();
        $tags_usuario = Tag::where('type', 'Livre')->orderBy('name')->get();
        return \View::make('tag.index', ['tags_a' => $tags_acervo, 'tags_u' => $tags_usuario]);
	}

	public function refreshCount() {
		$photos = Photo::all();
		echo "processando...\r\n";
		\DB::update('update tags set count = 0');
		foreach ($photos as $photo) {
			$photo_tags = $photo->tags;
			echo "Foto de ID = " . $photo->id . ":\r\n";
			foreach ($photo_tags as $tag) {
				$tag->count = $tag->count + 1;
				$tag->save();
				echo "Tag atualizada: " . $tag->name . "\r\n";
			}
		}
		$deleted = Photo::onlyTrashed()->get();
		foreach ($deleted as $photo) {
			\DB::table('tag_assignments')->where('photo_id', '=', $photo->id)->delete();
		}
	}

	public function create() 
    {
        $user = \Auth::user();
        if ($user->admin == False) 
        {
            return \Redirect::to('/home');
        }

        return \View::make('tag.create');
    }

    public function store()
    {
        //first, we will receive the form from the tag.create view
        $input = \Input::all();

        $validator = \Validator::make(
            $input,
            array(
                'name' => 'required', 
                'description' => 'sometimes', 
                'cat_1' => 'sometimes',
                'cat_2' => 'sometimes',
                'cat_3' => 'sometimes',
                'cat_4' => 'sometimes',
                'cat_5' => 'sometimes',
                'cat_6' => 'sometimes',
                'cat_7' => 'sometimes',
                'cat_8' => 'sometimes',
                'cat_9' => 'sometimes',
                 'is' => 'sometimes|exists:tags,name'
            )
        );

        //for now, inputing wrong data resets the form
        if ($validator->fails())
        {
            return \View::make('tag.create');
        }

        //create the new tag and add it to the database
        $tag = new Tag;
        $tag->name = $input['name'];
        $tag->description = $input['description'];
        $tag->cat_1 = $input['cat_1'];
        $tag->cat_2 = $input['cat_2'];
        $tag->cat_3 = $input['cat_3'];
        $tag->cat_4 = $input['cat_4'];
        $tag->cat_5 = $input['cat_5'];
        $tag->cat_6 = $input['cat_6'];
        $tag->cat_7 = $input['cat_7'];
        $tag->cat_8 = $input['cat_8'];
        $tag->cat_9 = $input['cat_9'];
        $tag->type = 'Acervo'; //only 'Acervo' tags can be edited and created by admins
        $equiv = Tag::where('name', $input['is'])->first();
        $tag->is = 0;
        if ($equiv != null) { $tag->is = $equiv->id;}
        $tag->save();

        return \Redirect::to('/tags');
    }

    public function edit($id) 
    {
        $user = \Auth::user();
        if ($user->admin == False) 
        {
            return \Redirect::to('/home');
        }
        
        $tag = Tag::find($id);
        $equiv = Tag::find($tag->is);
        $equivName = '';
        if($equiv != null) {$equivName = $equiv->name;}
        return \View::make('tag.edit', ['tagVec' => [$tag, $equivName]]);
    }

    public function update($id)
    {
        $input = \Input::all();

        $validator = \Validator::make(
            $input,
            array(
                'name' => 'required', 
                'description' => 'sometimes', 
                'cat_1' => 'sometimes',
                'cat_2' => 'sometimes',
                'cat_3' => 'sometimes',
                'cat_4' => 'sometimes',
                'cat_5' => 'sometimes',
                'cat_6' => 'sometimes',
                'cat_7' => 'sometimes',
                'cat_8' => 'sometimes',
                'cat_9' => 'sometimes',
                'is' => 'sometimes|exists:tags,name'
            )
        );

        //for now, inputting wrong data resets the form
        if ($validator->fails())
        {
            return TagsController::edit($id);
        }
        
        //finds the tag and edits it accordingly
        $tag = Tag::find($id);  
        $tag->name = $input['name'];
        $tag->description = $input['description'];
        $tag->cat_1 = $input['cat_1'];
        $tag->cat_2 = $input['cat_2'];
        $tag->cat_3 = $input['cat_3'];
        $tag->cat_4 = $input['cat_4'];
        $tag->cat_5 = $input['cat_5'];
        $tag->cat_6 = $input['cat_6'];
        $tag->cat_7 = $input['cat_7'];
        $tag->cat_8 = $input['cat_8'];
        $tag->cat_9 = $input['cat_9'];
        $tag->type = 'Acervo';
        $equiv = Tag::where('name', $input['is'])->first();
        if ($equiv != null) { $tag->is = $equiv->id;}
        else $tag->is = 0;
        $tag->save();

        return \Redirect::to('/tags');
    }

    public function destroy($id)
    {
        $user = \Auth::user();
        if ($user->admin == False) 
        {
            return \Redirect::to('/home');
        }
        
        TagsController::purge($id);
        return \Redirect::to('/tags');
    }

    public function purge($id) //actually destroys tags
    {
        $tag = Tag::find($id);
        foreach(Tag::where('is', $id)->get() as $dependancies) //'recursively' destroys all equivalent terms
        {
            TagsController::purge($dependancies->id);
        }
        $tag->delete();
        return;
    }

}
