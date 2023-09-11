<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\Workspace;
use App\Notifications\InvitationEmail;
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
                    'message'=>'Already sent invitation to this email'
                ], Response::HTTP_BAD_REQUEST);
            }


            $invitation->generateInvitationToken();
            $invitation->save();
            $link = $invitation->getLink();

            $invitation->notify(new InvitationEmail($link));


            if ($invitation) {
                return returnResponseJson(['message', 'The link invitation has been sent'], 200);
            }
        } catch (\Exception $e) {
            return returnResponseJson(['message', $e->getMessage()], 500);
        }
    }


}
