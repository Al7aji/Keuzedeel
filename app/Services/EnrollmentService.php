<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\KeuzedeelInstance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    /**
     * Enroll a student in a keuzedeel instance
     *
     * @param User $user
     * @param KeuzedeelInstance $instance
     * @return array{success: bool, message: string, enrollment?: Enrollment}
     */
    public function enroll(User $user, KeuzedeelInstance $instance): array
    {
        // Validate user is a student
        if (!$user->isStudent()) {
            return [
                'success' => false,
                'message' => 'Alleen studenten kunnen zich inschrijven voor keuzedelen.',
            ];
        }

        // Check if student has a program
        if (!$user->program_id) {
            return [
                'success' => false,
                'message' => 'Je moet eerst een opleiding selecteren.',
            ];
        }

        // Check if keuzedeel is available for student's program
        $keuzedeel = $instance->keuzedeel;
        if (!$keuzedeel->programs->contains($user->program_id)) {
            return [
                'success' => false,
                'message' => 'Dit keuzedeel is niet beschikbaar voor jouw opleiding.',
            ];
        }

        // Check if keuzedeel instance is active
        if (!$instance->is_active || !$keuzedeel->is_active) {
            return [
                'success' => false,
                'message' => 'Dit keuzedeel is momenteel niet beschikbaar.',
            ];
        }

        // Check if enrollment is open for this period
        if (!$instance->period->canEnroll()) {
            return [
                'success' => false,
                'message' => 'De inschrijving voor deze periode is niet geopend.',
            ];
        }

        // Check if already enrolled in this period
        if ($user->isEnrolledInPeriod($instance->period_id)) {
            return [
                'success' => false,
                'message' => 'Je bent al ingeschreven voor een keuzedeel in deze periode.',
            ];
        }

        // Check if already completed (for non-repeatable keuzedelen)
        if (!$keuzedeel->is_repeatable && $user->hasCompletedKeuzedeel($keuzedeel->id)) {
            return [
                'success' => false,
                'message' => 'Je hebt dit keuzedeel al afgerond en kunt je niet opnieuw inschrijven.',
            ];
        }

        // Use database transaction with locking to prevent race conditions
        try {
            return DB::transaction(function () use ($user, $instance) {
                // Lock the instance row to prevent concurrent enrollments
                $lockedInstance = KeuzedeelInstance::where('id', $instance->id)
                    ->lockForUpdate()
                    ->first();

                // Double-check capacity with lock
                $currentCount = $lockedInstance->activeEnrollments()->count();
                $maxStudents = $lockedInstance->keuzedeel->max_students;

                if ($currentCount >= $maxStudents) {
                    return [
                        'success' => false,
                        'message' => 'Dit keuzedeel is vol. Er zijn geen plekken meer beschikbaar.',
                    ];
                }

                // Create enrollment
                $enrollment = Enrollment::create([
                    'user_id' => $user->id,
                    'keuzedeel_instance_id' => $lockedInstance->id,
                    'status' => 'enrolled',
                    'enrolled_at' => now(),
                ]);

                return [
                    'success' => true,
                    'message' => 'Je bent succesvol ingeschreven voor dit keuzedeel!',
                    'enrollment' => $enrollment,
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Er is een fout opgetreden bij het inschrijven. Probeer het opnieuw.',
            ];
        }
    }

    /**
     * Cancel an enrollment
     *
     * @param Enrollment $enrollment
     * @param User $user
     * @return array{success: bool, message: string}
     */
    public function cancel(Enrollment $enrollment, User $user): array
    {
        // Check if user owns this enrollment
        if ($enrollment->user_id !== $user->id && !$user->isAdmin()) {
            return [
                'success' => false,
                'message' => 'Je kunt deze inschrijving niet annuleren.',
            ];
        }

        // Check if enrollment can be cancelled
        if ($enrollment->status === 'completed') {
            return [
                'success' => false,
                'message' => 'Een afgerond keuzedeel kan niet worden geannuleerd.',
            ];
        }

        if ($enrollment->status === 'cancelled') {
            return [
                'success' => false,
                'message' => 'Deze inschrijving is al geannuleerd.',
            ];
        }

        $enrollment->cancel();

        return [
            'success' => true,
            'message' => 'Je inschrijving is geannuleerd.',
        ];
    }

    /**
     * Check if a user can enroll in a specific instance
     *
     * @param User $user
     * @param KeuzedeelInstance $instance
     * @return array{can_enroll: bool, reason?: string}
     */
    public function canEnroll(User $user, KeuzedeelInstance $instance): array
    {
        if (!$user->isStudent()) {
            return ['can_enroll' => false, 'reason' => 'not_student'];
        }

        if (!$user->program_id) {
            return ['can_enroll' => false, 'reason' => 'no_program'];
        }

        $keuzedeel = $instance->keuzedeel;

        if (!$keuzedeel->programs->contains($user->program_id)) {
            return ['can_enroll' => false, 'reason' => 'wrong_program'];
        }

        if (!$instance->is_active || !$keuzedeel->is_active) {
            return ['can_enroll' => false, 'reason' => 'inactive'];
        }

        if (!$instance->period->canEnroll()) {
            return ['can_enroll' => false, 'reason' => 'enrollment_closed'];
        }

        if ($user->isEnrolledInPeriod($instance->period_id)) {
            return ['can_enroll' => false, 'reason' => 'already_enrolled_period'];
        }

        if (!$keuzedeel->is_repeatable && $user->hasCompletedKeuzedeel($keuzedeel->id)) {
            return ['can_enroll' => false, 'reason' => 'already_completed'];
        }

        if ($instance->isFull()) {
            return ['can_enroll' => false, 'reason' => 'full'];
        }

        return ['can_enroll' => true];
    }
}
