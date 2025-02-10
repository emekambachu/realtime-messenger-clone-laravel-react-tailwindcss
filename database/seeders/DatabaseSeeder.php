<?php

namespace Database\Seeders;

use App\Models\Group\Group;
use App\Models\Message\Message;
use App\Models\Role\Role;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Random\RandomException;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        $adminRole = Role::factory()->create([
            'name' => 'admin',
            'slug' => 'admin',
        ]);

        $userRole = Role::factory()->create([
            'name' => 'user',
            'slug' => 'user',
        ]);

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ])->roles()->attach($adminRole->id);

        User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ])->roles()->attach($adminRole->id);

        User::factory(10)->create()
            ->each(function ($user) use ($userRole) {
            $user->roles()->attach($userRole->id);
        });

        for($i = 0; $i < 5; $i++) {
            $group = Group::factory()->create([
                'owner_id' => 1,
            ]);

            $users = User::inRandomOrder()->limit(random_int(2, 5))->pluck('id')->toArray();
            $group->users()->attach(array_unique([1, ...$users]));
        }

        Message::factory(1000)->create();
        $messages = Message::whereNull('group_id')->orderBy('created_at')->get();

        $conversations = $messages->groupBy(function ($message) {
            return collect([$message->sender_id, $message->receiver_id])->sort()->implode('_');
        })->map(function ($groupedMessages) {
            return [
                'user_id1' => $groupedMessages->first()->sender_id,
                'user_id2' => $groupedMessages->first()->receiver_id,
                'last_message_id' => $groupedMessages->last()->id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
        })->values();


    }

}
