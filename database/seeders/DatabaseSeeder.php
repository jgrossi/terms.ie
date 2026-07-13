<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'junior@grossi.ie'],
            ['email_verified_at' => now()],
        );

        // A sample client ready to be sent an agreement.
        $user->clients()->firstOrCreate(
            ['email' => 'aoife.byrne@example.ie'],
            ['name' => 'Aoife Byrne'],
        );

        // A sample term with a first version, ready to assign to a client.
        $term = $user->terms()->firstOrCreate(
            ['name' => 'Photography Session Agreement'],
            ['body' => "Hello {{CLIENT_NAME}},\n\nThis agreement covers the photography session\nscheduled for {{SESSION_DATE}}.\n\n1. A 50% deposit is required to secure the date.\n2. Final edited photos are delivered within 14 days via\n   a private online gallery.\n3. The session fee is non-refundable.\n\nConfirm by signing below.\n\n{{CLIENT_EMAIL}}"],
        );

        $term->versions()->firstOrCreate(
            ['version' => 1],
            ['body' => $term->body],
        );
    }
}
