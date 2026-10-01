<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Course;
use App\Models\Content;
use App\Models\Evaluation;
use App\Models\Instructor;
use App\Models\Podcast;
use App\Models\Question;
use App\Models\SubscriptionPlan;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates a comprehensive course with all four types of content:
     * 1. Video Lecture
     * 2. Hands-on Activity
     * 3. Podcast/Audio
     * 4. Knowledge Evaluation
     */
    public function run(): void
    {
        // Create Subscription Plan if it doesn't exist
        $subscription = SubscriptionPlan::firstOrCreate(
            ['name' => 'Premium'],
            [
                'duration' => 'monthly',
                'price' => '9.99',
                'revenue_cart_product_id' => 'plan_premium_001'
            ]
        );

        // Create Category if it doesn't exist
        $category = Category::firstOrCreate(
            ['name' => 'Professional Development'],
            [
                'slug' => 'professional-development',
                'status' => 'active'
            ]
        );

        // Create Instructor if it doesn't exist
        $instructor = Instructor::firstOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'John Smith',
                'avatar' => 'uploads/instructor/default.png',
                'bio' => 'Experienced instructor with 10+ years in professional development',
                'phone' => '+1-234-567-8900',
                'address' => '123 Education Street, Learning City',
                'role' => 'instructor',
                'designation' => 'Senior Instructor',
                'country' => 'United States',
                'services' => 'Training, Mentoring'
            ]
        );

        // Get some existing tags or use the first few available
        $tags = Tag::limit(3)->pluck('id')->toArray();

        // Create the main Course
        $course = Course::create([
            'name' => 'Complete Professional Development Course',
            'description' => 'A comprehensive course covering video lectures, hands-on activities, podcast discussions, and knowledge evaluations. This course is designed to provide a complete learning experience with multiple content types.',
            'thumbnail' => 'uploads/course/default.png',
            'subscription_plans_id' => $subscription->id,
            'category_id' => $category->id,
            'status' => 'active'
        ]);

        // Attach tags to course
        if (!empty($tags)) {
            $course->tags()->attach($tags);
        }

        // ===== 1. VIDEO CONTENT =====
        $video = Video::create([
            'title' => 'Introduction to Professional Development',
            'file' => 'course/video/sample-video.mp4',
            'image' => 'uploads/video/default.png',
            'duration' => '00:15:30',
            'instructor_id' => $instructor->id,
            'status' => 'active'
        ]);

        // Attach tags to video
        if (!empty($tags)) {
            $video->tags()->attach($tags);
        }

        // Create video content entry
        Content::create([
            'course_id' => $course->id,
            'order' => 1,
            'type' => 'video',
            'contentable_id' => $video->id,
            'contentable_type' => Video::class
        ]);

        // ===== 2. ACTIVITY CONTENT =====
        $activity = Activity::create([
            'title' => 'Hands-on Practice Activity',
            'description' => 'Complete practical exercises to reinforce the concepts learned in the video lectures. This activity includes multiple scenarios and real-world examples.',
            'images' => json_encode([
                'uploads/activity/default.png',
                'uploads/activity/practice-image.png'
            ]),
            'status' => 'active'
        ]);

        // Attach tags to activity
        if (!empty($tags)) {
            $activity->tags()->attach($tags);
        }

        // Create activity content entry
        Content::create([
            'course_id' => $course->id,
            'order' => 2,
            'type' => 'activity',
            'contentable_id' => $activity->id,
            'contentable_type' => Activity::class
        ]);

        // ===== 3. PODCAST CONTENT =====
        $podcast = Podcast::create([
            'title' => 'Expert Discussion: Career Growth Strategies',
            'description' => 'Listen to industry experts discussing proven strategies for career advancement and professional growth. This podcast episode features insights from successful professionals.',
            'file' => 'uploads/podcast/sample-podcast.mp3',
            'instructor_id' => $instructor->id,
            'status' => 'active'
        ]);

        // Attach tags to podcast
        if (!empty($tags)) {
            $podcast->tags()->attach($tags);
        }

        // Create podcast content entry
        Content::create([
            'course_id' => $course->id,
            'order' => 3,
            'type' => 'podcast',
            'contentable_id' => $podcast->id,
            'contentable_type' => Podcast::class
        ]);

        // ===== 4. EVALUATION CONTENT =====
        $evaluation = Evaluation::create([
            'title' => 'Knowledge Assessment Quiz',
            'status' => 'active'
        ]);

        // Attach tags to evaluation
        if (!empty($tags)) {
            $evaluation->tags()->attach($tags);
        }

        // Create evaluation content entry
        Content::create([
            'course_id' => $course->id,
            'order' => 4,
            'type' => 'evaluation',
            'contentable_id' => $evaluation->id,
            'contentable_type' => Evaluation::class
        ]);

        // Create questions for evaluation
        $questions = [
            [
                'title' => 'Is professional development important for career growth?',
                'answer' => true,
                'link' => null
            ],
            [
                'title' => 'Should you avoid taking new challenges in your career?',
                'answer' => false,
                'link' => null
            ],
            [
                'title' => 'Can continuous learning improve your professional skills?',
                'answer' => true,
                'link' => null
            ],
            [
                'title' => 'Is networking overrated in professional development?',
                'answer' => false,
                'link' => null
            ],
            [
                'title' => 'Should you update your skills regularly?',
                'answer' => true,
                'link' => null
            ]
        ];

        foreach ($questions as $question) {
            Question::create([
                'title' => $question['title'],
                'answer' => $question['answer'] ? 1 : 0,
                'link' => $question['link'],
                'evaluation_id' => $evaluation->id
            ]);
        }

        // ===== COURSE 2: Advanced Web Development =====
        $category2 = Category::firstOrCreate(
            ['name' => 'Web Development'],
            [
                'slug' => 'web-development',
                'status' => 'active'
            ]
        );

        $subscription2 = SubscriptionPlan::firstOrCreate(
            ['name' => 'Premium Plus'],
            [
                'duration' => 'yearly',
                'price' => '99.99',
                'revenue_cart_product_id' => 'plan_premium_plus_001'
            ]
        );

        $course2 = Course::create([
            'name' => 'Advanced Web Development Fundamentals',
            'description' => 'Master modern web development with comprehensive lectures, practical exercises, expert discussions, and assessments. Learn the latest technologies and best practices.',
            'thumbnail' => 'uploads/course/default.png',
            'subscription_plans_id' => $subscription2->id,
            'category_id' => $category2->id,
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $course2->tags()->attach($tags);
        }

        // Video for Course 2
        $video2 = Video::create([
            'title' => 'Modern JavaScript and ES6+ Features',
            'file' => 'course/video/javascript-advanced.mp4',
            'image' => 'uploads/video/default.png',
            'duration' => '00:22:45',
            'instructor_id' => $instructor->id,
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $video2->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course2->id,
            'order' => 1,
            'type' => 'video',
            'contentable_id' => $video2->id,
            'contentable_type' => Video::class
        ]);

        // Activity for Course 2
        $activity2 = Activity::create([
            'title' => 'Build a Real-World Web Application',
            'description' => 'Develop a fully functional web application using modern frameworks and best practices. Includes database design, API development, and frontend integration.',
            'images' => json_encode([
                'uploads/activity/default.png',
                'uploads/activity/web-project.png'
            ]),
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $activity2->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course2->id,
            'order' => 2,
            'type' => 'activity',
            'contentable_id' => $activity2->id,
            'contentable_type' => Activity::class
        ]);

        // Podcast for Course 2
        $podcast2 = Podcast::create([
            'title' => 'Web Development Trends and Best Practices',
            'description' => 'Listen to industry leaders discuss the latest web development trends, tools, and best practices for 2026 and beyond.',
            'file' => 'uploads/podcast/web-dev-trends.mp3',
            'instructor_id' => $instructor->id,
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $podcast2->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course2->id,
            'order' => 3,
            'type' => 'podcast',
            'contentable_id' => $podcast2->id,
            'contentable_type' => Podcast::class
        ]);

        // Evaluation for Course 2
        $evaluation2 = Evaluation::create([
            'title' => 'Web Development Competency Assessment',
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $evaluation2->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course2->id,
            'order' => 4,
            'type' => 'evaluation',
            'contentable_id' => $evaluation2->id,
            'contentable_type' => Evaluation::class
        ]);

        $questions2 = [
            [
                'title' => 'Is JavaScript the only language used in web development?',
                'answer' => false,
                'link' => null
            ],
            [
                'title' => 'Are REST APIs important in modern web applications?',
                'answer' => true,
                'link' => null
            ],
            [
                'title' => 'Should you always use frameworks for web development?',
                'answer' => false,
                'link' => null
            ],
            [
                'title' => 'Is responsive design still relevant in 2026?',
                'answer' => true,
                'link' => null
            ],
            [
                'title' => 'Can Web Components replace traditional frameworks?',
                'answer' => false,
                'link' => null
            ]
        ];

        foreach ($questions2 as $question) {
            Question::create([
                'title' => $question['title'],
                'answer' => $question['answer'] ? 1 : 0,
                'link' => $question['link'],
                'evaluation_id' => $evaluation2->id
            ]);
        }

        // ===== COURSE 3: Digital Marketing Essentials =====
        $category3 = Category::firstOrCreate(
            ['name' => 'Digital Marketing'],
            [
                'slug' => 'digital-marketing',
                'status' => 'active'
            ]
        );

        $course3 = Course::create([
            'name' => 'Digital Marketing Essentials',
            'description' => 'Complete guide to digital marketing covering SEO, social media, email marketing, analytics, and conversion optimization. Learn strategies used by leading brands.',
            'thumbnail' => 'uploads/course/default.png',
            'subscription_plans_id' => $subscription->id,
            'category_id' => $category3->id,
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $course3->tags()->attach($tags);
        }

        // Video for Course 3
        $video3 = Video::create([
            'title' => 'Digital Marketing Strategy and Planning',
            'file' => 'course/video/marketing-strategy.mp4',
            'image' => 'uploads/video/default.png',
            'duration' => '00:18:20',
            'instructor_id' => $instructor->id,
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $video3->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course3->id,
            'order' => 1,
            'type' => 'video',
            'contentable_id' => $video3->id,
            'contentable_type' => Video::class
        ]);

        // Activity for Course 3
        $activity3 = Activity::create([
            'title' => 'Create a Digital Marketing Campaign',
            'description' => 'Design and execute a complete digital marketing campaign from planning to execution. Includes market research, channel selection, content creation, and performance tracking.',
            'images' => json_encode([
                'uploads/activity/default.png',
                'uploads/activity/marketing-campaign.png'
            ]),
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $activity3->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course3->id,
            'order' => 2,
            'type' => 'activity',
            'contentable_id' => $activity3->id,
            'contentable_type' => Activity::class
        ]);

        // Podcast for Course 3
        $podcast3 = Podcast::create([
            'title' => 'Marketing Leaders Discuss ROI and Analytics',
            'description' => 'Top marketing professionals share their insights on measuring ROI, using analytics, and optimizing marketing spend for maximum impact.',
            'file' => 'uploads/podcast/marketing-analytics.mp3',
            'instructor_id' => $instructor->id,
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $podcast3->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course3->id,
            'order' => 3,
            'type' => 'podcast',
            'contentable_id' => $podcast3->id,
            'contentable_type' => Podcast::class
        ]);

        // Evaluation for Course 3
        $evaluation3 = Evaluation::create([
            'title' => 'Digital Marketing Knowledge Test',
            'status' => 'active'
        ]);

        if (!empty($tags)) {
            $evaluation3->tags()->attach($tags);
        }

        Content::create([
            'course_id' => $course3->id,
            'order' => 4,
            'type' => 'evaluation',
            'contentable_id' => $evaluation3->id,
            'contentable_type' => Evaluation::class
        ]);

        $questions3 = [
            [
                'title' => 'Is SEO still important for digital marketing?',
                'answer' => true,
                'link' => null
            ],
            [
                'title' => 'Can email marketing provide good ROI?',
                'answer' => true,
                'link' => null
            ],
            [
                'title' => 'Is organic reach on social media more important than paid ads?',
                'answer' => false,
                'link' => null
            ],
            [
                'title' => 'Should marketers focus only on one marketing channel?',
                'answer' => false,
                'link' => null
            ],
            [
                'title' => 'Is data analytics essential for effective digital marketing?',
                'answer' => true,
                'link' => null
            ]
        ];

        foreach ($questions3 as $question) {
            Question::create([
                'title' => $question['title'],
                'answer' => $question['answer'] ? 1 : 0,
                'link' => $question['link'],
                'evaluation_id' => $evaluation3->id
            ]);
        }
    }
}
