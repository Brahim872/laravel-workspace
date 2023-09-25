<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use App\Models\Workspace;
use App\Notifications\InvitationEmail;
use App\Traits\Models\HasWorkspace;
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
                return returnResponseJson($validator->messages(), Response::HTTP_BAD_REQUEST);
            }

            $workspaceRequest = Workspace::find($workspace);

            if (!returnUserApi()->hasWorkspace($workspaceRequest->id)) {
                return returnResponseJson([
                    'message' => 'This workspace does not exist '
                ], Response::HTTP_BAD_REQUEST);
            }

            $userWorkspace = $workspaceRequest ? $workspaceRequest->users((string)User::TYPE_USER['0'])->first() : null;

            if (!$workspaceRequest || !$userWorkspace) {
                return returnResponseJson([
                    'message' => 'This workspace does not exist '
                ], Response::HTTP_BAD_REQUEST);
            }
            $request['workspace'] = $workspace;
            foreach ($request->email as $_email) {

                $user = User::where('email', '=', $_email)->first();

                if ($user && $user->hasWorkspaces()->count()!=0) {

                    return returnResponseJson(['message' => $user->email.': already has a workspace',
                        'note' => 'We didn\'t send any invitation. Resolve problem first'], Response::HTTP_BAD_REQUEST);

                } else {
                    $invitations[] = new Invite([
                        'email' => $_email,
                        'workspace' => $request['workspace'],
                    ]);
                }
            }

            if ($this->checkInvitation($invitations)[1]) {
                return returnResponseJson(['message' => 'Already send link to :' . $this->checkInvitation($invitations)[0],
                    'note' => 'We didn\'t send any invitation. Resolve problem first'], Response::HTTP_BAD_REQUEST);
            };


            $result = $this->generateNotification($invitations);


            if ($result) {
                return returnResponseJson(['message' => 'The link invitation has been sent'], 200);
            }

        } catch (\Exception $e) {
            return returnResponseJson([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
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
