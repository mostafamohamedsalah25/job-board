<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Employer;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Skill;
use App\Models\JobPosting;
use App\Models\Application;
use App\Models\Comment;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إيقاف حماية الحقول مؤقتاً لضمان إدخال كل البيانات بدون أخطاء Fillable
        Model::unguard();

        $faker = Faker::create();

        // 1. إنشاء الرتب (Roles)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employerRole = Role::firstOrCreate(['name' => 'employer']);
        $candidateRole = Role::firstOrCreate(['name' => 'candidate']);

        // 2. إنشاء حساب المدير (Admin) لكي تختبر به
        $admin = User::firstOrCreate(
            ['email' => 'admin@jobboard.com'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // 3. إنشاء التصنيفات (Categories)
        $categories = ['Software Engineering', 'Design & UX', 'Marketing', 'Sales', 'Data Science', 'Human Resources', 'Finance'];
        $categoryIds = [];
        foreach ($categories as $cat) {
            $c = Category::firstOrCreate(['name' => $cat, 'slug' => Str::slug($cat)]);
            $categoryIds[] = $c->id;
        }

        // 4. إنشاء المهارات التقنية (Skills)
        $skills = ['PHP', 'Laravel', 'Vue.js', 'React', 'Node.js', 'Python', 'MySQL', 'MongoDB', 'UI/UX', 'Figma', 'SEO', 'Agile', 'Docker', 'AWS', 'Tailwind CSS'];
        $skillIds = [];
        foreach ($skills as $skill) {
            $s = Skill::firstOrCreate(['name' => $skill]);
            $skillIds[] = $s->id;
        }

        // 5. إنشاء الشركات (Employers)
        $this->command->info('Generating Employers...');
        $employers = [];
        // إنشاء 30 حساب شركة
        for ($i = 0; $i < 30; $i++) {
            // تثبيت الباسورد هنا لجميع الشركات
            $user = User::factory()->create([
                'password' => bcrypt('password')
            ]);
            $user->assignRole($employerRole);

            $employer = Employer::create([
                'user_id' => $user->id,
                'company_name' => $faker->company,
                'website' => $faker->url,
            ]);
            $employers[] = $employer;
        }

        // 6. إنشاء الموظفين (Candidates) وبروفايلاتهم
        $this->command->info('Generating Candidates and Profiles...');
        $candidates = [];
        // إنشاء حساب موظف ثابت للتجارب السريعة
        $testCandidate = User::firstOrCreate(
            ['email' => 'candidate@jobboard.com'],
            ['name' => 'Test Candidate', 'password' => bcrypt('password'), 'email_verified_at' => now()]
        );
        $testCandidate->assignRole($candidateRole);
        $testProfile = Profile::firstOrCreate(
            ['user_id' => $testCandidate->id],
            ['bio' => 'I am a passionate developer looking for opportunities.', 'phone' => '01000000000', 'linkedin_id' => 'https://linkedin.com/in/test']
        );
        $testProfile->skills()->sync($faker->randomElements($skillIds, 4));
        $candidates[] = $testCandidate;

        // إنشاء 100 موظف عشوائي
        for ($i = 0; $i < 100; $i++) {
            // تثبيت الباسورد هنا لجميع الموظفين
            $user = User::factory()->create([
                'password' => bcrypt('password')
            ]);
            $user->assignRole($candidateRole);

            $profile = Profile::create([
                'user_id' => $user->id,
                'phone' => $faker->phoneNumber,
                'bio' => $faker->realText(200),
                'linkedin_id' => 'https://linkedin.com/in/' . $faker->userName,
            ]);

            // ربط الموظف بمهارات عشوائية
            $profile->skills()->attach($faker->randomElements($skillIds, rand(2, 6)));
            $candidates[] = $user;
        }

        // 7. إنشاء الوظائف (Job Postings)
        $this->command->info('Generating Job Postings...');
        $jobs = [];
        $types = ['remote', 'on-site', 'hybrid'];

        foreach ($employers as $employer) {
            // كل شركة تنشر من 3 إلى 7 وظائف
            for ($j = 0; $j < rand(3, 7); $j++) {
                $job = JobPosting::create([
                    'employer_id' => $employer->id,
                    'category_id' => $faker->randomElement($categoryIds),
                    'title' => $faker->jobTitle,
                    'description' => $faker->paragraphs(3, true),
                    'type' => $faker->randomElement($types),
                    'status' => 'approved', // نجعل الأغلبية Approved لكي تظهر في صفحة البحث
                    'is_active' => true,
                    'location' => $faker->city . ', ' . $faker->country,
                    'salary_range' => '$' . rand(40, 90) . 'k - $' . rand(100, 200) . 'k',
                    'deadline' => now()->addDays(rand(10, 60)),
                ]);

                // ربط الوظيفة بمهارات عشوائية مطلوبة
                $job->skills()->attach($faker->randomElements($skillIds, rand(2, 5)));
                $jobs[] = $job;
            }
        }

        // إنشاء بعض الوظائف الـ Pending لاختبار لوحة الإدارة
        for ($k = 0; $k < 15; $k++) {
            JobPosting::create([
                'employer_id' => $faker->randomElement($employers)->id,
                'category_id' => $faker->randomElement($categoryIds),
                'title' => $faker->jobTitle,
                'description' => $faker->realText(300),
                'type' => $faker->randomElement($types),
                'status' => $faker->randomElement(['pending', 'rejected']),
                'is_active' => false,
                'location' => $faker->city,
                'deadline' => now()->addDays(30),
            ]);
        }

        // 8. إنشاء التقديمات والتعليقات (Applications & Comments)
        $this->command->info('Simulating User Activity (Applications & Comments)...');
        foreach ($jobs as $job) {

            // اختيار مجموعة عشوائية من الموظفين للتقديم على هذه الوظيفة
            $applicants = $faker->randomElements($candidates, rand(0, 15));
            foreach ($applicants as $applicant) {
                Application::create([
                    'job_posting_id' => $job->id,
                    'candidate_id' => $applicant->id,
                    'status' => $faker->randomElement(['pending', 'accepted', 'rejected']),
                    'is_paid' => $faker->boolean(20), // 20% فرصة أن تكون الشركة دفعت لفتح بياناته
                ]);
            }

            // كتابة تعليقات عشوائية على الوظيفة
            for ($c = 0; $c < rand(0, 4); $c++) {
                Comment::create([
                    'job_posting_id' => $job->id,
                    'user_id' => $faker->randomElement($candidates)->id,
                    'body' => $faker->sentence(rand(6, 15)),
                    'is_hidden' => $faker->boolean(10), // 10% فرصة أن يكون التعليق مخفي بواسطة الإدارة
                ]);
            }
        }

        // إعادة تشغيل حماية الحقول
        Model::reguard();

        $this->command->info('Awesome! Database is fully seeded with realistic Job Board data.');
    }
}
