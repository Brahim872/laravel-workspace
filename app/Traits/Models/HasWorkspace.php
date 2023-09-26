<?php

namespace App\Traits\Models;

use App\Models\Workspace;

trait HasWorkspace
{


    /**
     * Determine if the model has (one of) the given role(s).
     *
     * @param string|null $guard
     * @return bool
     */
    public function hasWorkspace($workspaces, string $guard = null): bool
    {

        $this->loadMissing('workspaces');
        if (is_string($workspaces) && false !== strpos($workspaces, '|')) {
            $workspaces = $this->convertToArray($workspaces);
        }

        if (is_int($workspaces)) {
            $key = (new Workspace())->getKeyName();
            return $this->workspaces->where('id','=',$workspaces)->contains($key, $workspaces);
        }

        if (is_array($workspaces)) {
            foreach ($workspaces as $workspace) {
                if ($this->hasRole($workspace, $guard)) {
                    return true;
                }
            }

            return false;
        }

        return $workspaces->intersect($guard ? $this->workspaces->where('guard_name', $guard) : $this->workspaces)->isNotEmpty();
    }


    /**
     * Determine if the model has any of the given role(s).
     **
     * @param array $workspaces
     * @return bool
     */
    public function hasAnyWorkspaces(...$workspaces): bool
    {
        return $this->hasWorkspace($workspaces);
    }

    protected function convertToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (! in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }

}
