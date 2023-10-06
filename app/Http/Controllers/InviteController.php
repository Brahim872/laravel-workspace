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
            'email' => 'required|array', // Ensure 'emails' is an array
            'email.*' => 'email',
        ];
    }


    public function store(Request $request, $workspace)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }

            $request['workspace'] = $workspace;
            foreach ($request->email as $_email) {

                $user = User::where('email', '=', $_email)->first();

                if ($user && $user->hasWorkspaces()->count() != 0) {

                    return returnWarningsResponse(['email' => [
                        $user->email . ': already has a workspace',
                        'We didn\'t send any invitation. Resolve problem first'],
                    ]);
                } else {
                    $invitations[] = new Invite([
                        'email' => $_email,
                        'workspace' => $request['workspace'],
                    ]);
                }
            }

            if ($this->checkInvitation($invitations)[1]) {

                return returnWarningsResponse([
                    'email' => [
                        'Already send link to :' . $this->checkInvitation($invitations)[0],
                        'We didn\'t send any invitation. Resolve problem first'
                    ],
                ]);
            };


            $result = $this->generateNotification($invitations);


            if ($result) {
                return returnResponseJson(['message' => 'The link invitation has been sent'], 200);
            }

        } catch (\Exception $e) {
            return returnCatchException($e);
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
            return returnCatchException($e);
        }
    }

    private function checkInvitation($invitations)
    {
        $emails = "";
        $error = false;

        foreach ($invitations as $invitation) {


            if ($invitation->hasAleardyInvitation()) {
                $emails .= $invitation->email . ',';
                $error = true;
            }

        }
        return [substr_replace($emails, "", -1), $error];
    }

    private function generateNotification($invitations)
    {

        foreach ($invitations as $invitation) {

            $invitation->generateInvitationToken();
            $invitation->save();
            $link = $invitation->getLink();

            $invitation->notify(new InvitationEmail($link));
        }
        return true;

    }


}
