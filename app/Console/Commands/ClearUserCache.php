<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearUserCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:clear-cache {user_id?} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear user cache entries. Use --all to clear all users or specify user_id for specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->clearAllUsersCache();
        } elseif ($userId = $this->argument('user_id')) {
            $this->clearSpecificUserCache($userId);
        } else {
            $this->askForUserAction();
        }
    }

    /**
     * Clear cache for all users.
     */
    private function clearAllUsersCache(): void
    {
        $this->info('Clearing cache for all users...');
        
        $users = User::all();
        $count = 0;
        
        foreach ($users as $user) {
            $user->clearUserCache();
            $count++;
        }
        
        $this->info("Cache cleared for {$count} users.");
    }

    /**
     * Clear cache for a specific user.
     */
    private function clearSpecificUserCache(string $userId): void
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }
        
        $user->clearUserCache();
        $this->info("Cache cleared for user: {$user->name} (ID: {$userId})");
    }

    /**
     * Ask user for action when no arguments provided.
     */
    private function askForUserAction(): void
    {
        $choice = $this->choice(
            'What would you like to do?',
            ['Clear cache for all users', 'Clear cache for specific user', 'Show cache statistics'],
            0
        );

        match ($choice) {
            'Clear cache for all users' => $this->clearAllUsersCache(),
            'Clear cache for specific user' => $this->handleSpecificUser(),
            'Show cache statistics' => $this->showCacheStats(),
        };
    }

    /**
     * Handle specific user selection.
     */
    private function handleSpecificUser(): void
    {
        $userId = $this->ask('Enter user ID');
        $this->clearSpecificUserCache($userId);
    }

    /**
     * Show cache statistics.
     */
    private function showCacheStats(): void
    {
        $this->info('User Cache Statistics:');
        
        $users = User::take(10)->get();
        
        $this->table(
            ['User ID', 'Name', 'Avatar Cached', 'Roles Cached', 'Permissions Cached'],
            $users->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    Cache::has("user_avatar_{$user->id}") ? '✓' : '✗',
                    Cache::has("user_roles_{$user->id}") ? '✓' : '✗',
                    Cache::has("user_permissions_{$user->id}") ? '✓' : '✗',
                ];
            })
        );
    }
}
