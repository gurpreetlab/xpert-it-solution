<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ContactMessages\Index as ContactMessagesIndex;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContactMessagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_guests_cannot_access_contact_messages_index(): void
    {
        $response = $this->get(route('dashboard.contact-messages.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_customers_cannot_access_contact_messages_index(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($customer);

        $response = $this->get(route('dashboard.contact-messages.index'));

        // Converts Spatie UnauthorizedException to 404 per bootstrap/app.php exceptions configuration
        $response->assertStatus(404);
    }

    public function test_super_admins_can_access_contact_messages_index(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $this->actingAs($admin);

        $response = $this->get(route('dashboard.contact-messages.index'));
        $response->assertOk();
    }

    public function test_super_admins_can_view_and_delete_messages(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $message = ContactMessage::create([
            'name' => 'Corporate Client',
            'email' => 'client@company.com',
            'subject' => 'IT Infrastructure Quote Request',
            'message' => 'Please provide a quote for 50 networking routers.',
        ]);

        $this->actingAs($admin);

        // Test Livewire component
        Livewire::test(ContactMessagesIndex::class)
            ->assertSee($message->name)
            ->assertSee($message->subject)
            ->call('viewMessage', $message->id)
            ->assertSet('showMessageModal', true)
            ->assertSet('selectedMessageId', $message->id)
            ->call('deleteMessage', $message->id)
            ->assertSet('showMessageModal', false);

        // Confirm deletion in database
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }
}
