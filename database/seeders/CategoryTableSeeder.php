<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $name_en = ['mobile', 'laptop', 'electronic', 'tv', 'camera', 'home devices', 'video came', 'sports'];
        $name_ar = ['الموبايلات', 'أجهزة اللابتوب', 'الالكترونيات', 'تلفزيونات', 'الكاميرات', 'الأجهزة المنزلية', 'ألعاب الفيديو','الرياضة'];
        $i = 0;

        foreach ($name_en as $nameEn) {
            Category::create([
                'en' => ['name' => $nameEn],
                'ar' => ['name' => $name_ar[$i]],
            ]);

            $i++;
        }

    }

}
