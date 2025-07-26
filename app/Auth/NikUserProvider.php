<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class NikUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || (count($credentials) === 1 && array_key_exists('password', $credentials))) {
            return;
        }

        // Build query to find user by NIK instead of email
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if ($key === 'nik') {
                $query->where('nik', $value);
            } elseif (! str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }
}
