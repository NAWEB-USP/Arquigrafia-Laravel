<?php 
namespace lib\api\controllers;
use lib\utils\ActionUser;

class APIEvaluationController extends \BaseController {

	public function retrieveEvaluation($photoId, $userId) {
		$binomials = \Binomial::all()->keyBy('id');
		$evaluations =  \Evaluation::where("user_id",
        $userId)->where("photo_id", $photoId)->orderBy("binomial_id", "asc")->get();
        $evaluations = $evaluations->keyBy('binomial_id');
        $result = [];
        foreach ($binomials as $binomial) {
        	if (isset($evaluations[$binomial->id])) {
				array_push($result, ["id" => $binomial->id, "firstOption" => $binomial->firstOption, "secondOption" => $binomial->secondOption, "value" => $evaluations[$binomial->id]->evaluationPosition, "knownArchitecture" => $evaluations[$binomial->id]->knownArchitecture, "areArchitecture" => $evaluations[$binomial->id]->areArchitecture]);
        	}
        	else {
        		array_push($result, ["id" => $binomial->id, "firstOption" => $binomial->firstOption, "secondOption" => $binomial->secondOption, "value" => $binomial->defaultValue, "knownArchitecture" => 'no', "areArchitecture" => 'no']);
        	}
        }
        return \Response::json($result);
	}

	public function storeEvaluation($photoId, $userId) {
		$input = \Input::all();
		$input = $input["params"]["data"];

		$evaluations =  \Evaluation::where("user_id",
        $userId)->where("photo_id", $photoId)->orderBy("binomial_id", "asc")->get();
        $binomials = \Binomial::all();
        $evaluation_sentence = "";
        if ($evaluations->isEmpty()) {
        	foreach ($binomials as $binomial) {
        		$newEvaluation = \Evaluation::create([
            		'photo_id'=> $photoId,
            		'evaluationPosition'=> $input[$binomial->id],
            		'binomial_id'=> $binomial->id,
            		'user_id'=> $userId,
            		'knownArchitecture'=>$input["knownArchitecture"],
            		'areArchitecture'=>$input["areArchitecture"]
          		]);
                $evaluation_sentence = $evaluation_sentence . $binomial->firstOption . "-" . $binomial->secondOption . ": " . $input[$binomial->id] . ", ";
         	}

            /* Registro de logs */
            ActionUser::printEvaluation($userId, $photoId, 'mobile', 'user', 'Inseriu', $evaluation_sentence);
        }
        else {
        	foreach ($evaluations as $evaluation) {
        		$evaluation->knownArchitecture = $input["knownArchitecture"];
          		$evaluation->areArchitecture = $input["areArchitecture"];
          		$evaluation->evaluationPosition = $input[$evaluation->binomial_id];
          		$evaluation->save();
                $evaluation_sentence = $evaluation_sentence . \Binomial::find($evaluation->binomial_id)->firstOption . "-" . \Binomial::find($evaluation->binomial_id)->secondOption . ": " . $input[$evaluation->binomial_id] . ", ";
        	}

            /* Registro de logs */
            ActionUser::printEvaluation($userId, $photoId, 'mobile', 'user', 'Editou', $evaluation_sentence);
        }
		\Response::json($input);
	}

    public function averageEvaluationValues($photoId, $userId) {
        $result["binomials"] = \Binomial::all()->keyBy('id');
        $result["average"] = \Evaluation::average($photoId);
        $evaluations =  \Evaluation::where("user_id",
        $userId)->where("photo_id", $photoId)->orderBy("binomial_id", "asc")->get();
        $evaluations = $evaluations->keyBy('binomial_id');
        $result["user_evaluation"] = $evaluations;
        return \Response::json($result);
    }
}