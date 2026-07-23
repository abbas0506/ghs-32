<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\FtfVoucher;
use App\Models\FtfPayment;
use App\Models\AcademicSession;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StudentFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Grades 3 and 4 exist
        $grade3 = Grade::firstOrCreate(['grade_no' => 3], ['name' => 'Three']);
        $grade4 = Grade::firstOrCreate(['grade_no' => 4], ['name' => 'Four']);

        // 2. Ensure Sections exist for Grade 3 and 4
        $section3 = Section::firstOrCreate(['grade_id' => $grade3->id], ['name' => 'Three']);
        $section4 = Section::firstOrCreate(['grade_id' => $grade4->id], ['name' => 'Four']);

        // 3. Create 5 students for Class 3
        $studentsClass3 = [];
        for ($i = 1; $i <= 5; $i++) {
            $studentsClass3[] = Student::firstOrCreate(
                ['admission_no' => "ADM-3-" . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'name' => "Student Three {$i}",
                    'father_name' => "Father Three {$i}",
                    'section_id' => $section3->id,
                    'rollno' => $i,
                    'admission_date' => Carbon::now()->subMonths(6),
                    'gender' => ($i % 2 == 0) ? 'f' : 'm',
                    'status' => true,
                    'fee' => 1500,
                ]
            );
        }

        // 4. Create 5 students for Class 4
        $studentsClass4 = [];
        for ($i = 1; $i <= 5; $i++) {
            $studentsClass4[] = Student::firstOrCreate(
                ['admission_no' => "ADM-4-" . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'name' => "Student Four {$i}",
                    'father_name' => "Father Four {$i}",
                    'section_id' => $section4->id,
                    'rollno' => $i,
                    'admission_date' => Carbon::now()->subMonths(6),
                    'gender' => ($i % 2 == 0) ? 'f' : 'm',
                    'status' => true,
                    'fee' => 1600,
                ]
            );
        }

        // 5. Get the active academic session for linking
        $currentSession = AcademicSession::where('name', '2026-2027')->first();
        $sessionId = $currentSession ? $currentSession->id : null;

        // 6. Create 2 FTF vouchers for a couple of months (April and May 2026)
        $voucherApril = FtfVoucher::firstOrCreate(
            ['year' => 2026, 'month' => 4],
            [
                'amount'      => 1500,
                'due_date'    => '2026-04-10',
                'description' => 'FTF Tuition Fee April 2026',
            ]
        );

        $voucherMay = FtfVoucher::firstOrCreate(
            ['year' => 2026, 'month' => 5],
            [
                'amount'      => 1500,
                'due_date'    => '2026-05-10',
                'description' => 'FTF Tuition Fee May 2026',
            ]
        );

        // 6. Create FTF entries (payments) against these vouchers
        $allStudents = array_merge($studentsClass3, $studentsClass4);

        foreach ($allStudents as $index => $student) {
            // For April voucher:
            // Odd indices have paid, others unpaid
            $paidApril = ($index % 2 != 0);
            FtfPayment::firstOrCreate(
                [
                    'ftf_voucher_id' => $voucherApril->id,
                    'student_id' => $student->id,
                ],
                [
                    'payment_date' => $paidApril ? Carbon::parse('2026-04-05') : null,
                ]
            );

            // For May voucher:
            // Indices divisible by 3 have paid, others unpaid
            $paidMay = ($index % 3 == 0);
            FtfPayment::firstOrCreate(
                [
                    'ftf_voucher_id' => $voucherMay->id,
                    'student_id' => $student->id,
                ],
                [
                    'payment_date' => $paidMay ? Carbon::parse('2026-05-05') : null,
                ]
            );
        }
    }
}
