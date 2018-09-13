<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User;
use App\Teams;
use App\UserTeams; 
use Validator;
use Auth;
class TeamsControllers extends Controller {
	public $successStatus = 200;
    
 
    
    /** 
     * Create Team 
     * 
     * @return \Illuminate\Http\Response 
    */
    public function createTeams(Request $request){
    	$validator = Validator::make($request->all(), [ 
            'name' => 'required|string', 
        ]);

        if($validator->fails()){
        	return response()->json(['error'=>$validator->errors()], 401);
        }
        
        $teaams = new Teams();
        $teaams->name = request('name');
        if($teaams->save()){
        	return response()->json(["error" => false, 'message'=>'Team created'], $this-> successStatus);
        }else{
        	return response()->json(["error" => true, 'message'=>'failed to create team'], 401);
        }
    }



    /** 
     * update Team api 
     * 
     * @return \Illuminate\Http\Response 
    */
    public function updateTeam(Request $request){
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|string', 
            'team_id' => 'required|exists:teams,id'
        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $teaams =Teams::find(request('team_id'));
        $teaams->name = request('name');
        if($teaams->save()){
            return response()->json(["error" => false, 'message'=>'Team Updated'], $this-> successStatus);
        }else{
            return response()->json(["error" => true, 'message'=>'failed to update team'], 401);
        }
    }


    /** 
     * Delete Team api 
     * 
     * @return \Illuminate\Http\Response 
    */
    public function deleteTeam(Request $request){
         $validator = Validator::make($request->all(), [ 
            'team_id' => 'required|exists:teams,id',
        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $teaams =Teams::find(request('team_id'));
        if($teaams->delete()){
            return response()->json(["error" => false, 'message'=>'Team deleted'], $this-> successStatus);
        }else{
            return response()->json(["error" => true, 'message'=>'failed to detete team'], 401);
        }
    }

    /** 
     * Read Team api 
     * 
     * @return \Illuminate\Http\Response 
    */

    public function getTeams(){
      $teams = Teams::select('id','name','totalMembers')->paginate(15);
      return response()->json($teams,$this-> successStatus) ;
    }


    /** 
     * add user Team api 
     * 
     * @return \Illuminate\Http\Response 
    */
      public function addUserTOTeam(Request $request){
         $validator = Validator::make($request->all(), [ 
            'user_id' => 'required|exists:users,id', 
            'team_id' => 'required|exists:teams,id'
        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }


        //check if user already belong to that team
        $isExistCount =UserTeams::where('user_id', '=', request('user_id'))->where('teams_id', '=', request('team_id'))->count();
        if($isExistCount > 0){
            return response()->json(['error' => true, 'message' => 'User alreade belong to that team']);
        }else{
            //User is not found in the said team so add user to the team
           
            $userteam = UserTeams::create( array_merge($request->all(), ['teams_id' => request('team_id')]));
            $added = Teams::find(request('team_id'))->firs();
            return response()->json(['error'=> false, 'message' => 'User added to ' . $added->name. ' team'], $this-> successStatus);
        }

      }

    /** 
     * get user Team api 
     * 
     * @return \Illuminate\Http\Response 
    */
      function getUserTeam(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required|exists:users,id', 
        ]);
         
        //if validations failed
        if($validator->fails()){
            return response()->json(['error' => true,'message'=>$validator->errors()], 401);
        }
        
        $curUsersTeams = UserTeams::where('user_id', '=', request('user_id'))->with('team')->paginate();

        if($curUsersTeams->isEmpty()){
            return response()->json(['error' => true, 'message' => 'This user does not belong to any team'], 203);
        }else{
            return response()->json($curUsersTeams, $this-> successStatus);
        }
    }


    //************************************USers Action for self******************************
    
    /** 
     * get my Team api 
     * 
     * @return \Illuminate\Http\Response 
    */

   public function getMyTeam(){
        $user = Auth::user();
        $curUsersTeams = UserTeams::where('user_id', '=', $user->id )->with('team')->paginate();

        if($curUsersTeams->isEmpty()){
            return response()->json(['error' => false, 'message' => 'You dont belong to any team'], 202);
        }else{
            return response()->json($curUsersTeams, $this-> successStatus);
        }
    }

    public function addMyseltToTeam(Request $request){
         $validator = Validator::make($request->all(), [ 
            'team_id' => 'required|exists:teams,id'
        ]);

        if($validator->fails()){
            return response()->json([ 'error'=> true ,'errors'=>$validator->errors()], 413);
        }

        $user = Auth::user();
        $curUsersTeams = UserTeams::where('user_id', '=', $user->id)->where('teams_id', '=', request('team_id'))->first();

        if($curUsersTeams != null){
            return response()->json(['error' => true, 'message' => 'You already belong to this team'], 203);
        }else{
            $userteam = new UserTeams();
            $userteam->teams_id = request('team_id');
            $userteam->user_id = $user->id;
            $saved = $userteam->save();
            return response()->json(['error' => false, 'message' => 'Joined successfully'], $this-> successStatus);
        }
    }

    public function removeMeFromteam(Request $request){
      $validator = Validator::make($request->all(), [ 
            'team_id' => 'required|exists:teams,id'
        ]);

        if($validator->fails()){
            return response()->json([ 'error'=> true ,'errors'=>$validator->errors()], 413);
        }
         $user = Auth::user();
         $curUsersTeams = UserTeams::where('user_id', '=', $user->id)->where('teams_id', '=', request('team_id'))->first();
          if($curUsersTeams != null){
             $curUsersTeams->delete();
            return response()->json(['error' => false, 'message' => 'successfully Removed'], $this-> successStatus);
        }else{
           
            return response()->json(['error' => true, 'message' => 'You dont belong to this team'], 203);
        }

    }

    



}