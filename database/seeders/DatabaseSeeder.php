<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate([
            'email' => 'admin@clinicsaas.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Specialties with Services
        $specialties = [
            [
                'name_en' => 'General Medicine',
                'name_ar' => 'طب عام',
                'services' => [
                    ['name_en' => 'General Consultation', 'name_ar' => 'كشف عام'],
                    ['name_en' => 'Follow-up Visit', 'name_ar' => 'متابعة'],
                    ['name_en' => 'Blood Pressure Monitoring', 'name_ar' => 'قياس ضغط الدم'],
                    ['name_en' => 'Blood Sugar Test', 'name_ar' => 'تحليل سكر الدم'],
                    ['name_en' => 'Medical Report', 'name_ar' => 'تقرير طبي'],
                    ['name_en' => 'Vaccination', 'name_ar' => 'تطعيم'],
                    ['name_en' => 'Home Visit', 'name_ar' => 'زيارة منزلية'],
                ],
            ],
            [
                'name_en' => 'Dentistry',
                'name_ar' => 'طب أسنان',
                'services' => [
                    ['name_en' => 'Dental Consultation', 'name_ar' => 'كشف أسنان'],
                    ['name_en' => 'Teeth Cleaning', 'name_ar' => 'تنظيف أسنان'],
                    ['name_en' => 'Teeth Whitening', 'name_ar' => 'تبييض أسنان'],
                    ['name_en' => 'Dental Filling', 'name_ar' => 'حشو أسنان'],
                    ['name_en' => 'Root Canal Treatment', 'name_ar' => 'علاج عصب'],
                    ['name_en' => 'Tooth Extraction', 'name_ar' => 'خلع أسنان'],
                    ['name_en' => 'Dental Crown', 'name_ar' => 'تركيبة أسنان (تاج)'],
                    ['name_en' => 'Dental Bridge', 'name_ar' => 'جسر أسنان'],
                    ['name_en' => 'Dental Implant', 'name_ar' => 'زراعة أسنان'],
                    ['name_en' => 'Braces (Orthodontics)', 'name_ar' => 'تقويم أسنان'],
                    ['name_en' => 'Dental Veneer', 'name_ar' => 'فينير أسنان'],
                    ['name_en' => 'Gum Treatment', 'name_ar' => 'علاج لثة'],
                    ['name_en' => 'Wisdom Tooth Extraction', 'name_ar' => 'خلع ضرس العقل'],
                    ['name_en' => 'Dental X-Ray', 'name_ar' => 'أشعة أسنان'],
                    ['name_en' => 'Hollywood Smile', 'name_ar' => 'ابتسامة هوليوود'],
                ],
            ],
            [
                'name_en' => 'Ophthalmology',
                'name_ar' => 'طب عيون',
                'services' => [
                    ['name_en' => 'Eye Examination', 'name_ar' => 'كشف عيون'],
                    ['name_en' => 'Vision Test', 'name_ar' => 'فحص نظر'],
                    ['name_en' => 'LASIK Surgery', 'name_ar' => 'عملية ليزك'],
                    ['name_en' => 'Cataract Surgery', 'name_ar' => 'عملية مياه بيضاء'],
                    ['name_en' => 'Glaucoma Treatment', 'name_ar' => 'علاج المياه الزرقاء'],
                    ['name_en' => 'Retina Examination', 'name_ar' => 'فحص شبكية'],
                    ['name_en' => 'Contact Lens Fitting', 'name_ar' => 'تركيب عدسات لاصقة'],
                    ['name_en' => 'Eye Pressure Test', 'name_ar' => 'قياس ضغط العين'],
                    ['name_en' => 'Cornea Treatment', 'name_ar' => 'علاج القرنية'],
                    ['name_en' => 'Strabismus Treatment', 'name_ar' => 'علاج الحول'],
                ],
            ],
            [
                'name_en' => 'Dermatology',
                'name_ar' => 'جلدية',
                'services' => [
                    ['name_en' => 'Skin Consultation', 'name_ar' => 'كشف جلدية'],
                    ['name_en' => 'Acne Treatment', 'name_ar' => 'علاج حب الشباب'],
                    ['name_en' => 'Eczema Treatment', 'name_ar' => 'علاج إكزيما'],
                    ['name_en' => 'Psoriasis Treatment', 'name_ar' => 'علاج صدفية'],
                    ['name_en' => 'Fungal Infection Treatment', 'name_ar' => 'علاج فطريات'],
                    ['name_en' => 'Allergy Treatment', 'name_ar' => 'علاج حساسية'],
                    ['name_en' => 'Vitiligo Treatment', 'name_ar' => 'علاج بهاق'],
                    ['name_en' => 'Mole Removal', 'name_ar' => 'إزالة شامات'],
                    ['name_en' => 'Wart Removal', 'name_ar' => 'إزالة ثآليل'],
                    ['name_en' => 'Skin Biopsy', 'name_ar' => 'عينة جلدية'],
                    ['name_en' => 'Hair Loss Treatment', 'name_ar' => 'علاج تساقط الشعر'],
                ],
            ],
            [
                'name_en' => 'Cardiology',
                'name_ar' => 'قلب وأوعية دموية',
                'services' => [
                    ['name_en' => 'Cardiology Consultation', 'name_ar' => 'كشف قلب'],
                    ['name_en' => 'ECG (Electrocardiogram)', 'name_ar' => 'رسم قلب'],
                    ['name_en' => 'Echocardiography', 'name_ar' => 'إيكو على القلب'],
                    ['name_en' => 'Stress Test', 'name_ar' => 'اختبار مجهود'],
                    ['name_en' => 'Holter Monitor', 'name_ar' => 'هولتر مونيتور'],
                    ['name_en' => 'Blood Pressure Management', 'name_ar' => 'متابعة ضغط الدم'],
                    ['name_en' => 'Cholesterol Management', 'name_ar' => 'متابعة الكولسترول'],
                    ['name_en' => 'Cardiac Catheterization', 'name_ar' => 'قسطرة قلب'],
                    ['name_en' => 'Pacemaker Follow-up', 'name_ar' => 'متابعة منظم ضربات القلب'],
                ],
            ],
            [
                'name_en' => 'Orthopedics',
                'name_ar' => 'عظام',
                'services' => [
                    ['name_en' => 'Orthopedic Consultation', 'name_ar' => 'كشف عظام'],
                    ['name_en' => 'Fracture Treatment', 'name_ar' => 'علاج كسور'],
                    ['name_en' => 'Joint Pain Treatment', 'name_ar' => 'علاج آلام المفاصل'],
                    ['name_en' => 'Back Pain Treatment', 'name_ar' => 'علاج آلام الظهر'],
                    ['name_en' => 'Sports Injury Treatment', 'name_ar' => 'علاج إصابات رياضية'],
                    ['name_en' => 'Arthroscopy', 'name_ar' => 'منظار مفصل'],
                    ['name_en' => 'Joint Injection', 'name_ar' => 'حقن مفاصل'],
                    ['name_en' => 'Cast Application', 'name_ar' => 'تجبيس'],
                    ['name_en' => 'Knee Replacement', 'name_ar' => 'تغيير مفصل ركبة'],
                    ['name_en' => 'Hip Replacement', 'name_ar' => 'تغيير مفصل فخذ'],
                    ['name_en' => 'Spinal Disc Treatment', 'name_ar' => 'علاج الانزلاق الغضروفي'],
                ],
            ],
            [
                'name_en' => 'Pediatrics',
                'name_ar' => 'أطفال',
                'services' => [
                    ['name_en' => 'Pediatric Consultation', 'name_ar' => 'كشف أطفال'],
                    ['name_en' => 'Well Baby Check-up', 'name_ar' => 'فحص طفل سليم'],
                    ['name_en' => 'Vaccination', 'name_ar' => 'تطعيمات'],
                    ['name_en' => 'Growth Monitoring', 'name_ar' => 'متابعة نمو'],
                    ['name_en' => 'Newborn Examination', 'name_ar' => 'فحص حديثي الولادة'],
                    ['name_en' => 'Allergy Testing', 'name_ar' => 'اختبار حساسية'],
                    ['name_en' => 'Fever Management', 'name_ar' => 'علاج حرارة'],
                    ['name_en' => 'Asthma Management', 'name_ar' => 'علاج ربو'],
                    ['name_en' => 'Nutritional Counseling', 'name_ar' => 'استشارة تغذية طفل'],
                ],
            ],
            [
                'name_en' => 'ENT',
                'name_ar' => 'أنف وأذن وحنجرة',
                'services' => [
                    ['name_en' => 'ENT Consultation', 'name_ar' => 'كشف أنف وأذن وحنجرة'],
                    ['name_en' => 'Hearing Test', 'name_ar' => 'اختبار سمع'],
                    ['name_en' => 'Sinusitis Treatment', 'name_ar' => 'علاج جيوب أنفية'],
                    ['name_en' => 'Tonsillectomy', 'name_ar' => 'استئصال لوزتين'],
                    ['name_en' => 'Ear Cleaning', 'name_ar' => 'تنظيف أذن'],
                    ['name_en' => 'Nasal Endoscopy', 'name_ar' => 'منظار أنفي'],
                    ['name_en' => 'Voice Disorder Treatment', 'name_ar' => 'علاج اضطرابات الصوت'],
                    ['name_en' => 'Snoring Treatment', 'name_ar' => 'علاج الشخير'],
                    ['name_en' => 'Ear Tube Insertion', 'name_ar' => 'تركيب أنابيب أذن'],
                    ['name_en' => 'Deviated Septum Surgery', 'name_ar' => 'عملية انحراف حاجز أنفي'],
                ],
            ],
            [
                'name_en' => 'Gynecology',
                'name_ar' => 'نساء وتوليد',
                'services' => [
                    ['name_en' => 'Gynecology Consultation', 'name_ar' => 'كشف نساء وتوليد'],
                    ['name_en' => 'Pregnancy Follow-up', 'name_ar' => 'متابعة حمل'],
                    ['name_en' => 'Obstetric Ultrasound', 'name_ar' => 'سونار حمل'],
                    ['name_en' => 'Pap Smear', 'name_ar' => 'مسحة عنق الرحم'],
                    ['name_en' => 'Family Planning', 'name_ar' => 'تنظيم أسرة'],
                    ['name_en' => 'IUD Insertion', 'name_ar' => 'تركيب لولب'],
                    ['name_en' => 'Fertility Consultation', 'name_ar' => 'استشارة خصوبة'],
                    ['name_en' => 'Menstrual Disorder Treatment', 'name_ar' => 'علاج اضطرابات الدورة'],
                    ['name_en' => 'Breast Examination', 'name_ar' => 'فحص ثدي'],
                    ['name_en' => 'Postpartum Care', 'name_ar' => 'رعاية ما بعد الولادة'],
                ],
            ],
            [
                'name_en' => 'Urology',
                'name_ar' => 'مسالك بولية',
                'services' => [
                    ['name_en' => 'Urology Consultation', 'name_ar' => 'كشف مسالك بولية'],
                    ['name_en' => 'Kidney Stone Treatment', 'name_ar' => 'علاج حصوات الكلى'],
                    ['name_en' => 'Prostate Examination', 'name_ar' => 'فحص بروستاتا'],
                    ['name_en' => 'Urinary Tract Infection Treatment', 'name_ar' => 'علاج التهاب مسالك بولية'],
                    ['name_en' => 'Lithotripsy (ESWL)', 'name_ar' => 'تفتيت حصوات'],
                    ['name_en' => 'Cystoscopy', 'name_ar' => 'منظار مثانة'],
                    ['name_en' => 'Male Infertility Treatment', 'name_ar' => 'علاج عقم الرجال'],
                    ['name_en' => 'Circumcision', 'name_ar' => 'ختان'],
                    ['name_en' => 'Urinary Incontinence Treatment', 'name_ar' => 'علاج سلس البول'],
                ],
            ],
            [
                'name_en' => 'Neurology',
                'name_ar' => 'مخ وأعصاب',
                'services' => [
                    ['name_en' => 'Neurology Consultation', 'name_ar' => 'كشف مخ وأعصاب'],
                    ['name_en' => 'EEG (Electroencephalogram)', 'name_ar' => 'رسم مخ'],
                    ['name_en' => 'EMG (Electromyography)', 'name_ar' => 'رسم أعصاب'],
                    ['name_en' => 'Migraine Treatment', 'name_ar' => 'علاج صداع نصفي'],
                    ['name_en' => 'Epilepsy Management', 'name_ar' => 'علاج صرع'],
                    ['name_en' => 'Stroke Follow-up', 'name_ar' => 'متابعة سكتة دماغية'],
                    ['name_en' => 'Nerve Pain Treatment', 'name_ar' => 'علاج آلام الأعصاب'],
                    ['name_en' => 'Memory Disorder Assessment', 'name_ar' => 'تقييم اضطرابات الذاكرة'],
                    ['name_en' => 'Movement Disorder Treatment', 'name_ar' => 'علاج اضطرابات الحركة'],
                ],
            ],
            [
                'name_en' => 'Psychiatry',
                'name_ar' => 'طب نفسي',
                'services' => [
                    ['name_en' => 'Psychiatric Consultation', 'name_ar' => 'كشف نفسي'],
                    ['name_en' => 'Psychotherapy Session', 'name_ar' => 'جلسة علاج نفسي'],
                    ['name_en' => 'Depression Treatment', 'name_ar' => 'علاج اكتئاب'],
                    ['name_en' => 'Anxiety Treatment', 'name_ar' => 'علاج قلق'],
                    ['name_en' => 'OCD Treatment', 'name_ar' => 'علاج وسواس قهري'],
                    ['name_en' => 'Addiction Treatment', 'name_ar' => 'علاج إدمان'],
                    ['name_en' => 'Sleep Disorder Treatment', 'name_ar' => 'علاج اضطرابات النوم'],
                    ['name_en' => 'Stress Management', 'name_ar' => 'إدارة الضغط النفسي'],
                    ['name_en' => 'Couples Therapy', 'name_ar' => 'علاج أزواج'],
                    ['name_en' => 'Child Psychiatry', 'name_ar' => 'طب نفسي أطفال'],
                ],
            ],
            [
                'name_en' => 'Internal Medicine',
                'name_ar' => 'باطنة',
                'services' => [
                    ['name_en' => 'Internal Medicine Consultation', 'name_ar' => 'كشف باطنة'],
                    ['name_en' => 'Diabetes Management', 'name_ar' => 'متابعة سكر'],
                    ['name_en' => 'Liver Disease Treatment', 'name_ar' => 'علاج أمراض الكبد'],
                    ['name_en' => 'Kidney Disease Treatment', 'name_ar' => 'علاج أمراض الكلى'],
                    ['name_en' => 'Thyroid Disorder Treatment', 'name_ar' => 'علاج الغدة الدرقية'],
                    ['name_en' => 'Gastrointestinal Treatment', 'name_ar' => 'علاج الجهاز الهضمي'],
                    ['name_en' => 'Anemia Treatment', 'name_ar' => 'علاج أنيميا'],
                    ['name_en' => 'Rheumatology Consultation', 'name_ar' => 'استشارة روماتيزم'],
                    ['name_en' => 'Comprehensive Check-up', 'name_ar' => 'فحص شامل'],
                ],
            ],
            [
                'name_en' => 'Physiotherapy',
                'name_ar' => 'علاج طبيعي',
                'services' => [
                    ['name_en' => 'Physiotherapy Consultation', 'name_ar' => 'كشف علاج طبيعي'],
                    ['name_en' => 'Manual Therapy', 'name_ar' => 'علاج يدوي'],
                    ['name_en' => 'Electrotherapy', 'name_ar' => 'علاج كهربائي'],
                    ['name_en' => 'Post-Surgery Rehabilitation', 'name_ar' => 'تأهيل بعد العمليات'],
                    ['name_en' => 'Sports Rehabilitation', 'name_ar' => 'تأهيل رياضي'],
                    ['name_en' => 'Neck & Back Therapy', 'name_ar' => 'علاج الرقبة والظهر'],
                    ['name_en' => 'Joint Mobilization', 'name_ar' => 'تحريك مفاصل'],
                    ['name_en' => 'Ultrasound Therapy', 'name_ar' => 'علاج بالموجات فوق الصوتية'],
                    ['name_en' => 'Cupping Therapy', 'name_ar' => 'حجامة'],
                    ['name_en' => 'Stroke Rehabilitation', 'name_ar' => 'تأهيل بعد السكتة الدماغية'],
                ],
            ],
            [
                'name_en' => 'Nutrition',
                'name_ar' => 'تغذية',
                'services' => [
                    ['name_en' => 'Nutrition Consultation', 'name_ar' => 'استشارة تغذية'],
                    ['name_en' => 'Weight Loss Program', 'name_ar' => 'برنامج تخسيس'],
                    ['name_en' => 'Weight Gain Program', 'name_ar' => 'برنامج زيادة وزن'],
                    ['name_en' => 'Diabetes Diet Plan', 'name_ar' => 'نظام غذائي لمرضى السكر'],
                    ['name_en' => 'Sports Nutrition', 'name_ar' => 'تغذية رياضية'],
                    ['name_en' => 'Pregnancy Nutrition', 'name_ar' => 'تغذية الحامل'],
                    ['name_en' => 'Child Nutrition', 'name_ar' => 'تغذية أطفال'],
                    ['name_en' => 'Body Composition Analysis', 'name_ar' => 'تحليل تكوين الجسم'],
                    ['name_en' => 'Food Allergy Management', 'name_ar' => 'إدارة حساسية الطعام'],
                ],
            ],
            // ===== التخصصات الجديدة =====
            [
                'name_en' => 'Plastic Surgery',
                'name_ar' => 'جراحة تجميل',
                'services' => [
                    ['name_en' => 'Plastic Surgery Consultation', 'name_ar' => 'كشف جراحة تجميل'],
                    ['name_en' => 'Rhinoplasty', 'name_ar' => 'تجميل الأنف'],
                    ['name_en' => 'Facelift', 'name_ar' => 'شد الوجه'],
                    ['name_en' => 'Eyelid Surgery (Blepharoplasty)', 'name_ar' => 'شد الجفون'],
                    ['name_en' => 'Liposuction', 'name_ar' => 'شفط الدهون'],
                    ['name_en' => 'Tummy Tuck (Abdominoplasty)', 'name_ar' => 'شد البطن'],
                    ['name_en' => 'Breast Augmentation', 'name_ar' => 'تكبير الثدي'],
                    ['name_en' => 'Breast Reduction', 'name_ar' => 'تصغير الثدي'],
                    ['name_en' => 'Breast Lift', 'name_ar' => 'شد الثدي'],
                    ['name_en' => 'Gynecomastia Surgery', 'name_ar' => 'علاج التثدي عند الرجال'],
                    ['name_en' => 'Body Contouring', 'name_ar' => 'نحت الجسم'],
                    ['name_en' => 'Fat Transfer', 'name_ar' => 'حقن دهون ذاتية'],
                    ['name_en' => 'Ear Correction (Otoplasty)', 'name_ar' => 'تجميل الأذن'],
                    ['name_en' => 'Hair Transplant', 'name_ar' => 'زراعة الشعر'],
                    ['name_en' => 'Scar Revision', 'name_ar' => 'علاج الندبات'],
                    ['name_en' => 'Chin Augmentation', 'name_ar' => 'تجميل الذقن'],
                    ['name_en' => 'Lip Surgery', 'name_ar' => 'تجميل الشفايف جراحيًا'],
                    ['name_en' => 'Arm Lift (Brachioplasty)', 'name_ar' => 'شد الذراعين'],
                    ['name_en' => 'Thigh Lift', 'name_ar' => 'شد الفخذين'],
                    ['name_en' => 'BBL (Brazilian Butt Lift)', 'name_ar' => 'تكبير المؤخرة (برازيلي)'],
                ],
            ],
            [
                'name_en' => 'Cosmetic Dermatology',
                'name_ar' => 'تجميل وعناية بالبشرة',
                'services' => [
                    ['name_en' => 'Cosmetic Consultation', 'name_ar' => 'كشف تجميلي'],
                    ['name_en' => 'Botox Injection', 'name_ar' => 'حقن بوتوكس'],
                    ['name_en' => 'Dermal Fillers', 'name_ar' => 'حقن فيلر'],
                    ['name_en' => 'Lip Fillers', 'name_ar' => 'فيلر شفايف'],
                    ['name_en' => 'Cheek Fillers', 'name_ar' => 'فيلر خدود'],
                    ['name_en' => 'Under-Eye Fillers', 'name_ar' => 'فيلر تحت العين'],
                    ['name_en' => 'Jawline Fillers', 'name_ar' => 'فيلر الفك'],
                    ['name_en' => 'Mesotherapy', 'name_ar' => 'ميزوثيرابي'],
                    ['name_en' => 'PRP (Platelet Rich Plasma)', 'name_ar' => 'بلازما (PRP)'],
                    ['name_en' => 'PRP for Hair', 'name_ar' => 'بلازما للشعر'],
                    ['name_en' => 'Thread Lift', 'name_ar' => 'خيوط شد الوجه'],
                    ['name_en' => 'Chemical Peeling', 'name_ar' => 'تقشير كيميائي'],
                    ['name_en' => 'Laser Hair Removal', 'name_ar' => 'ليزر إزالة الشعر'],
                    ['name_en' => 'Skin Whitening Laser', 'name_ar' => 'ليزر تفتيح البشرة'],
                    ['name_en' => 'Fractional Laser', 'name_ar' => 'ليزر فراكشنال'],
                    ['name_en' => 'Carbon Laser Peel', 'name_ar' => 'ليزر كربوني'],
                    ['name_en' => 'Tattoo Removal Laser', 'name_ar' => 'ليزر إزالة التاتو'],
                    ['name_en' => 'HydraFacial', 'name_ar' => 'هيدرافيشيال'],
                    ['name_en' => 'Microneedling (Derma Pen)', 'name_ar' => 'ديرما بن'],
                    ['name_en' => 'Carboxytherapy', 'name_ar' => 'كاربوكسي ثيرابي'],
                    ['name_en' => 'Pigmentation Treatment', 'name_ar' => 'علاج التصبغات'],
                    ['name_en' => 'Stretch Mark Treatment', 'name_ar' => 'علاج علامات التمدد'],
                    ['name_en' => 'Skin Tightening (HIFU)', 'name_ar' => 'شد البشرة (هايفو)'],
                    ['name_en' => 'IV Drip Therapy (Skin Glow)', 'name_ar' => 'محاليل وريدية للبشرة'],
                    ['name_en' => 'Profhilo', 'name_ar' => 'بروفايلو'],
                    ['name_en' => 'Sculptra', 'name_ar' => 'سكلبترا'],
                ],
            ],
        ];

        foreach ($specialties as $specialtyData) {
            $services = $specialtyData['services'];
            unset($specialtyData['services']);

            $specialty = Specialty::firstOrCreate(
                ['name_en' => $specialtyData['name_en']],
                $specialtyData
            );

            foreach ($services as $service) {
                Service::firstOrCreate(
                    [
                        'specialty_id' => $specialty->id,
                        'name_en' => $service['name_en'],
                    ],
                    array_merge($service, ['specialty_id' => $specialty->id])
                );
            }
        }
    }
}
