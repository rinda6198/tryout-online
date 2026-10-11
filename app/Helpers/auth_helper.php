<?php

if (! function_exists('current_user')) {
    /**
     * Get current logged-in user's session data array.
     */
    function current_user(): ?array
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            return null;
        }

        return [
            'id'       => $session->get('user_id'),
            'name'     => $session->get('name'),
            'username' => $session->get('username'),
            'email'    => $session->get('email'),
            'role_id'  => $session->get('role_id'),
            'role_slug'=> $session->get('role_slug'),
            'role_name'=> $session->get('role_name'),
            'photo'    => $session->get('photo'),
        ];
    }
}

if (! function_exists('has_role')) {
    /**
     * Check whether current user has a given role slug (or one of an array of slugs)
     */
    function has_role($roles): bool
    {
        $role = session()->get('role_slug');

        if (is_array($roles)) {
            return in_array($role, $roles, true);
        }

        return $role === $roles;
    }
}