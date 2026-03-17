<?php

namespace Database\Seeders;

use App\Models\DrugInteraction;
use Illuminate\Database\Seeder;

class DrugInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $interactions = [
            [
                'drug_a'            => 'Warfarin',
                'drug_b'            => 'Aspirin',
                'severity'          => 'severe',
                'description_en'    => 'Concurrent use of warfarin and aspirin significantly increases the risk of bleeding, including gastrointestinal and intracranial hemorrhage.',
                'description_ar'    => 'الاستخدام المتزامن للوارفارين والأسبرين يزيد بشكل كبير من خطر النزيف، بما في ذلك النزيف المعدي المعوي والداخل الجمجمي.',
                'recommendation_en' => 'Avoid combination. If necessary, monitor INR closely and use the lowest effective aspirin dose.',
                'recommendation_ar' => 'تجنب الجمع. إذا كان ضرورياً، راقب INR عن كثب واستخدم أقل جرعة فعالة من الأسبرين.',
            ],
            [
                'drug_a'            => 'Metformin',
                'drug_b'            => 'Alcohol',
                'severity'          => 'moderate',
                'description_en'    => 'Alcohol potentiates the effect of metformin on lactate metabolism, increasing the risk of lactic acidosis.',
                'description_ar'    => 'يزيد الكحول من تأثير الميتفورمين على استقلاب اللاكتات، مما يزيد من خطر الحماض اللبني.',
                'recommendation_en' => 'Advise patients to avoid excessive alcohol consumption while taking metformin.',
                'recommendation_ar' => 'نصح المرضى بتجنب الاستهلاك المفرط للكحول أثناء تناول الميتفورمين.',
            ],
            [
                'drug_a'            => 'ACE Inhibitors',
                'drug_b'            => 'Potassium Supplements',
                'severity'          => 'severe',
                'description_en'    => 'ACE inhibitors reduce potassium excretion. Combining with potassium supplements can cause dangerous hyperkalemia.',
                'description_ar'    => 'مثبطات ACE تقلل من إفراز البوتاسيوم. الجمع مع مكملات البوتاسيوم يمكن أن يسبب فرط بوتاسيوم الدم الخطير.',
                'recommendation_en' => 'Monitor serum potassium levels regularly. Avoid potassium supplements unless clearly indicated.',
                'recommendation_ar' => 'راقب مستويات البوتاسيوم في الدم بانتظام. تجنب مكملات البوتاسيوم إلا إذا كانت مشار إليها بوضوح.',
            ],
            [
                'drug_a'            => 'SSRIs',
                'drug_b'            => 'MAOIs',
                'severity'          => 'contraindicated',
                'description_en'    => 'Combining SSRIs with MAOIs can cause serotonin syndrome, a potentially life-threatening condition with symptoms including agitation, high fever, rapid heart rate, and seizures.',
                'description_ar'    => 'الجمع بين SSRIs وMAOIs يمكن أن يسبب متلازمة السيروتونين، وهي حالة قد تكون مهددة للحياة مع أعراض تشمل الهياج والحمى الشديدة وتسارع ضربات القلب والنوبات.',
                'recommendation_en' => 'Absolutely contraindicated. Allow at least 14 days washout period between MAOI and SSRI.',
                'recommendation_ar' => 'ممنوع تماماً. اترك فترة غسيل لا تقل عن 14 يوماً بين MAOI وSSRI.',
            ],
            [
                'drug_a'            => 'NSAIDs',
                'drug_b'            => 'Warfarin',
                'severity'          => 'severe',
                'description_en'    => 'NSAIDs inhibit platelet function and may irritate the gastric mucosa, compounding the anticoagulant effects of warfarin and increasing hemorrhage risk.',
                'description_ar'    => 'مضادات الالتهاب غير الستيرويدية تثبط وظيفة الصفائح الدموية وقد تهيج الغشاء المخاطي للمعدة، مما يزيد من تأثيرات مضادات التخثر للوارفارين وخطر النزيف.',
                'recommendation_en' => 'Avoid combination. Use acetaminophen instead for pain relief if needed.',
                'recommendation_ar' => 'تجنب الجمع. استخدم الأسيتامينوفين بدلاً من ذلك لتخفيف الألم إذا لزم الأمر.',
            ],
            [
                'drug_a'            => 'Digoxin',
                'drug_b'            => 'Amiodarone',
                'severity'          => 'severe',
                'description_en'    => 'Amiodarone inhibits the renal excretion of digoxin, leading to elevated digoxin levels and risk of toxicity including bradycardia and arrhythmias.',
                'description_ar'    => 'الأميودارون يثبط الإفراز الكلوي للديجوكسين، مما يؤدي إلى ارتفاع مستويات الديجوكسين وخطر السمية بما في ذلك بطء القلب وعدم انتظام ضرباته.',
                'recommendation_en' => 'Reduce digoxin dose by 30-50% when starting amiodarone. Monitor digoxin levels and ECG.',
                'recommendation_ar' => 'قلل جرعة الديجوكسين بنسبة 30-50٪ عند بدء الأميودارون. راقب مستويات الديجوكسين ومخطط القلب.',
            ],
            [
                'drug_a'            => 'Lithium',
                'drug_b'            => 'NSAIDs',
                'severity'          => 'moderate',
                'description_en'    => 'NSAIDs reduce renal lithium clearance, potentially causing lithium toxicity with symptoms such as tremor, nausea, and confusion.',
                'description_ar'    => 'مضادات الالتهاب غير الستيرويدية تقلل من تصفية الليثيوم الكلوية، مما قد يسبب سمية الليثيوم مع أعراض مثل الرعشة والغثيان والارتباك.',
                'recommendation_en' => 'Monitor lithium levels closely. Consider using acetaminophen as an alternative analgesic.',
                'recommendation_ar' => 'راقب مستويات الليثيوم عن كثب. فكر في استخدام الأسيتامينوفين كمسكن بديل.',
            ],
            [
                'drug_a'            => 'Methotrexate',
                'drug_b'            => 'NSAIDs',
                'severity'          => 'severe',
                'description_en'    => 'NSAIDs reduce renal clearance of methotrexate, causing accumulation and severe toxicity including myelosuppression and hepatotoxicity.',
                'description_ar'    => 'مضادات الالتهاب غير الستيرويدية تقلل من التخلص الكلوي من الميثوتريكسات، مما يسبب تراكمه وسمية شديدة بما في ذلك قمع نخاع العظم وسمية الكبد.',
                'recommendation_en' => 'Avoid concurrent use. If necessary, closely monitor CBC, renal function, and methotrexate levels.',
                'recommendation_ar' => 'تجنب الاستخدام المتزامن. إذا كان ضرورياً، راقب CBC ووظائف الكلى ومستويات الميثوتريكسات عن كثب.',
            ],
            [
                'drug_a'            => 'Ciprofloxacin',
                'drug_b'            => 'Theophylline',
                'severity'          => 'moderate',
                'description_en'    => 'Ciprofloxacin inhibits CYP1A2, reducing theophylline metabolism and increasing theophylline levels, which can cause toxicity including nausea, tremor, and seizures.',
                'description_ar'    => 'السيبروفلوكساسين يثبط CYP1A2، مما يقلل من استقلاب الثيوفيلين ويزيد من مستوياته، مما قد يسبب سمية تشمل الغثيان والرعشة والنوبات.',
                'recommendation_en' => 'Reduce theophylline dose by 30-50% and monitor theophylline levels.',
                'recommendation_ar' => 'قلل جرعة الثيوفيلين بنسبة 30-50٪ وراقب مستوياته.',
            ],
            [
                'drug_a'            => 'Simvastatin',
                'drug_b'            => 'Grapefruit Juice',
                'severity'          => 'moderate',
                'description_en'    => 'Grapefruit juice inhibits CYP3A4 in the intestine, significantly increasing simvastatin bioavailability and risk of myopathy and rhabdomyolysis.',
                'description_ar'    => 'عصير الجريب فروت يثبط CYP3A4 في الأمعاء، مما يزيد بشكل كبير من توافر السيمفاستاتين الحيوي وخطر اعتلال العضلات وتحلل العضلات.',
                'recommendation_en' => 'Advise patients to avoid grapefruit juice. Consider switching to pravastatin which is unaffected.',
                'recommendation_ar' => 'نصح المرضى بتجنب عصير الجريب فروت. فكر في التحول إلى البرافاستاتين الذي لا يتأثر.',
            ],
            [
                'drug_a'            => 'Clopidogrel',
                'drug_b'            => 'Omeprazole',
                'severity'          => 'moderate',
                'description_en'    => 'Omeprazole inhibits CYP2C19, reducing conversion of clopidogrel to its active form and potentially decreasing antiplatelet efficacy.',
                'description_ar'    => 'الأوميبرازول يثبط CYP2C19، مما يقلل من تحويل الكلوبيدوغريل إلى شكله النشط ويقلل محتملاً من فعالية مضادات الصفائح الدموية.',
                'recommendation_en' => 'Consider using pantoprazole instead, which has less CYP2C19 inhibition.',
                'recommendation_ar' => 'فكر في استخدام البانتوبرازول بدلاً من ذلك، والذي لديه تثبيط أقل لـ CYP2C19.',
            ],
            [
                'drug_a'            => 'Fluoxetine',
                'drug_b'            => 'Tramadol',
                'severity'          => 'severe',
                'description_en'    => 'Fluoxetine inhibits tramadol metabolism and both drugs increase serotonin levels, significantly raising the risk of serotonin syndrome and seizures.',
                'description_ar'    => 'الفلوكستين يثبط استقلاب الترامادول وكلا الدواءين يزيد من مستويات السيروتونين، مما يزيد بشكل كبير من خطر متلازمة السيروتونين والنوبات.',
                'recommendation_en' => 'Avoid combination. Use alternative opioids or analgesics.',
                'recommendation_ar' => 'تجنب الجمع. استخدم مسكنات أفيونية أو مسكنات ألم بديلة.',
            ],
            [
                'drug_a'            => 'Sildenafil',
                'drug_b'            => 'Nitrates',
                'severity'          => 'contraindicated',
                'description_en'    => 'Both drugs cause vasodilation. Concurrent use can cause severe hypotension, potentially fatal, by an additive mechanism.',
                'description_ar'    => 'كلا الدواءين يسبب توسع الأوعية. الاستخدام المتزامن يمكن أن يسبب انخفاضاً شديداً في ضغط الدم، قد يكون مميتاً، من خلال آلية إضافية.',
                'recommendation_en' => 'Absolutely contraindicated. Do not use within 24 hours of each other (48 hours for tadalafil).',
                'recommendation_ar' => 'ممنوع تماماً. لا تستخدم في غضون 24 ساعة من بعضهما (48 ساعة للتادالافيل).',
            ],
            [
                'drug_a'            => 'Potassium-Sparing Diuretics',
                'drug_b'            => 'ACE Inhibitors',
                'severity'          => 'severe',
                'description_en'    => 'Both drug classes reduce potassium excretion, leading to potentially dangerous hyperkalemia, which can cause cardiac arrhythmias.',
                'description_ar'    => 'كلتا الفئتين الدوائيتين تقللان من إفراز البوتاسيوم، مما يؤدي إلى ارتفاع بوتاسيوم الدم الخطير المحتمل، والذي يمكن أن يسبب عدم انتظام ضربات القلب.',
                'recommendation_en' => 'Monitor serum potassium frequently. Avoid combination unless under specialist supervision.',
                'recommendation_ar' => 'راقب بوتاسيوم الدم بشكل متكرر. تجنب الجمع إلا تحت إشراف متخصص.',
            ],
            [
                'drug_a'            => 'Phenytoin',
                'drug_b'            => 'Carbamazepine',
                'severity'          => 'moderate',
                'description_en'    => 'Both drugs induce hepatic enzymes, altering each other\'s metabolism. The interaction is complex and may result in either toxicity or reduced efficacy.',
                'description_ar'    => 'كلا الدواءين يحفزان الإنزيمات الكبدية، مما يغير استقلاب كل منهما. التفاعل معقد وقد يؤدي إلى السمية أو انخفاض الفعالية.',
                'recommendation_en' => 'Monitor plasma levels of both drugs carefully. Adjust doses as needed.',
                'recommendation_ar' => 'راقب مستويات البلازما لكلا الدواءين بعناية. اضبط الجرعات حسب الحاجة.',
            ],
            [
                'drug_a'            => 'Clarithromycin',
                'drug_b'            => 'Statins',
                'severity'          => 'moderate',
                'description_en'    => 'Clarithromycin inhibits CYP3A4, increasing statin plasma levels and the risk of muscle toxicity including rhabdomyolysis.',
                'description_ar'    => 'الكلاريثروميسين يثبط CYP3A4، مما يزيد من مستويات الستاتين في البلازما وخطر سمية العضلات بما في ذلك تحلل العضلات.',
                'recommendation_en' => 'Temporarily withhold statin therapy during clarithromycin course. Use azithromycin as an alternative when possible.',
                'recommendation_ar' => 'أوقف علاج الستاتين مؤقتاً خلال دورة الكلاريثروميسين. استخدم الأزيثروميسين كبديل عند الإمكان.',
            ],
            [
                'drug_a'            => 'Rifampicin',
                'drug_b'            => 'Oral Contraceptives',
                'severity'          => 'severe',
                'description_en'    => 'Rifampicin strongly induces CYP3A4 and CYP2C9, dramatically reducing plasma levels of oral contraceptive hormones and their contraceptive efficacy.',
                'description_ar'    => 'الريفامبيسين يحفز بشدة CYP3A4 وCYP2C9، مما يقلل بشكل كبير من مستويات هرمونات موانع الحمل الفموية وفعاليتها في منع الحمل.',
                'recommendation_en' => 'Use additional or alternative contraception during and for at least 4 weeks after rifampicin treatment.',
                'recommendation_ar' => 'استخدم وسائل منع حمل إضافية أو بديلة أثناء وعلى الأقل 4 أسابيع بعد علاج الريفامبيسين.',
            ],
            [
                'drug_a'            => 'Amoxicillin',
                'drug_b'            => 'Warfarin',
                'severity'          => 'moderate',
                'description_en'    => 'Amoxicillin can disrupt gut flora that produces vitamin K, potentially enhancing warfarin\'s anticoagulant effect and increasing bleeding risk.',
                'description_ar'    => 'الأموكسيسيلين يمكن أن يعطل بكتيريا الأمعاء التي تنتج فيتامين K، مما قد يزيد من تأثير الوارفارين مضاد التخثر وخطر النزيف.',
                'recommendation_en' => 'Monitor INR during and after antibiotic course. Adjust warfarin dose as needed.',
                'recommendation_ar' => 'راقب INR أثناء وبعد دورة المضادات الحيوية. اضبط جرعة الوارفارين حسب الحاجة.',
            ],
            [
                'drug_a'            => 'Allopurinol',
                'drug_b'            => 'Azathioprine',
                'severity'          => 'severe',
                'description_en'    => 'Allopurinol inhibits xanthine oxidase, the enzyme responsible for azathioprine metabolism, leading to azathioprine accumulation and severe bone marrow toxicity.',
                'description_ar'    => 'الألوبيورينول يثبط أكسيداز الزانثين، الإنزيم المسؤول عن استقلاب الأزاثيوبرين، مما يؤدي إلى تراكم الأزاثيوبرين وسمية نخاع العظم الشديدة.',
                'recommendation_en' => 'Reduce azathioprine dose by 75% if combination cannot be avoided. Monitor CBC closely.',
                'recommendation_ar' => 'قلل جرعة الأزاثيوبرين بنسبة 75٪ إذا كان لا يمكن تجنب الجمع. راقب CBC عن كثب.',
            ],
            [
                'drug_a'            => 'Fluconazole',
                'drug_b'            => 'Warfarin',
                'severity'          => 'severe',
                'description_en'    => 'Fluconazole strongly inhibits CYP2C9, the primary enzyme for warfarin metabolism, significantly increasing warfarin levels and bleeding risk.',
                'description_ar'    => 'الفلوكونازول يثبط بشدة CYP2C9، الإنزيم الرئيسي لاستقلاب الوارفارين، مما يزيد بشكل كبير من مستويات الوارفارين وخطر النزيف.',
                'recommendation_en' => 'Reduce warfarin dose by approximately 25-50% and monitor INR closely.',
                'recommendation_ar' => 'قلل جرعة الوارفارين بحوالي 25-50٪ وراقب INR عن كثب.',
            ],
            [
                'drug_a'            => 'Beta-Blockers',
                'drug_b'            => 'Verapamil',
                'severity'          => 'severe',
                'description_en'    => 'Both drugs slow the heart rate and reduce cardiac conduction. Concurrent use can cause severe bradycardia, heart block, and cardiac arrest.',
                'description_ar'    => 'كلا الدواءين يبطئان معدل ضربات القلب ويقللان التوصيل القلبي. الاستخدام المتزامن يمكن أن يسبب بطء القلب الشديد وإحصار القلب والسكتة القلبية.',
                'recommendation_en' => 'Avoid IV combination. Oral combination requires extreme caution and cardiac monitoring.',
                'recommendation_ar' => 'تجنب الجمع الوريدي. الجمع الفموي يتطلب حذراً شديداً ومراقبة قلبية.',
            ],
            [
                'drug_a'            => 'Tramadol',
                'drug_b'            => 'MAOIs',
                'severity'          => 'contraindicated',
                'description_en'    => 'Tramadol combined with MAOIs can cause serotonin syndrome and seizures. The interaction is potentially life-threatening.',
                'description_ar'    => 'الترامادول مع MAOIs يمكن أن يسبب متلازمة السيروتونين والنوبات. التفاعل قد يكون مهدداً للحياة.',
                'recommendation_en' => 'Contraindicated. Allow at least 14 days after stopping MAOIs before using tramadol.',
                'recommendation_ar' => 'ممنوع. اترك 14 يوماً على الأقل بعد إيقاف MAOIs قبل استخدام الترامادول.',
            ],
            [
                'drug_a'            => 'Ceftriaxone',
                'drug_b'            => 'Calcium (IV)',
                'severity'          => 'contraindicated',
                'description_en'    => 'IV ceftriaxone and IV calcium can form a precipitate in the bloodstream or lungs, potentially causing fatal cardiorespiratory failure in neonates.',
                'description_ar'    => 'السيفترياكسون الوريدي والكالسيوم الوريدي يمكن أن يشكلا رواسب في مجرى الدم أو الرئتين، مما قد يسبب فشلاً قلبياً رئوياً مميتاً عند حديثي الولادة.',
                'recommendation_en' => 'Contraindicated in neonates. In adults, do not administer simultaneously; flush line between administrations.',
                'recommendation_ar' => 'ممنوع عند حديثي الولادة. في البالغين، لا تعطِ في نفس الوقت؛ اشطف الخط بين الإعطاءات.',
            ],
        ];

        foreach ($interactions as $interaction) {
            DrugInteraction::firstOrCreate(
                [
                    'drug_a' => $interaction['drug_a'],
                    'drug_b' => $interaction['drug_b'],
                ],
                $interaction
            );
        }

        $this->command->info('Drug interactions seeded: ' . count($interactions) . ' records.');
    }
}
