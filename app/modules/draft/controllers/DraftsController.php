<?php 
namespace modules\draft\controllers;
use Photo;

class DraftController extends BaseController {

  public function paginateDrafts() {
    $institution = Session::get('institutionId');
    $perPage = Input::get('perPage') ?: 50;
    $drafts = Photo::withInstitution($institution)->onlyDrafts()->paginate($perPage);
    $view = View::make('draft_list')
      ->with([ 'drafts' => $drafts ])->render();
    return Response::json([
        'view' => $view,
        'current_page' => $drafts->getCurrentPage(),
        'last_page' => $drafts->getLastPage(),
        'total_items' => $drafts->getTotal()
    ]);
  }

  public function listDrafts() {
    $institution = Session::get('institutionId');
    if ( is_null($institution) ) {
      return Redirect::to('/');
    }
    $drafts = Photo::with('tags')->withInstitution($institution)
      ->onlyDrafts()->paginate(100);
    return View::make('show')->with([
      'drafts' => $drafts
    ]);
  }

  public function getDraft($id) { 
    $photo = Photo::onlyDrafts()->find($id);
    if ( is_null($photo) ) {
      return Redirect::to('/');
    }
    $input = array(
      'dates' => true,
      'draft_id' => $id,
      'support' => $photo->support,
      'tombo' => $photo->tombo,
      'subject' => $photo->subject,
      'characterization' => $photo->characterization,
      'photo_name' => $photo->name,
      'description' => $photo->description,
      'country' => $photo->country,
      'state' => $photo->state,
      'city' => $photo->city,
      'street' => $photo->street,
      'observation' => $photo->observation,
      'allowCommercialUses' => $photo->allowCommercialUses,
      'allowModifications' => $photo->allowModifications,
      'hygieneDate' => $this->date->formatDatePortugues($photo->hygieneDate),
      'backupDate' => $this->date->formatDatePortugues($photo->backupDate),
      'imageAuthor' => $photo->imageAuthor,
      'observation' => $photo->observation,
      'authorized' => $photo->authorized,
      'new_album-name' => $photo->albums()->first()
    );
    if ( $photo->tags->count() ) {
      $input['tagsArea'] = implode(',', $photo->tags->lists('name'));
    }
    if ( $photo->authors->count() ) {
     $input['work_authors'] = implode(';', $photo->authors->lists('name')); 
    }
    if ( $photo->workDateType == 'year' ) {
      $input['workDate'] = $photo->workdate;
    } elseif ( $photo->workDateType == 'decade' ) {
      $input['decade_select'] = $photo->workdate;
    } elseif ( $photo->workDateType == 'century' ) {
      $input['century'] = $photo->workdate;
    }
    if ( $photo->imageDateType == 'date' ) {
      $input['image_date'] = $this->date->formatDatePortugues($photo->dataCriacao);
    } elseif ( $photo->imageDateType == 'decade' ) {
      $input['decade_select_image'] = $photo->dataCriacao;
    } elseif ( $photo->imageDateType == 'century' ) {
      $input['century_image'] = $photo->dataCriacao;
    }
    return Redirect::to('/institutions/form/upload')
      ->withInput($input);
  }

  public function deleteDraft() {
    $id = Input::get('draft');
    $last_page = Input::get('last_page');
    $perPage = Input::get('per_page');
    $institution = Session::get('institutionId');
    $draft = Photo::withInstitution($institution)->onlyDrafts()->find($id);
    if ( !is_null($draft) ) {
      $draft->deleted_at = $draft->freshTimestampString();
      $draft->save();
      $drafts = Photo::withInstitution($institution)->onlyDrafts()->paginate($perPage);
      if ( $last_page > $drafts->getLastPage()) {
        return Response::json([
          'refresh' => true,
          'view' => View::make('draft_list')->with(compact('drafts'))->render(),
          'current_page' => $drafts->getCurrentPage(),
          'last_page' => $drafts->getLastPage(),
          'total_items' => $drafts->getTotal()
        ]);
      } else {
        return Response::json([
            'refresh' => false,
            'total_items' => $drafts->getTotal()
          ]);
      }
    }
    return Response::json(false);
  }
}