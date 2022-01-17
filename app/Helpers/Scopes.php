<?php


namespace App\Helpers;


class Scopes
{

    public static function getScopes(string $x_scopes): array
    {

        $action_areas = static::getActionArea($x_scopes);
        foreach ($action_areas as $action_area) {
            if (static::ActionIsValid($action_area)) {
                $explode = explode('.', $action_area);
                $scopes['action_areas'][$explode[0]][] = $explode[1];
            }
        }
        return $scopes;
    }

    private static function ActionIsValid(string $action): bool
    {
        return preg_match('/\w+\.\d+/', $action);
    }

    private static function getActionArea(string $x_scopes): array
    {
        preg_match_all('/action_area:(\w+\.\d+)/', $x_scopes, $matches);
        return $matches[1];
    }

}
