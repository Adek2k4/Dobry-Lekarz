<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;

class TicketModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_prevents_self_reporting(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Nie można zgłosić samego siebie.');
        
        $user = User::factory()->create();
        $ticketType = TicketType::factory()->create();
        
        Ticket::create([
            'doctor_id' => $user->id,
            'patient_id' => $user->id,
            'ticket_type_id' => $ticketType->id,
        ]);
    }

    public function test_ticket_can_be_created_for_different_users(): void
    {
        $doctor = User::factory()->create();
        $patient = User::factory()->create();
        $ticketType = TicketType::factory()->create();
        
        $ticket = Ticket::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'ticket_type_id' => $ticketType->id,
        ]);
        
        $this->assertDatabaseHas('tickets', [
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);
    }

    public function test_ticket_has_relationships(): void
    {
        $doctor = User::factory()->create();
        $patient = User::factory()->create();
        $ticketType = TicketType::factory()->create();
        
        $ticket = Ticket::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'ticket_type_id' => $ticketType->id,
        ]);
        
        $this->assertInstanceOf(User::class, $ticket->doctor);
        $this->assertInstanceOf(User::class, $ticket->patient);
        $this->assertInstanceOf(TicketType::class, $ticket->ticketType);
    }
}
