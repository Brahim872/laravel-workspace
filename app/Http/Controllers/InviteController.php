<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use App\Models\Workspace;
use App\Notifications\InvitationEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class InviteController extends Controller
{
    //

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }

    public function store(Request $request, $workspace)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {
                return returnResponseJson($validator->messages(), Response::HTTP_BAD_REQUEST);
            }

            $workspaceRequest = Workspace::find($workspace);

            $userWorkspace = $workspaceRequest?$workspaceRequest->users((string)User::TYPE_USER['0'])->first():null;

            if(!$workspaceRequest  || !$userWorkspace){
                return returnResponseJson([
                    'message' => 'This workspace does not exist '
                ], Response::HTTP_BAD_REQUEST);
            }
            $request['workspace'] = $workspace;



            $invitation = new Invite($request->all());

            if ($invitation->hasAleardyInvitation()) {
                return returnResponseJson([
                    'message' => 'Already sent invitation to this email'
                ], Response::HTTP_BAD_REQUEST);
            }


            $invitation->generateInvitationToken();
            $invitation->save();
            $link = $invitation->getLink();

            $invitation->notify(new InvitationEmail($link));


            if ($invitation) {
                return returnResponseJson(['message' => 'The link invitation has been sent'], 200);
            }

        } catch (\Exception $e) {
            return returnResponseJson(['message', $e->getMessage()], 500);
        }
    }

    public function accept(Request $request)
    {
        try {

            $me = auth('sanctum')->user();


            $invite = Invite::where('email', '=', $me->email)
                ->where('token', '=', $request->token)
                ->where('workspace', '=', $request->workspace)
                ->first();


            $workspace = Workspace::find($request->workspace);

            if (!$invite) {
                return returnResponseJson(['message' => 'That credential are not compatible with data'], Response::HTTP_BAD_REQUEST);
            }

            if ($invite->accepted_at) {
                return returnResponseJson(['message' => 'this invitation has been accepted'], Response::HTTP_ACCEPTED);
            }


            $invite->update(['accepted_at' => Carbon::now()]);
            $me->update(['current_workspace' => $workspace->id]);


            $me->workspaces()->detach();

            $me->workspaces()->attach($workspace->id, [
                'type_user' => 1 // = invite
            ]);


            return returnResponseJson(['message' => 'accept successful'], 200);

        } catch (\Exception $e) {
            return returnResponseJson([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }


}
