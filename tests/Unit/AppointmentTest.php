<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Appointment;
use App\Models\DoctorData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_can_be_created(): void
    {
        $doctor = User::factory()->create();
        $patient = User::factory()->create();
        $doctorData = DoctorData::factory()->create(['user_id' => $doctor->id]);
        
        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDay(),
            'status' => 'scheduled',
            'reason' => 'Test appointment'
        ]);
        
        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'status' => 'scheduled'
        ]);
    }

    public function test_appointment_status_can_be_updated(): void
    {
        $doctor = User::factory()->create();
        $patient = User::factory()->create();
        
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'status' => 'scheduled'
        ]);
        
        $appointment->status = 'completed';
        $appointment->save();
        
        $this->assertEquals('completed', $appointment->fresh()->status);
    }

    public function test_appointment_has_relationships(): void
    {
        $doctor = User::factory()->create();
        $patient = User::factory()->create();
        
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
        ]);
        
        $this->assertInstanceOf(User::class, $appointment->doctor);
        $this->assertInstanceOf(User::class, $appointment->patient);
        $this->assertEquals($doctor->id, $appointment->doctor->id);
        $this->assertEquals($patient->id, $appointment->patient->id);
    }
}
