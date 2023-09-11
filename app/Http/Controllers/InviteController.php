<?php

namespace App\Http\Controllers;

use App\Models\Invite;
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

    public function store(Request $request, Workspace $workspace)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {
                return returnResponseJson($validator->messages(), Response::HTTP_BAD_REQUEST);
            }


            $request['workspace'] = $workspace->id;

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

            $invite = Invite::where('email', '=', $request->email)
                ->where('token', '=', $request->token)
                ->where('workspace', '=', $request->workspace)
                ->first();

            $workspace = Workspace::find($request->workspace);

            if (!$invite) {
                return returnResponseJson(['message' => 'That credential are not compatible with data'], 500);
            }

            $invite->update(['accepted_at'=>Carbon::now()]);


            $workspace->users()->detach();

            $workspace->users()->attach(returnUserApi()->id,[
                'type_user' => 1 // = admin
            ]);


            return returnResponseJson(['message' => 'The link invitation has been sent'], 200);

        } catch (\Exception $e) {
            return returnResponseJson(['message', $e->getMessage()], 500);
        }
    }


}
